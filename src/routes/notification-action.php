<?php

use Illuminate\Support\Facades\Route;
use ShufflingPixels\NotificationAction\Http\Controllers\NotificationActionController;

Route::group([
    'prefix' => config('notification-action.prefix', 'notification-action'),
    'middleware' => config('notification-action.middleware', ['web', 'auth']),
], function () {
    Route::get('{notification}/{action}', NotificationActionController::class)
        ->name('notification-actions.handle');
});
