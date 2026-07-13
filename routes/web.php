<?php

use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;
use App\Http\Controllers\Admin\AdminAttributeController;
use App\Http\Controllers\Admin\AdminAttributeValueController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CompanySettingController as AdminCompanySettingController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\CourierSettingController;
use App\Http\Controllers\Admin\HomepageSettingController as AdminHomepageSettingController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\PaymentSettingController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\SmsSettingController;
use App\Http\Controllers\Admin\SubCategoryController as AdminSubCategoryController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Backend\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Backend\DashboardController as AdminDashboardController;
use App\Http\Controllers\Frontend\Auth\LoginController as UserLoginController;
use App\Http\Controllers\Frontend\Auth\RegisterController as UserRegisterController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\CouponController;
use App\Http\Controllers\Frontend\CustomerOrderController;
use App\Http\Controllers\Frontend\DashboardController as UserDashboardController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\Frontend\PageController as FrontendPageController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\Frontend\SslcommerzController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{slug}', [HomeController::class, 'productDetails'])->name('product.details');
Route::get('/category/{id}', [HomeController::class, 'categoryProducts'])->name('category.products');
Route::get('/products/search-api', [HomeController::class, 'searchApi'])->name('products.search-api');
Route::get('/blog', [BlogController::class, 'index'])->name('blogs.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blogs.show');
Route::get('/page/{slug}', [FrontendPageController::class, 'show'])->name('page.show');
Route::get('/checkout', [HomeController::class, 'checkout'])->name('checkout');
Route::post('/order/place', [OrderController::class, 'store'])->name('order.store');
Route::post('/coupon/apply', [CouponController::class, 'apply'])->name('coupon.apply');
Route::get('/order/invoice/{invoiceNo}', [OrderController::class, 'invoice'])->name('order.invoice');

/* ========== SSLCommerz Callbacks ========== */
Route::controller(SslcommerzController::class)
    ->prefix('sslcommerz')
    ->name('sslc.')
    ->group(function () {
        Route::post('success', 'success')->name('success');
        Route::post('failure', 'failure')->name('failure');
        Route::post('cancel', 'cancel')->name('cancel');
        Route::post('ipn', 'ipn')->name('ipn');
    });
Route::post('/product/{product}/review', [ReviewController::class, 'store'])->name('product.review.store');

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

        Route::middleware('permission:view-categories|create-categories|edit-categories|delete-categories,admin')->group(function () {
            Route::resource('categories', AdminCategoryController::class);
            Route::patch('categories/{category}/toggle-status', [AdminCategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
            Route::patch('categories/{category}/toggle-trending', [AdminCategoryController::class, 'toggleTrending'])->name('categories.toggle-trending');
            Route::resource('sub-categories', AdminSubCategoryController::class);
        });

        Route::middleware('permission:manage-homepage-settings,admin')->group(function () {
            Route::get('settings/homepage', [AdminHomepageSettingController::class, 'index'])->name('settings.homepage');
            Route::post('settings/homepage/{section}', [AdminHomepageSettingController::class, 'update'])->name('settings.homepage.update');
        });

        Route::middleware('permission:manage-company-settings,admin')->group(function () {
            Route::get('settings/company', [AdminCompanySettingController::class, 'index'])->name('settings.company');
            Route::post('settings/company', [AdminCompanySettingController::class, 'update'])->name('settings.company.update');
            Route::get('settings/courier', [CourierSettingController::class, 'index'])->name('settings.courier');
            Route::post('settings/courier', [CourierSettingController::class, 'update'])->name('settings.courier.update');
            Route::get('settings/payment', [PaymentSettingController::class, 'index'])->name('settings.payment');
            Route::post('settings/payment', [PaymentSettingController::class, 'update'])->name('settings.payment.update');
            Route::get('settings/sms', [SmsSettingController::class, 'index'])->name('settings.sms');
            Route::post('settings/sms', [SmsSettingController::class, 'update'])->name('settings.sms.update');
        });

        Route::middleware('permission:view-brands|create-brands|edit-brands|delete-brands,admin')->group(function () {
            Route::resource('brands', AdminBrandController::class);
        });

        Route::middleware('permission:view-products|create-products|edit-products|delete-products,admin')->group(function () {
            Route::post('products/bulk-delete', [AdminProductController::class, 'bulkDestroy'])->name('products.bulk-destroy');
            Route::resource('products', AdminProductController::class);
            Route::patch('products/{product}/toggle-featured', [AdminProductController::class, 'toggleFeatured'])->name('products.toggle-featured');
            Route::patch('products/{product}/toggle-active', [AdminProductController::class, 'toggleActive'])->name('products.toggle-active');
        });

        Route::middleware('permission:view-orders|edit-orders|delete-orders,admin')->group(function () {
            Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
            Route::get('orders/bulk-print', [AdminOrderController::class, 'bulkPrint'])->name('orders.bulk-print');
            Route::post('orders/send-steadfast', [AdminOrderController::class, 'sendToSteadfast'])->name('orders.send-steadfast');
            Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
            Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
            Route::delete('orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');
        });

        Route::middleware('permission:view-coupons|create-coupons|edit-coupons|delete-coupons,admin')->group(function () {
            Route::resource('coupons', AdminCouponController::class);
        });

        Route::middleware('permission:view-reviews|delete-reviews,admin')->group(function () {
            Route::resource('reviews', App\Http\Controllers\Admin\ReviewController::class)->only(['index', 'destroy']);
        });

        Route::middleware('permission:view-blogs|create-blogs|edit-blogs|delete-blogs,admin')->group(function () {
            Route::resource('blog-posts', BlogPostController::class);
        });

        Route::middleware('permission:view-reports,admin')->group(function () {
            Route::get('reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
            Route::get('reports/stock', [ReportController::class, 'stock'])->name('reports.stock');
        });

        Route::middleware('permission:view-attributes|create-attributes|edit-attributes|delete-attributes,admin')->group(function () {
            Route::get('attribute-values', [AdminAttributeValueController::class, 'globalIndex'])->name('attribute-values.index');
            Route::resource('attributes', AdminAttributeController::class);
            Route::resource('attributes.values', AdminAttributeValueController::class);
        });

        Route::middleware('permission:view-roles|create-roles|edit-roles|delete-roles,admin')->group(function () {
            Route::resource('roles', AdminRoleController::class);
        });

        Route::middleware('permission:view-pages|edit-pages,admin')->group(function () {
            Route::resource('pages', AdminPageController::class)->only(['index', 'edit', 'update']);
        });

        Route::middleware('permission:view-permissions|create-permissions,admin')->group(function () {
            Route::get('permissions', [AdminRoleController::class, 'permissionsIndex'])->name('permissions.index');
            Route::post('permissions', [AdminRoleController::class, 'storePermission'])->name('permissions.store');
        });

        Route::middleware('permission:view-staffs|create-staffs|edit-staffs|delete-staffs,admin')->group(function () {
            Route::redirect('users', 'users/admins');
            Route::get('users/admins', [AdminUserController::class, 'adminsIndex'])->name('users.admins');
            Route::get('users/admins/create', [AdminUserController::class, 'createAdmin'])->name('users.admins.create');
            Route::post('users/admins', [AdminUserController::class, 'storeAdmin'])->name('users.admins.store');
            Route::get('users/admins/{id}/edit', [AdminUserController::class, 'editAdmin'])->name('users.admins.edit');
            Route::put('users/admins/{id}', [AdminUserController::class, 'updateAdmin'])->name('users.admins.update');
        });

        Route::middleware('permission:view-customers|create-customers|edit-customers|delete-customers,admin')->group(function () {
            Route::get('users/staff', [AdminUserController::class, 'staffIndex'])->name('users.staff');
            Route::get('users/create', [AdminUserController::class, 'create'])->name('users.create');
            Route::post('users', [AdminUserController::class, 'store'])->name('users.store');
            Route::get('users/{type}/{id}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
            Route::put('users/{type}/{id}', [AdminUserController::class, 'update'])->name('users.update');
            Route::delete('users/{type}/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');
        });

        Route::middleware('permission:view-activitylogs,admin')->group(function () {
            Route::get('activity-logs', [AdminActivityLogController::class, 'index'])->name('activity-logs.index');
        });
    });
});
