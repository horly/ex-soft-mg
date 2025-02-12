/**
 * record payment
 */

$('#record_payment_invoice').click(function(){
    var amount = $('#amount_invoice_record').val();
    var payment_methods = $('#payment_methods_invoice_record').val();
    var ref_invoice = $('#ref_invoice').val();
    var type_record = $('#type_record').val();
    var id_fu = $('#id_fu').val();
    var url = $('#record_payment_invoice').attr('url');
    var token = $('#record_payment_invoice').attr('token');

    //console.log('Montant : ' + amount + '\nSolde restant : ' + remainingBalance);

    if(payment_methods != "")
    {
        $('#payment_methods_invoice_record-error').text("");
        $('#payment_methods_invoice_record').removeClass('is-invalid');

        if(amount != "" && amount != 0)
        {
            $('#amount_invoice_record-error').text("");
            $('#amount_invoice_record').removeClass('is-invalid');

            $.ajax({
                type: 'post',
                url: url,
                data: {
                    '_token': token,
                    'amount': amount,
                    'ref_invoice': ref_invoice,
                    'type_record': type_record,
                    'id_fu': id_fu
                },
                success:function(response){

                    //console.log(response);

                    if(response.result == "success"){
                        $('#amount_invoice_record-error').text("");
                        $('#amount_invoice_record').removeClass('is-invalid');

                        $('#record_payment_invoice_form').submit();
                    }
                    else{
                        $('#amount_invoice_record-error').text($('#amount_invoice_record-error-message').val());
                        $('#amount_invoice_record').addClass('is-invalid');
                    }
                }
            });
        }
        else
        {
            $('#amount_invoice_record-error').text($('#amount_invoice_record-error-message-empty').val());
            $('#amount_invoice_record').addClass('is-invalid');
        }
    }
    else{

        $('#payment_methods_invoice_record-error').text($('#payment_methods_invoice_record-error-message').val());
        $('#payment_methods_invoice_record').addClass('is-invalid');
    }
});



$('#send-message').click(function(){
    var first_name = $('#first_name');
    var last_name = $('#last_name');
    var phone_number = $('#phone_number');
    var email_addr = $('#email_addr');
    var message_text = $('#message_text');
    var subject = $('#subject');

    var first_name_error = $('#first_name-error');
    var last_name_error = $('#last_name-error');
    var phone_number_error = $('#phone_number-error');
    var email_addr_error = $('#email_addr-error');
    var message_text_error = $('#message_text-error');
    var subject_error = $('#subject-error');

    var form = $('#send-message-form');
    var token = $('#send-message-token');

    if(first_name.val() != "" && /^[a-zA-Z ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]+$/.test(first_name.val())) {
        first_name.removeClass('is-invalid');
        first_name_error.text("");

        if(last_name.val() != "" && /^[a-zA-Z ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]+$/.test(last_name.val())) {
            last_name.removeClass('is-invalid');
            last_name_error.text("");

            if(phone_number.val() != "" && /^[0-9]/.test(phone_number.val())) {
                phone_number.removeClass('is-invalid');
                phone_number_error.text("");

                if(email_addr.val() != "" && /^[a-zA-Z0-9._-]+@[a-z0-9._-]+\.[a-z]{2,6}$/.test(email_addr.val())) {
                    email_addr.removeClass('is-invalid');
                    email_addr_error.text("");

                    if(subject.val() != "") {
                        subject.removeClass('is-invalid');
                        subject_error.text("");

                        if(message_text.val() != "") {
                            message_text.removeClass('is-invalid');
                            message_text_error.text("");

                            $('#send-message').addClass('d-none');
                            $('#send-message-loading').removeClass('d-none');

                            $.ajax({
                                type: 'post',
                                url: form.attr('action'),
                                data: {
                                    '_token': token.val(),
                                    'name': first_name.val() + ' ' + last_name.val(),
                                    'phone_number': phone_number.val(),
                                    'subject': subject.val(),
                                    'email_addr': email_addr.val(),
                                    'message_text': message_text.val()
                                },
                                success:function(response) {

                                    //console.log(response);

                                    $('#send-message').removeClass('d-none');
                                    $('#send-message-loading').addClass('d-none');

                                    if(response.status == "success") {
                                        $('.alert-success').removeClass('d-none');
                                        $('.alert-danger').addClass('d-none');
                                        document.getElementById('send-message-form').reset();
                                    } else {
                                        $('.alert-success').addClass('d-none');
                                        $('.alert-danger').removeClass('d-none');
                                    }
                                }
                            });

                        } else {
                            message_text.addClass('is-invalid');
                            message_text_error.text(message_text_error.attr('message'));
                        }
                    }else {
                        subject_error.addClass('is-invalid');
                        subject_error.text(subject_error.attr('message'));
                    }
                } else {
                    email_addr.addClass('is-invalid');
                    email_addr_error.text(email_addr_error.attr('message'));
                }
            } else {
                phone_number.addClass('is-invalid');
                phone_number_error.text(phone_number_error.attr('message'));
            }
        } else {
            last_name.addClass('is-invalid');
            last_name_error.text(last_name_error.attr('message'));
        }
    } else {
        first_name.addClass('is-invalid');
        first_name_error.text(first_name_error.attr('message'));
    }
});





/* function getPermissionFu(id_user, id_fu, fu_name, token, url)
{
    $('#fu-permissions').text(fu_name);
    $('#id_fu').val(id_fu);

    $.ajax({
        type: 'post',
        url: url,
        data: {
            '_token': token,
            'id_user': id_user,
            'id_fu': id_fu
        },
        success:function(response){

            console.log(response);

            $('#full_dashboard_view').removeAttr('checked');
            $('#edit_delete_contents').removeAttr('checked');
            $('#billing').removeAttr('checked');
            $('#report_generation').removeAttr('checked');

            if(response.full_dashboard_view_assgn == 1){
                $('#full_dashboard_view').attr('checked', 'checked');
            }

            if(response.edit_delete_contents_assgn == 1){
                $('#edit_delete_contents').attr('checked', 'checked');
            }

            if(response.billing_assgn == 1){
                $('#billing').attr('checked', 'checked');
            }

            if(response.report_generation_assgn == 1){
                $('#report_generation').attr('checked', 'checked');
            }
        }
    });
}

*/
