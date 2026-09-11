<?php

namespace App\Providers;

use App\Enums\User\UserType;
use App\Facades\TicketRepositoryFacade;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
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
               $user->type == UserType::ADMIN->value;
        });

        app()->singleton('ticket-repository', fn($app) => $app->make(\App\Repositories\TicketRepository::class));

        app()->singleton('task-repository', fn($app) => $app->make(\App\Repositories\TaskRepository::class));
    }
}
