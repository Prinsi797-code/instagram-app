<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InstagramController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('getcoupon', [AuthController::class, 'getCoupon'])->name('getcoupon');

Route::middleware('auth:sanctum')->get('/usercoin', [AuthController::class, 'getUserDetails'])->name('user.coin');


Route::post('register', [AuthController::class, 'Register'])->name('register');
Route::post('/instagram', [InstagramController::class, 'handle']);