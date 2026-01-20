<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Cached unread notifications count for the current request
     */
    private static ?int $unreadNotificationsCount = null;

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
        View::composer('*', function ($view) {
            $currentUserId = auth()->id();
            static $lastUserId = null;

            if ($lastUserId !== $currentUserId) {
                self::$unreadNotificationsCount = null;
                $lastUserId = $currentUserId;
            }

            if (self::$unreadNotificationsCount === null) {
                if (auth()->check()) {
                    self::$unreadNotificationsCount = auth()->user()->unreadNotifications()->count();
                } else {
                    self::$unreadNotificationsCount = 0;
                }
            }

            $view->with('unreadNotificationsCount', self::$unreadNotificationsCount);
        });
    }
}
