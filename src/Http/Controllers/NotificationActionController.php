<?php

namespace ShufflingPixels\NotificationAction\Http\Controllers;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\DB;
use ReflectionClass;
use ShufflingPixels\NotificationAction\Attributes\Action;
use ShufflingPixels\NotificationAction\Http\Response;

class NotificationActionController
{
    public function __invoke(DatabaseNotification $notification, string $action)
    {
        // 1. Ensure notification type supports actions
        $actionHandler = $this->resolveHandler($notification->type);
        if ($actionHandler === null || ! method_exists($actionHandler, $action)) {
            return abort(404);
        }

        // 2. Handle action + mark as read/delete in a transaction if handler wants.
        return DB::transaction(function () use ($actionHandler, $notification, $action) {
            $response = $actionHandler->$action($notification);
            if (! ($response instanceof Response)) {
                return new Response;
            }

            if ($response->markAsRead) {
                $notification->markAsRead();
            }

            if ($response->delete) {
                $notification->delete();
            }

            return $response;
        })->httpResponse;
    }

    protected function resolveHandler(?string $class): ?object
    {
        if ($class === null) {
            return null;
        }

        $reflection = new ReflectionClass($class);
        $attributes = $reflection->getAttributes(Action::class);

        if (count($attributes) < 1) {
            return null;
        }
        $arguments = $attributes[0]->getArguments();
        if (count($arguments) < 1) {
            return null;
        }

        return app()->make($arguments[0]);
    }

    protected function checkSettings(object $handler, $setting): mixed
    {
        if (property_exists($handler, $setting)) {
            return $handler->$setting;
        }

        if (method_exists($handler, $setting)) {
            return $handler->$setting();
        }

        return null;
    }
}
