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
