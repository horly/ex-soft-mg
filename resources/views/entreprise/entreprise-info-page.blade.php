@extends('base')
@section('title', $entreprise->name)
@section('content')

@include('menu.login-nav')

<div class="container mt-5">
    
    @include('entreprise.nav-entreprise')

    {{-- On inlut les messages flash--}}
    @include('message.flash-message')

    <div class="card">
        <div class="border-bottom p-4">
            @include('entreprise.entreprise-tab')
        </div>

        <div class="p-4" id="myTabContent">
            
            @include('entreprise.entreprise-info')    
            
        </div>
    </div>    

    <div class="m-5">
        @include('menu.footer-global')
    </div>

</div>

@endsection