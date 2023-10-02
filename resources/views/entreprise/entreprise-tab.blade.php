<ul class="nav nav-pills" id="entrepriseTab" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link @if (Request::route()->getName() == "app_entreprise") active @endif" href="{{ route('app_entreprise', ['id' => $entreprise->id]) }}">{{ __('entreprise.functional_unit')}}</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link @if (Request::route()->getName() == "app_entreprise_info_page") active @endif" href="{{ route('app_entreprise_info_page', ['id' => $entreprise->id]) }}">{{ __('entreprise.company_information')}}</a>
    </li>
</ul>