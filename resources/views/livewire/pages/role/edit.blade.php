<div class="card col-12 col-md-6">
    <div class="card-body">
        <form wire:submit="update" class="row">
            <div class="form-group p-3">
                <label for="defaultFormControlInput" class="form-label">نام</label>
                <input type="text" @class(['form-control', 'is-invalid' => $errors->has('name')]) wire:model="name" id="defaultFormControlInput" placeholder="" aria-describedby="defaultFormControlHelp">
                @error('name')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group p-3">
                <a class="btn btn-info" href="{{ route('role.permission', $role) }}" >دسترسی ها</a>
                <button class="btn btn-primary mx-2" type="submit">ویرایش</button>
                <a class="btn btn-warning" href="{{ route('role.index') }}" >بازگشت</a>
            </div>
        </form>
    </div>
</div>
