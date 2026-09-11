<?php

namespace App\Models\Scopes;

use App\Enums\User\UserType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TaskUserTypeScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $user = auth()->user();

        if($user->type == UserType::OPERATOR->value)
            $builder->where(function($query) use ($user){
                $query->where('creator_id', $user->id)
                    ->orWhere('recipient_id', $user->id);
            });
    }
}
