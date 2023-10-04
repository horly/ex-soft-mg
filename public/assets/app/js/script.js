$('.save').click(function(){
    $('.save').addClass('d-none');
    $('.btn-loading').removeClass('d-none');
});

$('#change-email-request-save').click(function(){
  $('#change-email-request-save').addClass('d-none');
  $('#change-email-request-loading').removeClass('d-none');
}); 


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

$('.country-select').change(function(){
  var iscodeselected = $('.country-select option:selected').attr('iso-code');
  //console.log(iscodeselected);
  if(iscodeselected == undefined || iscodeselected == ""){
    $('.country-code-label').text("");
  }else{
    $('.country-code-label').text(iscodeselected);
  }
});