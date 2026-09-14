<?php

namespace App\Policies;

use App\Enums\User\UserType;
use App\Models\Ticket;
use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if($user->isCustomer())
            return false;

        if($user->isSuperadmin())
            return true;

        return $user->isOperator();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $target): bool
    {
        if($user->isCustomer())
            return $target->id == $user->id;

        if($user->isSuperadmin())
            return true;

        return $user->isOperator()
            && $target->type == UserType::CUSTOMER->value
            && Ticket::withoutGlobalScopes()->where('user_id', $target->id)
                ->where('recipient_id', $user->id)
                ->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if($user->isCustomer())
            return false;

        return $user->isSuperadmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $target): bool
    {
        if($user->isCustomer())
            return false;

        if($user->isSuperadmin())
            return true;

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $target): bool
    {
        if($user->isCustomer())
            return false;

        if($user->isSuperadmin())
            return true;
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $target): bool
    {
        if($user->isCustomer())
            return false;

        if($user->isSuperadmin())
            return true;
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $target): bool
    {
        if($user->isCustomer())
            return false;

        if($user->isSuperadmin())
            return true;
        return false;
    }
}
