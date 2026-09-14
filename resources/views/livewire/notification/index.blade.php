<div class="card w-100">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">اعلان‌ها</h4>

            <select wire:model.live="filter" class="form-select w-auto">
                <option value="all">همه</option>
                <option value="unread">خوانده‌نشده</option>
            </select>
        </div>

        @forelse($notifications as $notification)
            <div class="border rounded p-3 mb-3">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="fw-semibold">{{ $notification->data['title'] ?? 'اعلان' }}</div>
                        <div class="text-muted mt-1">{{ $notification->data['message'] ?? '' }}</div>
                        @if(! empty($notification->data['link']))
                            <a href="{{ $notification->data['link'] }}" class="text-primary mt-2 d-inline-block">
                                {{ $notification->data['link_text'] ?? 'مشاهده' }}
                            </a>
                        @endif
                    </div>

                    @if(is_null($notification->read_at))
                        <button type="button" class="btn btn-sm btn-primary" wire:click="markAsRead('{{ $notification->id }}')">
                            علامت‌گذاری به‌عنوان خوانده شده
                        </button>
                    @else
                        <span class="badge bg-success">خوانده شده</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-muted">هیچ اعلان‌ای وجود ندارد.</div>
        @endforelse

        <div class="d-flex justify-content-center mt-4">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
