$('.type_contact').change(function(){
    var type = $('.type_contact').val();

    if(type == "company"){
        $('.company_info_contact').removeClass('d-none');
    }else{
        $('.company_info_contact').addClass('d-none');
    }
});


function addNewcontact() {

    var add = $('#add_a_new_contact_title').val();
    $('.modal #new-contact-title').text(add);
    document.getElementById("modal-new-contact-form").reset(); 
    $('#modal-new-contact-form #modalRequest').val("add");
    $('#id_contact').val("0");
}

function editContactClient(id, fullname_cl, fonction_contact_cl, departement_cl, phone_number_cl, email_adress_cl, address_cl) {
    var edit = $('#edit_contact').val();
    $('.modal #new-contact-title').text(edit);

    $('#full_name_cl').val(fullname_cl);
    $('#grade_cl').val(fonction_contact_cl);
    $('#department_cl').val(departement_cl);
    $('#phone_number_cl').val(phone_number_cl);
    $('#email_cl').val(email_adress_cl);
    $('#address_cl').val(address_cl);
    $('#id_contact').val(id);
    $('#modal-new-contact-form #modalRequest').val("edit");
    
}


$('#save_contact_client').click(function(){
    var full_name_cl = $('#full_name_cl');
    var grade_cl = $('#grade_cl');
    var department_cl = $('#department_cl');
    var email_cl = $('#email_cl');
    var phone_number_cl = $('#phone_number_cl');
    var address_cl = $('#address_cl');
    var form = $('#modal-new-contact-form');

    if(full_name_cl.val() != "") {
        full_name_cl.removeClass('is-invalid');
        $('#full_name_cl-error').text("");

        if(grade_cl.val() != "") {
            grade_cl.removeClass('is-invalid');
            $('#grade_cl-error').text("");

            if(department_cl.val() != "") {
                department_cl.removeClass('is-invalid');
                $('#department_cl-error').text("");

                if(email_cl.val() != "") {
                    email_cl.removeClass('is-invalid');
                    $('#email_cl-error').text("");

                    if(phone_number_cl.val() != "") {
                        phone_number_cl.removeClass('is-invalid');
                        $('#phone_number_cl-error').text("");

                        if(address_cl.val() != "") {
                            address_cl.removeClass('is-invalid');
                            $('#address_cl-error').text("");

                            $('.saveP').addClass('d-none');
                            $('.btn-loadingP').removeClass('d-none');
                
                            setTimeout(function(){
                              form.submit();
                            }, 2000);

                        }
                        else {
                            address_cl.addClass('is-invalid');
                            $('#address_cl-error').text($('#address_cl-error-message').val());
                        }
                    }
                    else {
                        phone_number_cl.addClass('is-invalid');
                        $('#phone_number_cl-error').text($('#phone_number_cl-error-message').val());
                    }
                }
                else {
                    email_cl.addClass('is-invalid');
                    $('#email_cl-error').text($('#email_cl-error-message').val());
                }
            } 
            else {
                department_cl.addClass('is-invalid');
                $('#department_cl-error').text($('#department_cl-error-message').val());
            }
        }
        else {
            grade_cl.addClass('is-invalid');
            $('#grade_cl-error').text($('#grade_cl-error-message').val());    
        }
    }
    else {
        full_name_cl.addClass('is-invalid');
        $('#full_name_cl-error').text($('#full_name_cl-error-message').val());
    }
});