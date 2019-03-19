<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
        'App\Events\Login' => [
            'App\Listeners\LogSuccessfulLogin',
        ],
        'App\Events\VerifyDiveEvent' => [
            'App\Listeners\VerifyDiveListener'
        ],
        'App\Events\VerifyRole' => [
            'App\Listeners\LogVerifiedRole'
        ],
        'App\Events\UserContactRequest' => [
            'App\Listeners\UserContactRequestListener'
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
