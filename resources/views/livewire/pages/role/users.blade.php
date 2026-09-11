<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="my-0">کاربران نقش {{ $role->name }}</h4>

        <div>
            <a href="{{ route('role.add.user', $role) }}" class="btn btn-outline-primary" >اضافه کردن کاربر</a>
        </div>
    </div>
    <div class="card-body">
        <x-alert/>
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
                            <button class="btn btn-outline-danger btn-sm" wire:click="detachUser({{ $user }})">حذف از این نقش</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

