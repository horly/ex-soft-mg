@if ($permission_assign || Auth::user()->role->name == "admin" || Auth::user()->role->name == "superadmin")
    <button class="btn btn-primary mb-3" type="button" onclick="addNewcontact();" data-bs-toggle="modal" data-bs-target="#add_contact">
        <i class="fa-solid fa-circle-plus"></i>
        &nbsp;{{ __('auth.add') }}
    </button>
@endif

<table class="table table-striped table-hover border bootstrap-datatable">
    <thead>
        <th>N°</th>
        <th>{{ __('client.contact_name') }}</th>
        <th>{{ __('client.grade') }}</th>
        <th>{{ __('client.department') }}</th>
        <th>{{ __('main.phone_number') }}</th>
        <th>{{ __('main.email_address') }}</th>
        <th>{{ __('main.address') }}</th>
        <th class="text-end">Action</th>
    </thead>
    <tbody>
        @foreach ($contacts as $contact)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $contact->fullname_cl }}</td>
                <td>{{ $contact->fonction_contact_cl }}</td>
                <td>{{ $contact->departement_cl }}</td>
                <td>{{ $contact->phone_number_cl }}</td>
                <td>{{ $contact->email_adress_cl }}</td>
                <td>{{ $contact->address_cl }}</td>
                <td class="text-end">
                    @if ($permission_assign || Auth::user()->role->name == "admin" || Auth::user()->role->name == "superadmin")
                        <button class="btn btn-success" type="button" onclick="editContactClient('{{ $contact->id }}', '{{ $contact->fullname_cl }}', '{{ $contact->fonction_contact_cl }}', '{{ $contact->departement_cl }}', '{{ $contact->phone_number_cl }}', '{{ $contact->email_adress_cl }}', '{{ $contact->address_cl }}');" title="{{ __('entreprise.edit') }}" data-bs-toggle="modal" data-bs-target="#add_contact">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        @php
                            $is_invoiced = DB::table('sales_invoices')->where('id_contact', $contact->id)->first();
                        @endphp

                        @if (!$is_invoiced)
                            <button class="btn btn-danger" type="button" onclick="deleteElement('{{ $contact->id }}', '{{ route('app_delete_bank_account') }}', '{{ csrf_token() }}');" title="{{ __('entreprise.delete') }}">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        @endif
                    @else
                        #
                    @endif

                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- Modal -->
<div class="modal fade" id="add_contact" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" id="modal-new-contact-form" method="POST" action="{{ route('app_add_new_contact_client') }}">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="new-contact-title"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                @csrf

                {{-- Translation --}}
                <input type="hidden" name="add_a_new_contact_title" id="add_a_new_contact_title" value="{{ __('invoice.add_a_new_contact') }}">
                <input type="hidden" name="edit_contact" id="edit_contact" value="{{ __('invoice.edit_contact') }}">
                <input type="hidden" name="full_name_cl-error-message" id="full_name_cl-error-message" value="{{ __('client.enter_customers_full_name_please') }}">
                <input type="hidden" name="grade_cl-error-message" id="grade_cl-error-message" value="{{ __('client.enter_customer_grade_please') }}">
                <input type="hidden" name="department_cl-error-message" id="department_cl-error-message" value="{{ __('client.please_enter_the_contact_department') }}">
                <input type="hidden" name="email_cl-error-message" id="email_cl-error-message" value="{{ __('client.enter_the_customers_email_address_please') }}">
                <input type="hidden" name="phone_number_cl-error-message" id="phone_number_cl-error-message" value="{{ __('client.enter_the_customers_phone_number_please') }}">
                <input type="hidden" name="address_cl-error-message" id="address_cl-error-message" value="{{ __('client.enter_the_customers_business_address_please') }}">

                <input type="hidden" name="id_entreprise" value="{{ $entreprise->id }}">
                <input type="hidden" name="id_fu" value="{{ $functionalUnit->id }}">
                <input type="hidden" name="id_client" value="{{ $client->id }}">
                <input type="hidden" name="modalRequest" id="modalRequest" value="add"> {{-- Default is add but can be edit also --}}
                <input type="hidden" name="id_contact" id="id_contact" value="0">

                <div class="mb-4 row">
                    <label for="full_name_cl" class="col-sm-4 col-form-label">{{ __('client.full_name') }}*</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control full_name_cl" id="full_name_cl" name="full_name_cl" placeholder="{{ __('client.customers_full_name') }}" value="{{ old('full_name_cl') }}">
                        <small class="text-danger" id="full_name_cl-error"></small>
                    </div>
                </div>

                <div class="mb-4 row">
                    <label for="grade_cl" class="col-sm-4 col-form-label">{{ __('client.grade') }}*</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control grade_cl" id="grade_cl" name="grade_cl" placeholder="{{ __('client.customer_grade') }}" value="{{ old('grade_cl') }}">
                        <small class="text-danger" id="grade_cl-error"></small>
                    </div>
                </div>

                <div class="mb-4 row">
                    <label for="department_cl" class="col-sm-4 col-form-label">{{ __('client.department') }}*</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control department_cl" id="department_cl" name="department_cl" placeholder="{{ __('client.contact_department') }}" value="{{ old('department_cl') }}">
                        <small class="text-danger" id="department_cl-error"></small>
                    </div>
                </div>

                <div class="mb-4 row">
                    <label for="email_cl" class="col-sm-4 col-form-label">{{ __('main.email_address') }}*</label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control email_cl" id="email_cl" name="email_cl" placeholder="{{ __('client.enter_the_customers_email_address') }}" value="{{ old('email_cl') }}">
                        <small class="text-danger" id="email_cl-error"></small>
                    </div>
                </div>

                <div class="mb-4 row">
                    <label for="phone_number_cl" class="col-sm-4 col-form-label">{{ __('main.phone_number') }}*</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control phone_number_cl" id="phone_number_cl" name="phone_number_cl" placeholder="{{ __('client.enter_the_customers_phone_number') }}" value="{{ old('phone_number_cl') }}">
                        <small class="text-danger" id="phone_number_cl-error"></small>
                    </div>
                </div>

                <div class="mb-4 row">
                    <label for="address_cl" class="col-sm-4 col-form-label">{{ __('main.address') }}*</label>
                    <div class="col-sm-8">
                      <textarea class="form-control address_cl" name="address_cl" id="address_cl" rows="4" placeholder="{{ __('client.enter_the_customers_business_address') }}">{{ old('address_cl') }}</textarea>
                      <small class="text-danger" id="address_cl-error"></small>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                {{-- button de fermeture modale --}}
                @include('button.close-button')

                <div class="d-grid gap-2">
                    <button class="btn btn-primary saveP" type="button" id="save_contact_client">
                        <i class="fa-solid fa-floppy-disk"></i>
                      {{ __('main.save') }}
                    </button>
                    <button class="btn btn-primary btn-loadingP d-none" type="button" disabled>
                      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                      {{ __('auth.loading') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
