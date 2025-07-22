<?php

namespace App\Http\Controllers;


use App\Models\GetCoupon;
use App\Models\User;
use App\Models\UserPurchase;
use Carbon\Exceptions\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    // api Route
    public function Register(Request $request)
    {
        try {
            // Check if device_id already exists
            $existingUser = User::where('device_id', $request->device_id)->first();

            if ($existingUser) {
                // Retrieve an existing token or generate a new one
                $token = $existingUser->tokens()->first()?->plainTextToken ?? $existingUser->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'success' => 'success',
                    'message' => 'Device already registered',
                    'data' => [
                        'device_id' => $existingUser->device_id,
                        'bearer_token' => $token,
                        'coin_count' => $existingUser->coin_count,
                    ]
                ], 200);
            }

            // Validate request for new user
            $request->validate([
                'device_id' => 'required|string|unique:users,device_id'
            ]);

            // Create new user
            $user = User::create([
                'device_id' => $request->device_id,
                'coin_count' => 15,
            ]);

            // Generate bearer token
            $token = $user->createToken('auth_token')->plainTextToken;

            // Return success response
            return response()->json([
                'success' => 'success',
                'message' => 'User registered successfully',
                'data' => [
                    'device_id' => $user->device_id,
                    'bearer_token' => $token,
                    'coin_count' => $user->coin_count,
                ]
            ], 201);

        } catch (ValidationException $e) {
            // Handle other validation errors
            return response()->json([
                'success' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (Exception $e) {
            return response()->json([
                'success' => 'error',
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function purchaseCoin(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'product_id' => 'required|string|exists:get_coupons,product_id',
            ]);

            // Get authenticated user
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => 'error',
                    'message' => 'Unauthorized',
                ], 401);
            }

            // Fetch the coupon/package details
            $coupon = GetCoupon::where('product_id', $request->product_id)->first();
            if (!$coupon) {
                return response()->json([
                    'success' => 'error',
                    'message' => 'Package not found',
                ], 404);
            }

            // Create purchase record
            $purchase = UserPurchase::create([
                'device_id' => $user->device_id,
                'product_id' => $request->product_id,
            ]);

            // Update user's coin count (base coins + giveaway)
            $totalCoins = $coupon->coins + ($coupon->giveaway ?? 0);
            $user->coin_count += $totalCoins;
            $user->save();

            // Return success response
            return response()->json([
                'success' => 'success',
                'message' => 'Purchase successful',
                'data' => [
                    'device_id' => $user->device_id,
                    'product_id' => $coupon->product_id,
                    'coins_added' => $totalCoins,
                    'new_coin_count' => $user->coin_count,
                    'package_details' => [
                        'title' => $coupon->title,
                        'coins' => $coupon->coins,
                        'giveaway' => $coupon->giveaway,
                        'pkg_image_url' => $coupon->pkg_image_url,
                        'label_popular' => $coupon->label_popular,
                        'label_color' => $coupon->label_color,
                        'price_per_coin' => $coupon->price_per_coin,
                        'total_price' => $coupon->total_price,
                    ],
                ],
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => 'error',
                'message' => 'Purchase failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    //api

    public function getUserDetails(Request $request)
    {
        \Log::info('Auth User: ' . json_encode(Auth::user()));
        try {
            $user = Auth::user();

            if (!$user) {
                \Log::error('Unauthorized access attempt');
                return response()->json([
                    'success' => "error",
                    'message' => 'Invalid or missing authentication token'
                ], 401);
            }

            return response()->json([
                'success' => "success",
                'message' => 'user coin get successfully',
                'data' => [
                    'device_id' => $user->device_id,
                    'coin_count' => $user->coin_count,
                    'run' => $user->run
                ]
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Error fetching user details: ' . $e->getMessage());
            return response()->json([
                'success' => "error",
                'message' => 'An error occurred while fetching user details'
            ], 500);
        }
    }

    public function getCoupon(Request $request)
    {
        $coupon = GetCoupon::get();
        return response()->json([
            'success' => 'success',
            'message' => 'Get Coupon Successfully',
            'data' => $coupon
        ]);
    }
}