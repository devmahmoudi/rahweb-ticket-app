<x-slot:style>
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}"/>
</x-slot:style>
<div class="card col-md-6">
    <div class="card-header">
        <h4>ارجاع وظیفه {{ $task->title }}</h4>
    </div>
    <form action="" wire:submit="referral" class="card-body">
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
            <button class="btn btn-primary" type="submit">ارجاع</button>
            <a class="btn btn-warning" href="{{ route('task.index') }}" >بازگشت</a>
        </div>
    </form>
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
