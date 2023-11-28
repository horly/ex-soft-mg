$('#customer_type_cl').change(function(){
    var type = $('#customer_type_cl').val();

    if(type == "company"){
        $('.company_info_cl').removeClass('d-none');
    }else{
        $('.company_info_cl').addClass('d-none');
    }
});