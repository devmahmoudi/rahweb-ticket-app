<?php

namespace App\Policies;

use App\Enums\Permission\BasicPermission;
use App\Enums\User\UserType;
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

        if($user->isAdmin())
            return true;

        if($user->isOperator()){
            if($role = $user->role)
            return $role->permissions()
                ->where("name", BasicPermission::READ->value)
                ->where('model', User::class)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $target): bool
    {
        if($user->isCustomer())
            return $target->id == auth()->id();

        if($user->isAdmin())
            return true;

        if($user->isOperator()){
            return $user->role->permissions()
                ->where("name", BasicPermission::READ->value)
                ->where('model', User::class)
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
                ->where('model', User::class)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $target): bool
    {
        if($user->isCustomer())
            return false;

        if($user->isAdmin())
            return true;

        if($user->isOperator()){
            return $user->role->permissions()
                ->where("name", BasicPermission::UPDATE->value)
                ->where('model', User::class)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $target): bool
    {
        if($user->isCustomer())
            return false;

        if($user->isAdmin())
            return true;

        if($user->isOperator()){
            return
                $user->role->permissions()
                ->where("name", BasicPermission::DELETE->value)
                ->where('model', User::class)
                ->exists()
                and
                $target->type != UserType::ADMIN->value;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $target): bool
    {
        if($user->isCustomer())
            return false;

        if($user->isAdmin())
            return true;

        if($user->isOperator()){
            return $user->role->permissions()
                ->where("name", BasicPermission::UPDATE->value)
                ->where('model', User::class)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $target): bool
    {
        if($user->isCustomer())
            return false;

        if($user->isAdmin())
            return true;

        if($user->isOperator()){
            return
                $user->role->permissions()
                    ->where("name", BasicPermission::DELETE->value)
                    ->where('model', User::class)
                    ->exists()
                and
                $target->type != UserType::ADMIN->value;
        }

        return false;
    }
}
