@if(Auth::user()->role->name == "admin")
    <div class="border-bottom p-4">
        <a href="{{ route('app_create_functional_unit', ['id' => $entreprise->id]) }}" class="btn btn-primary" role="button"><i class="fa-solid fa-building-circle-arrow-right"></i> 
            &nbsp;{{ __('entreprise.create_a_functional_unit') }}
        </a>
    </div>
@endif

<div class="p-4">
    <table class="table table-striped table-hover border bootstrap-datatable">
        <thead>
            <th>N°</th>
            <th>{{ __('entreprise.unit_name') }}</th>
            <th>{{ __('main.address') }}</th>
            <th>Action</th>
        </thead>
        <tbody>
            @foreach ($functionalUnits as $functionalUnit)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $functionalUnit->name }}</td>
                    <td>{{ $functionalUnit->address }}</td>
                    <td><a href="#">{{ __('main.show') }}</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>