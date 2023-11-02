<ul class="nav nav-pills" id="entrepriseTab" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link @if (Request::route()->getName() == "app_modules") active @endif" href="{{ route('app_modules', ['id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}">Modules</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link @if (Request::route()->getName() == "app_fu_infos") active @endif" href="{{ route('app_fu_infos', ['id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}" href="#">{{ __('entreprise.functional_unit_information')}}</a>
    </li>
</ul>