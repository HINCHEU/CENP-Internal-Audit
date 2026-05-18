<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
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
        View::composer('layouts.app', function ($view) {
            $pendingAuditsCount = auth()->check()
                ? auth()->user()->unsubmittedAuditsCount()
                : 0;

            $view->with('pendingAuditsCount', $pendingAuditsCount);
        });
    }
}
