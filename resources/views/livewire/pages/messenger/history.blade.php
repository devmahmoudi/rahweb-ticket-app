<div class="col app-chat-history bg-body">
    <div class="chat-history-wrapper">
        <div class="chat-history-header border-bottom px-1 py-2">
            <div class="d-flex justify-content-between align-items-center py-1">
                <div class="d-flex overflow-hidden align-items-center">
                    <i class="bx bx-menu bx-sm cursor-pointer d-lg-none d-block me-2"
                       data-bs-toggle="sidebar" data-overlay="" data-target="#app-chat-contacts"></i>
                    <div class="chat-contact-info flex-grow-1 ms-3">
                        <h6 class="m-0">{{ $chat->name }}</h6>
                        <small @class(["user-status", "text-muted" => !$isContactOnline, "text-primary" => $isContactOnline])>
                            @if($isContactOnline)
                                مخاطب آنلاین است
                            @else
                                مخاطب آفلاین است
                            @endif
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="chat-history-body bg-body" style="overflow: auto">
            <ul class="list-unstyled chat-history mb-0">
                @foreach($this->groupByMessages($messages) as $key => $group)
                    <li @class(["chat-message", "chat-message-right" => ($group[0]->user_id == auth()->id())])>
                        <div class="d-flex overflow-hidden">
                            <div class="chat-message-wrapper flex-grow-1">
                                @foreach($group as $message)
                                    <div @class(["chat-message-text", "my-2", 'unseen-message' => ($message->user_id != auth()->id() && $message->status != \App\Enums\Message\MessageStatus::SEEN->value)]) data-message-id="{{ $message->id }}">
                                        <p class="mb-0">{!! $message->body !!}</p>
                                    </div>
                                @endforeach
                                <div @class(["text-end" => ($group[0]->user_id == auth()->id()), "text-muted", "mt-1"])>
                                    <i @class(["bx",
                                        "bx-check-double" => ($group[0]->user_id == auth()->id()),
                                        "text-success" => ($group->last()->status === \App\Enums\Message\MessageStatus::SEEN->value)])></i>
                                    <small>{{ \Illuminate\Support\Str::of($key)->match('(\d{2}:\d{2})') }}</small>
                                </div>
                            </div>
                            <div class="user-avatar flex-shrink-0 ms-3">

                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
            <div class="ps__rail-x" style="left: 0px; bottom: -787px;">
                <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
            </div>
            <div class="ps__rail-y" style="top: 787px; height: 567px; right: 1031px;">
                <div class="ps__thumb-y" tabindex="0" style="top: 330px; height: 237px;"></div>
            </div>
        </div>
        <!-- Chat message form -->
        <livewire:messenger.sender :$chat/>
    </div>
</div>

@script
<script>
    $wire.on('MessagesListUpdated', ($event) => {
        setTimeout(function(){
            let lastMessage = document.querySelector('.chat-message:last-child')

            let chatHistoryBody = document.querySelector('.chat-history-body')

            chatHistoryBody.scrollTo(0, chatHistoryBody.scrollHeight + lastMessage.scrollHeight);

            const visibleMessages = getVisibleMessages();

            visibleMessages.forEach(notifySeenMessage)
        }, 500)
    })

    // Get the chat history body element and all message elements
    const chatHistoryBody = document.querySelector('.chat-history-body');

    // Function to check if an element is in the viewport
    function isInViewport(element) {
        const rect = element.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }

    // Function to extract visible messages
    function getVisibleMessages() {
        const messages = Array.from(chatHistoryBody.querySelectorAll('.unseen-message'));

        const visibleMessages = messages.filter(message => isInViewport(message));
        return visibleMessages;
    }

    function notifySeenMessage(messageEle){
        console.log(messageEle)
        messageEle.classList.remove('unseen-message')

        let id = messageEle.getAttribute('data-message-id')

        $wire.dispatch('i-seen-message', { messageId: id})
    }

    const visibleMessages = getVisibleMessages();

    visibleMessages.forEach(notifySeenMessage)

    // Event listener for scroll events
    chatHistoryBody.addEventListener('scroll', function() {
        const visibleMessages = getVisibleMessages();

        visibleMessages.forEach(notifySeenMessage)
    });
</script>
@endscript
