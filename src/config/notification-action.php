<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Route prefix
    |--------------------------------------------------------------------------
    |
    | The URI prefix for the notification actions route.
    | Example: /notification-action/{notification}/{action}
    |
    */

    'prefix' => 'notification-action',

    /*
    |--------------------------------------------------------------------------
    | Route middleware
    |--------------------------------------------------------------------------
    */

    'middleware' => ['web', 'auth'],
];
