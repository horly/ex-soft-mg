<div class="modal fade bd-example-modal-lg" id="edit-photo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">{{ __('entreprise.edit_photo')}}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="img-container1">

                    @if (Request::route()->getName() == "app_entreprise_info_page")
                        <img id="image" class="image img-fluid border" src="{{ asset('assets/img/logo/entreprise')}}/{{ $entreprise->url_logo }}.png">
                    @endif
                    
                    @if (Request::route()->getName() == "app_profile")
                        <img id="image" class="image img-fluid border" src="{{ asset('assets/img/profile') }}/{{ Auth::user()->photo_profile_url }}.png">
                    @endif
                </div>

                {{-- les informations utilisateur à passer --}}
                <a id="path-save-picture" class="d-none" token="{{ csrf_token() }}"  href="#"></a>

                <label class="mt-3">&nbsp;</label>
                <label class="btn btn-light border btn-upload" for="inputImage" title="Upload image file">
                    <input type="file" class="sr-only" id="inputImage" name="file" accept=".jpg,.jpeg,.png,.gif,.bmp,.tiff">
                    {{ __('entreprise.import')}}...
                </label>
                <br> &nbsp;
                <div class="docs-buttons text-center">

                  
                    &nbsp;&nbsp;
                    <div class="btn-group">

                        <button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1">
                            <i class="fas fa-search-plus"></i>
                        </button>

                        <button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1">
                            <i class="fas fa-search-minus"></i>
                        </button>

                    </div>

                    <div class="btn-group">

                        <button type="button" class="btn btn-primary" data-method="move" data-option="-10" data-second-option="0">
                            <i class="fa-solid fa-arrow-left"></i>
                        </button>

                        <button type="button" class="btn btn-primary" data-method="move" data-option="10" data-second-option="0">
                            <i class="fa fa-arrow-right"></i>
                        </button>

                    </div>

                    <div class="btn-group">
                        <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="-10">
                            <i class="fa fa-arrow-up"></i>
                        </button>

                        <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="10">
                            <i class="fa fa-arrow-down"></i>
                        </button>

                    </div>

                    <div class="btn-group">
                        <button type="button" class="btn btn-primary" data-method="rotate" data-option="-45">
                            <i class="fa fa-rotate-left"></i>
                        </button>

                        <button type="button" class="btn btn-primary" data-method="rotate" data-option="45">
                            <i class="fa fa-rotate-right"></i>
                        </button>

                    </div>

                    <div class="btn-group">

                        <button type="button" class="btn btn-primary" data-method="scaleX" data-option="-1">
                            <i class="fas fa-arrows-alt-h"></i>
                        </button>

                        <button type="button" class="btn btn-primary" data-method="scaleY" data-option="-1">
                            <i class="fas fa-arrows-alt-v"></i>
                        </button>

                    </div>

                </div>
            </div>
            <div class="modal-footer docs-buttons">
                @include('button.close-button')
               
                <form id="save-image-form" class="d-grid gap-2" action="{{ route('app_save_photo') }}" method="POST">
                    @csrf
                    
                    @if (Request::route()->getName() == "app_entreprise_info_page")
                        <input type="hidden" name="id-entreprise" id="id-entreprise" value="{{ $entreprise->id }}">
                        <input type="hidden" name="id-user" id="id-user" value="null">
                        <input type="hidden" name="type-photo" id="type-photo" value="entreprise">
                    @endif

                    @if (Request::route()->getName() == "app_profile")
                        <input type="hidden" name="id-user" id="id-user" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="id-entreprise" id="id-entreprise" value="null">
                        <input type="hidden" name="type-photo" id="type-photo" value="user">
                        
                    @endif
                    
                    <input type="hidden" name="image-saved" id="image-saved">
                    
                    <button class="btn btn-primary saveP" type="submit" data-method="getCroppedCanvas" data-option="{ &quot;maxWidth&quot;: 4096, &quot;maxHeight&quot;: 4096 }">
                        <i class="fa-solid fa-floppy-disk"></i>
                      {{ __('main.save') }}
                    </button>
                    <button class="btn btn-primary btn-loadingP d-none" type="button" disabled>
                      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                      {{ __('auth.loading') }}
                    </button>
                </form> 
            </div>
        </div>
    </div>
</div>