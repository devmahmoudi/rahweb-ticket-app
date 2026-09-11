<x-slot:style>
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}"/>
</x-slot:style>

<div class="card col-12 col-md-6">
    <div class="card-body">
        <form class="row">
            <div class="form-group p-3">
                <label for="defaultFormControlInput" class="form-label">نام *</label>
                <input type="text" @class(['form-control', 'is-invalid' => $errors->has('name')]) wire:model="name"
                       id="defaultFormControlInput" placeholder="نام اوپراتور"
                       aria-describedby="defaultFormControlHelp">
                @error('name')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group p-3">
                <label for="defaultFormControlInput" class="form-label">کلمه عبور</label>
                <input type="password" @class(['form-control', 'is-invalid' => $errors->has('password')]) wire:model="password"
                       id="defaultFormControlInput" placeholder="کلمه عبور اوپراتور را تغییر دهید"
                       aria-describedby="defaultFormControlHelp">
                @error('password')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group p-3">
                <label for="defaultFormControlInput" class="form-label">گروه کاری های اوپراتور</label>
                <div wire:ignore>
                    <select wire:model="workgroup_ids" @class(['is-invalid' => $errors->has('workgroup_ids')]) id="workgroups" multiple>
                        @foreach($workgroups as $workgroup)
                            <option value="{{ $workgroup->id }}" @selected(in_array($workgroup->id, $workgroup_ids))>{{ $workgroup->name }}</option>
                        @endforeach
                    </select>
                </div>
                @error('workgroup_ids')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group p-3">
                <label for="defaultFormControlInput" class="form-label">نقش اوپراتور</label>
                <div wire:ignore>
                    <select id="role" value="{{ $role_id }}">
                        <option value="">نقش اوپراتور</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" @selected($role->id == $role_id)>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                @error('role_id')
                <small class="text-danger text-sm">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group p-3">
                <button class="btn btn-primary" type="button" wire:click="update">ذخیره</button>
                <a class="btn btn-warning" href="{{ route('operator.index') }}">بازگشت</a>
            </div>
        </form>
    </div>
</div>

@script
<script>
    console.log({{ $workgroups->count() }})

    let workgroups = $('#workgroups').select2()

    workgroups.on('change', function (e) {
        console.log(workgroups.val())

        @this.set('workgroup_ids', workgroups.val())
    })

    let role_id = $('#role').select2()

    role_id.on('change', function (e) {
        @this.set('role_id', role_id.val())
    })
</script>
@endscript

<x-slot:script>

    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>


</x-slot:script>

