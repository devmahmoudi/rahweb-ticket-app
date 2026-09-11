<?php

namespace App\Repositories\Chat;

use App\Models\Chat;
use App\Models\Task;
use App\Repositories\Message\MessageRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait HasTaskChatMethods
{
    public function createForTask(Task $task):Chat|false
    {
        DB::beginTransaction();

        $chatData = [
            'name' => config('task.chat-name-prefix') . Str::words($task->title, 5),
            'meta' => Task::class . ",{$task->id}",
            'link' => config('task.chat-link-prefix') . $task->id,
        ];

        if(!$chat = $this->create($chatData, [auth()->id(), $task->recipient_id]))
            return false;

        $messageRepository = app()->makeWith(MessageRepository::class, ['chat' => $chat]);

        if(!$messageRepository->createInitialTaskMessage($task))
            return false;

        DB::commit();

        return $chat;
    }

    public function findRelevantTask(Chat $chat):Task|null
    {
        if(!$id = Str::of($chat->meta)->explode(',')[1])
            return null;

        return Task::find($id) ?? null;

    }
}
