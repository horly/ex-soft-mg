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