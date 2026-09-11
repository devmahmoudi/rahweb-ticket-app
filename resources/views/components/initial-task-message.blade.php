<div>
    <div>
        <ul class="list-unstyled">
            <li>وظیفه شماره: {{ $task->id }}</li>
            <li>عنوان وظیفه: {{ $task->title }}</li>
            <li>ایجاد کننده: <a href="#" class="text-secondary">{{ $task->creator->name }}</a></li>
            <li>تاریخ ایجاد: {{ $task->created_at }}</li>
        </ul>
        <br>
        <div>
            {{ $task->description }}
        </div>
    </div>

</div>
