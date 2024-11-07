<div class="card border">
    <div class="card-body">
        <div class="border-bottom mb-4 fw-bold">
            Note
        </div>

        <form action="{{ route('app_add_note_invoice') }}" method="POST">
            @csrf

            <div class="mb-3">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="type_note" id="type_note_sentence" value="sentence" checked>
                    <label class="form-check-label" for="type_note_sentence">{{ __('invoice.sentence') }}</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="type_note" id="type_note_list" value="list">
                    <label class="form-check-label" for="type_note_list">{{ __('invoice.list') }}</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="bold_note" value="1" id="bold_note">
                    <label class="form-check-label" for="bold_note">
                      {{ __('invoice.bold') }}
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="italic_note" value="1" id="italic_note">
                    <label class="form-check-label" for="italic_note">
                        {{ __('invoice.italic') }}
                    </label>
                  </div>
            </div>

            <input type="hidden" name="id_note" id="id_note" value="0">
            <input type="hidden" name="type_doc" id="type_doc" value="{{ $type_doc }}">
            <input type="hidden" name="reference_doc" value="{{ $ref_invoice }}">
            <input type="hidden" name="customerRequest" id="customerRequest" value="add">

            <input type="hidden" id="add-text" value="{{ __('auth.add') }}">
            <input type="hidden" id="edit-text" value="{{ __('entreprise.edit') }}">

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" class="form-control @error('note_content') is-invalid @enderror" placeholder="{{ __('invoice.add_a_note') }}" id="note_content" name="note_content">
                        <button class="btn btn-primary" id="add-note" type="submit">{{ __('auth.add') }}</button>
                    </div>
                    <small class="text-danger">@error('note_content') {{ $message }} @enderror</small>
                </div>
            </div>
        </form>

        <div class="mb-4 fw-bold">
            {{ __('invoice.preview') }}
        </div>

        <ul class="list-group list-group-flush border mb-3">
            @foreach ($notes as $note)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="@if ($note->bold_note == 1) fw-bold @endif @if ($note->italic_note == 1) fst-italic @endif">
                        @if ($note->type_note == 'list')
                            <span>&#x2022;</span>
                            &nbsp;&nbsp;
                        @endif
                        {{ $note->note_content }}
                    </div>
                    <div>
                        <button class="btn btn-success" type="button" onclick="setNoteDoc('{{ $note->id }}', '{{ $note->note_content }}')">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button class="btn btn-danger" type="button" onclick="deleteElement('{{ $note->id }}', '{{ route('app_delete_note_invoice') }}', '{{ csrf_token() }}')">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </li>
            @endforeach
        </ul>

    </div>
</div>
