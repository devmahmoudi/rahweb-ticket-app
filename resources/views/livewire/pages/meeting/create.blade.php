<x-slot:style>
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}"/>

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}"/>

</x-slot:style>
<div class="card col-12">
    <div class="card-header pb-0">
        <h4 class="my-0">ثبت صورت جلسه جدید</h4>
    </div>
    <div class="card-body">
        <form wire:submit="store" class="row">
            <div class="form-group p-3">
                <label for="defaultFormControlInput" class="form-label">عنوان</label>
                <input type="text" @class(['form-control', 'is-invalid' => $errors->has('title')]) wire:model="title">
                @error('title')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group p-3">
                <label for="defaultFormControlInput" class="form-label">حضار در جلسه</label>
                <input type="text" @class(['form-control', 'is-invalid' => $errors->has('participants')]) wire:model="participants">
                @error('participants')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group p-3 col-md-6">
                <label for="defaultFormControlInput" class="form-label">مشتری</label>
                <div wire:ignore>
                    <select wire:model="customer_id"
                            @class(['is-invalid' => $errors->has('customer_id')]) id="customer">
                        <option value="" selected>لطفا کاربر دریافت کننده وظیفه را انتخاب کنید</option>
                        @foreach($users as $user)
                            <option value="{{ $user->customer->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                @error('customer_id')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group p-3 col-md-6">
                <label for="defaultFormControlInput" class="form-label">تاریخ جلسه</label>
                <div wire:ignore>
                    <input type="text" class="form-control" placeholder="YYYY-MM-DD" id="flatpickr-date"
                           wire:model="date"/>
                </div>
                @error('date')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group p-3">
                <label for="defaultFormControlInput" class="form-label">متن</label>
                <div wire:ignore>
                    <textarea wire:model="text" id="text" class="form-control" rows="3"></textarea>
                </div>
                @error('text')
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

    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/autosize/autosize.js') }}"></script>

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

@script
<script>
    let customer = $('#customer').select2()

    customer.on('change', function (e) {
    @this.set('customer_id', customer.val())
    })
</script>
@endscript


