<?php

namespace App\Policies;

use App\Enums\Permission\BasicPermission;
use App\Enums\Ticket\TicketStatus;
use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if($user->isCustomer())
            return true;

        if($user->isAdmin())
            return true;

        if($user->isOperator()){
            if($role = $user->role)
            return $role->permissions()
                ->where("name", BasicPermission::READ->value)
                ->where('model', Ticket::class)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ticket $ticket): bool
    {
        if($user->isCustomer())
            return $ticket->user_id == $user->id;

        if($user->isAdmin())
            return true;

        if($user->isOperator()){
            return
                $ticket->recipient_id == $user->id ||
                $ticket->status == TicketStatus::WAITING->value;

        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if($user->isCustomer())
            return true;

        if($user->isAdmin())
            return false;

        if($user->isOperator())
            return false;

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        if($user->isCustomer())
            return $ticket->user_id == $user->id;

        if($user->isAdmin())
            return true;

        if($user->isOperator())
            return false;

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        if($user->isCustomer())
            return $user->customer and $ticket->customer_id == $user->customer->id;

        if($user->isAdmin())
            return true;

        if($user->isOperator())
            return false;

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Ticket $ticket): bool
    {
        if($user->isCustomer())
            return false;

        if($user->isAdmin())
            return true;

        if($user->isOperator()){
            return $user->role->permissions()
                ->where("name", BasicPermission::UPDATE->value)
                ->where('model', Ticket::class)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Ticket $ticket): bool
    {
        if($user->isCustomer())
            return false;

        if($user->isAdmin())
            return true;

        if($user->isOperator()){
            return
                $user->role->permissions()
                    ->where("name", BasicPermission::DELETE->value)
                    ->where('model', Ticket::class)
                    ->exists()
                and
                $ticket->type != UserType::ADMIN->value;
        }

        return false;
    }
}
