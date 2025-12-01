<?php

namespace ShufflingPixels\NotificationAction\Tests;

use Illuminate\Notifications\DatabaseNotification;
use ShufflingPixels\NotificationAction\Tests\Stubs\NoActionNotification;
use ShufflingPixels\NotificationAction\Tests\Stubs\SampleNotification;

class NotificationActionControllerTest extends TestCase
{
    public function test_returns_404_when_action_is_not_supported(): void
    {
        $notification = $this->createNotification(NoActionNotification::class);

        $uri = route('notification-actions.handle', [
            'notification' => $notification, 
            'action' => 'approve'
        ]);

        $this->get($uri)->assertNotFound();
    }

    public function test_executes_action_and_marks_notification_as_read(): void
    {
        $notification = $this->createNotification(SampleNotification::class);

        $uri = route('notification-actions.handle', [
            'notification' => $notification, 
            'action' => 'approve'
        ]);

        $this->get($uri)->assertOk();
        $this->assertNotNull($notification->fresh()->read_at, 'Notification should be marked as read');
    }

    public function test_executes_action_and_deletes_notification(): void
    {
        $notification = $this->createNotification(SampleNotification::class);

        $uri = route('notification-actions.handle', [
            'notification' => $notification,
            'action' => 'remove',
        ]);

        $this->get($uri)->assertOk();
        $this->assertDatabaseMissing('notifications', ['id' => $notification->id]);
    }

    protected function createNotification(string $type): DatabaseNotification
    {
        return DatabaseNotification::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'type' => $type,
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id' => 1,
            'data' => [],
        ]);
    }
}
