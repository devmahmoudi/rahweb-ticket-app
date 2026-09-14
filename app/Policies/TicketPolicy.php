<?php

namespace App\Policies;

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

        if($user->isSuperadmin())
            return true;

        return $user->isOperator();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ticket $ticket): bool
    {
        if($user->isCustomer())
            return $ticket->user_id == $user->id;

        if($user->isSuperadmin())
            return true;

        return $user->isOperator() && ($ticket->recipient_id == $user->id || $ticket->status == TicketStatus::WAITING->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if($user->isCustomer())
            return true;

        return $user->isSuperadmin() || $user->isCustomer();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        if($user->isCustomer())
            return $ticket->user_id == $user->id;

        if($user->isSuperadmin())
            return true;

        return $user->isOperator() && $ticket->recipient_id == $user->id;
    }

    /**
     * Determine whether the user can assign the ticket to another user.
     */
    public function assign(User $user, Ticket $ticket): bool
    {
        if ($ticket->status !== TicketStatus::PENDING->value) {
            return false;
        }

        return $user->isSuperadmin() || $ticket->recipient_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        if($user->isCustomer())
            return $user->customer and $ticket->customer_id == $user->customer->id;

        if($user->isSuperadmin())
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

        if($user->isSuperadmin())
            return true;

        return $user->isOperator() && $ticket->recipient_id == $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Ticket $ticket): bool
    {
        if($user->isCustomer())
            return false;

        if($user->isSuperadmin())
            return true;

        return false;
    }
}
