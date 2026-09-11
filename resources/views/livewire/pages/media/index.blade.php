<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="my-0">فایل ها</h4>
        @can('upload', \App\Models\Media::class)
            <div>
                <a href="{{ route('media.upload') }}" class="btn btn-primary">آپلود فایل جدید</a>
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
                    <th>نام</th>
                    {{--                    <th>حجم</th>--}}
                    <th>عمل‌ها</th>
                </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                @foreach($medias as $media)
                    <tr wire:key="{{ $media->id }}">
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="cursor-pointer text-primary" @can('download', $media) wire:click="download({{ $media }})"  @endcan href="#">{{ \Illuminate\Support\Str::limit($media->name, 20) }}</span></td>
                        {{--                        <td>--}}
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    @can('download', $media)
                                        <button type="button" class="dropdown-item" wire:click="download({{ $media }})"><i
                                                class='bx bx-download me-1'></i>دانلود</button>
                                    @endcan
                                    @can('delete', $media)
                                        <button type="button" class="dropdown-item"
                                           wire:confirm="آیا از حذف این فایل مطمئن هستید ؟"
                                           wire:click="delete({{ $media }})"
                                        ><i class='bx bx-trash me-1'></i>حذف</button>
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
            {{ $medias->links('vendor.livewire.bootstrap') }}
        </div>
    </div>
</div>

