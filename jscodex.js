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
	
	return;
	
}



function show_info(el_id) {

	if (getComputedStyle(document.getElementById(el_id)).display == "none") {

		document.getElementById(el_id).style.display = "grid";

	} else {

		document.getElementById(el_id).style.display = "none";

	}
	
	return;
	
}



function task_select(el_id) { /* overwriting old id which is "task-fullbox-var(el_id)" */
	
	if (document.getElementById("selected-task") == null) {
		
		document.getElementById("task-box-" + el_id).id = "selected-task";
	}
	
	return;
	
}



function task_select_later(el_id) {
	
	document.getElementById("selected-task").id = "task-box-" + el_id;
	
	return;
	
}



/*----------------------------------------|
|-----------------------------------------|
|------------DAY NAVIGATION---------------|
|-----------------------------------------|
|----------------------------------------*/

var days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
var fulldays = ['Sunday', 'Monday', 'Tuesday', 'Wedneday', 'Thursday', 'Friday', 'Saturday'];

var time = new Date();
var day = time.getDay();
var currentday = fulldays[day];

// Title Control:
document.getElementById('day-title').innerHTML = currentday;
	
var tasks = document.getElementsByClassName(days[day]);
for (var i = 0; i < tasks.length; i++) {
	tasks[i].style.display = 'grid';
}


function day_nav(order) { //function expect an order: "next" or "previous"
	if (order === 'next') { //next:
		
		++day;
		if (day === 7) { //creating the 0-6 loop:
			day = 0;
		}
		
	} else { //previous:
		
		--day;
		if (day === -1) { //creating the 0-6 loop:
			day = 6;
		}
		
	}
	
	currentday = fulldays[day];
	document.getElementById('day-title').innerHTML = currentday;
	
	for (var ii = 0; ii <= 6; ii++) {
	
		var tasks = document.getElementsByClassName(days[ii]);
		for (var i = 0; i < tasks.length; i++) {
			tasks[i].style.display = 'none';
		}
		
	}
	
	var tasks = document.getElementsByClassName(days[day]);
	for (var i = 0; i < tasks.length; i++) {
		tasks[i].style.display = 'grid';
		
	}
	
}










