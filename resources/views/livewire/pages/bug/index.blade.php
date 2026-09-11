<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="my-0">باگ های گزارش شده</h4>
        @can('create', \App\Models\Bug::class)
            <div>
                <a href="{{ route('bug.create') }}" class="btn btn-primary">ثبت باگ جدید</a>
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
                    <th>ایجاد کننده</th>
                    <th>وضعیت</th>
                    <th>تاریخ</th>
                    <th>عمل‌ها</th>
                </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                @foreach($bugs as $bug)
                    <tr wire:key="{{ $bug->id }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @can('view', $bug)
                                <a href="{{ route('bug.show', $bug) }}">{{ $bug->title }}</a>
                            @else
                                {{ $bug->title }}
                            @endcan
                        </td>
                        <td>{{ $bug->creator->name }}</td>
                        <td>
                            <x-bug-status :status="$bug->status"/>
                        </td>
                        <td>{{ \Morilog\Jalali\Jalalian::forge($bug->created_at)->format('%Y/%m/%d') }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    @can('view', $bug)
                                        <a class="dropdown-item" href="{{ route('bug.show', $bug)}}"><i
                                                class="bx bx-show-alt me-1"></i> مشاهده جزئیات</a>
                                    @endcan

                                    @can('changeStatus', $bug)
                                        <button type="button" class="dropdown-item" wire:click="changeStatus({{ $bug }})"><i
                                                class="bx bx-git-compare me-1"></i> تغییر وضعیت</button>
                                    @endcan

                                    @can('update', $bug)
                                        <a class="dropdown-item" href="{{ route('bug.edit', $bug)}}"><i
                                                class="bx bx-edit-alt me-1"></i> ویرایش</a>
                                    @endcan

                                    @can('delete', $bug)
                                        <button class="dropdown-item"
                                                wire:click="delete({{ $bug }})"
                                                wire:confirm="آیا از حذف این باگ مطمئن هستید ؟"
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
            {{ $bugs->links('vendor.livewire.bootstrap') }}
        </div>
    </div>
</div>

