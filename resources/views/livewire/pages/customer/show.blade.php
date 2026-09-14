<div class="card mb-4">
    <!-- Current Plan -->
    <div class="card-header d-flex justify-content-between">
        <h5 class="mb-0">مشخصات مشتری</h5>
        <a href="{{ \Illuminate\Support\Facades\URL::previous() }}" class="btn btn-warning" >بازگشت</a>
    </div>
    <div class="card-body">
        <div class="row">
                <div class="mb-4 col-md-6">
                    <h6 class="fw-semibold mb-2">نام</h6>
                    <p>{{ $user->name }}</p>
                </div>
                <div class="mb-4 col-md-6">
                    <h6 class="fw-semibold mb-2">ایمیل</h6>
                    <p>{{ $user->email }}</p>
                </div>
        </div>
    </div>
    <!-- Modal -->

    <!-- /Modal -->

    <!-- /Current Plan -->
</div>
