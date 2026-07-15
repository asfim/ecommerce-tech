<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\HomepageSetting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });

        view()->composer('layouts.header.header', function ($view) {
            $categories = Category::with('subCategories')->where('is_active', true)->get();
            $view->with('categories', $categories);
        });

        if (! app()->runningInConsole() || app()->bound('db')) {
            try {
                $settings = HomepageSetting::get('steadfast_settings', []);
                config([
                    'steadfast.api_key' => $settings['api_key'] ?? '',
                    'steadfast.secret_key' => $settings['secret_key'] ?? '',
                    'steadfast.base_url' => $settings['base_url'] ?? 'https://portal.packzy.com/api/v1',
                ]);

                $sslSettings = HomepageSetting::get('sslcommerz_settings', []);
                if (! empty($sslSettings)) {
                    config([
                        'sslcommerz.sandbox' => filter_var($sslSettings['sandbox'] ?? config('sslcommerz.sandbox'), FILTER_VALIDATE_BOOLEAN),
                        'sslcommerz.store.id' => ! empty($sslSettings['store_id']) ? $sslSettings['store_id'] : config('sslcommerz.store.id'),
                        'sslcommerz.store.password' => ! empty($sslSettings['store_password']) ? $sslSettings['store_password'] : config('sslcommerz.store.password'),
                        'sslcommerz.store.currency' => ! empty($sslSettings['currency']) ? $sslSettings['currency'] : config('sslcommerz.store.currency'),
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('AppServiceProvider boot failed: '.$e->getMessage());
            }
        }
    }
}
