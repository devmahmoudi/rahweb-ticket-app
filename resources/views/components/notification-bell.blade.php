<li class="nav-item navbar-dropdown dropdown-user dropdown me-2 me-xl-0">
    <a class="nav-link dropdown-toggle hide-arrow position-relative" href="javascript:void(0);" data-bs-toggle="dropdown">
        <i class="bx bx-bell bx-sm"></i>
        @if($unreadCount > 0)
            <span class="badge rounded-pill bg-danger position-absolute" style="top: 4px; right: 4px; font-size: 10px; min-width: 18px; height: 18px; line-height: 14px;">
                {{ $unreadCount }}
            </span>
        @endif
    </a>

    <ul class="dropdown-menu dropdown-menu-end" style="min-width: 300px;">
        @forelse($notifications as $notification)
            <li class="dropdown-item-text px-3 py-2 border-bottom">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="fw-semibold">{{ $notification->data['title'] ?? 'اعلان' }}</div>
                        <div class="small text-muted mt-1">{{ $notification->data['message'] ?? '' }}</div>
                    </div>

                    <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-primary">علامت‌گذاری به‌عنوان خوانده شده</button>
                    </form>
                </div>
            </li>
        @empty
            <li class="dropdown-item text-muted">هیچ اعلان خوانده‌نشده‌ای وجود ندارد.</li>
        @endforelse

        <li class="dropdown-item text-center">
            <a href="{{ route('notifications.index') }}" class="text-primary">مشاهده همه اعلان‌ها</a>
        </li>
    </ul>
</li>
