<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="my-0">وظایف</h4>

        @can('create', \App\Models\Task::class)
            <a href="{{ route('task.create') }}" class="btn btn-primary">
                <i class='bx bxs-plus-circle' style="padding-left: 10px"></i>
                <span>وظیفه جدید</span>
            </a>
        @endcan
    </div>
    <div class="card-body">
        <x-alert/>
        <div class="card text-center">
            <div class="card-header border-bottom">
                <ul class="nav nav-pills" role="tablist">
                    <li class="nav-item">
                        <a href="{{ route('task.index', ['type' => \App\Enums\Task\TaskType::SUBMIT->value]) }}"

                            @class(['nav-link', 'active' => (request()->query('type') == \App\Enums\Task\TaskType::SUBMIT->value)])>
                            {{ \App\Enums\Task\TaskType::SUBMIT->value }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('task.index', ['type' => \App\Enums\Task\TaskType::RECEIVED->value]) }}"

                            @class(['nav-link', 'active' => (request()->query('type') == \App\Enums\Task\TaskType::RECEIVED->value)])>
                            {{ \App\Enums\Task\TaskType::RECEIVED->value }}
                        </a>
                    </li>
                </ul>
            </div>
            <div class="tab-content">
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
                                    <a href="#" wire:click="openChat({{ $task }})">{{ $task->title }}</a>
                                </td>
                                <td>
                                    <x-task-status :status="$task->status"/>
                                </td>
                                <td>{{ \Morilog\Jalali\Jalalian::forge($task->created_at)->format('%D') }}</td>
                                <td>
                                    @can('view', $task)
                                        <button class="btn btn-outline-primary btn-sm"
                                                wire:click="openChat({{ $task }})">گفتگو
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

    </div>
    {{ $tasks->links('vendor.livewire.bootstrap') }}
</div>

