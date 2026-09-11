<?php

namespace App\Policies;

use App\Enums\Permission\BasicPermission;
use App\Enums\Task\TaskStatus;
use App\Enums\User\UserType;
use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->isAdmin())
            return true;

        if ($user->isOperator()) {
            if ($role = $user->role)
                return $role->permissions()
                    ->where("name", BasicPermission::READ->value)
                    ->where('model', Task::class)
                    ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        return
            $task->creator_id == $user->id
            or
            $task->recipient_id == $user->id;

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->isAdmin())
            return true;

        if ($user->isOperator()) {
            if ($role = $user->role)
                return $role->permissions()
                    ->where("name", BasicPermission::CREATE->value)
                    ->where('model', Task::class)
                    ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        if ($user->isAdmin())
            return true;

        if ($user->isOperator()) {
            if ($task->creator_id == $user->id && $role = $user->role)
                return $role->permissions()
                    ->where("name", BasicPermission::UPDATE->value)
                    ->where('model', Task::class)
                    ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        if ($user->isAdmin())
            return true;

        if ($user->isOperator()) {
            if ($task->creator_id == $user->id && $role = $user->role)
                return $role->permissions()
                    ->where("name", BasicPermission::READ->value)
                    ->where('model', Task::class)
                    ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can close the task or no.
     */
    public function close(User $user, Task $task): bool
    {
        return
            $task->creator_id == $user->id
            and
            $task->status != TaskStatus::CLOSED->value;
    }

    /**
     * Determine whether the user can send close the task inquiry or no.
     */
    public function sendCloseInquiry(User $user, Task $task):bool
    {
        return
            $task->recipient_id == $user->id
            and
            $task->status == TaskStatus::PENDING->value;
    }

    /**
     * Determine whether the user can referral the task or no.
     */
    public function referral(User $user, Task $task):bool
    {
        return
            $task->recipient_id == $user->id
            and
            $task->status == TaskStatus::PENDING->value;
    }


    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Task $task): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        //
    }
}
