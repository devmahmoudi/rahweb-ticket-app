<?php

namespace App\Policies;

use App\Enums\Permission\BasicPermission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RolePolicy
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
                    ->where('model', Role::class)
                    ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Role $role): bool
    {
        if($user->isCustomer())
            return false;

        if($user->isAdmin())
            return true;

        if($user->isOperator()){
            return $user->role->permissions()
                ->where("name", BasicPermission::READ->value)
                ->where('model', Role::class)
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
                ->where('model', Role::class)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Role $role): bool
    {
        if($user->isCustomer())
            return false;

        if($user->isAdmin())
            return true;

        if($user->isOperator()){
            return $user->role->permissions()
                ->where("name", BasicPermission::UPDATE->value)
                ->where('model', Role::class)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Role $role): bool
    {
        if($user->isCustomer())
            return false;

        if($user->isAdmin())
            return true;

        if($user->isOperator()){
            return $user->role->permissions()
                ->where("name", BasicPermission::DELETE->value)
                ->where('model', Role::class)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Role $role): bool
    {
        if($user->isCustomer())
            return false;

        if($user->isAdmin())
            return true;

        if($user->isOperator()){
            return $user->role->permissions()
                ->where("name", BasicPermission::UPDATE->value)
                ->where('model', Role::class)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Role $role): bool
    {
        if($user->isCustomer())
            return false;

        if($user->isAdmin())
            return true;

        if($user->isOperator()){
            return $user->role->permissions()
                ->where("name", BasicPermission::DELETE->value)
                ->where('model', Role::class)
                ->exists();
        }

        return false;
    }
}
