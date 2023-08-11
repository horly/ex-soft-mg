@extends('base')
@section('title', __('main.home'))
@section('content')

@include('menu.login-nav')

<div class="container container-margin-top">
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">{{ __('main.main_menu') }}</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ __('main.entreprises') }}</li>
        </ol>
    </nav>

    <div class="border">
        <div class="border-bottom p-4 row">
            <div class="col-md-8">
                <button class="btn btn-primary mb-2 mt-2"><i class="fa-solid fa-briefcase"></i> 
                    &nbsp;{{ __('main.create_entreprise') }}
                </button>
            </div>
            <div class="col-md-4">
                <input type="text" name="" id="" class="form-control mb-2 mt-2" placeholder="{{  __('main.search') }}">
            </div>
            
        </div>
        <div class="p-4">
            <table class="table table-striped table-hover border">
                <thead>
                    <th>N°</th>
                    <th>Name</th>
                    <th>RCCM</th>
                    <th>ID. NAT</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>EXAD SARL</td>
                        <td>NGF-5574585</td>
                        <td>AA854</td>
                        <td><a href="#">Show</a></td>
                    </tr>

                    <tr>
                        <td>2</td>
                        <td>EXAD SARL</td>
                        <td>NGF-5574585</td>
                        <td>AA854</td>
                        <td><a href="#">Show</a></td>
                    </tr>

                    <tr>
                        <td>3</td>
                        <td>EXAD SARL</td>
                        <td>NGF-5574585</td>
                        <td>AA854</td>
                        <td><a href="#">Show</a></td>
                    </tr>

                    <tr>
                        <td>4</td>
                        <td>EXAD SARL</td>
                        <td>NGF-5574585</td>
                        <td>AA854</td>
                        <td><a href="#">Show</a></td>
                    </tr>

                    <tr>
                        <td>5</td>
                        <td>EXAD SARL</td>
                        <td>NGF-5574585</td>
                        <td>AA854</td>
                        <td><a href="#">Show</a></td>
                    </tr>
                </tbody>
            </table>

            <nav aria-label="Page navigation example">
                <ul class="pagination">
                  <li class="page-item active"><a class="page-link" href="#">Previous</a></li>
                  <li class="page-item"><a class="page-link" href="#">1</a></li>
                  <li class="page-item"><a class="page-link" href="#">2</a></li>
                  <li class="page-item"><a class="page-link" href="#">3</a></li>
                  <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
              </nav>
        </div>
    </div>
</div>

@endsection
