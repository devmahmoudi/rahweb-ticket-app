<?php

namespace App\Models\Scopes;

use App\Enums\User\UserType;
use App\TicketStateManagement\TicketState;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Scoping read tickets according user type.
 * if authenticated user type is superadmin, they
 * can view all tickets whereas
 * if authenticated user type is customer,
 * he can just view its own tickets.
 * If authenticated user type is operator,
 * they can view waiting tickets in their workgroups
 * and tickets assigned to them.
 */
class TicketUserTypeScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if(auth()->user()->type == UserType::SUPERADMIN->value)
            return;

        elseif(auth()->user()->type == UserType::CUSTOMER->value)
            $builder->where('user_id', auth()->id());

        elseif(auth()->user()->type == UserType::OPERATOR->value)
            $builder
                ->where(function($query){
                    $query
                        ->where(function($query){
                           $query
                               ->where('status', TicketState::WAITING->value)
                               ->whereIn('workgroup_id', auth()->user()->workgroups->pluck('id')->toArray());
                        })
                        ->orWhere('recipient_id', auth()->id());
                });
    }
}
