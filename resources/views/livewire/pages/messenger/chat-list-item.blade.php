<li class="chat-contact-list-item position-relative">
    <a class="d-flex align-items-center" wire:click="open({{ $chat }})">
        <div @class(["flex-shrink-0", "avatar", "avatar-busy" => $isOnline])>
            <span class="avatar-initial rounded-circle bg-label-success">{{ \Illuminate\Support\Str::take(\Illuminate\Support\Str::reverse($chat->name), 2) }}</span>
        </div>
        <div class="chat-contact-info flex-grow-1 ms-3">
            <h6 class="chat-contact-name text-truncate m-0">{{ \Illuminate\Support\Str::words($chat->name, 4) }}</h6>
            <p class="chat-contact-status text-truncate mb-0 text-muted">
                {{ $lastMessage }}
            </p>
            @if($newMessagesCount > 0)
                <span class="badge bg-primary py-1 px-2 rounded-circle" style="position: absolute; left: 10px; bottom: 20%">{{ $newMessagesCount }}</span>
            @endif
        </div>
        <small class="text-muted mb-auto">
            {{
                ($agoDay = \Illuminate\Support\Carbon::parse($chat->created_at)->diffInDays()) < 1 ?
                'امروز' :
                "{$agoDay} روز پیش"
            }}
        </small>
    </a>
</li>
