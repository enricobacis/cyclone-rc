$(document).ready(function() {

  var form = document.getElementById('compilerform');
  form.onsubmit = function() {
    var formData = new FormData(form);
    formData.append('file', file);
    var xhr = new XMLHttpRequest();

    $("#output").slideUp(200, function() {
      xhr.open('POST', form.getAttribute('action'), true);
      xhr.send(formData);
    });

    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4) {
        $("#output").html(xhr.responseText);
        $("#output").slideDown();
        $(document).ready(function(){
          $("#expanderHead").click(function(){
            $("#expanderContent").slideToggle();
            if ($("#expanderSign").text() == "show"){
              $("#expanderSign").html("hide")
            }
            else {
              $("#expanderSign").text("show")
            }
          });
        });
      }
    }
    
    return false; // To avoid actual submission of the form
  }

});
