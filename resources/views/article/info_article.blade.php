@extends('base')
@section('title', __('article.article_details'))
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
                        <h3>{{ __('article.article_details') }}</h3>
                        <p class="text-subtitle text-muted"></p> 
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav class="float-start float-lg-end" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="{{ route('app_article', ['id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">{{ __('dashboard.articles') }}</a></li>
                              <li class="breadcrumb-item active" aria-current="page">{{ __('article.article_details') }}</li>
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
                                {{ $article->reference_art }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('article.description') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $article->description_art }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('article.unit_price') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ number_format($article->unit_price, 2, '.', ' ') }} {{ $deviseGest->iso_code }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('article.number_in_stock') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $article->number_in_stock }}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('article.inventory_value') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ number_format($article->unit_price * $article->number_in_stock, 2, '.', ' ') }} {{ $deviseGest->iso_code }}
                            </div>
                        </div>

                        @php
                            $subcat = DB::table('subcategory_articles')
                                        ->where('id', $article->id_sub_cat)
                                        ->first();
                        @endphp
                       
                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('article.article_subcategory') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $subcat->name_subcat_art }}
                            </div>
                        </div>

                        @php
                            $cat_art = DB::table('subcategory_articles')
                                        ->join('category_articles', 'category_articles.id', '=', 'subcategory_articles.id_cat')
                                        ->where('subcategory_articles.id', $article->id_sub_cat)
                                        ->first();
                        @endphp
                       
                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('article.article_category') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $cat_art->name_cat_art }}
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
                                {{ $article->name }} {{-- le nom du gestionnaire à cause de la jointure --}}
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('client.creation_date') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $article->created_at }} 
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                {{ __('client.last_modification_date') }}
                            </div>
                            <div class="col-md-8 text-primary fw-bold">
                                {{ $article->updated_at }} 
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <div class="d-grid gap-2">
                                    <a class="btn btn-success" role="button" href="{{ route('app_update_article', [
                                        'id' => $entreprise->id, 
                                        'id2' => $functionalUnit->id,
                                        'id3' => $article->id
                                        ]) }}">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        {{ __('entreprise.edit') }}
                                    </a>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-grid gap-2">
                                    <button class="btn btn-danger" type="button" onclick="deleteElementThreeVal('{{ $article->id }}', {{ $entreprise->id }}, {{ $functionalUnit->id }}, '{{ route('app_delete_article') }}', '{{ csrf_token() }}');" title="{{ __('entreprise.delete') }}">
                                        <i class="fa-solid fa-trash-can"></i>
                                        {{ __('entreprise.delete') }}
                                    </button>
                                </div>
                            </div>

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