@extends('base')
@section('title', __('article.article_category_details'))
@section('content')

<div id="app">
    @include('menu.navigation-menu')

    @include('menu.login-nav')

    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>

        <div class="page-heading">

            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>{{ __('article.article_category_details') }}</h3>
                        <p class="text-subtitle text-muted"></p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav class="float-start float-lg-end" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="{{ route('app_category_article', ['group' => 'stock', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">{{ __('article.article_category') }}</a></li>
                              <li class="breadcrumb-item active" aria-current="page">{{ __('article.article_category_details') }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            {{-- On inlut les messages flash--}}
            @include('message.flash-message')

            <section class="section">
                <div class="card">
                    <div class="card-body">

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('client.reference') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $category_article->reference_cat_art }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('article.category_name') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $category_article->name_cat_art }}
                            </div>
                        </div>

                        @php
                            $nbSubcat = DB::table('subcategory_articles')
                                        ->where('id_cat', $category_article->id)
                                        ->count();
                        @endphp

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('article.article_subcategory') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $nbSubcat }}
                            </div>
                        </div>

                        @php
                            $nbArt = DB::table('subcategory_articles')
                                        ->join('articles', 'articles.id_sub_cat', '=', 'subcategory_articles.id')
                                        ->where('subcategory_articles.id_cat', $category_article->id)
                                        ->count();
                        @endphp

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('dashboard.articles') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $nbArt }}
                            </div>
                        </div>


                        <div class="border-bottom mb-4 fw-bold">
                            {{ __('client.manager_information') }}
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('main.name') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $category_article->name }} {{-- le nom du gestionnaire à cause de la jointure --}}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('client.creation_date') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $category_article->created_at }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('client.last_modification_date') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $category_article->updated_at }}
                            </div>
                        </div>

                        <div class="row">
                            @if ($permission_assign || Auth::user()->role->name == "admin" || Auth::user()->role->name == "superadmin")
                                <div class="col-md-6 mb-3">
                                    <div class="d-grid gap-2">
                                        <a class="btn btn-success" role="button" href="{{ route('app_update_article_category', [
                                            'group' => 'stock',
                                            'id' => $entreprise->id,
                                            'id2' => $functionalUnit->id,
                                            'id3' => $category_article->id
                                            ]) }}">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                            {{ __('entreprise.edit') }}
                                        </a>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-danger" type="button" onclick="deleteElementThreeVal('{{ $category_article->id }}', {{ $entreprise->id }}, {{ $functionalUnit->id }}, '{{ route('app_delete_category_article') }}', '{{ csrf_token() }}');" title="{{ __('entreprise.delete') }}">
                                            <i class="fa-solid fa-trash-can"></i>
                                            {{ __('entreprise.delete') }}
                                        </button>
                                    </div>
                                </div>
                            @endif

                        </div>

                    </div>
                </div>
            </section>

            <div class="m-5">
                @include('menu.footer-global')
            </div>


        </div>

    </div>

</div>

@endsection
