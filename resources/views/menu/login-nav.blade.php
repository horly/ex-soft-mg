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
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-language"></i> Lang
                        @if (Config::get('app.locale') == 'en')
                            <i class="flag-icon flag-icon-gb rounded"></i>
                        @else
                            <i class="flag-icon flag-icon-fr rounded"></i>
                        @endif
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="{{ route('app_language', ['lang' => 'fr']) }}"><i class="flag-icon flag-icon-fr rounded"></i> Français</a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('app_language', ['lang' => 'en']) }}"><i class="flag-icon flag-icon-gb rounded"></i> English</a>
                        </li>
                    </ul>
                </div>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ asset('assets/img/profile') }}/{{ Auth::user()->id }}{{ Auth::user()->matricule }}.jpeg" class="rounded-circle" alt="..." width="40"> {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="fa-solid fa-chart-line"></i> {{ __('main.dashboard') }}</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#"><i class="fa-solid fa-user"></i> {{ __('main.profile') }}</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('app_logout') }}"><i class="fa-solid fa-right-from-bracket"></i> {{ __('main.logout') }}</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>