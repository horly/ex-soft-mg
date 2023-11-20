@extends('base')
@section('title', $functionalUnit->name . " informations")
@section('content')

@include('menu.login-nav')

<div class="container mt-5">

    @include('functional_unit.nav-functional_unit')

    {{-- On inlut les messages flash--}}
    @include('message.flash-message')

    <div class="card">
        <div class="border-bottom p-4">
            @include('functional_unit.functional_unit-tab')
        </div>

        <div class="p-4" id="myTabContent">
               
            @include('functional_unit.functional_unit-infos-page')
                
        </div>

    </div>

    <div class="m-5">
        @include('menu.footer-global')
    </div>
</div>


@endsection