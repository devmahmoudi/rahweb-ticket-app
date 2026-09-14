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
        <x-alert/>
        <div class="card text-center">
            <div class="card-header border-bottom">
                <ul class="nav nav-pills d-none d-md-flex" role="tablist">
                    @if(\Illuminate\Support\Facades\Gate::allows('cartable'))
                        <li class="nav-item">
                            <a href="{{ route('ticket.index', ['status' => \App\Livewire\Ticket\Index::CARTABLE_FILTER]) }}"
                               @class(['nav-link', 'active' => $selectedStatus === \App\Livewire\Ticket\Index::CARTABLE_FILTER])>
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
                               @class(['nav-link', 'active' => $selectedStatus === $state->value])>
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
                                @selected($selectedStatus === \App\Livewire\Ticket\Index::CARTABLE_FILTER)>
                                کارتابل @if($cartableCount > 0)({{ $cartableCount }})@endif
                            </option>
                        @endif
                        @foreach(\App\TicketStateManagement\TicketState::cases() as $state)
                            <option value="{{ $state->value }}"
                                @selected($selectedStatus === $state->value)>
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
                        @foreach($tickets as $ticket)
                            <tr wire:key="{{ $ticket->id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td class="underline">
                                    <a href="#" wire:click="openChat({{ $ticket }})">{{ $ticket->title }}</a>
                                </td>
                                <td>
                                    <x-ticket-status :status="$ticket->status"/>
                                </td>
                                <td>{{ \Morilog\Jalali\Jalalian::forge($ticket->created_at)->format('%D') }}</td>
                                <td>
                                    @can('view', $ticket)
                                        <button class="btn btn-outline-primary btn-sm">گفتگو</button>
                                    @endcan
                                    @can('assign', $ticket)
                                        <button class="btn btn-outline-warning btn-sm"
                                                wire:click="$dispatch('open-ticket-assignment', { ticketId: {{ $ticket->id }} })">
                                            واگذاری
                                        </button>
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
    {{ $tickets->links('vendor.livewire.bootstrap') }}

    <livewire:ticket.assignment/>
</div>

