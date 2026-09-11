<x-slot:style>
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}"/>
</x-slot:style>
<div class="card col-12 col-xl-8">
    <div class="card-header pb-0">
        <h4 class="my-0">ایجاد تیکت جدید</h4>
    </div>
    <div class="card-body">
        <form wire:submit="store" class="row">
            <div class="form-group p-3">
                <label for="defaultFormControlInput" class="form-label">عنوان</label>
                <input type="text" @class(['form-control', 'is-invalid' => $errors->has('title')]) wire:model="title" id="defaultFormControlInput" aria-describedby="defaultFormControlHelp">
                @error('title')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group p-3">
                <label for="defaultFormControlInput" class="form-label">گروه کاری</label>
                <div wire:ignore>
                    <select wire:model="workgroup_id" @class(['is-invalid' => $errors->has('workgroup_ids')]) id="workgroup">
                        <option value="" selected>لطفا گروه کاری مقصد تیکت را انتخاب کنید</option>
                        @foreach($workgroups as $workgroup)
                            <option value="{{ $workgroup->id }}">{{ $workgroup->name }}</option>
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
                    <textarea wire:model="description"id="description" class="form-control" rows="3"></textarea>
                </div>
                @error('workgroup_id')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group p-3">
                <button class="btn btn-success" type="submit">ذخیره</button>
                <a class="btn btn-warning" href="{{ route('ticket.index') }}" >بازگشت</a>
            </div>
        </form>
    </div>
</div>
@script
    <script>
        let workgroup = $('#workgroup').select2()

        workgroup.on('change', function (e) {
        @this.set('workgroup_id', workgroup.val())
        })
    </script>
@endscript

<x-slot:script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/autosize/autosize.js') }}"></script>
</x-slot:script>
