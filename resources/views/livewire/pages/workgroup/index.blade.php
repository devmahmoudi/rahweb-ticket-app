<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="my-0">گروه کاری ها</h4>

        @can('create', \App\Models\Workgroup::class)
            <a href="{{ route('workgroup.create') }}"  class="btn btn-primary">
                <i class='bx bxs-plus-circle' style="padding-left: 10px"></i>
                <span>گروه کاری جدید</span>
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
                    <th>تعداد اعضای</th>
                    <th>عمل‌ها</th>
                </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                @foreach($workgroups as $workgroup)
                    <tr wire:key="{{ $workgroup->id }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $workgroup->name }}</td>
                        <td>{{ $workgroup->users()->count() }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    @can('view', $workgroup)
                                        <a class="dropdown-item" href="{{ route('workgroup.users', $workgroup) }}" ><i class='bx bxs-user-detail me-1'></i></i>اعضا</a>
                                    @endcan

                                    @can('update', $workgroup)
                                        <a class="dropdown-item" href="{{ route('workgroup.edit', $workgroup)}}" ><i class="bx bx-edit-alt me-1"></i> ویرایش</a>
                                    @endcan

                                    @can('delete', $workgroup)
                                        <button class="dropdown-item" wire:click="delete({{ $workgroup }})"><i class="bx bx-trash me-1"></i> حذف</button>
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
    {{ $workgroups->links('vendor.livewire.bootstrap') }}
</div>

