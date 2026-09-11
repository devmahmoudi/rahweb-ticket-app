<div class="card col-12 col-md-6">
    <div class="card-header pb-0">
        <h6>آپلود فایل</h6>
    </div>
    <div class="card-body">
        <form wire:submit="store" class="row"
              x-data="{ uploading: false, progress: 0 }"
              x-on:livewire-upload-start="uploading = true"
              x-on:livewire-upload-finish="uploading = false"
              x-on:livewire-upload-cancel="uploading = false"
              x-on:livewire-upload-error="uploading = false"
              x-on:livewire-upload-progress="progress = $event.detail.progress"
        >
            <div class="form-group p-3">
                <label for="defaultFormControlInput" class="form-label">نام</label>
                <input type="text" @class(['form-control', 'is-invalid' => $errors->has('name')]) wire:model="name" id="defaultFormControlInput" placeholder="با چه نامی فایل را ذخیره کنیم ؟" aria-describedby="defaultFormControlHelp">
                @error('name')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group p-3">
                <label for="defaultFormControlInput" class="form-label">فایل *</label>
                <input type="file" @class(['form-control', 'is-invalid' => $errors->has('file')]) wire:model="file">
                <div class="d-flex flex-row justify-content-center align-items-center">
                    <div class="progress mt-3 px-0" x-show="uploading" style="width: 94%">
                        <div class="progress-bar" role="progressbar" :style="{width: progress + '%'}" aria-valuenow="progress"
                             aria-valuemin="0" aria-valuemax="100" x-text="progress + '%'">
                        </div>
                    </div>
                    <div style="width: 4%" x-show="uploading" class="text-end px-0">
                        <i class='bx bx-x-circle mt-3' wire:click="$cancelUpload('file')"></i>
                    </div>
                </div>
                @error('file')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group p-3" x-show="!uploading">
                <button @class(['btn','btn-success', 'd-none' => !$file]) type="submit">آپلود</button>
                <a class="btn btn-warning" href="{{ route('media.index') }}" >بازگشت</a>
            </div>
        </form>
    </div>
</div>
