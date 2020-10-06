<?php
	
if (MAGICKEY != "haangon") {
	die("Pathway Forbidden.");
} else {
	
	$current_day = date("w"); //(numeric index value)
	$display_day = $current_day;
	
	
	/*----------------------------------------|
	|-----------------------------------------|
	|--------------TASK CREATION--------------|
	|-----------------------------------------|
	|----------------------------------------*/
	
	if (isset($_POST['submit_task']) && !isset($uploadfinished)) { //handeling HTML output
	
		//Control if user == loggedin. aviods errors later.
		if (!isset($_COOKIE['UID'])) {
			
			echo "<p> account not recognised, please log in. </p>";
			
		} else {
		
			//required outputs:
			$task_title = clean_input($_POST['title']);
			$task_category = $_POST['category'];
			$task_repetition = $_POST['repetition'];
			
			//Controlling non-required inputs:
			if (isset($_POST['description'])) {
				$task_description = clean_input($_POST['description']);
			} else {$task_description = "blank";}
			
			if (isset($_POST['prio'])) {
				$task_prio = $_POST['prio'];
			} else {$task_prio = 0;}
			
			if (!isset($_POST['day'])) {
				$task_day = array(date("w"));
			} else {
				$task_day = $_POST['day'];
			}
			
			//turning array(day) into a string for upload:
			$task_day = implode($task_day); //$str is now "DayDayDay"...
			if ($task_repetition == "daily") {
				$task_day = "1234560";
			}
			//we'll str_split(); it when we pull it back down.
			
			
			$update = "INSERT INTO tasks SET
			userid = '$user_id',
			title = '$task_title',
			description = '$task_description',
			priority = '$task_prio',
			repetition = '$task_repetition',
			category = '$task_category',
			day = '$task_day'";
			
			$conn->query($update); //BEAM IT UP SCOTTY!
			
			//test output:
			echo "<p> Task Added! </p>";
			
			//need to remove the refresh bug:
			
		}
		
	}
	
	
	
	/*----------------------------------------|
	|-----------------------------------------|
	|---------------TASK EDITING--------------|
	|-----------------------------------------|
	|----------------------------------------*/
	
	if (isset($_POST["edit_task"])) {
		$edit_task_id = $_POST["edit_task"];
		
		//Control if user == loggedin. aviods errors later.
		if (!isset($_COOKIE['UID'])) {
			
			echo "<p> account not recognised, please log in. </p>";
			
		} else {
		
			//required outputs:
			$task_title = clean_input($_POST['title']);
			$task_category = $_POST['category'];
			$task_repetition = $_POST['repetition'];
			
			//Controlling non-required inputs:
			if (isset($_POST['description'])) {
				$task_description = clean_input($_POST['description']);
			} else {$task_description = "blank";}
			
			if (isset($_POST['prio'])) {
				$task_prio = $_POST['prio'];
			} else {$task_prio = 0;}
			
			if (!isset($_POST['day'])) {
				$task_day = array(date("w"));
			} else {
				$task_day = $_POST['day'];
			}
			
			//turning array(day) into a string for upload:
			$task_day = implode($task_day); //$str is now "DayDayDay"...
			if ($task_repetition == "daily") {
				$task_day = "1234560";
			}
			//we'll str_split(); it when we pull it back down.
			
			
			$update = "UPDATE tasks SET
			title = '$task_title',
			description = '$task_description',
			priority = '$task_prio',
			repetition = '$task_repetition',
			category = '$task_category',
			day = '$task_day'
			WHERE taskid = $edit_task_id AND userid = $user_id";
			
			$conn->query($update);
			//edited task should now have been updated
		}
		
	}
	
	
	
	/*----------------------------------------|
	|-----------------------------------------|
	|---------------TASK DELETION-------------|
	|-----------------------------------------|
	|----------------------------------------*/
	
	if (isset($_POST["delete_task"])) {
		$delete_task_id = $_POST["delete_task"];
		
		//Control if user == loggedin. aviods errors later.
		if (!isset($_COOKIE['UID'])) {
			
			echo "<p> account not recognised, please log in. </p>";
			
		} else {
			// https://i.gyazo.com/e496f9f478ca040f42821adb9d3930d5.png
			
			$delete = "DELETE FROM tasks WHERE taskid = $delete_task_id AND userid = $user_id";
			$conn->query($delete);
			
		}
		
	}

}

/*

Issue #1: we're not explaining what the task categories are for, 
people might think its just the themes of tasks they're doing.
Possible Solution: as they work with the prompts they eventually learn?


Issue #2: does daily repetition mean every single day? or everysingle workday?
what is a user selects every day manually, and then selects the daily repetition.
Possible solution: remove daily, as users are already able to define that via the task_days.
thus keeping only once (do this task each $task_day for one week) and weekly (do this task 
each $task_day every week).


Issue #4: the first task after login isn't sent to the database. not sure what causes this.
INFO: editing the login cookie and logging back in seems to not reproduce the same issue.

*/
	
?>




