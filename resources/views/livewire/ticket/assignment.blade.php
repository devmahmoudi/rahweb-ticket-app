<div>
    @if($ticketId)
        <div class="modal d-block" tabindex="-1" role="dialog" style="background: rgba(0, 0, 0, .5)">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">واگذاری تیکت</h5>
                        <button type="button" class="btn-close" wire:click="$set('ticketId', null)"></button>
                    </div>
                    <div class="modal-body">
                        <select wire:model="userId" class="form-select">
                            <option value="">پاسخگوی جدید را انتخاب کنید</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('userId') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="$set('ticketId', null)">انصراف</button>
                        <button type="button" class="btn btn-primary" wire:click="assign">تایید واگذاری</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
