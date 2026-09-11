@assets
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-chat.css') }}">
<script src="{{ asset('assets/js/app-chat.js') }}"></script>
@endassets

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="app-chat card overflow-hidden">
        <div class="row g-0">
            <!-- Sidebar Left -->
            <div class="col app-chat-sidebar-left app-sidebar overflow-hidden" id="app-chat-sidebar-left">
                <div
                    class="chat-sidebar-left-user sidebar-header d-flex flex-column justify-content-center align-items-center flex-wrap px-4 pt-5">
                    <div class="avatar avatar-xl avatar-online">
                        <img src="../../assets/img/avatars/1.png" alt="آواتار" class="rounded-circle">
                    </div>
                    <h5 class="mt-2 mb-0">جان اسنو</h5>
                    <small>مدیر</small>
                    <i class="bx bx-x bx-sm cursor-pointer close-sidebar" data-bs-toggle="sidebar" data-overlay=""
                       data-target="#app-chat-sidebar-left"></i>
                </div>
                <div class="sidebar-body px-4 pb-4 ps ps__rtl ps--active-y">
                    <div class="my-4">
                        <p class="text-muted text-uppercase">درباره</p>
                        <textarea id="chat-sidebar-left-user-about"
                                  class="form-control chat-sidebar-left-user-about mt-3" rows="4" maxlength="120">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است. چاپگرها و متون بلکه</textarea>
                    </div>
                    <div class="my-4">
                        <p class="text-muted text-uppercase">وضعیت</p>
                        <div class="d-grid gap-1">
                            <div class="form-check form-check-success">
                                <input name="chat-user-status" class="form-check-input" type="radio" value="active"
                                       id="user-active" checked="">
                                <label class="form-check-label" for="user-active">فعال</label>
                            </div>
                            <div class="form-check form-check-danger">
                                <input name="chat-user-status" class="form-check-input" type="radio" value="busy"
                                       id="user-busy">
                                <label class="form-check-label" for="user-busy">مشغول</label>
                            </div>
                            <div class="form-check form-check-warning">
                                <input name="chat-user-status" class="form-check-input" type="radio" value="away"
                                       id="user-away">
                                <label class="form-check-label" for="user-away">دور</label>
                            </div>
                            <div class="form-check form-check-secondary">
                                <input name="chat-user-status" class="form-check-input" type="radio" value="offline"
                                       id="user-offline">
                                <label class="form-check-label" for="user-offline">آفلاین</label>
                            </div>
                        </div>
                    </div>
                    <div class="my-4">
                        <p class="text-muted text-uppercase">تنظیمات</p>
                        <ul class="list-unstyled d-grid gap-3 me-3">
                            <li class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bx bx-message-square-detail me-1"></i>
                                    <span class="align-middle">اعتبارسنجی دو مرحله‌ای</span>
                                </div>
                                <label class="switch switch-primary me-4">
                                    <input type="checkbox" class="switch-input" checked="">
                                    <span class="switch-toggle-slider">
                                <span class="switch-on"></span>
                                <span class="switch-off"></span>
                              </span>
                                </label>
                            </li>
                            <li class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bx bx-bell me-1"></i>
                                    <span class="align-middle">اعلان</span>
                                </div>
                                <label class="switch switch-primary me-4">
                                    <input type="checkbox" class="switch-input">
                                    <span class="switch-toggle-slider">
                                <span class="switch-on"></span>
                                <span class="switch-off"></span>
                              </span>
                                </label>
                            </li>
                            <li>
                                <i class="bx bx-user me-1"></i>
                                <span class="align-middle">دعوت دوستان</span>
                            </li>
                            <li>
                                <i class="bx bx-trash me-1"></i>
                                <span class="align-middle">حذف حساب</span>
                            </li>
                        </ul>
                    </div>
                    <div class="d-flex mt-4">
                        <button class="btn btn-primary" data-bs-toggle="sidebar" data-overlay=""
                                data-target="#app-chat-sidebar-left">
                            خروج
                        </button>
                    </div>
                    <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
                        <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                    </div>
                    <div class="ps__rail-y" style="top: 0px; height: 558px; right: 323px;">
                        <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 433px;"></div>
                    </div>
                </div>
            </div>
            <!-- /Sidebar Left-->

            <!-- Chat & Contacts -->
            <livewire:messenger.chat-list/>
            <!-- /Chat contacts -->

            <!-- Chat History -->
            @if(isset($chat))
                <livewire:messenger.history :chat="$chat" :isContactOnline="$chatsOnlineStatus[$chat->id] ?? false" />
            @else
                <div class="col app-chat-history bg-body d-flex align-items-center justify-content-center">
                    <p>گفتگویی انتخاب کنید</p>
                </div>
            @endif
            <!-- /Chat History -->

            <div class="app-overlay"></div>
        </div>
    </div>
</div>

