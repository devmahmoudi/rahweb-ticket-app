<div class="card mb-4">
    <!-- Current Plan -->
    <div class="card-header d-flex justify-content-between">
        <h5 class="mb-0">مشخصات باگ</h5>
        <a href="{{ route('bug.index') }}" class="btn btn-warning">بازگشت</a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="mb-4 col-md-6">
                <h6 class="fw-semibold mb-2">عنوان</h6>
                <p>{{ $bug->title }}</p>
            </div>
            <div class="mb-4 col-md-6">
                <h6 class="fw-semibold mb-2">کاربر ایجاد کننده</h6>
                <p>{{ $bug->creator->name }}</p>
            </div>
            <div class="mb-4 col-md-6">
                <h6 class="fw-semibold mb-2">نوع کاربر</h6>
                <p>{{ $bug->creator->type }}</p>
            </div>
            <div class="mb-4 col-md-6">
                <h6 class="fw-semibold mb-2">وضعیت</h6>
                <div class="d-flex flex-row">
                    <p>
                        <x-bug-status :status="$bug->status"/>
                    </p>
                    @can('changeStatus', $bug)
                        <button class="btn btn-info btn-sm mx-2" wire:click="changeStatus">
                            تغییر وضعیت به
                            @if($bug->status == \App\Enums\Bug\BugStatus::PENDING->value)
                                برطرف شده
                            @else
                                برطرف نشده
                            @endif
                        </button>
                    @endcan
                </div>
            </div>
            <div class="mb-4 col-md-12">
                <h6 class="fw-semibold mb-2">توضیحات</h6>
                <p>{{ $bug->description }}</p>
            </div>
        </div>
    </div>
    <!-- Modal -->

    <!-- /Modal -->

    <!-- /Current Plan -->
</div>
