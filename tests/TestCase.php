<?php

namespace ShufflingPixels\NotificationAction\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;
use ShufflingPixels\NotificationAction\Providers\NotificationActionServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpDatabase();
    }

    protected function getPackageProviders($app): array
    {
        return [
            NotificationActionServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        // keep route model binding active while keeping auth out of the way
        $app['config']->set('notification-action.middleware', ['web']);
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function setUpDatabase(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        // Ensure model uses the testing connection.
        DatabaseNotification::unguard();
        DatabaseNotification::setConnectionResolver($this->app['db']);
        DatabaseNotification::setEventDispatcher($this->app['events']);
    }
}
