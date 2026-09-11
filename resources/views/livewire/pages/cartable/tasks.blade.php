<div class="card overflow-auto" style="max-height: 50vh">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>وظایف</h5>
    </div>
    <div class="card-body">
        @if(session()->get('alert-focus') == 'task')
            <x-alert/>
        @endif
        <div class="table-responsive text-nowrap overflow-visible">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>عنوان</th>
                    <th>وضعیت</th>
                    <th>تاریخ</th>
                    <th>عمل‌ها</th>
                </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                @foreach($tasks as $task)
                    <tr wire:key="{{ $task->id }}">
                        <td>{{ $loop->iteration }}</td>
                        <td class="underline">
                            <a href="#" wire:click="open({{ $task }})">
                                {{ \Illuminate\Support\Str::words($task->title, 3) }}
                                @if($task->status == \App\Enums\Task\TaskStatus::SENT->value && $task->creator_id != auth()->id())
                                    <small class="badge text-white bg-danger p-1">جدید</small>
                                @endif
                            </a>
                        </td>
                        <td>
                            <x-task-status :status="$task->status"/>
                        </td>
                        <td>{{ \Morilog\Jalali\Jalalian::forge($task->created_at)->format('%D') }}</td>
                        <td>
                            @can('view', $task)
                                <button class="btn btn-outline-primary btn-sm" wire:click="open({{ $task }})">گفتگو
                                </button>
                            @endcan
                            @can('close', $task)
                                <button class="btn btn-outline-warning btn-sm"
                                        wire:click="close({{ $task }})"
                                        wire:confirm="ایا از بستن این وظیفه مطمئن هستید ؟">
                                    بستن وظیفه
                                </button>
                            @endcan
                            @can('sendCloseInquiry', $task)
                                <button class="btn btn-outline-warning btn-sm"
                                        wire:click="sendCloseInquiry({{ $task }})"
                                        wire:confirm="ایا از ارسال درخواست بستن این وظیفه مطمئن هستید ؟">
                                    ارسال درخواست بستن وظیفه
                                </button>
                            @endcan
                            @can('referral', $task)
                                <a href="{{ route('task.referral', $task) }}" class="btn btn-outline-info btn-sm">
                                    ارجاع وظیفه
                                </a>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>
