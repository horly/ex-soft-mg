<ul class="nav nav-pills" id="entrepriseTab" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link @if (Request::route()->getName() == "app_profile") active @endif" href="{{ route('app_profile') }}">{{ __('profile.profile_information') }}</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link @if (Request::route()->getName() == "app_email_password") active @endif" href="{{ route('app_email_password') }}">{{ __('profile.change_email_password') }}</a>
    </li>
</ul>