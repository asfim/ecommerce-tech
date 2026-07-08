<?php

use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;
use App\Http\Controllers\Admin\AdminAttributeController;
use App\Http\Controllers\Admin\AdminAttributeValueController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\HomepageSettingController as AdminHomepageSettingController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\SubCategoryController as AdminSubCategoryController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Backend\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Backend\DashboardController as AdminDashboardController;
use App\Http\Controllers\Frontend\Auth\LoginController as UserLoginController;
use App\Http\Controllers\Frontend\Auth\RegisterController as UserRegisterController;
use App\Http\Controllers\Frontend\CustomerOrderController;
use App\Http\Controllers\Frontend\DashboardController as UserDashboardController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{slug}', [HomeController::class, 'productDetails'])->name('product.details');
Route::get('/checkout', [HomeController::class, 'checkout'])->name('checkout');
Route::post('/order/place', [OrderController::class, 'store'])->name('order.store');
Route::get('/order/invoice/{invoiceNo}', [OrderController::class, 'invoice'])->name('order.invoice');

/* ========== Frontend (User) ========== */
Route::prefix('account')->name('user.')->group(function () {
    Route::get('login', [UserLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [UserLoginController::class, 'login'])->name('login.submit');
    Route::get('register', [UserRegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [UserRegisterController::class, 'register'])->name('register.submit');
    Route::post('logout', [UserLoginController::class, 'logout'])->name('logout');

    Route::middleware('auth')->group(function () {
        Route::get('dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
        Route::get('orders', [CustomerOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');
    });
});

/* ========== Backend (Admin) ========== */
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminLoginController::class, 'login'])->name('login.submit');
    Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');

    Route::middleware(AdminMiddleware::class)->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::middleware('permission:manage-categories,admin')->group(function () {
            Route::resource('categories', AdminCategoryController::class);
            Route::patch('categories/{category}/toggle-status', [AdminCategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
            Route::patch('categories/{category}/toggle-trending', [AdminCategoryController::class, 'toggleTrending'])->name('categories.toggle-trending');
            Route::resource('sub-categories', AdminSubCategoryController::class);
        });

        Route::middleware('role:Super Admin,admin')->group(function () {
            Route::get('settings/homepage', [AdminHomepageSettingController::class, 'index'])->name('settings.homepage');
            Route::post('settings/homepage/{section}', [AdminHomepageSettingController::class, 'update'])->name('settings.homepage.update');
        });

        Route::middleware('permission:manage-brands,admin')->group(function () {
            Route::resource('brands', AdminBrandController::class);
        });

        Route::middleware('permission:manage-products,admin')->group(function () {
            Route::resource('products', AdminProductController::class);
            Route::patch('products/{product}/toggle-featured', [AdminProductController::class, 'toggleFeatured'])->name('products.toggle-featured');
            Route::patch('products/{product}/toggle-active', [AdminProductController::class, 'toggleActive'])->name('products.toggle-active');
        });

        Route::middleware('permission:manage-orders,admin')->group(function () {
            Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
            Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
            Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
        });

        Route::middleware('permission:manage-attributes,admin')->group(function () {
            Route::get('attribute-values', [AdminAttributeValueController::class, 'globalIndex'])->name('attribute-values.index');
            Route::resource('attributes', AdminAttributeController::class);
            Route::resource('attributes.values', AdminAttributeValueController::class);
        });

        Route::middleware('role:Super Admin,admin')->group(function () {
            Route::resource('roles', AdminRoleController::class);
            Route::get('permissions', [AdminRoleController::class, 'permissionsIndex'])->name('permissions.index');
            Route::post('permissions', [AdminRoleController::class, 'storePermission'])->name('permissions.store');

            // User & Staff Management
            Route::redirect('users', 'users/admins');
            Route::get('users/admins', [AdminUserController::class, 'adminsIndex'])->name('users.admins');
            Route::get('users/admins/create', [AdminUserController::class, 'createAdmin'])->name('users.admins.create');
            Route::post('users/admins', [AdminUserController::class, 'storeAdmin'])->name('users.admins.store');
            Route::get('users/admins/{id}/edit', [AdminUserController::class, 'editAdmin'])->name('users.admins.edit');
            Route::put('users/admins/{id}', [AdminUserController::class, 'updateAdmin'])->name('users.admins.update');
            Route::get('users/staff', [AdminUserController::class, 'staffIndex'])->name('users.staff');
            Route::get('users/create', [AdminUserController::class, 'create'])->name('users.create');
            Route::post('users', [AdminUserController::class, 'store'])->name('users.store');
            Route::get('users/{type}/{id}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
            Route::put('users/{type}/{id}', [AdminUserController::class, 'update'])->name('users.update');
            Route::delete('users/{type}/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');

            // Activity Logs
            Route::get('activity-logs', [AdminActivityLogController::class, 'index'])->name('activity-logs.index');
        });
    });
});
