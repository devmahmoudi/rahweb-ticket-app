<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="my-0">تیکت ها</h4>

        @can('create', \App\Models\Ticket::class)
        <a href="{{ route('ticket.create') }}" class="btn btn-primary">
            <i class='bx bxs-plus-circle' style="padding-left: 10px"></i>
            <span>تیکت جدید</span>
        </a>
        @endcan
    </div>
    <div class="card-body">
        <x-alert />
        <div class="card text-center">
            <div class="card-header border-bottom">
                <ul class="nav nav-pills d-none d-md-flex" role="tablist">
                    @if(\Illuminate\Support\Facades\Gate::allows('cartable'))
                    <li class="nav-item">
                        <a href="{{ route('ticket.index', ['status' => \App\Livewire\Ticket\Index::CARTABLE_FILTER]) }}"
                            @class(['nav-link', 'active'=> $selectedStatus === \App\Livewire\Ticket\Index::CARTABLE_FILTER])>
                            کارتابل
                            @if($cartableCount > 0)
                            <span class="badge bg-secondary ms-1">{{ $cartableCount }}</span>
                            @endif
                        </a>
                    </li>
                    @endif
                    @foreach(\App\TicketStateManagement\TicketState::cases() as $state)
                    <li class="nav-item">
                        <a href="{{ route('ticket.index', ['status' => $state->value]) }}"
                            @class(['nav-link', 'active'=> $selectedStatus === $state->value])>
                            @switch($state)
                            @case(\App\TicketStateManagement\TicketState::PENDING)
                            در انتظار رسیدگی
                            @break
                            @case(\App\TicketStateManagement\TicketState::ACCEPTED)
                            در حال رسیدگی
                            @break
                            @case(\App\TicketStateManagement\TicketState::DELEGATED)
                            ارجاع شده
                            @break
                            @case(\App\TicketStateManagement\TicketState::WEBSERVICE)
                            ارسال شده به وب سرویس
                            @break
                            @case(\App\TicketStateManagement\TicketState::REJECTED)
                            رد شده
                            @break
                            @endswitch
                            @if(($ticketCounts[$state->value] ?? 0) > 0)
                            <span class="badge bg-secondary ms-1">{{ $ticketCounts[$state->value] }}</span>
                            @endif
                        </a>
                    </li>
                    @endforeach
                </ul>
                <form class="d-md-none p-3" method="GET" action="{{ route('ticket.index') }}">
                    <label class="visually-hidden" for="ticket-status">وضعیت تیکت</label>
                    <select id="ticket-status" name="status" class="form-select" onchange="this.form.submit()">
                        @if(\Illuminate\Support\Facades\Gate::allows('cartable'))
                        <option value="{{ \App\Livewire\Ticket\Index::CARTABLE_FILTER }}"
                            @selected($selectedStatus===\App\Livewire\Ticket\Index::CARTABLE_FILTER)>
                            کارتابل @if($cartableCount > 0)({{ $cartableCount }})@endif
                        </option>
                        @endif
                        @foreach(\App\TicketStateManagement\TicketState::cases() as $state)
                        <option value="{{ $state->value }}"
                            @selected($selectedStatus===$state->value)>
                            @switch($state)
                            @case(\App\TicketStateManagement\TicketState::PENDING)
                            در انتظار رسیدگی
                            @break
                            @case(\App\TicketStateManagement\TicketState::ACCEPTED)
                            در حال رسیدگی
                            @break
                            @case(\App\TicketStateManagement\TicketState::DELEGATED)
                            ارجاع شده
                            @break
                            @case(\App\TicketStateManagement\TicketState::WEBSERVICE)
                            ارسال شده به وب سرویس
                            @break
                            @case(\App\TicketStateManagement\TicketState::REJECTED)
                            رد شده
                            @break
                            @endswitch
                            @if(($ticketCounts[$state->value] ?? 0) > 0)
                            ({{ $ticketCounts[$state->value] }})
                            @endif
                        </option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="tab-content">
                @if(auth()->user()->isOperator() || auth()->user()->isSuperadmin())
                    <div class="d-flex flex-wrap gap-2 align-items-center p-3 border-bottom">
                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                wire:click="toggleBulkMode">
                            {{ $bulkMode ? 'لغو حالت گروهی' : 'تغییر وضعیت گروهی' }}
                        </button>
                        @if($bulkMode && count($selectedTicketIds) > 0)
                            @if(in_array('claim', $bulkActions, true))
                                <button type="button" class="btn btn-outline-primary btn-sm"
                                        wire:click="applyBulkTransition('claim')"
                                        wire:confirm="از پذیرش تیکت‌های انتخاب‌شده مطمئن هستید؟">
                                    پذیرش انتخاب‌شده‌ها
                                </button>
                            @endif
                            @if(in_array('delegate', $bulkActions, true))
                                <button type="button" class="btn btn-outline-warning btn-sm"
                                        wire:click="openBulkDelegateModal">
                                    ارجاع انتخاب‌شده‌ها
                                </button>
                            @endif
                            @if(in_array('publish', $bulkActions, true))
                                <button type="button" class="btn btn-outline-info btn-sm"
                                        wire:click="applyBulkTransition('publish')"
                                        wire:confirm="از ارسال تیکت‌های انتخاب‌شده به وب سرویس مطمئن هستید؟">
                                    ارسال انتخاب‌شده‌ها به وب سرویس
                                </button>
                            @endif
                            @if(in_array('reject', $bulkActions, true))
                                <button type="button" class="btn btn-outline-danger btn-sm"
                                        wire:click="applyBulkTransition('reject')"
                                        wire:confirm="از رد تیکت‌های انتخاب‌شده مطمئن هستید؟">
                                    رد انتخاب‌شده‌ها
                                </button>
                            @endif
                        @endif
                    </div>
                @endif
                <div class="table-responsive text-nowrap overflow-visible">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                @if($bulkMode)
                                    <th></th>
                                @endif
                                <th>#</th>
                                <th>عنوان</th>
                                <th>وضعیت</th>
                                <th>تاریخ</th>
                                <th>عمل‌ها</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach($tickets as $ticket)
                            <tr wire:key="{{ $ticket->id }}">
                                @if($bulkMode)
                                    <td>
                                        @can('update', $ticket)
                                            <input type="checkbox" value="{{ $ticket->id }}"
                                                   wire:model.live="selectedTicketIds"
                                                   aria-label="انتخاب {{ $ticket->title }}">
                                        @endcan
                                    </td>
                                @endif
                                <td>{{ $loop->iteration }}</td>
                                <td class="underline">
                                    <a href="#" wire:click="openChat({{ $ticket }})">{{ $ticket->title }}</a>
                                </td>
                                <td>
                                    <x-ticket-status :status="$ticket->status" />
                                </td>
                                <td>{{ \Morilog\Jalali\Jalalian::forge($ticket->created_at)->format('%D') }}</td>
                                <td>
                                    @can('view', $ticket)
                                    <button wire:click="openChat({{ $ticket }})" class="btn btn-outline-primary btn-sm">گفتگو</button>
                                    @endcan
                                    @can('update', $ticket)
                                    @switch($ticket->status)
                                    @case(\App\TicketStateManagement\TicketState::PENDING->value)
                                    <button class="btn btn-outline-primary btn-sm"
                                        wire:click="transition({{ $ticket->id }}, 'claim')"
                                        wire:confirm="از پذیرش این تیکت مطمئن هستید؟">
                                        پذیرش
                                    </button>
                                    @break
                                    @case(\App\TicketStateManagement\TicketState::ACCEPTED->value)
                                    <select wire:model="delegateTargetId" class="form-select form-select-sm d-inline-block w-auto">
                                        <option value="">انتخاب مدیر</option>
                                        @foreach(\App\Models\User::query()->where('type', \App\Enums\User\UserType::SUPERADMIN->value)->where('id', '!=', auth()->id())->get() as $admin)
                                        <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-outline-warning btn-sm"
                                        wire:click="delegate({{ $ticket->id }})"
                                        wire:confirm="از ارجاع این تیکت مطمئن هستید؟">
                                        ارجاع
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm"
                                        wire:click="transition({{ $ticket->id }}, 'reject')"
                                        wire:confirm="از رد این تیکت مطمئن هستید؟">
                                        رد
                                    </button>
                                    @break
                                    @case(\App\TicketStateManagement\TicketState::DELEGATED->value)
                                    @if(auth()->user()->isSuperadmin())
                                    <button class="btn btn-outline-info btn-sm"
                                        wire:click="transition({{ $ticket->id }}, 'publish')"
                                        wire:confirm="از ارسال تیکت به وب سرویس مطمئن هستید؟">
                                        ارسال به وب سرویس
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm"
                                        wire:click="transition({{ $ticket->id }}, 'reject')"
                                        wire:confirm="از رد این تیکت مطمئن هستید؟">
                                        رد
                                    </button>
                                    @endif
                                     @break
                                    @endswitch
                                 @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($showBulkDelegateModal)
            <div class="modal d-block" tabindex="-1" role="dialog" style="background: rgba(0, 0, 0, .5)">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">ارجاع گروهی تیکت‌ها</h5>
                            <button type="button" class="btn-close" wire:click="closeBulkDelegateModal"></button>
                        </div>
                        <div class="modal-body">
                            <select wire:model="bulkDelegateTargetId" class="form-select">
                                <option value="">انتخاب مدیر</option>
                                @foreach(\App\Models\User::query()->where('type', \App\Enums\User\UserType::SUPERADMIN->value)->where('id', '!=', auth()->id())->get() as $admin)
                                    <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeBulkDelegateModal">انصراف</button>
                            <button type="button" class="btn btn-primary" wire:click="applyBulkDelegate"
                                    wire:confirm="از ارجاع تیکت‌های انتخاب‌شده مطمئن هستید؟">
                                تایید ارجاع
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
    {{ $tickets->links('vendor.livewire.bootstrap') }}

</div>