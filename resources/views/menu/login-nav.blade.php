<nav class="bg-primary d-flex align-items-center justify-content-between">
    <div>
        <div class="d-flex align-items-end ms-3" id="logo-nav-zone">
            <img class="rounded mx-auto d-block me-2" src="{{ asset('assets/img/logo/exad.jpeg') }}" alt="" srcset="" width="150">
            <span class="text-white fw-bold h3">ERP</span>
        </div>
    </div>

    <div class="d-flex align-items-center">
        <div class="dropdown-personal-menu me-2">
            <button class="dropbtn">
                <i class="fa-solid fa-language"></i> Lang
                @if (Config::get('app.locale') == 'en')
                    <i class="flag-icon flag-icon-gb rounded"></i>
                @else
                    <i class="flag-icon flag-icon-fr rounded"></i>
                @endif
                <i class="fa-solid fa-caret-down ms-2"></i>
            </button>
            <div class="dropdown-content" style="left:0;">
                <hr class="dropdown-divider">
                <a class="dropdown-item" href="{{ route('app_language', ['lang' => 'fr']) }}"><i class="flag-icon flag-icon-fr rounded"></i> Français</a>
                <hr class="dropdown-divider">
                <a class="dropdown-item" href="{{ route('app_language', ['lang' => 'en']) }}"><i class="flag-icon flag-icon-gb rounded"></i> English</a>
                <hr class="dropdown-divider">
            </div>
        </div>

        @php
            $notifs = null;

            $notifCount = null;

            if(Auth::user()->role->name == "admin")
            {
                $notifs = DB::table('notifications')
                            ->join('read_notifs', 'read_notifs.id_notif', '=', 'notifications.id')
                            ->where('read_notifs.id_user', Auth::user()->id)
                            ->take(4)
                            ->orderBy('read_notifs.id', 'desc')
                            ->get();
                $notifCount = DB::table('notifications')
                            ->join('read_notifs', 'read_notifs.id_notif', '=', 'notifications.id')
                            ->where([
                                'read_notifs.id_user' => Auth::user()->id,
                                'read_notifs.read' => 0,
                            ])
                            ->count();
            }
            else
            {
                $notifs = DB::table('notifications')
                        ->join('manages', 'manages.id_entreprise', '=', 'notifications.id_entreprise')
                        ->join('read_notifs', 'read_notifs.id_notif', '=', 'notifications.id')
                        ->where('read_notifs.id_user', Auth::user()->id)
                        ->orderBy('read_notifs.id', 'desc')
                        ->take(4)
                        ->get();

                $notifCount = DB::table('notifications')
                            ->join('manages', 'manages.id_entreprise', '=', 'notifications.id_entreprise')
                            ->join('read_notifs', 'read_notifs.id_notif', '=', 'notifications.id')
                            ->where([
                                'read_notifs.id_user' => Auth::user()->id,
                                'read_notifs.read' => 0,
                            ])
                            ->count();
            }

                //dd($notifs);
                //$notifCount = $notifs->count();

        @endphp

        <div class="dropdown-personal-menu me-4">
            <button class="dropbtn">
                <i class="fas fa-bell fa-2x"
                    @if ($notifCount != 0)
                        data-count="
                            @if ($notifCount <= 99)
                                {{ $notifCount }}
                            @else
                                99+
                            @endif
                        "
                    @endif></i>
            </button>
            <div class="dropdown-content nav-login-dropdown" style="left:0;">
                @foreach ($notifs as $notif)
                    <a href="#" class="d-flex flex-row

                            @if($notif->read == 0)
                                bg-light-theme
                            @endif
                            link-secondary link-offset-2 link-underline link-underline-opacity-0 p-3"
                            onclick="readNotification('{{ $notif->id }}', '{{ route('app_read_notification') }}', '{{ csrf_token() }}');">

                        @php
                            $user = DB::table('users')->where('id', $notif->id_sender)->first();
                            $entreprise = DB::table('entreprises')->where('id', $notif->id_entreprise)->first();
                        @endphp

                        <img src="{{ asset('assets/img/profile') }}/{{ $user->photo_profile_url }}.png" class="rounded-circle border me-2" alt="..." height="35">
                        <small>
                            <b>{{ $user->name }}</b>
                            <span> {{ __($notif->description) }}.</span><br>
                            <i class="fa-solid fa-building-circle-check"></i>&nbsp;
                            <span><b>{{ $entreprise->name }}</b></span><br>
                            <i class="fa-solid fa-calendar-days"></i>&nbsp;
                            <span>{{ Carbon\Carbon::parse($notif->created_at)->ago() }}</span>
                        </small>
                    </a>
                    <hr class="dropdown-divider">
                @endforeach
                <div class="d-flex justify-content-center">
                    <a href="{{ route('app_all_notification') }}" class="link-secondary link-offset-2 link-underline link-underline-opacity-0">
                        <small>{{ __('entreprise.view_all')}}</small>
                    </a>
                </div>
            </div>
        </div>

        <div class="dropdown-personal-menu" style="float:right;">
            <button class="dropbtn user">
                <img src="{{ asset('assets/img/profile') }}/{{ Auth::user()->photo_profile_url }}.png" class="rounded-circle border me-2" alt="..." width="40">
                {{ Auth::user()->name }}
                <i class="fa-solid fa-caret-down ms-2"></i>
            </button>
            <div class="dropdown-content nav-login-dropdown">
                <hr class="dropdown-divider">
                <a class="dropdown-item" href="{{ route('app_main') }}"><i class="fa-solid fa-house"></i> {{ __('main.home') }}</a>
                <hr class="dropdown-divider">
                <a class="dropdown-item" href="{{ route('app_profile') }}"><i class="fa-solid fa-user"></i> {{ __('main.profile') }}</a>
                <hr class="dropdown-divider">
                @if(Auth::user()->role->name == "admin")
                    <a class="dropdown-item" href="{{ route('app_user_management') }}"><i class="fa-solid fa-users"></i> {{ __('main.user_management') }}</a>
                @endif
                <hr class="dropdown-divider">
                <a class="dropdown-item" href="{{ route('app_login_history') }}"><i class="fa-solid fa-list"></i> {{ __('main.my_login_history') }}</a>
                <hr class="dropdown-divider">
                <a class="dropdown-item" href="{{ route('app_logout') }}"><i class="fa-solid fa-right-from-bracket"></i> {{ __('main.logout') }}</a>
            </div>
        </div>
    </div>
</nav>
