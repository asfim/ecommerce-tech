<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\HomepageSetting;
use Illuminate\Support\Facades\Gate;
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
            } catch (\Exception $e) {
                // Table might not exist yet
            }
        }
    }
}
