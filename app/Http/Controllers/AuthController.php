<?php

namespace App\Http\Controllers;


use App\Models\GetCoupon;
use App\Models\User;
use Carbon\Exceptions\Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    // api Route
    public function Register(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'device_id' => 'required|string|unique:users,device_id'
            ]);

            // Check if device is already registered
            $existingUser = User::where('device_id', $request->device_id)->first();
            if ($existingUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Device already registered'
                ], 400);
            }

            // Create new user
            $user = User::create([
                'device_id' => $request->device_id,
                'coin_count' => 12,
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

    //api

    public function getUserDetails(Request $request)
    {
        try {
            // Get the authenticated user
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => 'Unauthorized',
                    'message' => 'Invalid or missing authentication token'
                ], 401);
            }

            // Return user details
            return response()->json([
                'success' => 'success',
                'message' => 'User Coin get successfully',
                'data' => [
                    'device_id' => $user->device_id,
                    'coin_count' => $user->coin_count,
                    'run' => $user->run
                ]
            ], 200);

        } catch (\Exception $e) {
            // Log the error
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