<?php

namespace App\Policies;

use App\Enums\Permission\BasicPermission;
use App\Models\Purchase;
use App\Models\User;

class PurchasePolicy
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
            if($purchase = $user->purchase)
                return $user->role->permissions
                    ->where("name", BasicPermission::READ->value)
                    ->where('model', Purchase::class)
                    ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Purchase $purchase): bool
    {
        if($user->isCustomer())
            return false;

        if($user->isAdmin())
            return true;

        if($user->isOperator()){
            return $user->role->permissions()
                ->where("name", BasicPermission::READ->value)
                ->where('model', Purchase::class)
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
                ->where('model', Purchase::class)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Purchase $purchase): bool
    {
        if($user->isCustomer())
            return false;

        if($user->isAdmin())
            return true;

        if($user->isOperator()){
            return $user->role->permissions()
                ->where("name", BasicPermission::UPDATE->value)
                ->where('model', Purchase::class)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Purchase $purchase): bool
    {
        if($user->isCustomer())
            return false;

        if($user->isAdmin())
            return true;

        if($user->isOperator()){
            return $user->role->permissions()
                ->where("name", BasicPermission::DELETE->value)
                ->where('model', Purchase::class)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Purchase $purchase): bool
    {
        if($user->isCustomer())
            return false;

        if($user->isAdmin())
            return true;

        if($user->isOperator()){
            return $user->role->permissions()
                ->where("name", BasicPermission::UPDATE->value)
                ->where('model', Purchase::class)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Purchase $purchase): bool
    {
        if($user->isCustomer())
            return false;

        if($user->isAdmin())
            return true;

        if($user->isOperator()){
            return $user->role->permissions()
                ->where("name", BasicPermission::DELETE->value)
                ->where('model', Purchase::class)
                ->exists();
        }

        return false;
    }
}
