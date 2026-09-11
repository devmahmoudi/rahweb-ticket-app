<?php

namespace App\Policies;

use App\Enums\Permission\BasicPermission;
use App\Models\Meeting;
use App\Models\User;

class MeetingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if($user->isCustomer())
            return false;

        if($user->isAdmin())
            return true;

        if($user->isOperator()){
            if($meeting = $user->role)
                return $meeting->permissions()
                    ->where("name", BasicPermission::READ->value)
                    ->where('model', Meeting::class)
                    ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Meeting $meeting): bool
    {
        if($user->isCustomer())
            return false;

        if($user->isAdmin())
            return true;

        if($user->isOperator()){
            return $user->role->permissions()
                ->where("name", BasicPermission::READ->value)
                ->where('model', Meeting::class)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if($user->isCustomer())
            return false;

        if($user->isAdmin())
            return true;

        if($user->isOperator()){
            return $user->role->permissions()
                ->where("name", BasicPermission::CREATE->value)
                ->where('model', Meeting::class)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Meeting $meeting): bool
    {
        if($user->isCustomer())
            return false;

        if($user->isAdmin())
            return true;

        if($user->isOperator()){
            return $user->role->permissions()
                ->where("name", BasicPermission::UPDATE->value)
                ->where('model', Meeting::class)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Meeting $meeting): bool
    {
        if($user->isCustomer())
            return false;

        if($user->isAdmin())
            return true;

        if($user->isOperator()){
            return $user->role->permissions()
                ->where("name", BasicPermission::DELETE->value)
                ->where('model', Meeting::class)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Meeting $meeting): bool
    {
        if($user->isCustomer())
            return false;

        if($user->isAdmin())
            return true;

        if($user->isOperator()){
            return $user->role->permissions()
                ->where("name", BasicPermission::UPDATE->value)
                ->where('model', Meeting::class)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Meeting $meeting): bool
    {
        if($user->isCustomer())
            return false;

        if($user->isAdmin())
            return true;

        if($user->isOperator()){
            return $user->role->permissions()
                ->where("name", BasicPermission::DELETE->value)
                ->where('model', Meeting::class)
                ->exists();
        }

        return false;
    }
}
