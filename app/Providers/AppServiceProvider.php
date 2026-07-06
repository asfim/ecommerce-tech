<?php

namespace App\Providers;

use App\Models\Category;
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
    }
}
