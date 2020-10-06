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



function show_info(el_id) {

	if (getComputedStyle(document.getElementById(el_id)).display == "none") {

		document.getElementById(el_id).style.display = "grid";

	} else {

		document.getElementById(el_id).style.display = "none";

	}

}



function task_select(el_id) { /* overwriting old id which is "task-fullbox-var(el_id)" */
	
	if (document.getElementById("selected-task") == null) {
		
		document.getElementById("task-box-" + el_id).id = "selected-task";
	}
	
}



function task_select_later(el_id) {
	document.getElementById("selected-task").id = "task-box-" + el_id;
}











