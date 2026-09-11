<x-slot:style>
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}"/>
</x-slot:style>
<div class="card col-12 col-xl-8">
    <div class="card-header pb-0">
        <h4 class="my-0">ایجاد وظیفه جدید</h4>
    </div>
    <div class="card-body">
        <form wire:submit="store" class="row">
            <div class="form-group p-3">
                <label for="defaultFormControlInput" class="form-label">عنوان</label>
                <input type="text" @class(['form-control', 'is-invalid' => $errors->has('title')]) wire:model="title" id="defaultFormControlInput" placeholder="عنوان وظیفه" aria-describedby="defaultFormControlHelp">
                @error('title')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group p-3">
                <label for="defaultFormControlInput" class="form-label">دریافت کننده</label>
                <div wire:ignore>
                    <select wire:model="recipient_id" @class(['is-invalid' => $errors->has('recipient_id')]) id="recipient">
                        <option value="" selected>لطفا کاربر دریافت کننده وظیفه را انتخاب کنید</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                @error('workgroup_id')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group p-3">
                <label for="defaultFormControlInput" class="form-label">توضیحات</label>
                <div wire:ignore>
                    <textarea wire:model="description" id="description" class="form-control" rows="3"></textarea>
                </div>
                @error('workgroup_id')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group p-3">
                <button class="btn btn-success" type="submit">ذخیره</button>
                <a class="btn btn-warning" href="{{ route('task.index') }}" >بازگشت</a>
            </div>
        </form>
    </div>
</div>
@script
<script>
    let recipient = $('#recipient').select2()

    recipient.on('change', function (e) {
    @this.set('recipient_id', recipient.val())
    })
</script>
@endscript

<x-slot:script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/autosize/autosize.js') }}"></script>
</x-slot:script>
