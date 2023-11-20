<nav class="navbar navbar-expand-lg bg-body-tertiary fixed-top">
    <div class="container">
        <a class="navbar-brand d-flex flex-row align-items-end" href="#">
            <img class="rounded mx-auto d-block" src="{{ asset('assets/img/logo/exad.jpeg') }}" alt="" srcset="" width="150">
            <span>ERP</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <ul class="navbar-nav ms-auto d-flex align-items-center">
                @include('button.language-dropdown')

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

                <li class="nav-item dropdown me-3 ms-1">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-regular fa-bell"></i>

                        @if ($notifCount != 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                @if ($notifCount <= 99)
                                    {{ $notifCount }}
                                @else
                                    99+
                                @endif
                                <span class="visually-hidden">unread messages</span>
                            </span> 
                        @endif
                       
                    </button>
                    <ul class="dropdown-menu nav-login-dropdown">
                        @foreach ($notifs as $notif)
                            <li>
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
                            </li>
                            <li><hr class="dropdown-divider"></li>
                        @endforeach
                        <li class="d-flex justify-content-center">
                            <a href="{{ route('app_all_notification') }}" class="link-secondary link-offset-2 link-underline link-underline-opacity-0">
                                <small>{{ __('entreprise.view_all')}}</small>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ asset('assets/img/profile') }}/{{ Auth::user()->photo_profile_url }}.png" class="rounded-circle border" alt="..." width="40"> {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end nav-login-dropdown">
                        <li><a class="dropdown-item" href="{{ route('app_main') }}"><i class="fa-solid fa-house"></i> {{ __('main.home') }}</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('app_profile') }}"><i class="fa-solid fa-user"></i> {{ __('main.profile') }}</a></li>
                        <li><hr class="dropdown-divider"></li>

                        @if(Auth::user()->role->name == "admin")
                            <li><a class="dropdown-item" href="{{ route('app_user_management') }}"><i class="fa-solid fa-users"></i> {{ __('main.user_management') }}</a></li>
                            <li><hr class="dropdown-divider"></li>
                        @endif

                        <li><a class="dropdown-item" href="{{ route('app_login_history') }}"><i class="fa-solid fa-list"></i> {{ __('main.my_login_history') }}</a></li>
                        <li><hr class="dropdown-divider"></li>

                        <li><a class="dropdown-item" href="{{ route('app_logout') }}"><i class="fa-solid fa-right-from-bracket"></i> {{ __('main.logout') }}</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>