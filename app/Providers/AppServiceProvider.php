<?php

namespace App\Providers;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use App\Models\Subscription;
use App\Observers\SubscriptionObserver;
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
         Subscription::observe(SubscriptionObserver::class);
        if (config('app.env') === 'local') {
            URL::forceScheme('https');
        }

              Schema::defaultStringLength(191);
    }
}
