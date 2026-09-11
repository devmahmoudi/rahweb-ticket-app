<div>
    <span @class([
        'badge',
        'bg-success' => \App\Enums\Bug\BugStatus::FIXED->value == $status,
        'bg-danger' => \App\Enums\Bug\BugStatus::PENDING->value == $status,
        ])>{{ $status }}</span>
</div>
