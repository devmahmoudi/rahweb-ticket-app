<?php

namespace App\Policies;

use App\Enums\Permission\BasicPermission;
use App\Models\Media;
use App\Models\User;

class MediaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->isCustomer())
            return false;

        if ($user->isAdmin())
            return true;

        if ($user->isOperator()) {
            return $user->role->permissions()
                ->where("name", BasicPermission::READ->value)
                ->where('model', Media::class)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function download(User $user, Media $media): bool
    {
        if ($user->isCustomer())
            return false;

        if ($user->isAdmin())
            return true;

        if ($user->isOperator()) {
            return $user->role->permissions()
                ->where("name", BasicPermission::READ->value)
                ->where('model', Media::class)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function upload(User $user): bool
    {
        if ($user->isCustomer())
            return false;

        if ($user->isAdmin())
            return true;

        if ($user->isOperator()) {
            return $user->role->permissions()
                ->where("name", BasicPermission::CREATE->value)
                ->where('model', Media::class)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Media $media): bool
    {
        if ($user->isCustomer())
            return false;

        if ($user->isAdmin())
            return true;

        if ($user->isOperator()) {
            return $user->role->permissions()
                ->where("name", BasicPermission::UPDATE->value)
                ->where('model', Media::class)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Media $media): bool
    {
        if ($user->isCustomer())
            return false;

        if ($user->isAdmin())
            return true;

        if ($user->isOperator()) {
            return $user->role->permissions()
                ->where("name", BasicPermission::DELETE->value)
                ->where('model', Media::class)
                ->exists();
        }

        return false;
    }
}
