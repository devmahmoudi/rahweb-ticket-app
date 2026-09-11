<?php

namespace App\Policies;

use App\Enums\Permission\BasicPermission;
use App\Models\Bug;
use App\Models\User;

class BugPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if($user->isOperator()){
            if($bug = $user->role)
                return $bug->permissions()
                    ->where("name", BasicPermission::READ->value)
                    ->where('model', Bug::class)
                    ->exists();
        }

        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Bug $bug): bool
    {
        if($user->isCustomer())
            return $bug->creator_id == $user->id;

        if($user->isOperator()){
            return $user->role->permissions()
                ->where("name", BasicPermission::READ->value)
                ->where('model', Bug::class)
                ->exists();
        }

        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if($user->isOperator()){
            return $user->role->permissions()
                ->where("name", BasicPermission::CREATE->value)
                ->where('model', Bug::class)
                ->exists();
        }

        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Bug $bug): bool
    {
        if($user->isCustomer())
            return $bug->creator_id == $user->id;

        if($user->isOperator()){
            return $user->role->permissions()
                ->where("name", BasicPermission::UPDATE->value)
                ->where('model', Bug::class)
                ->exists();
        }

        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Bug $bug): bool
    {
        if($user->isCustomer())
            return false;

        if($user->isOperator()){
            return $user->role->permissions()
                ->where("name", BasicPermission::DELETE->value)
                ->where('model', Bug::class)
                ->exists();
        }

        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Bug $bug): bool
    {
        if($user->isOperator()){
            return $user->role->permissions()
                ->where("name", BasicPermission::UPDATE->value)
                ->where('model', Bug::class)
                ->exists();
        }

        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Bug $bug): bool
    {
        if($user->isOperator()){
            return $user->role->permissions()
                ->where("name", BasicPermission::DELETE->value)
                ->where('model', Bug::class)
                ->exists();
        }

        return true;
    }

    /**
     * Determine whether the user can change the bug status.
     */
    public function changeStatus(User $user, Bug $bug): bool
    {
        return $user->isAdmin();
    }
}
