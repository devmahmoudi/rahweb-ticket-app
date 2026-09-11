<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="my-0">فروش</h4>
        <div>
            <a href="{{ route('purchase.create') }}" class="btn btn-primary">ثبت فروش جدید</a>
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
                    <th>مبلغ</th>
                    <th>شماره تماس</th>
                    <th>اوپراتور</th>
                    <th>تاریخ فروش</th>
                    <th>عمل‌ها</th>
                </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                @foreach($purchases as $purchase)
                    <tr wire:key="{{ $purchase->id }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $purchase->fullname }}</td>
                        <td>{{ number_format((int)$purchase->amount_paid) }} تومان</td>
                        <td>{{ $purchase->phone }}</td>
                        <td>{{ $purchase->creator->name }}</td>
                        <td>{{ $purchase->sale_date }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    @can('view', $purchase)
                                        <a class="dropdown-item" href="{{ route('purchase.show', $purchase)}}"><i
                                                class="bx bx-show-alt me-1"></i> مشاهده</a>
                                    @endcan

                                    @can('update', $purchase)
                                        <a class="dropdown-item" href="{{ route('purchase.edit', $purchase)}}"><i
                                                class="bx bx-edit-alt me-1"></i> ویرایش</a>
                                    @endcan

                                    @can('delete', $purchase)
                                        <button class="dropdown-item"
                                                wire:click="delete({{ $purchase }})"
                                                wire:confirm="آیا از حذف این فروش مطمئن هستید ؟"
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
            {{ $purchases->links('vendor.livewire.bootstrap') }}
        </div>
    </div>
</div>

