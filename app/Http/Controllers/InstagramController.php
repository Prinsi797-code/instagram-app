<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use DOMDocument;
use DOMXPath;

class InstagramController extends Controller
{
    /**
     * Handle the Instagram post request
     */
    public function handle(Request $request)
    {
        // Set headers for CORS and JSON response
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');
        header('Access-Control-Allow-Headers: Content-Type');

        // Validate input
        $request->validate([
            'postUrl' => 'required|url',
            'maxComments' => 'nullable|integer|min:1'
        ]);

        $postUrl = $request->input('postUrl');
        $maxComments = $request->input('maxComments', 100);

        // Check if the URL is for Instagram
        if (strpos($postUrl, 'instagram.com') === false) {
            return response()->json(['error' => 'Invalid Instagram URL'], 400);
        }

        // Process Instagram URL
        $mediaInfo = $this->fetchFromGraphQL($postUrl, $maxComments);

        if (isset($mediaInfo['error'])) {
            return response()->json($mediaInfo, 400);
        }

        if ($mediaInfo['type'] === 'carousel') {
            $zipResult = $this->downloadAndCreateZip($mediaInfo['media']);
            return response()->json([
                'platform' => 'instagram',
                'type' => 'carousel',
                'caption' => $mediaInfo['caption'],
                'posted_by' => $mediaInfo['posted_by'],
                'zipFilePath' => $zipResult['zipFilePath'] ?? null,
                'comments_count' => $mediaInfo['comments_count'] ?? 0,
                'comments_fetched' => $mediaInfo['comments_fetched'] ?? 0,
                'comments' => $mediaInfo['comments'] ?? [],
                'error' => $zipResult['error'] ?? null
            ]);
        }

        return response()->json([
            'platform' => 'instagram',
            'type' => $mediaInfo['type'],
            'caption' => $mediaInfo['caption'],
            'posted_by' => $mediaInfo['posted_by'],
            'fileUrl' => $mediaInfo['url'],
            'fileType' => $mediaInfo['type'] === 'video' ? 'mp4' : 'jpg',
            'dimensions' => $mediaInfo['dimensions'],
            'comments_count' => $mediaInfo['comments_count'] ?? 0,
            'comments_fetched' => $mediaInfo['comments_fetched'] ?? 0,
            'comments' => $mediaInfo['comments'] ?? []
        ]);
    }

    /**
     * Fetch data from Instagram GraphQL API
     */
    private function fetchFromGraphQL($postUrl, $maxComments)
    {
        preg_match('/\/(p|reel)\/([A-Za-z0-9_-]+)/', $postUrl, $matches);

        if (empty($matches[2])) {
            \Log::error("Invalid Instagram URL: {$postUrl}");
            return ['error' => 'Invalid Instagram URL'];
        }

        $shortcode = $matches[2];
        \Log::info("Extracted shortcode: {$shortcode}");

        // First request to get media data and initial comments
        $data = $this->makeGraphQLRequest($shortcode);

        if (isset($data['error'])) {
            return $data;
        }

        $mediaData = $data['data']['xdt_shortcode_media'] ?? null;
        if (!$mediaData) {
            return ['error' => 'No media data found', 'response' => $data];
        }

        // Get total comments count
        $totalCommentsCount = 0;
        if (isset($mediaData['edge_media_to_parent_comment'])) {
            $totalCommentsCount = $mediaData['edge_media_to_parent_comment']['count'] ?? 0;
        } elseif (isset($mediaData['edge_media_to_comment'])) {
            $totalCommentsCount = $mediaData['edge_media_to_comment']['count'] ?? 0;
        } elseif (isset($mediaData['edge_media_preview_comment'])) {
            $totalCommentsCount = $mediaData['edge_media_preview_comment']['count'] ?? 0;
        }

        \Log::info("Total comments available: {$totalCommentsCount}, Requested max: {$maxComments}");

        // Fetch all comments with pagination
        $allComments = $this->fetchAllComments($shortcode, $maxComments);

        $caption = $mediaData['edge_media_to_caption']['edges'][0]['node']['text'] ?? '';
        $posted_by = [
            'username' => $mediaData['owner']['username'] ?? '',
            'profile_pic_url' => $mediaData['owner']['profile_pic_url'] ?? '',
            'profile_url' => 'https://www.instagram.com/' . ($mediaData['owner']['username'] ?? ''),
            'is_verified' => $mediaData['owner']['is_verified'] ?? false
        ];

        \Log::info("Total comments in post: {$totalCommentsCount}, Actually fetched: " . count($allComments));

        // Handle carousel posts
        if ($mediaData['__typename'] === 'XDTGraphSidecar') {
            $mediaItems = $mediaData['edge_sidecar_to_children']['edges'] ?? [];
            $mediaUrls = array_map(function ($item) {
                $childMedia = $item['node'];
                return [
                    'type' => $childMedia['is_video'] ? 'video' : 'image',
                    'url' => $childMedia['is_video'] ? $childMedia['video_url'] : $childMedia['display_url'],
                    'dimensions' => $childMedia['dimensions'] ?? null,
                ];
            }, $mediaItems);

            return [
                'type' => 'carousel',
                'media' => $mediaUrls,
                'caption' => $caption,
                'posted_by' => $posted_by,
                'comments_count' => $totalCommentsCount,
                'comments_fetched' => count($allComments),
                'comments' => $allComments
            ];
        }

        // Check if it's a video
        if ($mediaData['is_video']) {
            return [
                'type' => 'video',
                'caption' => $caption,
                'posted_by' => $posted_by,
                'url' => $mediaData['video_url'],
                'dimensions' => $mediaData['dimensions'] ?? null,
                'comments_count' => $totalCommentsCount,
                'comments_fetched' => count($allComments),
                'comments' => $allComments
            ];
        }

        // Handle single image posts
        if (!empty($mediaData['display_url'])) {
            return [
                'type' => 'image',
                'url' => $mediaData['display_url'],
                'caption' => $caption,
                'posted_by' => $posted_by,
                'dimensions' => $mediaData['dimensions'] ?? null,
                'comments_count' => $totalCommentsCount,
                'comments_fetched' => count($allComments),
                'comments' => $allComments
            ];
        }

        return ['error' => 'Unknown media type.'];
    }

    /**
     * Make a single GraphQL request
     */
    private function makeGraphQLRequest($shortcode, $after = null, $first = 50)
    {
        $apiUrl = "https://www.instagram.com/api/graphql";
        $requestData = $this->encodePostRequestData($shortcode, $after, $first);

        $response = Http::withHeaders([
            'Accept' => '*/*',
            'Accept-Language' => 'en-US,en;q=0.5',
            'Content-Type' => 'application/x-www-form-urlencoded',
            'X-FB-Friendly-Name' => 'PolarisPostActionLoadPostQueryQuery',
            'X-CSRFToken' => 'RVDUooU5MYsBbS1CNN3CzVAuEP8oHB52',
            'X-IG-App-ID' => '1217981644879628',
            'X-FB-LSD' => 'AVqbxe3J_YA',
            'X-ASBD-ID' => '129477',
            'User-Agent' => 'Mozilla/5.0 (Linux; Android 11; SAMSUNG SM-G973U) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/14.2 Chrome/87.0.4280.141 Mobile Safari/537.36'
        ])->timeout(30)->post($apiUrl, $requestData);

        if ($response->failed()) {
            \Log::error("GraphQL request failed: " . $response->body());
            return ['error' => 'Request failed'];
        }

        $data = $response->json();

        // Remove "for (;;);" if present in response
        if (strpos($response->body(), "for (;;);") === 0) {
            $data = json_decode(substr($response->body(), 9), true);
        }

        \Log::info('GraphQL Response: ' . json_encode($data));
        return $data;
    }

    /**
     * Encode request data for POST request
     */
    private function encodePostRequestData($shortcode, $after = null, $first = 50)
    {
        $variables = [
            'shortcode' => $shortcode,
            'fetch_comment_count' => $first,
            'fetch_related_profile_media_count' => null,
            'parent_comment_count' => $first,
            'child_comment_count' => 3,
            'fetch_like_count' => 10,
            'fetch_tagged_user_count' => null,
            'fetch_preview_comment_count' => $first,
            'has_threaded_comments' => true,
            'hoisted_comment_id' => null,
            'hoisted_reply_id' => null,
        ];

        if ($after) {
            $variables['after'] = $after;
        }

        return [
            'av' => '0',
            '__d' => 'www',
            '__user' => '0',
            '__a' => '1',
            '__req' => '3',
            '__hs' => '19624.HYP:instagram_web_pkg.2.1..0.0',
            'dpr' => '3',
            '__ccg' => 'UNKNOWN',
            '__rev' => '1008824440',
            '__s' => 'xf44ne:zhh75g:xr51e7',
            '__hsi' => '7282217488877343271',
            '__dyn' => '7xeUmwlEnwn8K2WnFw9-2i5U4e0yoW3q32360CEbo1nEhw2nVE4W0om78b87C0yE5ufz81s8hwGwQwoEcE7O2l0Fwqo31w9a9x-0z8-U2zxe2GewGwso88cobEaU2eUlwhEe87q7-0iK2S3qazo7u1xwIw8O321LwTwKG1pg661pwr86C1mwraCg',
            '__csr' => 'gZ3yFmJkillQvV6ybimnG8AmhqujGbLADgjyEOWz49z9XDlAXBJpC7Wy-vQTSvUGWGh5u8KibG44dBiigrgjDxGjU0150Q0848azk48N09C02IR0go4SaR70r8owyg9pU0V23hwiA0LQczA48S0f-x-27o05NG0fkw',
            '__comet_req' => '7',
            'lsd' => 'AVqbxe3J_YA',
            'jazoest' => '2957',
            '__spin_r' => '1008824440',
            '__spin_b' => 'trunk',
            '__spin_t' => '1695523385',
            'fb_api_caller_class' => 'RelayModern',
            'fb_api_req_friendly_name' => 'PolarisPostActionLoadPostQueryQuery',
            'variables' => json_encode($variables),
            'server_timestamps' => 'true',
            'doc_id' => '10015901848480474',
        ];
    }

    /**
     * Fetch all comments with pagination
     */
    private function fetchAllComments($shortcode, $maxComments = 100)
    {
        $allComments = [];
        $hasNextPage = true;
        $after = null;
        $fetchedCount = 0;
        $maxRequestsPerBatch = 5;
        $requestCount = 0;

        while ($hasNextPage && $fetchedCount < $maxComments && $requestCount < $maxRequestsPerBatch) {
            $remainingComments = $maxComments - $fetchedCount;
            $batchSize = min(50, $remainingComments);

            $data = $this->makeGraphQLRequest($shortcode, $after, $batchSize);
            \Log::info('GraphQL Batch Request ' . ($requestCount + 1) . ': ' . json_encode($data));

            if (isset($data['error'])) {
                \Log::error("Error fetching comments: " . $data['error']);
                break;
            }

            $mediaData = $data['data']['xdt_shortcode_media'] ?? null;
            if (!$mediaData) {
                \Log::error("No media data found in response");
                break;
            }

            $commentEdges = [];
            $pageInfo = null;

            if ($requestCount === 0 && isset($mediaData['edge_media_preview_comment'])) {
                $commentEdges = $mediaData['edge_media_preview_comment']['edges'] ?? [];
                $pageInfo = $mediaData['edge_media_preview_comment']['page_info'] ?? null;
                \Log::info("Using edge_media_preview_comment for first request");
            } elseif (isset($mediaData['edge_media_to_parent_comment'])) {
                $commentEdges = $mediaData['edge_media_to_parent_comment']['edges'] ?? [];
                $pageInfo = $mediaData['edge_media_to_parent_comment']['page_info'] ?? null;
                \Log::info("Using edge_media_to_parent_comment for request " . ($requestCount + 1));
            } elseif (isset($mediaData['edge_media_to_comment'])) {
                $commentEdges = $mediaData['edge_media_to_comment']['edges'] ?? [];
                $pageInfo = $mediaData['edge_media_to_comment']['page_info'] ?? null;
                \Log::info("Using edge_media_to_comment for request " . ($requestCount + 1));
            }

            \Log::info("Found " . count($commentEdges) . " comment edges in request " . ($requestCount + 1));

            $batchComments = [];
            foreach ($commentEdges as $comment) {
                if ($fetchedCount >= $maxComments) {
                    break;
                }

                $commentData = [
                    'id' => $comment['node']['id'] ?? '',
                    'text' => $comment['node']['text'] ?? '',
                    'created_at' => $comment['node']['created_at'] ?? '',
                    'like_count' => $comment['node']['edge_liked_by']['count'] ?? 0,
                    'user' => [
                        'username' => $comment['node']['owner']['username'] ?? '',
                        'profile_pic_url' => $comment['node']['owner']['profile_pic_url'] ?? '',
                        'profile_url' => 'https://www.instagram.com/' . ($comment['node']['owner']['username'] ?? ''),
                        'is_verified' => $comment['node']['owner']['is_verified'] ?? false
                    ],
                    'replies' => []
                ];

                if (isset($comment['node']['edge_threaded_comments']['edges'])) {
                    foreach ($comment['node']['edge_threaded_comments']['edges'] as $reply) {
                        $commentData['replies'][] = [
                            'id' => $reply['node']['id'] ?? '',
                            'text' => $reply['node']['text'] ?? '',
                            'created_at' => $reply['node']['created_at'] ?? '',
                            'like_count' => $reply['node']['edge_liked_by']['count'] ?? 0,
                            'user' => [
                                'username' => $reply['node']['owner']['username'] ?? '',
                                'profile_pic_url' => $reply['node']['owner']['profile_pic_url'] ?? '',
                                'profile_url' => 'https://www.instagram.com/' . ($reply['node']['owner']['username'] ?? ''),
                                'is_verified' => $reply['node']['owner']['is_verified'] ?? false
                            ]
                        ];
                    }
                }

                $batchComments[] = $commentData;
                $fetchedCount++;
            }

            $allComments = array_merge($allComments, $batchComments);
            \Log::info("Added " . count($batchComments) . " comments from batch " . ($requestCount + 1) . ". Total comments now: " . count($allComments));

            $hasNextPage = $pageInfo['has_next_page'] ?? false;
            $after = $pageInfo['end_cursor'] ?? null;
            $requestCount++;

            \Log::info("Has next page: " . ($hasNextPage ? 'true' : 'false') . ", After cursor: " . ($after ?? 'null'));

            if ($hasNextPage && $requestCount < $maxRequestsPerBatch) {
                usleep(500000); // 0.5 second delay
            }

            \Log::info("Completed batch {$requestCount}: " . count($batchComments) . " new comments. Total so far: " . count($allComments));
        }

        \Log::info("Final result: " . count($allComments) . " total comments fetched");
        return $allComments;
    }

    /**
     * Download file content
     */
    private function downloadFile($url)
    {
        $response = Http::withHeaders([
            'Accept' => '*/*',
            'Accept-Language' => 'en-US,en;q=0.5',
            'Referer' => 'https://www.instagram.com/'
        ])->timeout(30)->get($url);

        if ($response->successful()) {
            return $response->body();
        }

        \Log::error("Failed to download file: " . $url);
        return false;
    }

    /**
     * Download files and create ZIP
     */
    private function downloadAndCreateZip($mediaUrls)
    {
        $zip = new ZipArchive();
        $downloadsDir = storage_path('app/public/downloads/');
        $zipFileName = 'instagram_download_' . uniqid() . '.zip';
        $zipPath = $downloadsDir . $zipFileName;

        if (!file_exists($downloadsDir)) {
            mkdir($downloadsDir, 0777, true);
        }

        // Delete old files in downloads directory
        $this->deleteOldFiles($downloadsDir);

        if ($zip->open($zipPath, ZipArchive::CREATE) !== true) {
            \Log::error("Unable to create ZIP file at: " . $zipPath);
            return ['error' => 'Unable to create ZIP file'];
        }

        foreach ($mediaUrls as $index => $media) {
            $fileContent = $this->downloadFile($media['url']);
            if ($fileContent) {
                $extension = ($media['type'] === 'video') ? '.mp4' : '.jpg';
                $fileName = 'instagram_media_' . ($index + 1) . $extension;
                $zip->addFromString($fileName, $fileContent);
            }
        }

        $zip->close();

        if (!file_exists($zipPath) || filesize($zipPath) === 0) {
            \Log::error("ZIP file creation failed at: " . $zipPath);
            return ['error' => 'ZIP file creation failed'];
        }

        // Construct the public URL for the ZIP file
        $zipFileUrl = url('storage/downloads/' . $zipFileName);

        return ['zipFilePath' => $zipFileUrl];
    }

    /**
     * Delete old files in the downloads directory
     */
    private function deleteOldFiles($directory)
    {
        $files = glob($directory . '*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
}