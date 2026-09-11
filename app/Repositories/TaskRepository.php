<?php

namespace App\Repositories;

use App\Enums\Task\TaskStatus;
use App\Events\TaskReferred;
use App\Models\Chat;
use App\Models\ReferralHistory;
use App\Models\Task;
use App\Models\User;
use App\Repositories\Chat\ChatRepository;
use App\Repositories\Message\MessageRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class TaskRepository
{
    public function count(array $where = [], ?string $taskStatus = null): int
    {
        return
            Task::where($where)
                ->when($taskStatus, function ($query) use ($taskStatus){
                    $query->where('status', $taskStatus);
                })->count();
    }

    public function all(array $columns = ['*']):Collection
    {
        return Task::all();
    }

    public function paginate(int $perPage = 20)
    {
        return Task::paginate($perPage);
    }

    public function allSubmits(bool $pagination = true, int $perPage = 20)
    {
        return Task::where('creator_id', auth()->id())->paginate($perPage);
    }

    public function allReceives(bool $pagination = true, int $perPage = 20)
    {
        return $pagination ?
            Task::where('recipient_id', auth()->id())->paginate($perPage):
            Task::where('recipient_id', auth()->id())->get();
    }

    public function allNotClosedTasks(bool $pagination = true, int $perPage = 20)
    {
        return $pagination ?
            Task::where('status', '!=', TaskStatus::CLOSED->value)->paginate($perPage):
            Task::where('status', '!=', TaskStatus::CLOSED->value)->get();
    }

    public function create(array $data):Task|false
    {
        DB::beginTransaction();

        if(!$task = Task::create($data))
            return false;

        $chatRepository = app()->make(ChatRepository::class);

        if(!$chat = $chatRepository->createForTask($task))
            return false;

        DB::commit();

        return $task;
    }

    public function close(Task $task):bool
    {
        DB::beginTransaction();

        if(!$chat = $this->findRelevantChat($task))
            return false;

        $chatRepository = app()->make(ChatRepository::class);

        $chatRepository->kickMember($chat, $task->recipient);

        $messageRepository = app()->makeWith(MessageRepository::class, ['chat' => $chat]);

        if(!$messageRepository->createTaskClosedMessage($task))
            return false;

        $this->update($task, [
            'status' => TaskStatus::CLOSED->value,
        ]);

        DB::commit();

        return true;
    }

    public function update(Task $task, array $data):bool
    {
        return $task->update($data);
    }

    public function find(int $id):Task|null
    {
        return Task::find($id);
    }

    public function referral(Task $task, User $destination):bool
    {
        DB::beginTransaction();

        if(!$this->update($task, [
            'recipient_id' => $destination->id
        ]))
            return false;

        if(!$task->referralHistory()->create([
            'destination_id' => $destination->id,
        ]))
            return false;

        if(!$chat = $this->findRelevantChat($task))
            return false;

        $chatRepository = app()->make(ChatRepository::class);

        $chatRepository->joinMember($chat, $destination);

        $chatRepository->kickMember($chat, auth()->user());

        $messageRepository = app()->makeWith(MessageRepository::class, ['chat' => $chat]);

        if(!$messageRepository->createTaskReferredMessage($task, auth()->user()))
            return false;

        TaskReferred::dispatch($task, auth()->user());

        DB::commit();

        return true;
    }

    public function findRelevantChat(Task $task):Chat|null
    {
        $this->setPendingStatus($task);

        return Chat::withoutGlobalScopes()->where('meta', Task::class . ",$task->id")->first();
    }

    /**
     * Sets pending status for task if its recipient_id equivalent with current user id and its current status is sent.
     *
     * @param Task $task
     * @return void
     */
    private function setPendingStatus(Task $task):void
    {
        if($task->recipient_id == auth()->id())
            if($task->status == TaskStatus::SENT->value)
                $this->update($task, [
                    'status' => TaskStatus::PENDING->value
                ]);
    }
}
