$('#register-user').click(function() {
    var firstname = $('#firstname').val();
    var lastname = $('#lastname').val();
    var email = $('#email').val();
    var password = $('#password').val();
    var password2 = $('#password-confirm').val();
    var passwordLength = password.length;
    //var agreeTerms = $('#agreeTerms');
    var role = $('#role').val();
    var grade = $('#function').val();
    var phone_number = $('#phone-number').val();
    var phone_number_ind = $('#phone-number-ind').text();
    var matricule = $('#matricule').val();
    var address = $('#address').val();

    if (firstname != "" && /^[a-zA-Z ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]+$/.test(firstname)) {
        $('#firstname').addClass('is-valid');
        $('#firstname').removeClass('is-invalid');
        $('#error-firstname').text("");

        if (lastname != "" && /^[a-zA-Z ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]+$/.test(lastname)) {
            $('#lastname').addClass('is-valid');
            $('#lastname').removeClass('is-invalid');
            $('#error-lastname').text("");

            if (email != "" && /^[a-zA-Z0-9._-]+@[a-z0-9._-]+\.[a-z]{2,6}$/.test(email)) {
                $('#email').addClass('is-valid');
                $('#email').removeClass('is-invalid');
                $('#error-email').text("");

                if (passwordLength >= 8) {
                    $('#password').addClass('is-valid');
                    $('#password').removeClass('is-invalid');
                    $('#error-password').text("");

                    if (password == password2) {
                        $('#password-confirm').addClass('is-valid');
                        $('#password-confirm').removeClass('is-invalid');
                        $('#error-password-confirmation').text("");

                        if (role != ""){
                            $('#role').addClass('is-valid');
                            $('#role').removeClass('is-invalid');
                            $('#error-role').text("");

                            if (grade != "") {
                                $('#function').addClass('is-valid');
                                $('#function').removeClass('is-invalid');
                                $('#error-function').text("");

                                if (phone_number != "" && /^[0-9]{9,9}$/.test(phone_number)) {

                                    $('#phone-number').addClass('is-valid');
                                    $('#phone-number').removeClass('is-invalid');
                                    $('#error-phone-number').text("");

                                    if(matricule != ""){
                                        $('#matricule').addClass('is-valid');
                                        $('#matricule').removeClass('is-invalid');
                                        $('#error-matricule').text("");

                                        $('#register-user').addClass('d-none');
                                        $('#loading-btn').removeClass('d-none');

                                        $.ajax({
                                            type : 'post',
                                            url : $('#form-register').attr('action'),
                                            data : {
                                                '_token' : $('#form-register').attr('token'),
                                                'name' : firstname + " " + lastname,
                                                'email' : email,
                                                'password' : password,
                                                'role' : role,
                                                'grade' : grade,
                                                'phone_number' : phone_number_ind + phone_number,
                                                'matricule' : matricule,
                                                'address' : address,
                                            },
                                            success: function(data){
                                                setTimeout(function(){
                                                    //console.log(data.user);
                                                    let title = $('#success-message').val(); //Success
                                                    let content = $('#user_added-message').val(); //User added successfully

                                                    successMessage(title, content);
                                                    $('#form-register')[0].reset();

                                                    $('#register-user').removeClass('d-none');
                                                    $('#loading-btn').addClass('d-none');
                                                    $(".form-control, .form-select").removeClass('is-valid');
                                                }, 3000);
                                            }
                                        });
                                        
                                    }else {
                                        $('#matricule').addClass('is-invalid');
                                        $('#matricule').removeClass('is-valid');
                                        $('#error-matricule').text($('#error-matricule-register-message').val()); //Enter the registration number please!
                                    }

                                } else {
                                    $('#phone-number').addClass('is-invalid');
                                    $('#phone-number').removeClass('is-valid');
                                    $('#error-phone-number').text($('#error-phone-number-register-message').val()); //The phone number you entered is invalid
                                }

                            }else {
                                $('#function').addClass('is-invalid');
                                $('#function').removeClass('is-valid');
                                $('#error-function').text($('#error-function-register-message').val()); //Select a function please!
                            }

                        }else{
                            $('#role').addClass('is-invalid');
                            $('#role').removeClass('is-valid');
                            $('#error-role').text($('#error-role-register-message').val()); //Select a role please!
                        }

                    } else {
                        $('#password-confirm').addClass('is-invalid');
                        $('#password-confirm').removeClass('is-valid');
                        $('#error-password-confirmation').text($('#error-password-confirmation-register-message').val()); //Your passwords must be identical!
                    }
                } else {
                    $('#password').addClass('is-invalid');
                    $('#password').removeClass('is-valid');
                    $('#error-password').text($('#error-password-register-message').val()); //Your password must be at least 8 characters!
                }
            } else {
                $('#email').addClass('is-invalid');
                $('#email').removeClass('is-valid');
                $('#error-email').text($('#error-email-register-message').val()); //Email is not valid!
            }
        } else {
            $('#lastname').addClass('is-invalid');
            $('#lastname').removeClass('is-valid');
            $('#error-lastname').text($('#error-lastname-register-message').val()); //Last Name is not valid!
        }
    } else {
        $('#firstname').addClass('is-invalid');
        $('#firstname').removeClass('is-valid');
        $('#error-firstname').text($('#error-firstname-register-message').val()); //First Name is not valid!
    }
});

function successMessage(title, content){
    swal({
        title: title,
        text: content,
        type: "success",
        showCancelButton: false,
        confirmButtonColor: "#198754",
        confirmButtonText: "OK",
        closeOnConfirm: true
    });
}

function errorMessage(title, content){
    swal({
        title: title,
        text: content,
        type: "error",
        showCancelButton: false,
        confirmButtonColor: "#dc3545",
        confirmButtonText: "OK",
        closeOnConfirm: true
    });
}

function warningMessage(title, content){
    swal({
        title: title,
        text: content,
        type: "warning",
        showCancelButton: false,
        confirmButtonColor: "#ffc107",
        confirmButtonText: "OK",
        closeOnConfirm: true
    });
}

$("#show-password").click(function(){
    $('#show-password').addClass('d-none');
    $('#hide-password').removeClass('d-none');
    $('#password').attr('type', 'text');
});


$("#hide-password").click(function(){
    $('#hide-password').addClass('d-none');
    $('#show-password').removeClass('d-none');
    $('#password').attr('type', 'password');
});


$("#show-password-confirm").click(function(){
    $('#show-password-confirm').addClass('d-none');
    $('#hide-password-confirm').removeClass('d-none');
    $('#password-confirm').attr('type', 'text');
});


$("#hide-password-confirm").click(function(){
    $('#hide-password-confirm').addClass('d-none');
    $('#show-password-confirm').removeClass('d-none');
    $('#password-confirm').attr('type', 'password');
});
