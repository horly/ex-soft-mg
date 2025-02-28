//$('#client_sales_invoice').select2();

$('#article_sales_invoice').select2({
    dropdownParent: $('#new_article_invoice'),
    width: '100%',
});

$('#service_sales_invoice').select2({
    dropdownParent: $('#new_service_invoice'),
    width: '100%',
});

function setUpinvoice(id_functionalUnit, id_entreprise, token, url, is_proforma, is_client_specific_invoice, is_delivery_note, id_client)
{
    var inputs = '';
    inputs += '<input type="hidden" name="id_functionalUnit" value="' + id_functionalUnit + '" />'
                + '<input type="hidden" name="id_entreprise" value="' + id_entreprise + '" />'
                + '<input type="hidden" name="is_proforma" value="' + is_proforma + '" />'
                + '<input type="hidden" name="is_client_specific_invoice" value="' + is_client_specific_invoice + '" />'
                + '<input type="hidden" name="is_delivery_note" value="' + is_delivery_note + '" />'
                + '<input type="hidden" name="id_client" value="' + id_client + '" />'
                + '<input type="hidden" name="_token" value="' + token + '" />';

    $("body").append('<form action="' + url + '" method="POST" id="poster">' + inputs + '</form>');
    $("#poster").submit();
}


function getPriceArticleInvoice()
{
    var article_sales_invoice = $('#article_sales_invoice');
    var article_purchase_price_invoice = $('#article_purchase_price_invoice');
    var article_sale_price_invoice = $('#article_sale_price_invoice');
    var article_total_price_invoice = $('#article_total_price_invoice');
    var qty = $('#article_qty_invoice').val();


    if(article_sales_invoice.val() == "")
    {
        article_purchase_price_invoice.val("0");
        article_sale_price_invoice.val("0");
    }
    else
    {
        article_purchase_price_invoice.val($('#article_sales_invoice option:selected').attr('purchase_price'));

        var sale_p = calculateMargin($('#article_margin_invoice').attr('url'), $('#article_margin_invoice').attr('token'), 'add');
        console.log(qty);
        var total_sale_p = sale_p * qty;

        article_sale_price_invoice.val(sale_p.toFixed(2));
        article_total_price_invoice.val(total_sale_p.toFixed(2));

        /**
         * on met à jour la description de l'article
         */
        $('#descrption_saved_art').val($('#article_sales_invoice option:selected').attr('description'))
    }
}

function getPriceServiceInvoice()
{
    var service_sales_invoice = $('#service_sales_invoice');
    var service_unit_price_invoice = $('#service_unit_price_invoice');
    var service_total_price_invoice = $('#service_total_price_invoice');
    var service_qty_invoice = $('#service_qty_invoice').val();

    if(service_sales_invoice.val() == "")
    {
        service_unit_price_invoice.val("0");
        service_total_price_invoice.val("0");
    }
    else
    {
        service_unit_price_invoice.val($('#service_sales_invoice option:selected').attr('unit_price'));

        var total_sale_p = service_unit_price_invoice.val() * service_qty_invoice;

        service_total_price_invoice.val(total_sale_p.toFixed(2));

        /**
         * on met à jour la description de l'article
         */
        $('#descrption_saved_serv').val($('#service_sales_invoice option:selected').attr('description'))
    }
}

//calculateMargin($('#article_sales_invoice').attr('url'), $('#article_sales_invoice').attr('token'));

function calculateMargin(url, token, operation)
{
    var article_margin_invoice = null;
    var article_purchase_price_invoice = null;
    var quantity = null;

    if(operation == "add")
    {
        var article_margin_invoice = $('#article_margin_invoice').val();
        var article_purchase_price_invoice = $('#article_purchase_price_invoice').val();
        var quantity = $('#article_qty_invoice').val();
        //var article_sale_price_invoice = $('#article_sale_price_invoice').val();
    }
    else
    {
        var article_margin_invoice = $('#article_margin_invoice_updt').val();
        var article_purchase_price_invoice = $('#article_purchase_price_invoice_updt').val();
        var quantity = $('#article_qty_invoice_updt').val();
        //var article_sale_price_invoice = $('#article_sale_price_invoice').val();
    }

    var pu = 0;
    var pt = 0;

    $.ajax({
        type : 'POST',
        url : url,
        data : {
            '_token' : token,
            'margin' : article_margin_invoice,
            'purchase_price' : article_purchase_price_invoice,
        },
        success:function(response){
            //var value = (article_purchase_price_invoice * article_margin_invoice) / 100;
            //var final_sale_price = article_purchase_price_invoice + value;
            if(operation == "add")
            {
                $('#article_sale_price_invoice').val(response.final_price.toFixed(2));

                pu = response.final_price;
                pt = pu * quantity
                $('#article_total_price_invoice').val(pt.toFixed(2));
            }
            else
            {
                $('#article_sale_price_invoice_updt').val(response.final_price.toFixed(2));

                pu = response.final_price;
                pt = pu * quantity
                $('#article_total_price_invoice_updt').val(pt.toFixed(2));
            }
        },
        async : false
    });
    return pu;
}

function changeTotalPrice(operation)
{
    if(operation == "add")
    {
        var article_sale_price_invoice = $('#article_sale_price_invoice').val();
        var quantity = $('#article_qty_invoice').val();

        var pt = article_sale_price_invoice * quantity;

        $('#article_total_price_invoice').val(pt.toFixed(2));
    }
    else
    {
        var article_sale_price_invoice = $('#article_sale_price_invoice_updt').val();
        var quantity = $('#article_qty_invoice_updt').val();

        var pt = article_sale_price_invoice * quantity;


        $('#article_total_price_invoice_updt').val(pt.toFixed(2));
    }
}

function changeTotalPriceService(operation)
{
    if(operation == "add")
    {
        var service_unit_price_invoice = $('#service_unit_price_invoice').val();
        var quantity = $('#service_qty_invoice').val();

        var pt = service_unit_price_invoice * quantity;

        $('#service_total_price_invoice').val(pt.toFixed(2));
    }
    else
    {
        var service_unit_price_invoice = $('#service_unit_price_invoice_updt').val();
        var quantity = $('#service_qty_invoice_updt').val();

        var pt = service_unit_price_invoice * quantity;

        $('#service_total_price_invoice_updt').val(pt.toFixed(2));
    }
}

function insert_invoice_item(operation){
    var article_sales_invoice = $('#article_sales_invoice').val();
    var article_qty_invoice = $('#article_qty_invoice').val();
    var article_margin_invoice = $('#article_margin_invoice').val();
    var article_sales_invoice_manual = $('#article_sales_invoice_manual').val();

    if(article_sales_invoice != "")
    {
        $('#article_sales_invoice').removeClass('is-invalid');
        $('#article_sales_invoice-error').text("");

        if(article_sales_invoice_manual != "")
        {
            $('#article_sales_invoice_manual').removeClass('is-invalid');
            $('#article_sales_invoice_manual-error').text("");

            if(article_qty_invoice != "" && article_qty_invoice != 0 && article_qty_invoice != "0")
                {
                    $('#article_qty_invoice').removeClass('is-invalid');
                    $('.article_qty_invoice-error').text("");

                    if(article_margin_invoice != "")
                    {
                        $('#article_margin_invoice').removeClass('is-invalid');
                        $('.article_margin_invoice-error').text("");

                        $('#concerne_session').val($('#invoice_concern_sales').val());

                        operation == "add" ?
                            $('#form_insert_article_invoice').submit() :
                            $('#form_insert_article_invoice_updt').submit();
                    }
                    else
                    {
                        $('#article_margin_invoice').addClass('is-invalid');
                        $('.article_margin_invoice-error').text($('#article_margin_invoice-message').val()); //margin_cannot_be_empty
                    }
            }
            else
            {
                $('#article_qty_invoice').addClass('is-invalid');
                $('.article_qty_invoice-error').text($('#article_qty_invoice-message').val()); //Quantity cannot be empty
            }
        }
        else
        {
            $('#article_sales_invoice_manual').addClass('is-invalid');
            $('#article_sales_invoice_manual-error').text($('#article_sales_invoice-message').val()); //Select an article please!
        }
    }
    else
    {
        $('#article_sales_invoice').addClass('is-invalid');
        $('#article_sales_invoice-error').text($('#article_sales_invoice-message').val()); //Select an article please!
    }
}

function insert_invoice_item_service(operation){

    var service_sales_invoice = $('#service_sales_invoice').val();
    var service_sales_invoice_manual = $('#service_sales_invoice_manual').val();

    if(service_sales_invoice != "")
    {
        $('#service_sales_invoice').removeClass('is-invalid');
        $('#service_sales_invoice-error').text("");

        if(service_sales_invoice_manual != "")
        {
            $('#service_sales_invoice_manual').removeClass('is-invalid');
            $('#service_sales_invoice_manual-error').text("");

            $('#concerne_session_service').val($('#invoice_concern_sales').val());

            operation == "add" ?
                $('#form_insert_service_invoice').submit() :
                $('#form_insert_service_invoice_updt').submit();
        }
        else
        {
            $('#service_sales_invoice_manual').addClass('is-invalid');
            $('#service_sales_invoice_manual-error').text($('#service_sales_invoice-message').val());
        }
    }
    else
    {
        $('#service_sales_invoice').addClass('is-invalid');
        $('#service_sales_invoice-error').text($('#service_sales_invoice-message').val());
    }
}

function updateArticleInvoice(id, type)
{
    var qty = $('#quantity-' + id).val();
    var margin = $('#margin-' + id).val();
    var purchase_price = $('#purchase-price-' + id).val();
    var sale_price = $('#sale-price-' + id).val();
    var total_price = $('#total-price-' + id).val();
    //var ref_article = $('#ref_article-' + id).val();
    var description_inv_elmnt = $('#description_inv_elmnt-' + id).val();
    var custom_reference = $('#custom_reference-' + id).val();

    //console.log(custom_reference);

    if(type == "article")
    {
        //console.log(purchase_price);
        $('#article_qty_invoice_updt').val(qty);
        $('#article_margin_invoice_updt').val(margin);
        $('#article_purchase_price_invoice_updt').val(purchase_price);
        $('#article_sale_price_invoice_updt').val(sale_price);
        $('#article_total_price_invoice_updt').val(total_price);
        //$('#article_sales_invoice').val(ref_article);
        //$('#article_sales_invoice option:selected').text(description_inv_elmnt);
        $('#article_sales_invoice').val(id).trigger('change'); //select2 selection

        //$('#article_margin_invoice').prop('readonly', true);

        $('.select2-invoice-item-zone').addClass('d-none');
        $('.input-invoice-item-zone').removeClass('d-none');
        $('.input-invoice-item-article').val(description_inv_elmnt);
        $('.custom_reference_article').val(custom_reference);





        $('.id_invoice_element').val(id);
        //$('.modalRequest-article').val('edit');
    }
    else
    {
        //console.log(sale_price);
        $('#service_qty_invoice_updt').val(qty);
        $('#service_unit_price_invoice_updt').val(sale_price);
        $('#service_total_price_invoice_updt').val(total_price);
        $('#service_sales_invoice').val(id).trigger('change'); //select2 selection

        $('.select2-invoice-item-zone').addClass('d-none');
        $('.input-invoice-item-zone').removeClass('d-none');
        $('.input-invoice-item-service').val(description_inv_elmnt);
        $('.custom_reference_service').val(custom_reference);

        $('.id_invoice_element').val(id);
        //$('.modalRequest-article').val('edit');
    }
}

function modalInsertArticleInvoice()
{
    $('#modalRequest-article').val('add');
    $('#id_invoice_element').val('0');
    $('#article_sales_invoice').val("").trigger('change');

    $('.select2-invoice-item-zone').removeClass('d-none');
    $('.input-invoice-item-zone').addClass('d-none');

    $('#article_margin_invoice').prop('readonly', false);

    $('#article_qty_invoice').val('1');
    $('#article_purchase_price_invoice').val(0);
    $('#article_sale_price_invoice').val(0);
    $('#article_total_price_invoice').val(0);
}

function modalInsertServiceInvoice()
{
    $('#service_sales_invoice').val("").trigger('change');
    $('#service_total_price_invoice').val(0);

    $('.select2-invoice-item-zone').removeClass('d-none');
    $('.input-invoice-item-zone').addClass('d-none');
}

function choiseDiscount(value)
{
    //value == "yes" ? $('.discount-zone').removeClass('d-none') : $('.discount-zone').addClass('d-none');
    if(value == "yes")
    {
        $('.discount-zone').removeClass('d-none');
        $('#discount-yes[name=discount_choise]').val('yes');
    }
    else
    {
        $('.discount-zone').addClass('d-none');
        $('#discount-no[name=discount_choise]').val('no');
    }
}

function changeVat(url, token)
{
    var vat_purcentage = $('#vat-apply-change').find(":selected").val();
    var tot_excl_tax = $('#tot_excl_tax').val();

    if($('#discount-yes').is(':checked'))
    {
        tot_excl_tax = $('#discount_apply_input').val();
    }

    $.ajax({
        type : 'post',
        url : url,
        data : {
            '_token' : token,
            'vat_purcentage' : vat_purcentage,
            'tot_excl_tax' : tot_excl_tax
        },
        success:function(response){
            //console.log(response.vat_value.toFixed(2));
            $('#vat_apply_td').text(response.vat_value_format_double);
            $('#vat_apply_input').val(response.vat_value);

            $('#tot_incl_tax_td').text(response.tot_incl_tax_format_double);
            $('#tot_incl_tax_input').val(response.tot_incl_tax);

            $('#vat_apply_pur').text(vat_purcentage);
        }
    });
}

function changeDiscountSet(value)
{
    $('#discount-type-label').text(value);
}



function changeDiscountValue(url, token)
{
    var discount_value = $('#discount_value').val();
    var tot_excl_tax = $('#tot_excl_tax').val();
    var vat_purcentage = $('#vat-apply-change').find(":selected").val();
    var type = "";

    $('#discount-pourcentage').is(':checked') ? type = "pourcentage" : type = "currency";

    $.ajax({
        type : 'post',
        url : url,
        data : {
            '_token' : token,
            'discount_value' : discount_value,
            'vat_purcentage' : vat_purcentage,
            'tot_excl_tax' : tot_excl_tax,
            'type' : type,
        },
        success:function(response){
            //console.log(response.vat_value.toFixed(2));
            $('#vat_apply_td').text(response.vat_value_format_double);
            $('#vat_apply_input').val(response.vat_value);

            $('#discount_apply_td').text(response.sub_total_format_number);
            $('#discount_apply_input').val(response.sub_total);

            $('#tot_incl_tax_td').text(response.total_val_format_number);
            $('#tot_incl_tax_input').val(response.total_val);
        }
    });
}


$('#client_sales_invoice').change(function(){
    var value = $(this).val();
    var url = $(this).attr('url');
    var token = $(this).attr('token');

    var contacts = $('#client_contact_sales_invoice');
    contacts.html("");

    //console.log(value);

    if(value != 0)
    {
        $.ajax({
            type: 'post',
            url: url,
            data: {
                '_token' : token,
                'id_client' : value,
            },
            success:function(response) {
                //console.log(response);

                var data = response.contacts;
                var data_first = response.contact_first.id;

                var listitems = "";
                $.each(data, function(index, data){
                    listitems += "<option value=" + data.id + ">" + data.fullname_cl + "</option>";
                });
                contacts.append(listitems);

                $('.customer_session').val(value); //id_client pour enregistrer dans session
                $('.contact_session').val(data_first); //id_contact pour enregistrer dans session
            }
        });
    }
    else
    {
        contacts.html("<option value=''>" + $('#client_select_a_contact').val() + "</option>");
    }
});

$('#client_contact_sales_invoice').change(function(){
    var value = $(this).val();
    $('.contact_session').val(value); //id_contact pour enregistrer dans session
});

function modalInsertSerialNumberInvoice(id_inv_elemnt, description, qty)
{
    $('.modal #insert_serial_number_invoice-modal-title').text($('#add_serial_number_title').val())

    $('#modalRequest-serial_number_invoice').val('add');
    $('#id_invoice_element_sn').val(id_inv_elemnt);
    $('#article_serial_number_invoice').val(description);
    $('#quantity_serial_number_invoice').val(qty);
    $('#id_serial_number_invoice').val(0);
    $('#serial_number_invoice').val("");
    $('#serial_number_invoice-error').text("");
}

function modalUpdateSerialNumberInvoice(id_inv_elemnt, description, qty, id_sn, sn, loop)
{
    $('.modal #insert_serial_number_invoice-modal-title').text($('#update_serial_number_title').val())

    $('#modalRequest-serial_number_invoice').val('edit');
    $('#id_invoice_element_sn').val(id_inv_elemnt);
    $('#article_serial_number_invoice').val(description);
    $('#quantity_serial_number_invoice').val(qty);
    $('#id_serial_number_invoice').val(id_sn);
    $('#serial_number_invoice-iteration').text(loop + "-");
    $('#serial_number_invoice').val(sn);
    $('#serial_number_invoice-error').text("");
}

function addserialNumberInvoice(id) {
    var serial_number_invoice = $('#serial_number_invoice_' + id);
    var form = $('#serial_number_invoice-form');

    console.log(serial_number_invoice.val());

    if(serial_number_invoice.val() != "")
    {
        serial_number_invoice.removeClass('is-invalid');
        $('#serial_number_invoice-error_' + id).text("");

        form.submit();
    }
    else
    {
        serial_number_invoice.addClass('is-invalid');
        $('#serial_number_invoice-error_' + id).text($('#serial_number_invoice-message').val());
    }
};

$('#insert-serial-number-invoice').click(function(){
    var serial_number_invoice = $('#serial_number_invoice');
    var form_serial_number_invoice = $('#form_serial_number_invoice');

    if(serial_number_invoice.val() != ""){
        serial_number_invoice.removeClass('is-invalid');
        $('#serial_number_invoice-error').text("");

        form_serial_number_invoice.submit();

    }else{
        serial_number_invoice.addClass('is-invalid');
        $('#serial_number_invoice-error').text($('#serial_number_invoice-message').val());
    }
});

function generateDeliveryNote(ref_invoice, id_entreprise, id_functionalUnit, token, url){
    var inputs = '';
    inputs += '<input type="hidden" name="_token" value="' + token + '" />'
                + '<input type="hidden" name="id_functionalUnit" value="' + id_functionalUnit + '" />'
                + '<input type="hidden" name="id_entreprise" value="' + id_entreprise + '" />'
                + '<input type="hidden" name="ref_invoice" value="' + ref_invoice + '" />';

    $("body").append('<form action="' + url + '" method="POST" id="poster">' + inputs + '</form>');
    $("#poster").submit();
}

function setEntrance(id_functionalUnit, id_entreprise, token, url)
{
    var inputs = '';
    inputs += '<input type="hidden" name="id_functionalUnit" value="' + id_functionalUnit + '" />'
                + '<input type="hidden" name="id_entreprise" value="' + id_entreprise + '" />'
                + '<input type="hidden" name="_token" value="' + token + '" />';

    $("body").append('<form action="' + url + '" method="POST" id="poster1">' + inputs + '</form>');
    $("#poster1").submit();
}

$('#save-entrance-btn').click(function(){
    var description_entr = $('#description_entr');
    var amount_entrance = $('#amount_entrance');
    var pay_method_entr = $('#pay_method_entr');

    var currency_exp_iso_code= $('#currency_exp option:selected').attr('iso_code');
    var pay_method_exp_iso_code= $('#pay_method_entr option:selected').attr('iso_code');

    if(description_entr.val() != ""){
        description_entr.removeClass('is-invalid');
        $('#description_entr-error').addClass('d-none');

        if(amount_entrance.val() != ""){
            amount_entrance.removeClass('is-invalid');
            $('#amount_entrance-error').addClass('d-none');

            /** console.log("Payment methode iso_code : " + pay_method_exp_iso_code + "\n" +
              *  "Currency selected iso_code : " + currency_exp_iso_code);
              */

            if(currency_exp_iso_code == pay_method_exp_iso_code){
                pay_method_entr.removeClass('is-invalid');
                $('#pay_method_entr-error').addClass('d-none');

                $('.saveP').addClass('d-none');
                $('.btn-loadingP').removeClass('d-none');

                $('#save-entrance-form').submit();


            }else{
                pay_method_entr.addClass('is-invalid');
                $('#pay_method_entr-error').removeClass('d-none');
            }
        }else{
            amount_entrance.addClass('is-invalid');
            $('#amount_entrance-error').removeClass('d-none');
        }
    }else{
        description_entr.addClass('is-invalid');
        $('#description_entr-error').removeClass('d-none');
    }
});


function payment_terms_select()
{
    if($('#payment_terms').val() == "after_delivery"){
        //console.log("after_delivery");
        $('.after_delivery_zone').removeClass('d-none');
    }
    else if($('#payment_terms').val() == "to_order") {
        //console.log("to_order");
        $('.after_delivery_zone').addClass('d-none');
        $('.to_order_zone').removeClass('d-none');
    }
    else{
        $('.after_delivery_zone').addClass('d-none');
        $('.to_order_zone').addClass('d-none');
    }
}


function setNoteDoc(id, note_content)
{
    var edit = $('#edit-text').val();

    //console.log(edit);

    $('#id_note').val(id);
    $('#customerRequest').val('edit');
    $('#add-note').text(edit);

    $('#note_content').val(note_content);
}

function add_sign_seal(title)
{
    $('#title_crop_photo').text(title);
}


/**
 * send invoice via email
 */

$('#send_email_invoice').click(function(e){
    e.preventDefault();

    var to_email = $('#to-email');
    var recipient_name = $('#recipient_name');
    var message_email = $('#message-email');

    if(to_email.val() != "" && /^[a-zA-Z0-9._-]+@[a-z0-9._-]+\.[a-z]{2,6}$/.test(to_email.val())){
        $(to_email).removeClass('is-invalid');
        $('#to-email-error').text("");

        if(recipient_name.val() != "" && /^[a-zA-Z ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]+$/.test(recipient_name.val())){
            $(recipient_name).removeClass('is-invalid');
            $('#recipient_name-error').text("");

            if(message_email.val() != ""){
                $(message_email).removeClass('is-invalid');
                $('#message-email-error').text("");

                $('.saveP').addClass('d-none');
                $('.btn-loadingP').removeClass('d-none');

                $('#send_email_invoice_form').submit();

            } else {
                $(message_email).addClass('is-invalid');
                $('#message-email-error').text($('#message-email-error').attr('message'));
            }
        } else {
            $(recipient_name).addClass('is-invalid');
            $('#recipient_name-error').text($('#recipient_name-error').attr('message'));
        }
    } else {
        $(to_email).addClass('is-invalid');
        $('#to-email-error').text($('#to-email-error').attr('message'));
    }

});


$('#add_manually').click(function(e){
    e.preventDefault();

    $('.article_sales_invoice_default_zone').addClass('d-none');
    $('.article_sales_invoice_manual_zone').removeClass('d-none');
    $('#article_sales_invoice').val("manually");
    $('#article_sales_invoice_manual').val("");

});

$('#cancel_btn_article').click(function(e){
    e.preventDefault();

    $('.article_sales_invoice_default_zone').removeClass('d-none');
    $('.article_sales_invoice_manual_zone').addClass('d-none');
    $('#article_sales_invoice').val("");
    $('#article_sales_invoice_manual').val("default");
});


$('#add_manually_service').click(function(e){
    e.preventDefault();

    $('.service_sales_invoice_default_zone').addClass('d-none');
    $('.service_sales_invoice_manual_zone').removeClass('d-none');
    $('#service_sales_invoice').val("manually");
    $('#service_sales_invoice_manual').val("");

});

$('#cancel_btn_service').click(function(e){
    e.preventDefault();

    $('.service_sales_invoice_default_zone').removeClass('d-none');
    $('.service_sales_invoice_manual_zone').addClass('d-none');
    $('#service_sales_invoice').val("");
    $('#service_sales_invoice_manual').val("default");
});


