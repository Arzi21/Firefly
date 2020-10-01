/*Firefly*//*jscodex.js*//**/



//CONTROLING USERACCESS FORMS:

//the inherit start of the doc:	
currentstate = "signin";
function toggle_useraccess() {
	
	if (currentstate == "signin") { //if signin is displayed and button is pressed:
		
		document.getElementById("signin-form").style.display = "none";
		document.getElementById("signup-form").style.display = "block";
		
		currentstate = "signup";
		
	} else {
		
		document.getElementById("signup-form").style.display = "none";
		document.getElementById("signin-form").style.display = "block";
		
		currentstate = "signin";
		
	}
	
}


