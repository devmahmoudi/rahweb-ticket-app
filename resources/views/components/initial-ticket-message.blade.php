<div>
    <ul class="list-unstyled">
        <li>تیکت شماره: {{ $ticket->id }}</li>
        <li>عنوان تیکت: {{ $ticket->title }}</li>
        <li>کاربر: <a href="{{ route('customer.show', $ticket->user_id) }}" class="text-secondary">{{ $ticket->owner->name }}</a></li>
        <li>تاریخ ایجاد: {{ $ticket->created_at }}</li>
    </ul>
    <br>
    <div>
        {{ $ticket->description }}
    </div>
</div>
