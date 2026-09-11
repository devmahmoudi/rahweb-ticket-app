<x-slot:style>
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
</x-slot:style>
<div class="card col-12 col-xl-8">
    <div class="card-header pb-0">
        <h4 class="my-0">ثبت فروش جدید</h4>
    </div>
    <div class="card-body">
        <form wire:submit="store" class="row">
            <div class="form-group p-3">
                <label for="defaultFormControlInput" class="form-label">نام و نام خانوادگی مشتری:</label>
                <input type="text" @class(['form-control', 'is-invalid' => $errors->has('fullname')]) wire:model="fullname">
                @error('fullname')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group p-3">
                <label for="defaultFormControlInput" class="form-label">مبلغ پرداختی:</label>
                <input type="text" @class(['form-control', 'is-invalid' => $errors->has('amount_paid')]) wire:model="amount_paid">
                @error('amount_paid')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group p-3">
                <label for="defaultFormControlInput" class="form-label">شماره تلفن:</label>
                <input type="text" @class(['form-control', 'is-invalid' => $errors->has('phone')]) wire:model="phone">
                @error('phone')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group p-3">
                <label for="defaultFormControlInput" class="form-label">تاریخ فروش:</label>
                <div wire:ignore>
                    <input type="text" class="form-control" placeholder="YYYY-MM-DD" id="flatpickr-date" wire:model="sale_date"/>
                </div>
                @error('sale_date')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group p-3">
                <label for="defaultFormControlInput" class="form-label">توضیحات</label>
                <div wire:ignore>
                    <textarea wire:model="description" id="description" class="form-control" rows="3"></textarea>
                </div>
                @error('description')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group p-3">
                <button class="btn btn-success" type="submit">ذخیره</button>
                <a class="btn btn-warning" href="{{ route('purchase.index') }}" >بازگشت</a>
            </div>
        </form>
    </div>
</div>
<x-slot:script>
    <script src="{{ asset('assets/vendor/libs/autosize/autosize.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/jdate/jdate.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr-jdate.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/flatpickr/l10n/fa-jdate.js') }}"></script>

    <script>
        let flat = document.getElementById('flatpickr-date')

        flat.flatpickr({
            monthSelectorType: 'static',
            locale: 'fa',
            altInput: true,
            altFormat: 'Y/m/d',
            disableMobile: true
        });
    </script>
</x-slot:script>

