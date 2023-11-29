$('#customer_type_sup').change(function(){
    var type = $('#customer_type_sup').val();

    if(type == "company"){
        $('.company_info_sup').removeClass('d-none');
    }else{
        $('.company_info_sup').addClass('d-none');
    }
});