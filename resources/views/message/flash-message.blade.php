{{--Afficher le message succès--}}
@if (Session::has('success'))
<div class="alert alert-success text-center" role="alert">
    <i class="fa-solid fa-circle-check"></i>
    {{ Session::get('success') }}
</div>
@endif

{{--Afficher le message d'erreur--}}
@if (Session::has('danger'))
<div class="alert alert-danger text-center" role="alert">
    <i class="fa-solid fa-circle-exclamation"></i>
    {{ Session::get('danger') }}
</div>
@endif

{{--Afficher le message d'avertissement--}}
@if (Session::has('warning'))
<div class="alert alert-warning text-center" role="alert">
    <i class="fa-solid fa-triangle-exclamation"></i>
    {{ Session::get('warning') }}
</div>
@endif
