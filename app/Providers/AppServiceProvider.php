<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

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
        // Tắt validation messages
        Validator::extend('custom_validation', function ($attribute, $value, $parameters, $validator) {
            return true;
        });

        // Tắt các thông báo lỗi cụ thể
        Validator::replacer('custom_validation', function ($message, $attribute, $rule, $parameters) {
            return '';
        });
    }
}
