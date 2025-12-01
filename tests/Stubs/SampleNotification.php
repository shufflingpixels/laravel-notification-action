<?php

namespace ShufflingPixels\NotificationAction\Tests\Stubs;

use Illuminate\Notifications\Notification;
use ShufflingPixels\NotificationAction\Attributes\Action;

#[Action(SampleActionHandler::class)]
class SampleNotification extends Notification
{
}
