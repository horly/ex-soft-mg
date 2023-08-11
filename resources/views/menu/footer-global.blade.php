<footer>
    <div class="d-flex flex-row justify-content-center mb-4">
        <a class="link-underline-light me-5" href="/">{{ __('main.home') }}</a>
        <a class="link-underline-light me-5" href="#">{{ __('main.terms') }}</a>
        <a class="link-underline-light me-5" href="#">{{ __('main.privacy') }}</a>
        <a class="link-underline-light" href="#">{{ config('app.name') }} support</a>
    </div>
    <div class="d-flex flex-row justify-content-center text-muted mb-2">
        Copyrigth &copy; {{ date('Y') }} {{ config('app.name') }}
    </div>
    <div class="d-flex flex-row justify-content-center align-items-center text-muted">
        <i class="fa-solid fa-power-off text-success"></i> &nbsp;{{ __('auth.powered_by') }} &nbsp;<a class="link-underline-light" href="https://www.exadgroup.org" target="_blank">{{ config('app.company_name') }}</a>
    </div>
</footer>