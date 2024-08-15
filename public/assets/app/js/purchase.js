function setPurchase(id_functionalUnit, id_entreprise, token, url, is_simple_purchase, is_purchase_order, is_specific_supplier, id_supplier)
{
    var inputs = '';
    inputs += '<input type="hidden" name="id_functionalUnit" value="' + id_functionalUnit + '" />'
                + '<input type="hidden" name="id_entreprise" value="' + id_entreprise + '" />'
                + '<input type="hidden" name="is_simple_purchase" value="' + is_simple_purchase + '" />'
                + '<input type="hidden" name="is_purchase_order" value="' + is_purchase_order + '" />'
                + '<input type="hidden" name="is_specific_supplier" value="' + is_specific_supplier + '" />'
                + '<input type="hidden" name="id_supplier" value="' + id_supplier + '" />'
                + '<input type="hidden" name="_token" value="' + token + '" />';

    $("body").append('<form action="' + url + '" method="POST" id="poster">' + inputs + '</form>');
    $("#poster").submit();
}


/**
 * upload file with progress bar
 */
$('#purchase_upload_pdf_form').ajaxForm({
    beforeSubmit : function(){
        var percentVal = "0%";
        var file_purchase = $('#file_purchase');

        var maxSizeKB = 4000; //Size in KB.
        var maxSizeMB = 4;
        var maxSize = maxSizeKB * 1024;

        if(file_purchase.val() != "")
        {
            var size = parseFloat(file_purchase[0].files[0].size);

            if(size <= maxSize)
            {
                $('#progress-bar-purchase').width(percentVal);
                $('#progress-bar-purchase').text(percentVal);
                $('#zone-progress-bar-purchase').attr('hidden', false);
                $('#file_purchase-error').text("");
                file_purchase.removeClass('is-invalid');
            }
            else
            {
                $('#file_purchase-error').text($('#file_purchase-size').val() + " " + maxSizeMB + " MB");
                file_purchase.addClass('is-invalid');
                return false;
            }
        }
        else
        {
            $('#file_purchase-error').text($('#file_purchase-message').val());
            file_purchase.addClass('is-invalid');
            return false;
        }
    },
    uploadProgress : function(event, position, total, percentComplete) {
        var percentVal = percentComplete + "%";
        $('#progress-bar-purchase').width(percentVal);
        $('#progress-bar-purchase').text(percentVal);
    },
    complete : function() {
        window.location.reload();
    }
});

function setExpense(id_functionalUnit, id_entreprise, token, url)
{
    var inputs = '';
    inputs += '<input type="hidden" name="id_functionalUnit" value="' + id_functionalUnit + '" />'
                + '<input type="hidden" name="id_entreprise" value="' + id_entreprise + '" />'
                + '<input type="hidden" name="_token" value="' + token + '" />';

    $("body").append('<form action="' + url + '" method="POST" id="poster1">' + inputs + '</form>');
    $("#poster1").submit();
}

function changeCurexpLabel()
{
    $('#current-exp-selected').text($("#currency_exp option:selected").text());
}


$('#save-expense-btn').click(function(){
    var description_exp = $('#description_exp');
    var amount_expense = $('#amount_expense');
    var pay_method_exp = $('#pay_method_exp');

    var currency_exp_iso_code= $('#currency_exp option:selected').attr('iso_code');
    var pay_method_exp_iso_code= $('#pay_method_exp option:selected').attr('iso_code');

    if(description_exp.val() != ""){
        description_exp.removeClass('is-invalid');
        $('#description_exp-error').addClass('d-none');

        if(amount_expense.val() != ""){
            amount_expense.removeClass('is-invalid');
            $('#amount_expense-error').addClass('d-none');

            /** console.log("Payment methode iso_code : " + pay_method_exp_iso_code + "\n" +
              *  "Currency selected iso_code : " + currency_exp_iso_code);
              */

            if(currency_exp_iso_code == pay_method_exp_iso_code){
                pay_method_exp.removeClass('is-invalid');
                $('#pay_method_exp-error').addClass('d-none');

                $('.saveP').addClass('d-none');
                $('.btn-loadingP').removeClass('d-none');

                $('#save-expense-form').submit();


            }else{
                pay_method_exp.addClass('is-invalid');
                $('#pay_method_exp-error').removeClass('d-none');
            }
        }else{
            amount_expense.addClass('is-invalid');
            $('#amount_expense-error').removeClass('d-none');
        }
    }else{
        description_exp.addClass('is-invalid');
        $('#description_exp-error').removeClass('d-none');
    }
});
