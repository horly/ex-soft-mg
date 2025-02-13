@extends('base')
@section('title', __('super_admin.add_subscription'))
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
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">

                        @if ($subscription)
                            <h3>{{ __('super_admin.update_subscription') }}</h3>
                        @else
                            <h3>{{ __('super_admin.add_subscription') }}</h3>
                        @endif

                        <p class="text-subtitle text-muted"></p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav class="float-start float-lg-end" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('app_subscription') }}">{{ __('super_admin.subscriptions') }}</a></li>

                                @if ($subscription)
                                    <li class="breadcrumb-item active" aria-current="page">{{ __('super_admin.update_subscription') }}</li>
                                @else
                                    <li class="breadcrumb-item active" aria-current="page">{{ __('super_admin.add_subscription') }}</li>
                                @endif

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
                        <form action="{{ route('app_create_subscription') }}" method="POST">
                            @csrf

                            <input type="hidden" name="id_sub" value="{{ $id_sub }}">
                            <input type="hidden" name="customerRequest" id="customerRequest" value="{{ $subscription ? 'edit' : 'add' }}">

                            @if ($subscription)
                                <div class="mb-4 row">
                                    <label for="reference" class="col-sm-4 col-form-label">{{ __('client.reference') }}</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="reference" value="{{ $subscription->reference }}" disabled>
                                    </div>
                                </div>
                            @endif

                            <div class="mb-4 row">
                                <label for="type_sub" class="col-sm-4 col-form-label">Type*</label>
                                <div class="col-sm-8">
                                    <select name="type_sub" id="type_sub" class="form-select @error('type_sub') is-invalid @enderror">
                                        @if ($subscription)
                                            <option value="{{ $subscription->type }}" selected>{{ $type }}</option>
                                        @endif
                                        <option value="3">Startup</option>
                                        <option value="2">Prenium</option>
                                        <option value="1">Business</option>
                                    </select>
                                    <small class="text-danger">@error('type_sub') {{ $message }} @enderror</small>
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="description_sub" class="col-sm-4 col-form-label">{{ __('article.description') }}</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control @error('description_sub') is-invalid @enderror" id="description_sub" name="description_sub" placeholder="{{ __('super_admin.enter_the_subscription_description') }}" value="{{ $subscription ? $subscription->description : old('description_sub') }}">
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="start_date_sub" class="col-sm-4 col-form-label">{{ __('super_admin.start_date') }}*</label>
                                <div class="col-sm-8">
                                    <input type="date" class="form-control" id="start_date_sub" name="start_date_sub" value="{{ $subscription ? date('Y-m-d', strtotime($subscription->start_date)) : date('Y-m-d') }}">
                                </div>
                            </div>

                            <div class="mb-4 row">
                                <label for="end_date_sub" class="col-sm-4 col-form-label">{{ __('super_admin.end_date') }}*</label>
                                <div class="col-sm-8">
                                    <input type="date" class="form-control" id="end_date_sub" name="end_date_sub" value="{{ $subscription ? date('Y-m-d', strtotime($subscription->end_date)) : date('Y-m-d') }}">
                                </div>
                            </div>

                            @if ($subscription)
                                <div class="mb-4 row">
                                    <label for="state_sub" class="col-sm-4 col-form-label">{{ __('super_admin.state') }}</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control {{ $text }}" id="state_sub" name="state_sub" value="{{ $state }}" disabled>
                                    </div>
                                </div>
                            @endif

                            {{-- button de sauvegarde --}}
                            @include('button.save-button')

                            @if ($subscription)
                                <button class="btn btn-danger" type="button" onclick="deleteElement('{{ $subscription->id }}', '{{ route('app_delete_subscription') }}', '{{ csrf_token() }}');" title="{{ __('entreprise.delete') }}">
                                    <i class="fa-solid fa-trash-can"></i>
                                    {{ __('entreprise.delete') }}
                                </button>
                            @endif

                        </form>
                    </div>
                </div>

                @if ($subscription)
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-4">{{ __('super_admin.user_list') }}</h6>

                            <a href="{{ route('app_add_user_admin', ['id' => 0]) }}" class="btn btn-primary mb-3" role="button">
                                <i class="fa-solid fa-circle-plus"></i>
                                &nbsp;{{ __('auth.add') }}
                            </a>
                            <table class="table table-striped table-hover border bootstrap-datatable">
                                <thead>
                                    <th>N°</th>
                                    <th>{{ __('main.name') }}</th>
                                    <th>{{ __('super_admin.subscription') }}</th>
                                    <th>{{ __('auth.email') }}</th>
                                    <th>{{ __('main.function') }}</th>
                                    <th>Action</th>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <a href="{{ Auth::user()->id == $user->id ? route('app_profile') : route('app_add_user_admin', ['id' => $user->id ]) }}">{{ $user->name }}</a>
                                            </td>
                                            <td>{{ $user->subscription->description }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->grade }}</td>
                                            <td><a href="{{ Auth::user()->id == $user->id ? route('app_profile') : route('app_add_user_admin', ['id' => $user->id ]) }}">{{ __('main.show') }}</a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                @endif
            </section>

            <div class="m-5">
                @include('menu.footer-global')
            </div>


        </div>

    </div>


</div>

@endsection
