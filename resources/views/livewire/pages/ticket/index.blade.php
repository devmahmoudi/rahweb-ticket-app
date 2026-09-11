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
                <ul class="nav nav-pills" role="tablist">
                    @if(auth()->user()->type != \App\Enums\User\UserType::CUSTOMER->value)
                        <li class="nav-item">
                            <a href="{{ route('ticket.index', ['status' => \App\Enums\Ticket\TicketStatus::WAITING->value]) }}"

                                @class(['nav-link', 'active' => (request()->query('status') == \App\Enums\Ticket\TicketStatus::WAITING->value)])>در
                                انتظار پاسخگو
                            </a>
                        </li>
                    @endcan
                    <li class="nav-item">
                        <a href="{{ route('ticket.index', ['status' => \App\Enums\Ticket\TicketStatus::PENDING->value]) }}"

                            @class(['nav-link', 'active' => (request()->query('status') == \App\Enums\Ticket\TicketStatus::PENDING->value)])>تیکت
                            های باز
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('ticket.index', ['status' => \App\Enums\Ticket\TicketStatus::CLOSED->value]) }}"

                            @class(['nav-link', 'active' => (request()->query('status') == \App\Enums\Ticket\TicketStatus::CLOSED->value)])>بسته
                            شده
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
</div>

