$('#save-entreprise').click(function(){
    var name_entreprise = $('#name-entreprise').val();


    if(name_entreprise != ""){
        $('#name-entreprise-error').text('');
        $('#name-entreprise').removeClass('is-invalid');
        $('#name-entreprise').addClass('is-valid');

    }else{
        $('#name-entreprise-error').text($('#name-entreprise-error-message').val()); //Enter your company name please !
        $('#name-entreprise').addClass('is-invalid');
        $('#name-entreprise').removeClass('is-valid');
    }
});