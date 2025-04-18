@extends('base')
@section('title', __('article.update_an_article_subcategory'))
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
                        <h3>{{ __('article.update_an_article_subcategory') }}</h3>
                        <p class="text-subtitle text-muted"></p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav class="float-start float-lg-end" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="{{ route('app_subcategory_article', ['group' => 'stock', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">{{ __('article.article_subcategory') }}</a></li>
                              <li class="breadcrumb-item"><a href="{{ route('app_info_article_subcategory', ['group' => 'stock', 'id' => $entreprise->id, 'id2' => $functionalUnit->id, 'id3' => $subcategory_article->id]) }}">{{ __('article.article_subcategory_details') }}</a></li>
                              <li class="breadcrumb-item active" aria-current="page">{{ __('article.update_an_article_subcategory') }}</li>
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
                        <form action="{{ route('app_create_subcategory_article') }}" method="POST">
                            @csrf

                            <input type="hidden" name="id_entreprise" value="{{ $entreprise->id }}">
                            <input type="hidden" name="id_fu" value="{{ $functionalUnit->id }}">
                            <input type="hidden" name="id_subcat_art" value="{{ $subcategory_article->id }}">
                            <input type="hidden" name="customerRequest" id="customerRequest" value="edit">

                            <div class="mb-4 row">
                                <label for="cat_art_sub" class="col-sm-4 col-form-label">{{ __('article.article_category') }}*</label>
                                <div class="col-sm-5">
                                  <select name="cat_art_sub" id="cat_art_sub" class="form-select type_contact @error('cat_art_sub') is-invalid @enderror">
                                      <option value="{{ $cat_art_get->id }}" selected>{{ $cat_art_get->name_cat_art }}</option>

                                      @foreach ($category_articles as $category_article)
                                        <option value="{{ $category_article->id }}">{{ $category_article->name_cat_art }}</option>
                                      @endforeach

                                  </select>
                                  <small class="text-danger">@error('cat_art_sub') {{ $message }} @enderror</small>
                                </div>
                                <div class="col-sm-3 d-grid gap-2">
                                    @if ($permission_assign || Auth::user()->role->name == "admin" || Auth::user()->role->name == "superadmin")
                                        <a href="{{ route('app_add_new_category_article', ['group' => 'stock', 'id' => $entreprise->id, 'id2' => $functionalUnit->id ]) }}" class="btn btn-primary mb-3" role="button">
                                            <i class="fa-solid fa-box-archive"></i>
                                            &nbsp;{{ __('article.add_a_new_category') }}
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="name_subcat" class="col-sm-4 col-form-label">{{ __('article.subcategory_name') }}*</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control @error('name_subcat') is-invalid @enderror" id="name_subcat" name="name_subcat" placeholder="{{ __('article.enter_the_subcategory_name') }}" value="{{ $subcategory_article->name_subcat_art }}">
                                    <small class="text-danger">@error('name_subcat') {{ $message }} @enderror</small>
                                </div>
                            </div>

                            @if ($permission_assign || Auth::user()->role->name == "admin" || Auth::user()->role->name == "superadmin")
                                {{-- button de sauvegarde --}}
                                @include('button.save-button')
                            @endif

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
