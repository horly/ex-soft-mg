$('.save').click(function(){
    $('.save').addClass('d-none');
    $('.btn-loading').removeClass('d-none');
});

$('#change-email-request-save').click(function(){
  $('#change-email-request-save').addClass('d-none');
  $('#change-email-request-loading').removeClass('d-none');
}); 

function loadingBtnEntreManagement(id_entreprise, id_user, token, url){
  $('#btn-' + id_entreprise).addClass('d-none');
  $('#loading-' + id_entreprise).removeClass('d-none');

  var inputs = '';
  inputs += '<input type="hidden" name="id_entreprise" value="' + id_entreprise + '" />' 
            + '<input type="hidden" name="id_user" value="' + id_user + '" />'
            + '<input type="hidden" name="_token" value="' + token + '" />';
  
  $("body").append('<form action="' + url + '" method="POST" id="poster">' + inputs + '</form>');
  $("#poster").submit();
}

function loadingBtnEntreManagementUF(id_functionalUnit, id_entreprise, id_user, token, url){
  $('#btn-' + id_functionalUnit).addClass('d-none');
  $('#loading-' + id_functionalUnit).removeClass('d-none');

  var inputs = '';
  inputs += '<input type="hidden" name="id_functionalUnit" value="' + id_functionalUnit + '" />' 
            + '<input type="hidden" name="id_entreprise" value="' + id_entreprise + '" />'
            + '<input type="hidden" name="id_user" value="' + id_user + '" />'
            + '<input type="hidden" name="_token" value="' + token + '" />';
  
  $("body").append('<form action="' + url + '" method="POST" id="posterFu">' + inputs + '</form>');
  $("#posterFu").submit();
}


function deleteElement(id, url, token){
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
          inputs += '<input type="hidden" name="id_element" value="' + id + '" />' 
                    + '<input type="hidden" name="_token" value="' + token + '" />';
          
          $("body").append('<form action="' + url + '" method="POST" id="poster">' + inputs + '</form>');
          $("#poster").submit();
        });
    });
}

function deleteElementTwoVal(id1, id2, url, token){
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
        inputs += '<input type="hidden" name="id_element1" value="' + id1 + '" />' 
                  + '<input type="hidden" name="id_element2" value="' + id2 + '" />'
                  + '<input type="hidden" name="_token" value="' + token + '" />';
        
        $("body").append('<form action="' + url + '" method="POST" id="poster">' + inputs + '</form>');
        $("#poster").submit();
      });
  });
}

function deleteElementThreeVal(id1, id2, id3, url, token){
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
        inputs += '<input type="hidden" name="id_element1" value="' + id1 + '" />' 
                  + '<input type="hidden" name="id_element2" value="' + id2 + '" />'
                  + '<input type="hidden" name="id_element3" value="' + id3 + '" />'
                  + '<input type="hidden" name="_token" value="' + token + '" />';
        
        $("body").append('<form action="' + url + '" method="POST" id="poster">' + inputs + '</form>');
        $("#poster").submit();
      });
  });
}

$('.country-select').change(function(){
  var iscodeselected = $('.country-select option:selected').attr('iso-code');
  //console.log(iscodeselected);
  if(iscodeselected == undefined || iscodeselected == ""){
    $('.country-code-label').text("");
  }else{
    $('.country-code-label').text(iscodeselected);
  }
});

/**
 * read notification
 */

function readNotification(id, url, token){
    var inputs = '';
    inputs += '<input type="hidden" name="id_element" value="' + id + '" />' 
              + '<input type="hidden" name="_token" value="' + token + '" />';
    
    $("body").append('<form action="' + url + '" method="POST" id="posterNotif">' + inputs + '</form>');
    $("#posterNotif").submit();
}


function displayNotifications()
{
  $url = $('#display-notif').val();
  //console.log($url);
  window.location.replace($url);
}

$('#currency_name_dev').change(function(){
    var devise = $('option:selected', this).attr('devise');
    $('#currency_selected_dev').text(devise);
});