<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="my-0">نقش ها</h4>

       @can('create', \App\Models\Role::class)
            <a href="{{ route('role.create') }}"  class="btn btn-primary">
                <i class='bx bxs-plus-circle' style="padding-left: 10px"></i>
                <span>نقش جدید</span>
            </a>
        @endcan
    </div>
    <div class="card-body">
        <x-alert/>
        <div class="table-responsive text-nowrap overflow-visible">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>نام</th>
                    <th>تعداد کاربران</th>
                    <th>عمل‌ها</th>
                </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                @foreach($roles as $role)
                    <tr wire:key="{{ $role->id }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $role->name }}</td>
                        <td>{{ $role->users()->count() }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    @can('view', $role)
                                        <a class="dropdown-item" href="{{ route('role.permission', $role) }}" ><i class='bx bx-universal-access me-1'></i></i>دسترسی ها</a>

                                        <a class="dropdown-item" href="{{ route('role.users', $role) }}" ><i class='bx bxs-user-detail me-1'></i></i>کاربران</a>
                                    @endcan

                                    @can('update', $role)
                                        <a class="dropdown-item" href="{{ route('role.edit', $role)}}" ><i class="bx bx-edit-alt me-1"></i> ویرایش</a>
                                    @endcan

                                    @can('delete', $role)
                                        <button class="dropdown-item" wire:click="delete({{ $role }})"><i class="bx bx-trash me-1"></i> حذف</button>
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
        {{ $roles->links('vendor.livewire.bootstrap') }}
</div>

