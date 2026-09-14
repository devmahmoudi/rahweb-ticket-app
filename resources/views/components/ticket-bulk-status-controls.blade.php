@props([
    'bulkMode' => false,
    'selectedTicketIds' => [],
    'bulkActions' => [],
])

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
