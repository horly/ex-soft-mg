<div class="row">
    <div class="col-md-3 mb-3">
        <div class="d-grid gap-2">
            <a class="card bg-primary text-white text-center p-4 modules" role="button" href="{{ route('app_dashboard', ['id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">
                <div class="card-body">
                    <p><i class="fa-solid fa-cash-register fa-3x"></i></p>
                    <p>{{ __('entreprise.accounting') }} & {{ __('entreprise.invoicing') }}</p>
                </div>
            </a>
        </div>         
    </div>

    <div class="col-md-3 mb-3">
        <div class="d-grid gap-2">
            <a class="card bg-primary text-white text-center p-4 modules" role="button" href="#">
                <div class="card-body">
                    <p><i class="fa-solid fa-people-roof fa-3x"></i></p>
                    <p>{{ __('entreprise.human_resources') }}</p>
                </div>
            </a>
        </div>         
    </div>

    <div class="col-md-3 mb-3">
        <div class="d-grid gap-2">
            <a class="card bg-primary text-white text-center p-4 modules" role="button" href="#">
                <div class="card-body">
                    <p><i class="fa-regular fa-folder-open fa-3x"></i></p>
                    <p>{{ __('entreprise.archive_management') }}</p>
                </div>
            </a>
        </div>         
    </div>
</div>