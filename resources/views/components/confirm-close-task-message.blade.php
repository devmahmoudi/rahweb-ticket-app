<div>
    <p>{{ $task->recipient->name }} درخواست بستن وظیفه را دارد. اگر این خواسته را تایید می کنید از طریق لینک زیر وظیفه را به وضعیت بسته شد انتقال دهید.</p>
    <div>
        <a href="{{ route('task.close', $task->id) }}" class="btn btn-outline-warning btn-sm">بستن وظیفه</a>
    </div>
</div>
