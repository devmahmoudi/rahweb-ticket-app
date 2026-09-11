<div class="card">
    <div class="card-body">
        <div class="text-end pb-3">
            <a href="{{ route('workgroup.users', $workgroup) }}" class="btn btn-outline-warning" >بازگشت</a>
        </div>
        <div class="d-flex justify-content-between">
            <h4>اضافه کردن عضو به گروه کاری {{ $workgroup->name }}</h4>
            <div class="form-group col-12 col-md-6 col-lg-4 col-xl-3 mb-3">
                <input type="text" id="search" placeholder="جستجو..." class="form-control"
                       wire:model.live.debounce.300ms="search">
            </div>
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
                            @if(!$user->workgroups->where('id',$workgroup->id)->count())
                                <button class="btn btn-outline-primary btn-sm" wire:click="attachUser({{ $user }})"
                                        wire:confirm="آیا از اضافه کردن این عضو مطمئن هستید ؟">اضافه کردن به این گروه کاری
                                </button>
                            @else
                                <button class="btn btn-outline-danger btn-sm" wire:click="detachUser({{ $user }})"
                                        wire:confirm="آیا از حذف عضو از گروه کاری {{ $workgroup->name }} مطمئن هستید ؟">حذف از گروه کاری {{ $workgroup->name }}
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
