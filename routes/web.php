<?php

use App\Http\Controllers\Admin\AdminAttributeController;
use App\Http\Controllers\Admin\AdminAttributeValueController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Backend\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Backend\DashboardController as AdminDashboardController;
use App\Http\Controllers\Frontend\Auth\LoginController as UserLoginController;
use App\Http\Controllers\Frontend\Auth\RegisterController as UserRegisterController;
use App\Http\Controllers\Frontend\DashboardController as UserDashboardController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

/* ========== Frontend (User) ========== */
Route::prefix('account')->name('user.')->group(function () {
    Route::get('login', [UserLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [UserLoginController::class, 'login'])->name('login.submit');
    Route::get('register', [UserRegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [UserRegisterController::class, 'register'])->name('register.submit');
    Route::post('logout', [UserLoginController::class, 'logout'])->name('logout');

    Route::middleware('auth')->group(function () {
        Route::get('dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    });
});

/* ========== Backend (Admin) ========== */
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminLoginController::class, 'login'])->name('login.submit');
    Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');

    Route::middleware(AdminMiddleware::class)->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('categories', AdminCategoryController::class);
        Route::resource('brands', AdminBrandController::class);
        Route::resource('products', AdminProductController::class);
        Route::get('attribute-values', [AdminAttributeValueController::class, 'globalIndex'])->name('attribute-values.index');
        Route::resource('attributes', AdminAttributeController::class);
        Route::resource('attributes.values', AdminAttributeValueController::class);
    });
});
