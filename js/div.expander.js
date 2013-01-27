$(document).ready(function(){
	$("#expanderHead").click(function(){
		$("#expanderContent").slideToggle();
		if ($("#expanderSign").text() == "(SHOW)"){
			$("#expanderSign").html("(HIDE)")
		}
		else {
			$("#expanderSign").text("(SHOW)")
		}
	});
});
