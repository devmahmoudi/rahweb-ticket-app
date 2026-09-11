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
                <div class="mb-3 col-md-6">
                    <h6 class="fw-semibold mb-2">
                        کد ملی
                    </h6>
                    <p>{{ $user->customer->nation_code ?? 'خالی' }}</p>
                </div>
                <div class="mb-4 col-md-6" role="alert">
                    <h6 class="fw-semibold mb-1">شماره همراه</h6>
                    <span>{{ $user->customer->phone ?? 'خالی' }}</span>
                </div>
            <div class="mb-4 col-md-6" role="alert">
                <h6 class="fw-semibold mb-1">شماره تلفن</h6>
                <span>{{ $user->customer->tel ?? 'خالی' }}</span>
            </div>
            <div class="mb-4 col-md-6" role="alert">
                <h6 class="fw-semibold mb-1">آدرس</h6>
                <span>{{ $user->customer->address ?? 'خالی' }}</span>
            </div>
            <div class="mb-4 col-md-6" role="alert">
                <h6 class="fw-semibold mb-1">نوع</h6>
                <span>{{ $user->customer->type ?? 'خالی' }}</span>
            </div>
            @if($user->customer->type == \App\Enums\Customer\CustomerType::LEGAL->value)
                <div class="mb-4 col-md-6" role="alert">
                    <h6 class="fw-semibold mb-1">نام شرکت</h6>
                    <span>{{ $user->customer->company_name ?? 'خالی' }}</span>
                </div>
                <div class="mb-4 col-md-6" role="alert">
                    <h6 class="fw-semibold mb-1">شماره اقتصادی شرکت</h6>
                    <span>{{ $user->customer->economic_code ?? 'خالی' }}</span>
                </div>
            @endif
        </div>
    </div>
    <!-- Modal -->

    <!-- /Modal -->

    <!-- /Current Plan -->
</div>
