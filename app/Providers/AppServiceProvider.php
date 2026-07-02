<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        RateLimiter::for('evaluation-submissions', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip());
        });

        View::composer('layouts.app', function ($view) {
            $pendingAuditsCount = auth()->check()
                ? auth()->user()->unsubmittedAuditsCount()
                : 0;

            $view->with('pendingAuditsCount', $pendingAuditsCount);
        });
    }
}
