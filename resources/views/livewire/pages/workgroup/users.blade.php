<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="my-0">اعضای گروه کاری {{ $workgroup->name }}</h4>

        <div>
            <a href="{{ route('workgroup.add.user', $workgroup) }}" class="btn btn-outline-primary" >اضافه کردن عضو</a>
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
                            @can('update', $workgroup)
                                <button class="btn btn-outline-danger btn-sm" wire:click="detachUser({{ $user }})">حذف از این گروه کاری</button>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

