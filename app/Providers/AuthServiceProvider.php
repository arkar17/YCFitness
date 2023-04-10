<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

        /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Event::listen('Illuminate\Auth\Events\Authenticated', function ($event) {
            $event->user->update(['last_seen' => now()]);
        });
    }

    // public function booted(Closure $callback = null)
    // {
    //     parent::booted($callback);

    //     // Here you can define your event listeners and policies
    //     $this->registerPolicies();

    //     Event::listen('Illuminate\Auth\Events\Authenticated', function ($event) {
    //         $event->user->update(['last_seen' => now()]);
    //     });
    // }
    
}
