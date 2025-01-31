<ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link @if (Request::route()->getName() == "app_signature") active @endif" href="{{ route('app_signature', ['group' => 'sale', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}"
            role="tab" aria-controls="home" aria-selected="true">{{ __('invoice.your_signature') }}</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link @if (Request::route()->getName() == "app_seal") active @endif" href="{{ route('app_seal', ['group' => 'sale', 'id' => $entreprise->id, 'id2' => $functionalUnit->id]) }}"
            role="tab" aria-controls="profile" aria-selected="false">{{ __('invoice.seal') }}</a>
    </li>
</ul>
