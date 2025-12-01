<?php

namespace ShufflingPixels\NotificationAction\Tests\Stubs;

use Illuminate\Notifications\DatabaseNotification;
use ShufflingPixels\NotificationAction\Http\Response;

class SampleActionHandler
{
    public function approve(DatabaseNotification $notification): Response
    {
        return Response::markAsRead();
    }

    public function remove(DatabaseNotification $notification): Response
    {
        return Response::delete();
    }
}
