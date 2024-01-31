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