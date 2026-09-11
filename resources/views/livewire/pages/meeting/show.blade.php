<div class="card mb-4">
    <!-- Current Plan -->
    <div class="card-header d-flex justify-content-between">
        <h5 class="mb-0">جزئیات جلسه {{ $meeting->title }}</h5>
        <a href="{{ route("meeting.index") }}" class="btn btn-warning">بازگشت</a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="mb-4 col-md-6">
                <h6 class="fw-semibold mb-2">عنوان</h6>
                <p>{{ $meeting->title }}</p>
            </div>
            <div class="mb-4 col-md-6">
                <h6 class="fw-semibold mb-2">مشتری</h6>
                <p>{{ $meeting->customer->user->name }}</p>
            </div>
            <div class="mb-4 col-md-6">
                <h6 class="fw-semibold mb-2">حضار</h6>
                <p>{{ $meeting->participants }}</p>
            </div>
            <div class="mb-4 col-md-6">
                <h6 class="fw-semibold mb-2">تاریخ</h6>
                <p>{{ $meeting->date }}</p>
            </div>
            <div class="mb-4 col-md-6" role="alert">
                <h6 class="fw-semibold mb-1">اوپراتور ثبت کننده</h6>
                <span>{{ $meeting->creator->name }}</span>
            </div>
            <div class="mb-4 col-md-12" role="alert">
                <h6 class="fw-semibold mb-1">توضیحات</h6>
                <span>{{ $meeting->text }}</span>
            </div>

        </div>
    </div>
    <!-- Modal -->

    <!-- /Modal -->

    <!-- /Current Plan -->
</div>
