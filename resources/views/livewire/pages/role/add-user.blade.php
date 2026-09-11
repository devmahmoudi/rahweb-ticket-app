<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="my-0">اضافه کردن کاربر به نقش {{ $role->name }}</h4>

        <div>
            <a href="{{ route('role.users', $role) }}" class="btn btn-outline-warning" >بازگشت</a>
        </div>
    </div>
    <div class="card-body">
        <x-alert/>
        <div class="form-group col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
            <input type="text" id="search" placeholder="جستجو..." class="form-control"
                   wire:model.live.debounce.300ms="search">
        </div>
        <div class="table-responsive text-nowrap overflow-visible">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>نام</th>
                    <th>ایمیل</th>
                    <th>عمل‌ها</th>
                </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                @foreach($users as $user)
                    <tr wire:key="{{ $user->id }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role_id != $role->id)
                                <button class="btn btn-outline-primary btn-sm" wire:click="add({{ $user }})"
                                        wire:confirm="آیا از تغییر نقش کاربر مطمئن هستید ؟">اضافه کردن به این نقش
                                </button>
                            @else
                                <button class="btn btn-outline-danger btn-sm" wire:click="remove({{ $user }})"
                                        wire:confirm="آیا از حذف کاربر از نقش {{ $role->name }} مطمئن هستید ؟">حذف از این نقش نقش
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>

