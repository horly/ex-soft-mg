
<div class="border p-4 mb-5">
    <div class="mb-3 row">
        <label for="display-notif" class="col-sm-2 col-form-label">{{ __('main.display') }}</label>
        <div class="col-sm-4">
            <select class="form-select" aria-label="Default select example" id="display-notif" onchange="displayNotifications();">
                <option value="{{ route('app_all_notification') }}"
                    @if (Request::route()->getName() == "app_all_notification")
                        selected
                    @endif>
                    {{ __('main.all_notification') }}
                </option>

                <option value="{{ route('app_unviewed_notifications') }}"
                    @if (Request::route()->getName() == "app_unviewed_notifications")
                        selected
                    @endif>
                    {{ __('main.unviewed_notifications') }}
                </option>
            </select>
        </div>
    </div>

    <div class="list-group">
        @foreach ($notifsAll as $notif)
            <a href="#" class="list-group-item list-group-item-action d-flex flex-row text-muted p-3
                    
                    @if($notif->read == 0) 
                        bg-light-theme  
                    @endif"

                    onclick="readNotification('{{ $notif->id }}', '{{ route('app_read_notification') }}', '{{ csrf_token() }}');">
                @php
                    $user = DB::table('users')->where('id', $notif->id_sender)->first();
                    $entreprise = DB::table('entreprises')->where('id', $notif->id_entreprise)->first();
                @endphp
                <img src="{{ asset('assets/img/profile') }}/{{ $user->photo_profile_url }}.png" class="rounded-circle border me-2" alt="..." height="35">
                <small>
                    <b>{{ $user->name }}</b>
                    <span> {{ __($notif->description) }}.</span><br>
                    <i class="fa-solid fa-building-circle-check"></i>&nbsp;
                    <span><b>{{ $entreprise->name }}</b></span><br>
                    <i class="fa-solid fa-calendar-days"></i>&nbsp;
                    <span>{{ Carbon\Carbon::parse($notif->created_at)->ago() }}</span>
                </small>
            </a>
        @endforeach
    </div>

    <div class="mt-3">
        {{ $notifsAll->onEachSide(1)->links() }}
    </div>

</div>