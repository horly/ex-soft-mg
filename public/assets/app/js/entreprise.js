function changeIsoCode(){
  var iscodeselected = $('#country_entreprise option:selected').attr('iso-code');

  //alert(iscodeselected);
  if(iscodeselected == undefined || iscodeselected == ""){
    $('.iso-code-label').text("");
  }else{
    $('.iso-code-label').text("+"  + iscodeselected);
  }
}

$('#save_number_entreprise').click(function(){
    var new_phone_number = $('#new_phone_number');
    var form = $('#form_new_phone_number_entreprise');
  
    if(new_phone_number.val() != "" && /^[0-9]/.test(new_phone_number.val())){
      $('.saveP').addClass('d-none');
      $('.btn-loadingP').removeClass('d-none');

      new_phone_number.removeClass('is-invalid');
      $('#error_new_phone_number').text("");
      
      setTimeout(function(){
        form.submit();
      }, 2000);
      
    }else{
      new_phone_number.addClass('is-invalid');
      $('#error_new_phone_number').text($('#message_new_phone_number_entreprise').val());
    }
});


$('#save_email_entreprise').click(function(){
  var new_email_address = $('#new_email_address');
  var form = $('#form_new_email_entreprise');

  if(new_email_address.val() != "" && /^[a-zA-Z0-9._-]+@[a-z0-9._-]+\.[a-z]{2,6}$/.test(new_email_address.val())){
    $('.saveP').addClass('d-none');
    $('.btn-loadingP').removeClass('d-none');

    new_email_address.removeClass('is-invalid');
    $('#error_new_email_addressr').text("");
    
    setTimeout(function(){
      form.submit();
    }, 2000);
    
  }else{
    new_email_address.addClass('is-invalid');
    $('#error_new_email_addressr').text($('#message_new_email_entreprise').val());
  }
});

$('#save_bank_account_entreprise').click(function(){
  var form = $('#form_new_bank_account_entreprise');
  var bank_name = $('#bank_name');
  var account_title = $('#account_title');
  var account_number = $('#account_number');
  var account_currency = $('#account_currency');
  
  if(bank_name.val() != ""){

    bank_name.removeClass('is-invalid');
    $('#error_bank_name').text("");

    if(account_title.val() != ""){

      account_title.removeClass('is-invalid');
      $('#error_account_title').text("");

      if(account_number.val() != ""){

        account_number.removeClass('is-invalid');
        $('#error_account_number').text("");

        if(account_currency.val() != ""){

          account_currency.removeClass('is-invalid');
          $('#error_account_currency').text("");
          
          $('.saveP').addClass('d-none');
          $('.btn-loadingP').removeClass('d-none');

          setTimeout(function(){
            form.submit();
          }, 2000);

        }else{
          account_currency.addClass('is-invalid');
          $('#error_account_currency').text($('#message_account_currency').val());
        }
      }else{
        account_number.addClass('is-invalid');
        $('#error_account_number').text($('#message_account_number').val());
      }
    }else{
      account_title.addClass('is-invalid');
      $('#error_account_title').text($('#message_account_title').val());
    }
  }else{
    bank_name.addClass('is-invalid');
    $('#error_bank_name').text($('#message_bank_name').val());
  }
});

function addNewPhoneNModal(){
  $('.modal #new-number-phone-modal').text($('#title_add_a_new_number').val());
  document.getElementById("form_new_phone_number_entreprise").reset(); //on reintialise le formulaire
  $('#newPhone #modalRequest').val("add");
  $('#id_phone').val("0");
}

function editNewPhoneNModal(id, phone_number){
  $('.modal #new-number-phone-modal').text($('#title_edit_the_phone_number').val());
  $('#newPhone #modalRequest').val("edit");
  $('#new_phone_number').val(phone_number);
  $('#id_phone').val(id);
}

/**
 * Email
 */

function addNewEmailNModal(){
  $('.modal #new-email-modal').text($('#title_add_new_email_address').val());
  document.getElementById("form_new_email_entreprise").reset(); //on reintialise le formulaire
  $('#newEmail #modalRequest').val("add");
  $('#id_email').val("0");
}

function editNewEmailNModal(id, email){
  $('.modal #new-email-modal').text($('#title_edit_the_email_address').val());
  $('#newEmail #modalRequest').val("edit");
  $('#new_email_address').val(email);
  $('#id_email').val(id);
}

/**
 * Bank_Account
 */
function addBankAccountNModal(){
  $('.modal #new-bank-account-modal').text($('#title_add_a_new_bank_account').val());
  document.getElementById("form_new_bank_account_entreprise").reset(); //on reintialise le formulaire
  $('#newAccount #modalRequest').val("add");
  $('#id_bank').val("0");
}

function editBankAccountNModal(id, bank_name, account_title, account_number, devise_id, 
                                devise_iso_code, devise_motto_en, devise_motto, url, token, app_local){
  $('.modal #new-bank-account-modal').text($('#title_edit_a_new_bank_account').val());
  $('#newAccount #modalRequest').val("edit");
  $('#bank_name').val(bank_name);
  $('#id_bank').val(id);
  $('#account_title').val(account_title);
  $('#account_number').val(account_number);

  var select = $('#account_currency');
  select.html("");
  var option = "";
  
  var devises = getAllDevises(token, url);

  //console.log(devises);

  if(app_local == "en"){
    option += '<option value="' + devise_id + '" >' + devise_iso_code + ' - ' + devise_motto_en +'</option>';
    
  }else{
    option += '<option value="' + devise_id + '" >' + devise_iso_code + ' - ' + devise_motto +'</option>';
  }

  select.append(option);

  //console.log(app_local);
  
}

function getAllDevises(token, url){
    var devises = null;

    $.ajax({
      type: 'POST',
      url: url, 
      data:{
        '_token' : token
      },
      success:function(response){
        //console.log(response);
        devises = response.devises;
      }, 
      async: false
  });

  return devises;
}

function deletePhoneNumberEntr(id, url, token){

  swal({
    title: $('#are_you_sure_to_delete').val(),
    text: $('#this_action_is_irreversible').val(),
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: $('#yes_delete_it').val(),
    closeOnConfirm: false
},
function(){
    swal({
      title : $('#deleted').val(), 
      text : $('#the_item_was_successfully_deleted').val(), 
      type : "success",
    }, function(){
      //$('#form_phone_delete').submit();
      //console.log(id);
      //console.log(url);
      //console.log(token);
      var inputs = '';
      inputs += '<input type="hidden" name="id_phone_delete" value="' + id + '" />' 
                + '<input type="hidden" name="_token" value="' + token + '" />';
      
      $("body").append('<form action="' + url + '" method="POST" id="poster">' + inputs + '</form>');
      $("#poster").submit();
    });
  });
}

function deleteEmailEntr(id, url, token){

  swal({
    title: $('#are_you_sure_to_delete').val(),
    text: $('#this_action_is_irreversible').val(),
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: $('#yes_delete_it').val(),
    closeOnConfirm: false
},
function(){
    swal({
      title : $('#deleted').val(), 
      text : $('#the_item_was_successfully_deleted').val(), 
      type : "success",
    }, function(){
      //$('#form_phone_delete').submit();
      //console.log(id);
      //console.log(url);
      //console.log(token);
      var inputs = '';
      inputs += '<input type="hidden" name="id_email_delete" value="' + id + '" />' 
                + '<input type="hidden" name="_token" value="' + token + '" />';
      
      $("body").append('<form action="' + url + '" method="POST" id="poster">' + inputs + '</form>');
      $("#poster").submit();
    });
  });
}


/*const toastTrigger = document.getElementById('liveToastBtn') 
const toastLiveExample = document.getElementById('liveToast')

if (toastTrigger) {
  const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample)
  toastTrigger.addEventListener('click', () => {
    toastBootstrap.show()
  })
}*/