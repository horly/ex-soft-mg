@extends('base')
@section('title', __('super_admin.users'))
@section('content')


<div id="app">

    {{-- Navigation menu here --}}

    @include('super_admin.navigation-menu-super-admin')

    @include('menu.login-nav')

    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>

        <div class="page-heading">
            <h3> {{ __('super_admin.users') }} </h3>
        </div>



    </div>

</div>

@endsection
