<div class="card">
    <div class="card-body">
        <h4>دسترسی های نقش {{ $role->name }}</h4>
        <hr>
        <form wire:submit="save">
            <div class="alert-danger">
                <ul>
                    @foreach($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
            <ul class="list-unstyled d-flex justify-content-center justify-content-around row">
                <li class="col-3 text-center">Model</li>

                @foreach(\App\Enums\Permission\BasicPermission::cases() as $permission)
                    <li class="col-2 text-center">{{ $permission->value }}</li>
                @endforeach

                @foreach($permissions->groupBy('model') as $model => $modelPermissions)
                    <li class="col-3 mt-4 text-center">{{ __('model.'. class_basename($model))  }}</li>

                    @foreach($modelPermissions as $permission)
                        <li class="col-2 text-center">
                            <div class="form-check mt-3 d-flex justify-content-center">
                                <input class="form-check-input" type="checkbox" wire:model="checkedPermissions" value="{{ $permission->id }}" id="defaultCheck1"
                                       @if(in_array($permission->id, $this->checkedPermissions))
                                       checked
                                    @endif>
                            </div>
                        </li>
                    @endforeach
                @endforeach
            </ul>
            <x-alert />
            <div class="d-flex justify-content-end flex-row">
                <button class="btn btn-primary mx-2" type="submit">ذخیره</button>
                <a class="btn btn-warning" href="{{ url()->previous() }}" >بازگشت</a>
            </div>
        </form>
    </div>
</div>
