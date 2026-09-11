<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="my-0">اوپراتور ها</h4>

        <div>
            <a href="{{ route('operator.create') }}" class="btn btn-outline-primary">ایجاد اوپراتور جدید</a>
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
                    <th>نقش</th>
                    <th>عمل‌ها</th>
                </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                @foreach($users as $user)
                    <tr wire:key="{{ $user->id }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role->name }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    @can('update', $user)
                                        <a class="dropdown-item" href="{{ route('operator.edit', $user)}}"><i class="bx bx-edit-alt me-1"></i> ویرایش</a>
                                    @endcan

                                    @can('delete', $user)
                                        <button class="dropdown-item" wire:click="delete({{ $user }})"><i class="bx bx-trash me-1"></i> حذف</button>
                                    @endcan
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

