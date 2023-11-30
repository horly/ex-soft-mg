$('.type_contact').change(function(){
    var type = $('.type_contact').val();

    if(type == "company"){
        $('.company_info_contact').removeClass('d-none');
    }else{
        $('.company_info_contact').addClass('d-none');
    }
});