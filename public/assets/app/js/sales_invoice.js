//$('#client_sales_invoice').select2();

$('#article_sales_invoice').select2({
    dropdownParent: $('#new_article_invoice'),
    width: '100%',
});

$('#service_sales_invoice').select2({
    dropdownParent: $('#new_service_invoice'),
    width: '100%',
});

function setUpinvoice(id_functionalUnit, id_entreprise, token, url, is_proforma)
{
    var inputs = '';
    inputs += '<input type="hidden" name="id_functionalUnit" value="' + id_functionalUnit + '" />' 
                + '<input type="hidden" name="id_entreprise" value="' + id_entreprise + '" />'
                + '<input type="hidden" name="is_proforma" value="' + is_proforma + '" />'
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

        var sale_p = calculateMargin($('#article_margin_invoice').attr('url'), $('#article_margin_invoice').attr('token'));
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
    var service_total_price_invoice = $('#service_total_price_invoice');

    if(service_sales_invoice.val() == "")
    {
        service_total_price_invoice.val("0");
    }
    else
    {
        service_total_price_invoice.val($('#service_sales_invoice option:selected').attr('unit_price'));

        /**
         * on met à jour la description de l'article
         */
        $('#descrption_saved_serv').val($('#service_sales_invoice option:selected').attr('description'))
    }
}

//calculateMargin($('#article_sales_invoice').attr('url'), $('#article_sales_invoice').attr('token'));

function calculateMargin(url, token)
{
    var article_margin_invoice = $('#article_margin_invoice').val();
    var article_purchase_price_invoice = $('#article_purchase_price_invoice').val();
    var quantity = $('#article_qty_invoice').val();
    //var article_sale_price_invoice = $('#article_sale_price_invoice').val();
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

            $('#article_sale_price_invoice').val(response.final_price.toFixed(2));
            
            pu = response.final_price;
            pt = pu * quantity
            $('#article_total_price_invoice').val(pt.toFixed(2));
        },
        async : false
    });
    return pu;
}

function changeTotalPrice()
{
    var article_sale_price_invoice = $('#article_sale_price_invoice').val();
    var quantity = $('#article_qty_invoice').val();

    var pt = article_sale_price_invoice * quantity; 

    $('#article_total_price_invoice').val(pt.toFixed(2));
}

$('#insert_article_invoice').click(function(){
    var article_sales_invoice = $('#article_sales_invoice').val();
    var article_qty_invoice = $('#article_qty_invoice').val();
    var article_margin_invoice = $('#article_margin_invoice').val();

    if(article_sales_invoice != "")
    {
        $('#article_sales_invoice').removeClass('is-invalid');
        $('#article_sales_invoice-error').text("");

        if(article_qty_invoice != "" && article_qty_invoice != 0 && article_qty_invoice != "0")
        {
            $('#article_qty_invoice').removeClass('is-invalid');
            $('#article_qty_invoice-error').text("");

            if(article_margin_invoice != "")
            {
                $('#article_margin_invoice').removeClass('is-invalid');
                $('#article_margin_invoice-error').text("");

                $('#form_insert_article_invoice').submit();
            }
            else
            {
                $('#article_margin_invoice').addClass('is-invalid');
                $('#article_margin_invoice-error').text($('#article_margin_invoice-message').val()); //margin_cannot_be_empty
            }
        }
        else
        {
            $('#article_qty_invoice').addClass('is-invalid');
            $('#article_qty_invoice-error').text($('#article_qty_invoice-message').val()); //Quantity cannot be empty
        }
    }
    else
    {
        $('#article_sales_invoice').addClass('is-invalid');
        $('#article_sales_invoice-error').text($('#article_sales_invoice-message').val()); //Select an article please!
    }
});

$('#insert_service_invoice').click(function(){
    
    var service_sales_invoice = $('#service_sales_invoice').val();

    if(service_sales_invoice != "")
    {
        $('#service_sales_invoice').removeClass('is-invalid');
        $('#service_sales_invoice-error').text(""); 

        $('#form_insert_service_invoice').submit();
    }
    else
    {
        $('#service_sales_invoice').addClass('is-invalid');
        $('#service_sales_invoice-error').text($('#service_sales_invoice-message').val()); 
    }
});

function updateArticleInvoice(id)
{
    var qty = $('#quantity-' + id).val();
    var margin = $('#margin-' + id).val();
    var purchase_price = $('#purchase-price-' + id).val();
    var sale_price = $('#sale-price-' + id).val();
    var total_price = $('#total-price-' + id).val();
    var ref_article = $('#ref_article-' + id).val();
    var description_inv_elmnt = $('#description_inv_elmnt-' + id).val();

    $('#article_qty_invoice').val(qty);
    $('#article_margin_invoice').val(margin);
    $('#article_purchase_price_invoice').val(purchase_price);
    $('#article_sale_price_invoice').val(sale_price);
    $('#article_total_price_invoice').val(total_price);
    //$('#article_sales_invoice').val(ref_article);
    //$('#article_sales_invoice option:selected').text(description_inv_elmnt);
    $('#article_sales_invoice').val(ref_article).trigger('change'); //select2 selection

    $('#article_margin_invoice').prop('readonly', true);

    $('.select2-invoice-item-zone').addClass('d-none');
    $('.input-invoice-item-zone').removeClass('d-none');
    $('#input-invoice-item').val(description_inv_elmnt);

    $('#id_invoice_element').val(id);
    $('#modalRequest-article').val('edit');

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


/**
 * record payment
 */

$('#record_payment_invoice').click(function(){
    var amount = $('#amount_invoice_record').val();
    var payment_methods = $('#payment_methods_invoice_record').val();
    var ref_invoice = $('#ref_invoice').val();
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
