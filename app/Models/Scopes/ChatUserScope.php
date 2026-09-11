<?php

namespace App\Models\Scopes;

use App\Enums\User\UserType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ChatUserScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $user = auth()->user();

        $builder
            ->whereHas('members', function ($query) use ($user){
                $query->where('user_id', $user->id);
            });

    }
}
