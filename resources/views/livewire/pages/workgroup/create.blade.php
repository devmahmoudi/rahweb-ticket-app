<div class="card col-12 col-md-6">
    <div class="card-body">
        <form wire:submit="store" class="row">
            <div class="form-group p-3">
                <label for="defaultFormControlInput" class="form-label">نام</label>
                <input type="text" @class(['form-control', 'is-invalid' => $errors->has('name')]) wire:model="name" id="defaultFormControlInput" placeholder="نام گروه کاری جدید" aria-describedby="defaultFormControlHelp">
                @error('name')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group p-3">
                <button class="btn btn-success" type="submit">ذخیره</button>
                <a class="btn btn-warning" href="{{ route('workgroup.index') }}" >بازگشت</a>
            </div>
        </form>
    </div>
</div>
