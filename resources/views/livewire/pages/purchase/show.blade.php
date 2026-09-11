<div class="card mb-4">
    <!-- Current Plan -->
    <div class="card-header d-flex justify-content-between">
        <h5 class="mb-0">جزئیات فروش</h5>
        <a href="{{ route("purchase.index") }}" class="btn btn-warning">بازگشت</a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="mb-4 col-md-6">
                <h6 class="fw-semibold mb-2">نام و نام خانوادگی</h6>
                <p>{{ $purchase->fullname }}</p>
            </div>
            <div class="mb-4 col-md-6">
                <h6 class="fw-semibold mb-2">مبلغ پرداختی</h6>
                <p>{{ number_format((int)$purchase->amount_paid) }} تومان</p>
            </div>
            <div class="mb-3 col-md-6">
                <h6 class="fw-semibold mb-2">شماره تماس</h6>
                <p>{{ $purchase->phone }}</p>
            </div>
            <div class="mb-4 col-md-6" role="alert">
                <h6 class="fw-semibold mb-1">اوپراتور ثبت کننده</h6>
                <span>{{ $purchase->creator->name }}</span>
            </div>
            <div class="mb-4 col-md-12" role="alert">
                <h6 class="fw-semibold mb-1">توضیحات</h6>
                <span>{{ $purchase->description }}</span>
            </div>

        </div>
    </div>
    <!-- Modal -->

    <!-- /Modal -->

    <!-- /Current Plan -->
</div>
