$(document).ready(function(){

  var form = document.getElementById('compilerform');

  function showResult(output) {
    $("#output").html(output);
    prettyPrint();
    $("#output").slideDown(400, function() {
      $("#compiling").hide();
      $("#submit").show();
    });
  }

  var makeRequest = function(event) {
    // this is the REAL cross-browser way to prevent submitting!
    event.preventDefault();

    var xhr = new XMLHttpRequest(),
        dataToSend = new FormData(event.target),
        // progress = document.querySelector('progress'),
        // textprogress = document.querySelector('i'),

        updateProgress = function(evt) {
          // var howmuch = (evt.loaded / evt.total) * 100;
          // textprogress.innerHTML = ''+ Math.ceil(howmuch);
          // progress.value = howmuch;
        },

        updateLoading = function(evt) {
          // progress.value = 100;
        },

        onLoadHandler = function(evt) {
          showResult(xhr.responseText);
          $(document).ready(function() {
            $("#expanderHead").click(function() {
              $("#expanderContent").slideToggle();
              if ($("#expanderSign").text() === "show") {
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
    var file = document.getElementById('file').files[0];

    $("#compiling").show();
    $("#submit").hide();
    $("#output").slideUp(200, function() {
        if (!file) {
          showResult("<p class=\"error\">No file selected</p>");
        } else if (file.name.split('.').pop() !== "cyc") {
          showResult("<p class=\"error\">File must have extension <i>.cyc</i></p>");
        } else if(file.size >= (200 * 1024)) {
          showResult("<p class=\"error\">File must be smaller than 200 kB</p>");
        } else {
          makeRequest(event);
        }
    });
  });

});
