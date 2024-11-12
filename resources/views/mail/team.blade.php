@if (config('app.name') == "EXADERP")
    <div style="margin-bottom: 20px">
        <b>{{ __('auth.the_exad_team') }}.</b>
    </div>
@else
    <div style="margin-bottom: 20px">
        <b>{{ __('auth.the_prestavice_team') }}.</b>
    </div>
@endif
