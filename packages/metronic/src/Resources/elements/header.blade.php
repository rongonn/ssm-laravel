<div id="kt_header" style="" class="header align-items-stretch">
    <div class="container-fluid d-flex align-items-stretch justify-content-between">
        <div class="d-flex align-items-center d-lg-none ms-n3 me-1" title="Show aside menu">
            <div class="btn btn-icon btn-active-color-white" id="kt_aside_mobile_toggle">
                <i class="bi bi-list fs-1"></i>
            </div>
        </div>
        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
            <a href="/" class="d-lg-none">
                <img alt="Logo" style="filter: drop-shadow(rgb(255, 255, 255) 5px 2px 10px);" src="{{ isset(settings()->company_logo) ? Storage::url(settings()->company_logo) : asset('isotope/metronic/img/isotopeit.png') }}" class="h-25px" />
            </a>
        </div>
        <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
            <div class="d-flex align-items-center fs-4 fw-bold text-white px-3">
                {{ settings()->company_name ?? 'Isotope IT' }}
            </div>
            <div></div>
            <div class="topbar d-flex align-items-stretch flex-shrink-0">
                <div class="d-flex align-items-stretch">
                    <div class="topbar-item px-3 px-lg-5 position-relative" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end" data-kt-menu-flip="bottom">
                        <div class="mr-2">
                            <img width="40px" src="{{ asset(app()->getLocale() == 'bn' ? 'isotope/metronic/img/flag/bn.png' : 'isotope/metronic/img/flag/en.png' ) }}" alt="Country Flag">
                        </div>
                        <span class="fw-bold ms-2 text-white">{{ app()->getLocale() == 'bn' ? 'বাংলা' : 'English' }}</span>
                    </div>
                    <div class="menu menu-sub menu-sub-dropdown menu-column w-200px w-lg-200px" data-kt-menu="true" style="">
                        <div class="d-flex flex-column bgi-no-repeat rounded-top bg-isotope border-top border-info">
                            <h3 class="text-white fw-semibold px-9 my-4">Language
                        </div>
                        <div class="scroll-y mh-325px my-5 px-8">
                            <div class="d-flex flex-stack py-4">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-35px me-4">
                                        <img width="30px" src="{{ asset('isotope/metronic/img/flag/en.png') }}" alt="Country Flag">
                                    </div>
                                    <div class="mb-0 me-2">
                                        <a href="{{ url((tenant() ? '' : '/owner') . '/locale-change/en') }}" class="fs-6 text-gray-800 text-hover-primary fw-bold">English</a>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-stack py-4">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-35px me-4">
                                        <img width="30px" src="{{ asset('isotope/metronic/img/flag/bn.png') }}" alt="Country Flag">
                                    </div>
                                    <div class="mb-0 me-2">
                                        <a href="{{ url((tenant() ? '' : '/owner') . '/locale-change/bn') }}" class="fs-6 text-gray-800 text-hover-primary fw-bold">Bangla</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-stretch">
                    <div class="topbar-item px-3 px-lg-5 position-relative" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end" data-kt-menu-flip="bottom">
                        <i class="bi bi-bell-fill fs-3 {{ auth()->user()?->unreadNotifications->count() > 0 ? 'text-warning' : 'text-dark' }}"></i> <sup><span class="notification_count">{{ auth()->user()?->unreadNotifications->count() }}</span></sup>
                    </div>
                    <div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px" data-kt-menu="true" style="">
                        <div class="d-flex flex-column bgi-no-repeat rounded-top bg-isotope border-top border-info">
                            <h3 class="text-dark px-9 my-4">Notifications
                        </div>
                        <div class="scroll-y mh-325px my-5 px-8">
                            @foreach (auth()->user()?->unreadNotifications->take(9)??[] as $notification)
                                <div class="d-flex flex-stack py-4">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-35px me-4 my-auto">
                                            <i class="{{ $notification->data['icon'] }}"></i>
                                        </div>
                                        <div class="mb-0 me-2">
                                            <a href="#" class="fs-6 text-gray-800 text-hover-primary fw-bold">{{ $notification->data['title'] }}</a>
                                            <div class="text-gray-500 fs-7 mt-1">{{ $notification->data['description'] }}</div>
                                            <span class="badge badge-light fs-8 mt-1 float-end">{{ $notification->created_at->diffforhumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="py-3 text-center border-top">
                            <a href="{{ tenant() ? url('/notifications') : url('/owner/notifications') }}" class="btn btn-color-gray-600 btn-active-color-primary">View All
                            <span class="svg-icon svg-icon-5">
                                <i class="bi bi-arrow-right"></i>
                            </span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-stretch" id="kt_header_user_menu_toggle">
                    <div class="topbar-item cursor-pointer symbol px-3 px-lg-5 me-n3 me-lg-n5 symbol-30px symbol-md-35px"
                        data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end"
                        data-kt-menu-flip="bottom">
                        <img style="filter: drop-shadow(0px 3px 15px white)" src="{{ asset('isotope/metronic/img/blank.png') }}" alt="metronic" />
                        <span class="fw-bold text-white ms-5 text-capitalize">{{ Auth::user()->name ?? "Isotope IT" }}</span>
                    </div>
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                        data-kt-menu="true">
                        <div class="menu-item px-3">
                            <div class="menu-content d-flex align-items-center px-3">
                                <div class="symbol symbol-50px me-5">
                                    <img alt="Avatar" src="{{ asset('isotope/metronic/img/blank.png') }}" />
                                </div>
                                <div class="d-flex flex-column">
                                    <div class="fw-bold d-flex align-items-center fs-5">{{ Auth::user()->name ?? "Isotope IT" }}</div>
                                    <div class="fw-semibold text-muted text-hover-primary fs-7">{{ Auth::user()->email ?? "info@isotopeit.com" }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="separator my-2"></div>
                        <div class="menu-item px-5">
                            <a href="{{ route(tenant() ? 'profile' : 'owner.profile') }}" class="menu-link px-5">My Profile</a>
                        </div>
                        <div class="menu-item px-5">
                            <a href="{{ route(tenant() ? 'showChangePasswordForm' : 'owner.showChangePasswordForm') }}" class="menu-link px-5">Change Password</a>
                        </div>
                        <div class="menu-item px-5">
                            <a href="javascript:void();" onclick="$('#logout').submit()" class="menu-link px-5">Sign Out</a>
                            <form action="{{ route(tenant() ? 'logout' : 'owner.logout') }}" id="logout" method="post">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-stretch d-lg-none px-3 me-n3" title="Show header menu">
                    <div class="topbar-item" id="kt_header_menu_mobile_toggle">
                        <i class="bi bi-text-left fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>