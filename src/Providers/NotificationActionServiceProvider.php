<?php

namespace ShufflingPixels\NotificationAction\Providers;

use Illuminate\Support\ServiceProvider;

class NotificationActionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/notification-action.php', 
            'notification-action');
    }

    public function boot(): void
    {
        // Routes
        $this->loadRoutesFrom(__DIR__.'/../routes/notification-action.php');

        // Publish config
        $this->publishes([
            __DIR__.'/../config/notification-action.php' => config_path('notification-action.php'),
        ], 'notification-action-config');
    }
}
