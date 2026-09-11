<div>
    <div class="chat-history-footer shadow-sm">
        <form class="form-send-message d-flex justify-content-between align-items-center" wire:submit="send">
            <input wire:model="body" class="form-control message-input border-0 me-3 shadow-none"
                   placeholder="پیام خود را اینجا بنویسید">
            <div class="message-actions d-flex align-items-center">
                <label for="attach-doc" class="form-label mb-0">
                    <i class="bx bx-paperclip bx-sm cursor-pointer mx-3"></i>
                    <input type="file" id="attach-doc" hidden="">
                </label>
                <button class="btn btn-primary d-flex send-msg-btn">
                    <i class="bx bx-paper-plane me-md-1 me-0"></i>
                    <span class="align-middle d-md-inline-block d-none">ارسال</span>
                </button>
            </div>
        </form>
    </div>
</div>
