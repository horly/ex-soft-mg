function changeIsoCode(){
  var iscodeselected = $('#country_entreprise option:selected').attr('iso-code');

  //alert(iscodeselected);
  if(iscodeselected == undefined || iscodeselected == ""){
    $('.iso-code-label').text("");
  }else{
    $('.iso-code-label').text("+"  + iscodeselected);
  }
}

/*const toastTrigger = document.getElementById('liveToastBtn')
const toastLiveExample = document.getElementById('liveToast')

if (toastTrigger) {
  const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample)
  toastTrigger.addEventListener('click', () => {
    toastBootstrap.show()
  })
}*/