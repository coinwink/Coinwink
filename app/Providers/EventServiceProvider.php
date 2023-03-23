<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

use Illuminate\Auth\Events\Login;
use App\Listeners\LoginSuccessful;

// use Illuminate\Auth\Events\Failed;
// use App\Listeners\LogFailedAuthenticationAttempt;

use Illuminate\Auth\Events\Attempting;
use App\Listeners\WordPressPasswordUpdate;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // Failed::class => [
        //     LogFailedAuthenticationAttempt::class,
        // ],
        Attempting::class => [
            WordPressPasswordUpdate::class,
        ],
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Login::class => [
            LoginSuccessful::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
