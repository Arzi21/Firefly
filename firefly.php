<?php

	define("MAGICKEY", "haangon");

	require("misc/access.php");

	// define variables:
	$error_message = false;
	$days = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");

	$conn = new mysqli($host, $dbuser, $dbpass,$db);
	if ($conn->connect_error) {
		$error_message = "Failed to connect to server. Document Error";
	}

	require("misc/functions.php"); //globally used functions
	require("misc/useraccess.php"); //login/signup/session authentication
	require("misc/tasksystems.php"); //task input and upload

?>

<!DOCTYPE html>

<html lang='en'> <!-- WIP colour, darker, abit easier on the eyes. -->



<head>

	<meta charset='UTF-8'>
	<meta name='viewport' content='width-device-width, initial-scale=1'>

	<link rel='stylesheet' href='css/smallstyle.css'>
	<!-- fonts.google font: -->
	<link href="https://fonts.googleapis.com/css2?family=Raleway:wght@700&display=swap" rel="stylesheet">

	<title> Firefly - Illuminating the uncertainties of everyday life </title>

</head>



<body>

	<header>

		<?php
		if ($error_message == true) {
			echo
		"<div id='error_alert'>

			<p> $error_message </p>

		</div>
			";
		}

		if (isset($_COOKIE["UID"])) {echo "<p> Welcome, ". $user_name .". </p>";}

		?>

		<h1> Firefly </h1>

		<nav>

			<button type='button' onclick='previous()'> &lt;&lt;&lt;&lt; </button>

			<?php

				echo "<h2> $days[$current_day] </h2>";

			?>

			<button type='button' onclick='next()'> &gt;&gt;&gt;&gt; </button>

		</nav>


	</header>



	<main> <!--  -->
		<?php //if user isn't logged in, display login forms:
		if (!isset ($_COOKIE['UID'])) {
			echo"
		<!-- This Div contains all the sign in/up/out inputs the users has to go through for accound validation -->
		<!-- NOTE!: if the user is already signed in, the only input here will be <input name='submit_signout'> -->
		<div id='useraccess-form'>

			<form id='signin-form' method='POST' autocomplete='off'>

				<fieldset>

					<legend> Log in </legend>

					<p> Welcome back, lets get started. </p>

					<label for='si_email_field'> E-mail </label>
					<input type='email' id='si_email_field' name='usermail' autofocus required>

					<label for='si_password_field'> Password </label>
					<input type='password' id='si_password_field' name='password' required>


					<input type='submit' id='signin-btn' class='submit-button' value='Log in' name='submit_signin'>


				</fieldset>

				<div>

					<p> Dont have an account? </p>
					<button type='button' onclick='toggle_useraccess()'> Sign up </button>

				</div>

			</form>


			<form id='signup-form' method='POST' autocomplete='off'>

				<fieldset>

					<legend> Sign up </legend>

					<p> Welcome, lets make sure you're all set up. </p>

					<label for='email_field'> E-mail </label>
					<input type='email' id='email_field' name='usermail' autofocus required>

					<label for='name_field'> Name </label>
					<input type='text' id='name_field' name='username' required>

					<label for='password_field'> Password </label>
					<input type='password' id='password_field' name='password' required>

					<label for='password_field_check'> Password </label>
					<input type='password' id='password_field_check' name='password_check' required>


					<input type='submit' id='signup-btn' class='submit-button' value='Sign up' name='submit_signup'>

				</fieldset>

				<div>

					<p> Already have an account? </p>
					<button type='button' onclick='toggle_useraccess()'> Sign in </button>

				</div>

			</form>

		</div>";
		}
		?>

		<section id='task-list'> <!-- The task navigation section, content exported from database/taskdisplay.php -->
			<!-- Im squishing the 160 lines of variable form code into "taskpasteration", as each task comes with said 160
			"just in case" lines of code. this is mostly done to clean up our "homefile"
			These lines include:
			task pasting (fetching ALL tasks from the database and formatting them for JS),
			Task interaction (editing the tasks, along side clever forms that display the previous values),
			and lastly, deletion. NOTE that there is no "are you sure" yet when it comes to deletion. deletion on contact.
			-->
			<?php require("misc/taskpasteraction.php")?>

		</section>

	</main>



	<footer>

		<div id='task-creation'> <!-- The Task creation div -->
			<legend id="boxtitle">New Task</legend>
			<form id='task-form' method='POST' autocomplete='off'>

				<!-- Task Titles -->
				<label for='task_title'> Title: </label>
				<input type='text' id='task_title' name='title' required> <!--add autofocus when js implement-->

				<!-- Task Priority -->
				<div class="prio-cb">
					<input type='checkbox' id='task_prio' value='1' name='task-prio'>
					<label for="task-prio">
						<img src="images/star.svg" alt="">

					</label>
				</div>

				<!-- Task Descriptions -->
				<label for='task_description'> Description: </label>
				<textarea id='task_description' name='description' rows='4' cols='40'></textarea>


				<fieldset> <!-- Task Categories -->

					<legend> Categories </legend>
					<div class="categories">

						<div class="cat-block">
							<label for='task_type1'> Mental </label>
							<input type='radio' id='task_type1' value='mental' name='category' required>
						</div>

						<div class="cat-block">
							<label for='task_type2'> Personal </label>
							<input type='radio' id='task_type2' value='personal' name='category' required>
						</div>

						<div class="cat-block">
							<label for='task_type3'> Physical </label>
							<input type='radio' id='task_type3' value='physical' name='category' required>
						</div>

						<div class="cat-block">
							<label for='task_type4'> Social </label>
							<input type='radio' id='task_type4' value='social' name='category' required>
						</div>



					</div>

				</fieldset>


				<fieldset> <!-- Task Days -->

					<legend> Days </legend> <!-- If 0 selected, "today" is fallback value -->
					<!-- we COULD use JS to force users to select atleast one item.
					But we'd eventually change it back to this. -->

					<div class="day-block">
						<label for='task_day1'> Mon </label>
						<input type='checkbox' id='task_day1' value='1' name='day[]'>
					</div>

					<div class="day-block">
						<label for='task_day2'> Tue </label>
						<input type='checkbox' id='task_day2' value='2' name='day[]'>
					</div>

					<div class="day-block">
						<label for='task_day3'> Wed </label>
						<input type='checkbox' id='task_day3' value='3' name='day[]'>

					</div>

					<div class="day-block">
						<label for='task_day4'> Thu </label>
						<input type='checkbox' id='task_day4' value='4' name='day[]'>

					</div>

					<div class="day-block">
						<label for='task_day5'> Fri </label>
						<input type='checkbox' id='task_day5' value='5' name='day[]'>
					</div>

					<div class="day-block">
						<label for='task_day6'> Sat </label>
						<input type='checkbox' id='task_day6' value='6' name='day[]'>
					</div>

					<div class="day-block">
						<label for='task_day7'> Sun </label>
						<input type='checkbox' id='task_day7' value='0' name='day[]'>
					</div>

				</fieldset>


				<fieldset> <!-- Task Repetition -->

					<legend> Repetition </legend>

					<div class="rep-block">
						<label for='task_repetition1'> Once </label>
						<input type='radio' id='task_repetition1' value='once' name='repetition' required>
					</div>

					<div class="rep-block">
						<label for='task_repetition2'> Daily </label>
						<input type='radio' id='task_repetition2' value='daily' name='repetition' required>
					</div>

					<div class="rep-block">
						<label for='task_repetition3'> Weekly </label>
						<input type='radio' id='task_repetition3' value='weekly' name='repetition' required>
					</div>


				</fieldset>

				<!-- I've added the label to be able to maintain the task id, for the smoothest method of editing -->
				<label for='create-task-btn'> Done! </label>
				<input type='submit' id='create-task-btn' value='Done!' name='submit_task'>

			</form>

		</div>

		<script src='jscodex.js'>  </script>

	</footer>

</body>

<?php $conn->close() ?>

</html>
