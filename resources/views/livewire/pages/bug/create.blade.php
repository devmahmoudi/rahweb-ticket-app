<div class="card">
    <div class="card-header">
        <h4>ثبت باگ جدید</h4>
    </div>
    <div class="card-body">
        <form class="row" wire:submit="store">
            <div class="form-group p-3">
                <label for="defaultFormControlInput" class="form-label">عنوان *</label>
                <input type="text" @class(['form-control', 'is-invalid' => $errors->has('title')]) wire:model="title"
                       id="defaultFormControlInput"
                       aria-describedby="defaultFormControlHelp">
                @error('title')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group p-3">
                <label for="defaultFormControlInput" class="form-label">توضیحات *</label>
                <div wire:ignore>
                    <textarea wire:model="description" id="description" class="form-control" rows="3"></textarea>
                </div>
                @error('description')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group p-3">
                <button class="btn btn-success" type="submit">ثبت</button>
                <a class="btn btn-warning" href="{{ route('bug.index') }}">بازگشت</a>
            </div>
        </form>
    </div>
</div>

<x-slot:script>
    <script src="{{ asset('assets/vendor/libs/autosize/autosize.js') }}"></script>
</x-slot:script>

