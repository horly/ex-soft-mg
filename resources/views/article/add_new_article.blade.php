@extends('base')
@section('title', __('article.add_a_new_article'))
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
                        <h3>{{ __('article.add_a_new_article') }}</h3>
                        <p class="text-subtitle text-muted"></p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav class="float-start float-lg-end" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="{{ route('app_article', ['id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">{{ __('dashboard.articles') }}</a></li>
                              <li class="breadcrumb-item active" aria-current="page">{{ __('article.add_a_new_article') }}</li>
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
                        <form action="{{ route('app_create_article') }}" method="POST">
                            @csrf

                            <input type="hidden" name="id_entreprise" value="{{ $entreprise->id }}">
                            <input type="hidden" name="id_fu" value="{{ $functionalUnit->id }}">
                            <input type="hidden" name="id_art" value="0">
                            <input type="hidden" name="customerRequest" id="customerRequest" value="add">

                            <div class="mb-4 row">
                                <label for="description_art" class="col-sm-4 col-form-label">{{ __('article.description') }}*</label> 
                                <div class="col-sm-8">
                                    <input type="text" class="form-control @error('description_art') is-invalid @enderror" id="description_art" name="description_art" placeholder="{{ __('article.enter_the_article_description') }}" value="{{ old('description_art') }}">
                                    <small class="text-danger">@error('description_art') {{ $message }} @enderror</small>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="subcat_art" class="col-sm-4 col-form-label">{{ __('article.article_subcategory') }}*</label> 
                                <div class="col-sm-5">
                                  <select name="subcat_art" id="subcat_art" class="form-select type_contact @error('subcat_art') is-invalid @enderror">
                                      <option value="" selected>{{ __('article.select_a_subcategory') }}</option>
                                      
                                      @foreach ($subcategory_articles as $subcategory_article)
                                        <option value="{{ $subcategory_article->id }}">{{ $subcategory_article->name_subcat_art }}</option>
                                      @endforeach

                                  </select>
                                  <small class="text-danger">@error('subcat_art') {{ $message }} @enderror</small>
                                </div>
                                <div class="col-sm-3 d-grid gap-2">
                                    <a href="{{ route('app_add_new_subcategory_article', ['id' => $entreprise->id, 'id2' => $functionalUnit->id ]) }}" class="btn btn-primary mb-3" role="button">
                                        <i class="fa-solid fa-circle-plus"></i> 
                                        &nbsp;{{ __('auth.add') }}
                                    </a>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="unit_price_art" class="col-sm-4 col-form-label">{{ __('article.unit_price') }}*</label> 
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="number" step="0.01" class="form-control text-end @error('unit_price_art') is-invalid @enderror" id="unit_price_art" name="unit_price_art" placeholder="{{ __('article.enter_the_article_unit_price') }}" value="{{ old('unit_price_art') }}">
                                        <span class="input-group-text" id="basic-addon2">{{ $deviseGest->iso_code }}</span>
                                    </div>
                                    <small class="text-danger">@error('unit_price_art') {{ $message }} @enderror</small>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="number_in_stock_art" class="col-sm-4 col-form-label">{{ __('article.number_in_stock') }}</label> 
                                <div class="col-sm-8">
                                    <input type="number" class="form-control text-end @error('number_in_stock_art') is-invalid @enderror" id="number_in_stock_art" name="number_in_stock_art" placeholder="{{ __('article.enter_the_number_in_stock') }}" value="{{ old('number_in_stock_art') }}">
                                    <small class="text-danger">@error('number_in_stock_art') {{ $message }} @enderror</small>
                                </div>
                            </div>

                            {{-- button de sauvegarde --}}
                            @include('button.save-button')

                        </form>
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