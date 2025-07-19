<?php

use App\Http\Controllers\GetCouponController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('home');
// });
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected routes (accessible only to authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/', [UserController::class, 'dashboard'])->name('home');

    Route::get('users', [UserController::class, 'Users'])->name('users');

    Route::get('/coupon-list', [GetCouponController::class, 'coupon'])->name('index');
    Route::get('/coupon', [GetCouponController::class, 'index'])->name('coupons');
    Route::post('/coupon-store', [GetCouponController::class, 'store'])->name('coupons.store');

    Route::get('/coupons/{id}/edit', [GetCouponController::class, 'edit'])->name('coupons.edit');
    Route::put('/coupons/{id}', [GetCouponController::class, 'update'])->name('coupons.update');
    Route::delete('/coupons/{id}', [GetCouponController::class, 'destroy'])->name('coupons.destroy');
});