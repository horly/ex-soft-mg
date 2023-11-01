@extends('base')
@section('title', $entreprise->name . ' / ' . $functionalUnit->name)
@section('content')

@include('menu.login-nav')

<div class="container container-margin-top">

    @include('functional_unit.nav-functional_unit')
</div>


@endsection