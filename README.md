# Laravel Notification Action

Adds simple, callable endpoints for Laravel database notifications. 
Define an action handler class, attach it to a notification via an attribute, and the package 
wires a `/notification-action/{notification}/{action}` route that executes the handler, 
optionally marking the notification as read.

## Requirements

- PHP 8.2+
- Laravel 10, 11, or 12

## Installation

```bash
composer require shufflingpixels/laravel-notification-action
```

## How it works

1) Annotate your notification with the `#[Action(...)]` attribute, pointing to an action handler class.  
2) Implement public methods on the handler; method names become callable actions.  
3) The package route resolves the handler, runs the requested method with the `DatabaseNotification` model, and returns its HTTP response.  
4) Returning `ShufflingPixels\NotificationAction\Http\Response::markAsRead()` marks the notification as read inside the transaction.

### Example

```php
use Illuminate\Notifications\Notification;
use ShufflingPixels\NotificationAction\Attributes\Action;
use ShufflingPixels\NotificationAction\Http\Response;

#[Action(MyNotificationActions::class)]
class InvoicePaidNotification extends Notification {}

class MyNotificationActions
{
    public function acknowledge(DatabaseNotification $notification): Response
    {
        // Do any side effects you need...
        return Response::markAsRead(); // returns 200 and marks as read if needed
    }
}
```

Call it from the browser or an email button:
```
GET /notification-action/{notification-uuid}/acknowledge
```

## Configuration

Publish the config to tweak prefix or middleware:
```bash
php artisan vendor:publish --tag=notification-action-config
```
Default values:
```php
return [
    'prefix' => 'notification-action',
    'middleware' => ['web', 'auth'],
];
```

## Routing

The package registers:
```
GET {prefix}/{notification}/{action}  -> NotificationActionController
```
Route model binding resolves the `DatabaseNotification` instance by ID. Missing handlers or methods return 404.

## Testing

```bash
vendor/bin/phpunit
```

## License

AGPL-3.0-or-later
