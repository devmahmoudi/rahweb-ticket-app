<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="my-0">فروش</h4>
        @can('create', \App\Models\Meeting::class)
        <div>
            <a href="{{ route('meeting.create') }}" class="btn btn-primary">ثبت جلسه جدید</a>
        </div>
        @endcan
    </div>
    <div class="card-body">
        <x-alert/>
        <div class="table-responsive text-nowrap overflow-visible">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>عنوان</th>
                    <th class="d-none d-md-table-cell">مشتری</th>
                    <th class="d-none d-md-table-cell">تاریخ جلسه</th>
                    <th>عمل‌ها</th>
                </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                @foreach($meetings as $meeting)
                    <tr wire:key="{{ $meeting->id }}">
                        <td>{{ $loop->iteration }}</td>
                        <td><a href="{{ route('meeting.show', $meeting) }}">{{ \Illuminate\Support\Str::words($meeting->title, 6) }}</a></td>
                        <td class="d-none d-md-table-cell">{{ $meeting->customer->user->name }}</td>
                        <td class="d-none d-md-table-cell">{{ $meeting->date }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    @can('view', $meeting)
                                        <a class="dropdown-item" href="{{ route('meeting.show', $meeting)}}"><i
                                                class="bx bx-show-alt me-1"></i> مشاهده</a>
                                    @endcan

                                    @can('update', $meeting)
                                        <a class="dropdown-item" href="{{ route('meeting.edit', $meeting)}}"><i
                                                class="bx bx-edit-alt me-1"></i> ویرایش</a>
                                    @endcan

                                    @can('delete', $meeting)
                                        <button class="dropdown-item"
                                                wire:click="delete({{ $meeting }})"
                                                wire:confirm="آیا از حذف این صورت جلسه مطمئن هستید ؟"
                                        ><i class="bx bx-trash me-1"></i> حذف
                                        </button>
                                    @endcan
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $meetings->links('vendor.livewire.bootstrap') }}
        </div>
    </div>
</div>

