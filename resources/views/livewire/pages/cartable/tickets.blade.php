<div class="card overflow-auto" style="max-height: 50vh">
    <div class="card-header">
        <h5>تیکت ها</h5>
    </div>
    <div class="card-body">
        <x-alert/>
        <div class="table-responsive text-nowrap overflow-visible">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th class="d-none d-xl-table-cell">#</th>
                    <th>عنوان</th>
                    <th class="d-none d-sm-table-cell d-xl-none">وضعیت</th>
                    <th>تاریخ</th>
                    <th>عمل ها</th>
                </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                @foreach($tickets as $ticket)
                    <tr wire:key="{{ $ticket->id }}">
                        <td class="d-none d-xl-table-cell">{{ $loop->iteration }}</td>
                        <td class="underline">
                            <a href="#" wire:click="open({{ $ticket }})">
                                {{ \Illuminate\Support\Str::words($ticket->title, 3) }}
                                @if($ticket->status == \App\Enums\Ticket\TicketStatus::WAITING->value)
                                    <small class="badge text-white bg-danger p-1">جدید</small>
                                @endif
                            </a>
                        </td>
                        <td class="d-none d-sm-table-cell d-xl-none">
                            <x-ticket-status :status="$ticket->status"/>
                        </td>
                        <td>
                            <small>{{ \Morilog\Jalali\Jalalian::forge($ticket->created_at)->format('H:i Y/m/d') }}</small>
                        </td>
                        <td>
                            @if($ticket->status == \App\Enums\Ticket\TicketStatus::WAITING->value)
                                <a wire:click="open({{ $ticket }})" class="btn btn-outline-primary btn-sm" href="#"><i
                                        class="bx bx-message-dots me-1"></i>پذیرش
                                    تیکت</a>
                            @else
                                <a class="btn btn-outline-warning btn-sm" href="#"
                                   wire:click="sendCloseTicketInquiry({{ $ticket }})"
                                   wire:confirm="ایا از ارسال درخواست بستن تیکت مطمئن هستید ؟">
                                    <i class="bx bx-message-dots me-1"></i>درخواست
                                    بستن تیکت
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>
