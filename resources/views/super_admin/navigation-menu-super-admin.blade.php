<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    <a href="#">
                        @if (config('app.name') == "EXADERP")
                            <img src="{{ asset('assets/img/logo/exad-logo.png') }}" id="logo-exad-erp" alt="Logo" srcset="">
                        @else
                            <img src="{{ asset('assets/img/logo/Prestavice-logo-erp1.png') }}" id="logo-presta" alt="Logo" srcset="">
                        @endif
                    </a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>

        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Super Admin Menu</li>

                {{-- Start Dashboard --}}
                <li class="sidebar-item @if (Request::route()->getName() == "app_super_admin_dashboard") active @endif">
                    <a href="{{ route('app_super_admin_dashboard') }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>{{ __('dashboard.dashboard') }}</span>
                    </a>
                </li>
                {{-- Start Dashboard --}}

                {{-- Start Subscription --}}
                <li class="sidebar-item @if (Request::route()->getName() == "app_subscription") active @endif">
                    <a href="{{ route('app_subscription') }}" class='sidebar-link'>
                        <i class="fa-solid fa-arrow-rotate-right"></i>
                        <span>{{ __('super_admin.subscriptions') }}</span>
                    </a>
                </li>
                {{-- Start Subscription --}}

                {{-- Start Users --}}
                <li class="sidebar-item @if (Request::route()->getName() == "app_user_super_admin") active @endif">
                    <a href="{{ route('app_user_super_admin') }}" class='sidebar-link'>
                        <i class="fa-solid fa-users"></i>
                        <span>{{ __('super_admin.users') }}</span>
                    </a>
                </li>
                {{-- Start Users --}}


            </ul>
        </div>



    </div>
</div>
