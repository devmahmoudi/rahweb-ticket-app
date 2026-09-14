<?php

namespace App\Providers;

use App\Enums\User\UserType;
use App\Models\User;
use App\TicketStateManagement\States\AcceptedState;
use App\TicketStateManagement\States\DelegatedState;
use App\TicketStateManagement\States\PendingState;
use App\TicketStateManagement\States\RejectedState;
use App\TicketStateManagement\States\WebserviceState;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        foreach ([PendingState::class, AcceptedState::class, DelegatedState::class, WebserviceState::class, RejectedState::class] as $state) {
            $this->app->bind($state, fn ($app, array $parameters) => new $state($parameters['ticket']));
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('cartable', function (User $user){
           return
               $user->type == UserType::OPERATOR->value
               or
               $user->type == UserType::SUPERADMIN->value;
        });

        app()->singleton('ticket-repository', fn($app) => $app->make(\App\Repositories\TicketRepository::class));

    }
}
