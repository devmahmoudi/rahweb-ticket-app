<?php

use App\Models\User;
use App\Models\Workgroup;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('admin', function (User $user){
   return $user->type == \App\Enums\User\UserType::ADMIN->value;
});

Broadcast::channel('workgroup.{workgroup_id}', function (User $user, int $workgroup_id){
    return in_array($workgroup_id, $user->workgroups->pluck('id')->toArray());
});

Broadcast::channel('user.{user_id}', function (User $user, int $user_id){
    return $user->id == $user_id;
});


Broadcast::channel(config('chat.channel-prefix'). "{chat}", function (User $user, \App\Models\Chat $chat){
    return $chat->members()->where('users.id', $user->id)->exists();
});
