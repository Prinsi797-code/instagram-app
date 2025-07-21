<?php

use App\Http\Controllers\GetCouponController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SettingController;
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
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

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

    Route::get('/setting', [SettingController::class, 'setting'])->name('setting');
    Route::get('/setting-view', [SettingController::class, 'viewSetting'])->name('setting.view');
    Route::post('/setting-store', [SettingController::class, 'store'])->name('setting.store');

    Route::get('/setting/{id}/edit', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('/setting/{id}', [SettingController::class, 'update'])->name('settings.update');
    Route::delete('/setting/{id}', [SettingController::class, 'destroy'])->name('settings.destroy');

});