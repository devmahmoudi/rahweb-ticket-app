<div class="col app-chat-contacts app-sidebar flex-grow-0 overflow-hidden border-end"
     id="app-chat-contacts">
    <div class="sidebar-header px-4 border-bottom" style="padding: 17px 10px;">
        <div class="d-flex align-items-center">
            <div class="flex-grow-1 input-group input-group-merge rounded-pill">
                            <span class="input-group-text" id="basic-addon-search31"><i
                                    class="bx bx-search fs-4"></i></span>
                <input wire:model.live="search" type="text" class="form-control chat-search-input" placeholder="جستجو ..."
                       aria-label="Search..." aria-describedby="basic-addon-search31">
            </div>
        </div>
        <i class="bx bx-x cursor-pointer position-absolute top-0 end-0 mt-2 me-1 fs-4 d-lg-none d-block"
           data-overlay="" data-bs-toggle="sidebar" data-target="#app-chat-contacts"></i>
    </div>
    <div class="sidebar-body ps ps__rtl ps--active-y">
        <!-- Chats -->
        <ul class="list-unstyled chat-contact-list" id="chat-list">
            <li @class(['chat-contact-list-item', 'chat-list-item-0', 'd-none' => $chats->isNotEmpty()])>
                <h6 class="text-muted mb-0">گفتگویی پیدا نشد</h6>
            </li>
            @foreach($chats as $item)
                <livewire:messenger.chat-list-item :chat="$item"/>
            @endforeach
        </ul>
        <div class="ps__rail-x" style="left: 0px; bottom: -518px;">
            <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
        </div>
        <div class="ps__rail-y" style="top: 518px; height: 651px; right: 322px;">
            <div class="ps__thumb-y" tabindex="0" style="top: 289px; height: 362px;"></div>
        </div>
    </div>
</div>
