$(document).ready(function(){

  var form = document.getElementById('compilerform');

  var makeRequest = function(event){
    // this is the REAL cross-browser way to prevent submitting!
    event.preventDefault();

    var xhr = new XMLHttpRequest(),
        dataToSend = new FormData(event.target),
        // progress = document.querySelector('progress'),
        // textprogress = document.querySelector('i'),

        updateProgress = function(evt){
          // var howmuch = (evt.loaded / evt.total) * 100;
          // textprogress.innerHTML = ''+ Math.ceil(howmuch);
          // progress.value = howmuch;
        },

        updateLoading = function(evt){
          // progress.value = 100;
        },

        onLoadHandler = function(evt){
          $("#output").html(xhr.responseText);
          $("#output").slideDown(400, function() {
            $("#compiling").hide();
            $("#submit").show();
          });
          $(document).ready(function(){
            $("#expanderHead").click(function(){
              $("#expanderContent").slideToggle();
              if ($("#expanderSign").text() === "show"){
                $("#expanderSign").html("hide");
              }
              else {
                $("#expanderSign").text("show");
              }
            });
          });
        };

    xhr.upload.addEventListener('progress', updateProgress, false);
    xhr.upload.addEventListener('load', updateLoading, false);
    xhr.upload.addEventListener('loadend', updateLoading, false);

    xhr.onload = onLoadHandler;

    xhr.open('POST', form.getAttribute('action'), true);
    xhr.send(dataToSend);
  };

  form.addEventListener("submit", function (event) {
    event.preventDefault();
    $("#compiling").show();
    $("#submit").hide();
    $("#output").slideUp(200, function() {
      makeRequest(event);
    });
  });


});
