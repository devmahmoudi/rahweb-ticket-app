<?php

namespace App\Repositories\Chat;

use App\Models\Chat;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use function app;
use function auth;
use function config;

class ChatRepository
{
    use HasTicketChatMethods, HasTaskChatMethods;

    public function find(int $id):Chat|null
    {
        return Chat::find($id);
    }

    public function all(array $columns = ['*']):Collection
    {
        return Chat::all($columns);
    }

    public function create(array $data, array $user_ids):Chat|false
    {
        if(!$chat = Chat::create($data))
            return false;

        if(!$chat->members()->sync($user_ids))
            return false;

        return $chat;
    }

    public function joinMember(Chat $chat, User $member):void
    {
        $chat->members()->attach($member->id);

        $chat->save();
    }

    public function kickMember(Chat $chat, User $member):void
    {
        $chat->members()->detach($member->id);

        $chat->save();
    }
}
