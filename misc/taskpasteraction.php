<?php
	
if (MAGICKEY != "haangon") {
	die("Pathway Forbidden.");
} else {
	
	/*
	Im squishing the 160 lines of variable form code into task paste, as each task comes with said 160 
	"just in case" lines of code. this is mostly done to clean up our "homefile"
	*/
	$current_day = date("w"); //(numeric index value)
	$display_day = $current_day;
	
	
	
	//Control if user == loggedin. aviods errors later.
	if (!isset($_COOKIE["UID"])) {
		
		echo "<p> Login to view your tasks </p>";
		
	} else {
		
		// I will be pasting ALL days for a week with php, then the user can navigate it with javascript.
		
		//pulling ALL tasks belonging to one user:
		
		$seek_task = "SELECT * FROM tasks WHERE userid = '$user_id'";
		$task_result = $conn->query($seek_task);
		
		
		//pring $result in the main document
		if ($task_result->num_rows == 0) { // if/else to avoid error later
			echo "<p> No tasks added yet! </p>";
		} else {
				
		while ($row = $task_result->fetch_assoc()) {
			$task_id = $row["taskid"];
			$title = $row["title"];
			$description = $row["description"];
			$priority = $row["priority"];
			$category = $row["category"];
			$repetition = $row["repetition"];
			
			$days = str_split($row["day"]);
			$days = day_array_spit($days);
			
			echo "
			<article class='$priority $category $days task-box' id='task-box-$task_id' onclick='test()'>
				
				<div id='stripe-div'>  </div>
				
				<h3> $title </h3>
				
				<button type='button' onclick='showinfo($task_id)'> <img src='images/Task_Settings.svg'> </button> <!-- js display btn -->
				
			</article>
			
			<form method='POST' id='task-action-prompt'>
				
				<label for='taskdelete' class='task-box-btnset2'> Delete </label>
				<input type='submit' class='task-box-btnset2' id='taskdelete' name='delete_task' value='$task_id'>
				
				<label for='taskedit' class='task-box-btnset1'> Edit </label>
				<input type='submit' class='task-box-btnset1' id='taskedit' name='$task_id' value='Edit Task'>
				
			</form>
			
			<article id='$task_id' class='$category task-info-box'>
			
				<button type='button' class='exitbtn' onclick='showinfo($task_id)'> &#10005; </button>
				
				<div id='expanded-stripe-div'> </div>
			
				<h3> $title </h3>
				
				<!--<p> priority: $priority </p>-->
				<p> Repetition $repetition. </p>
				<!--<p> $category </p>-->
				
				<p> $description. </p>
				
				<form method='POST'>
					
					<label for='taskedit' class='task-box-btnset1'> Edit </label>
					<input type='submit' class='task-box-btnset1' id='taskedit' name='$task_id' value='Edit Task'>
					
					<label for='taskdelete' class='task-box-btnset2'> Delete </label>
					<input type='submit' class='task-box-btnset2' id='taskdelete' name='delete_task' value='$task_id'>
					
				</form>
				
			</article>
					";
					
			
			if (isset($_POST[$task_id])) {
						// if you click an edit button that matches this elements id, 
						// You'll now be able to edit any part of that unique element.
						// add autofocus to rescroll to it immidiently. 
						
						// maybe do this with js?^
						
						// this pastes the form ABOVE the task in question.
				echo "
			<div id='task-edit'>
				
				<form id='task-edit-form' method='POST' autocomplete='off'>
				
					<!-- Editing Task Titles -->
					<label for='task_title_edit'> Title: </label>
					<input type='text' id='task_title_edit' name='title' value='$title' autofocus required>
					
					<!-- Editing Task Descriptions -->
					<label for='task_description_edit'> Description: </label>
					<textarea id='task_description_edit' name='description' rows='4' cols='40'>$description</textarea>
					
					<!-- Editing Task Priority -->
					<label for='task_prio_edit'> Priority: </label>
					<input type='checkbox' id='task_prio_edit' value='1' name='prio'";
						
				if ($priority == 1) {
					echo " checked";
				}
						
				echo ">
					
					
					<fieldset> <!-- Editing Task Categories -->
						
						<legend> Categories </legend>
						
						<label for='task_type1_edit'> Mental </label>
						<input type='radio' id='task_type1_edit' value='mental' name='category' required";
						
				if ($category == "mental") {
					echo " checked";
				}
				
				echo ">
						
						<label for='task_type2_edit'> Personal </label>
						<input type='radio' id='task_type2_edit' value='personal' name='category' required";
						
				if ($category == "personal") {
					echo " checked";
				}
				
				echo ">
						
						<label for='task_type3_edit'> Physical </label>
						<input type='radio' id='task_type3_edit' value='physical' name='category' required";
						
				if ($category == "physical") {
					echo " checked";
				}
				
				echo ">
						
						<label for='task_type4_edit'> Social </label>
						<input type='radio' id='task_type4_edit' value='social' name='category' required";
						
				if ($category == "social") {
					echo " checked";
				}
				
				echo ">
						
					</fieldset>
					
					
					<fieldset> <!-- Editing Task Days -->
						
						<legend> Days </legend> <!-- If 0 selected, 'today' is fallback value -->
						<!-- we COULD use JS to force users to select atleast one item. 
						But we'd eventually change it back to this. -->
						
						<label for='task_day1_edit'> Mon </label>
						<input type='checkbox' id='task_day1_edit' value='1' name='day[]'";
						
				if (strpos($days, "Mon")) { //this str is directly linked to the function day_array_spit.
					echo " checked";
				}
				
				echo ">
						
						<label for='task_day2_edit'> Tue </label>
						<input type='checkbox' id='task_day2_edit' value='2' name='day[]'";
						
				if (strpos($days, "Tue")) { //this str is directly linked to the function day_array_spit.
					echo " checked";
				}
				
				echo ">
						
						<label for='task_day3_edit'> Wed </label>
						<input type='checkbox' id='task_day3_edit' value='3' name='day[]'";
						
				if (strpos($days, "Wed")) { //this str is directly linked to the function day_array_spit.
					echo " checked";
				}
						
						echo ">
						
						<label for='task_day4_edit'> Thu </label>
						<input type='checkbox' id='task_day_edit4' value='4' name='day[]'";
						
				if (strpos($days, "Thu")) { //this str is directly linked to the function day_array_spit.
					echo " checked";
				}
				
				echo ">
						
						<label for='task_day5_edit'> Fri </label>
						<input type='checkbox' id='task_day5_edit' value='5' name='day[]'";
						
				if (strpos($days, "Fri")) { //this str is directly linked to the function day_array_spit.
					echo " checked";
				}
				
				echo ">
						
						<label for='task_day6_edit'> Sat </label>
						<input type='checkbox' id='task_day6_edit' value='6' name='day[]'";
						
				if (strpos($days, "Sat")) { //this str is directly linked to the function day_array_spit.
					echo " checked";
				}
				
				echo ">
						
						<label for='task_day7_edit'> Sun </label>
						<input type='checkbox' id='task_day7_edit' value='0' name='day[]'";
						
				if (strpos($days, "Sun")) { //this str is directly linked to the function day_array_spit.
					echo " checked";
				}
				
				echo ">
						
					</fieldset>
					
					
					<fieldset> <!-- Editing Task Repetition -->
					
						<legend> Repetition </legend>
						
						<label for='task_repetition1_edit'> Once </label>
						<input type='radio' id='task_repetition1_edit' value='once' name='repetition' required";
						
				if ($repetition == "once") {
					echo " checked";
				}
				
				echo ">
						
						<label for='task_repetition2_edit'> Daily </label>
						<input type='radio' id='task_repetition2_edit' value='daily' name='repetition' required";
						
				if ($repetition == "daily") {
					echo " checked";
				}
				
				echo ">
						
						<label for='task_repetition3_edit'> Weekly </label>
						<input type='radio' id='task_repetition3_edit' value='weekly' name='repetition' required";
						
				if ($repetition == "weekly") {
					echo " checked";
				}
				
				echo ">
						
					</fieldset>
					
					<!-- I've added the label to be able to maintain the task id, for the smoothest method of editing -->
					<label for='edit-task-btn'> Done! </label>
					<input type='submit' id='edit-task-btn' value='$task_id' name='edit_task'>
					
				</form>
				
			</div>
						";
					}
					
				}

			}
			
	}
	
}
	
?>