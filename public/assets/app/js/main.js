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
