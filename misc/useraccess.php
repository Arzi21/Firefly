<?php
	
if (MAGICKEY != "haangon") {
	die("Pathway Forbidden.");
} else {
	
	
	//Declare variables:
	// $user_flame = ""; //Flame data currently unavailable
	
	$user_pass = $user_pass1 = $user_pass2 = "";
	//Scrapping the variables because paranoia is fun^
	
	
	//defining usersystem functions:
	
	function create_tempkey() {
		$tempkey = "";
		$chars = "qwrtypsdfghjklzxcvbnm1234567890";
		for ($i = 0; $i < 32; $i++) {
			$tempkey .= $chars[rand(0, strlen($chars)-1)];
		}
		return $tempkey;
	}
	
	
	
	/*----------------------------------------|
	|-----------------------------------------|
	|-----------------INTEGRITY---------------|
	making sure the login has not been highjacked
	|-----------------------------------------*/
	
	if (isset($_COOKIE["UID"]) || isset($_COOKIE["TK"])) {
		
		global $user_id;
		
		$user_id = $_COOKIE["UID"]; // ID has uses for later, all over the website.
		$user_tk = $_COOKIE["TK"];
		
		
		//Checking our current cookie against the login-given data:
		$seek = "SELECT * FROM users WHERE userid LIKE '$user_id' AND tempkey LIKE '$user_tk'";
		$result = $conn->query($seek);
		if ($result->num_rows == 1) { //integrity confirmed:
		
			//echo "<p> good cookie </p>";
			
			setcookie("UID", $user_id, time() + 3600, "/");
			setcookie("TK", $user_tk, time() + 3600, "/");
			
			//fetching name and flame:
			$user_name = $result->fetch_assoc()['name'];
			// $user_flame = $result->fetch_assoc()['flame']; //Flame data currently unavailable
			
		} else { //corrupted cookies found:
		
			//echo "<p> bad cookie </p>";
			
			//De-baking cookies:
			setcookie("UID", null, time() - 1, "/");
			setcookie("TK", null, time() - 1, "/");
			
			header("Refresh:0");
			
		}
		
	}
	
	
	
	/*----------------------------------------|
	|-----------------------------------------|
	|-----------------SIGN UP-----------------|
	|-----------------------------------------|
	|-----------------------------------------*/
	
	if (isset($_POST["submit_signup"])) { //signup input detected
		
		//declaring work variables to be used:
		$user_email = clean_input($_POST["usermail"]);
		$user_name = clean_input($_POST["username"]);
		$user_pass1 = clean_input($_POST["password"]);
		$user_pass2 = clean_input($_POST["password_check"]);
		
		//controlling input:
		if ($user_pass1 === $user_pass2 && 
		strlen($user_pass1) > 6 && 
		strlen($user_name) > 1 && 
		strlen($user_email) > 5) { //JS SHOULD be controlling this before PHP handles it.
		
			$user_hashword = password_hash($user_pass1, PASSWORD_DEFAULT);
			
			//checking for existing users:
			$seek = "SELECT * FROM users WHERE email LIKE '$user_email'";
			$result = $conn->query($seek);
			if ($result->num_rows > 0) { //existing email found:
				
				echo "<p> This email or password is already in use. Failed to create account. </p>";
				
			} elseif (null !== $result->fetch_assoc() && password_verify($user_pass, $result->fetch_assoc()["password"])) { //existing password found:
				
				echo "<p> This email or password is already in use. Failed to create account. </p>";
				
			} else { //input vacant details, account is created and uploaded:
				
				$tempkey = create_tempkey();
				
				$create = "INSERT INTO users SET 
				email = '$user_email', 
				name = '$user_name', 
				password = '$user_hashword',
				tempkey = '$tempkey'";
				$conn->query($create) or die("Upload Failed");
				
				// quickly fetching new new users id, and auto signing in
				
				$updateid = $conn->insert_id;
				
				setcookie("UID", $updateid, time() + 3600, "/");
				setcookie("TK", $tempkey, time() + 3600, "/");
				
			} //New account established ^
			
		} else { //user input does not met requirements.
			
			echo "<p> some information was faulty, please try again. </p>";
			
		} //Signup method ended ^
			
	} //variable cleanup completed
	
	
	
	/*----------------------------------------|
	|-----------------------------------------|
	|-----------------SIGN IN-----------------|
	|-----------------------------------------|
	|-----------------------------------------*/
	
	if (isset($_POST["submit_signin"])) {
		
		$user_email = clean_input($_POST["usermail"]);
		$user_pass = clean_input($_POST["password"]);
		
		$seek_user = "SELECT * FROM users WHERE email LIKE '$user_email'"; //Verifying email:
		$result = $conn->query($seek_user);
		if ($result->num_rows == 0) {
			
			echo "<p> Login failed. Credential(s) not found </p>";
				/*SHRED VARIABLES*/
		
		} else { //Email verified ^
			
			while ($row = $result->fetch_assoc()) {
				$user_id = $row["userid"];
				$user_name = $row["name"];
				$user_hashword = $row["password"];
				$user_flame = $row["flame"]; //needed for later
			}
			
			//Checking the userinput against the database hash:
			if (password_verify($user_pass, $user_hashword) == true) { //On success:
				
				//User credentials accepted. "logging" them in:
				$tempkey = create_tempkey();
				
				$update = "UPDATE users SET tempkey= '$tempkey' WHERE userid= '$user_id'";
				$conn->query($update);
				
				setcookie("UID", $user_id, time() + 3600, "/");
				setcookie("TK", $tempkey, time() + 3600, "/");
				//User will now be logged in for 1 hour (3600s)
				
				//TEST DISPLAY;
				echo "<p> Login Completed: OK </p>";
				
				header("Refresh:0");
				
			} else { //On fail:
				
				echo "<p> Username or password incorrect, please try again. </p>";
				$user_id = "";
				$user_name = "";
				$user_hashword = "";
				$user_flame = "";
				/*removed for data privacy*/
				
			}
			
		}
		
	}
	
}

/*

Sign up works without noticable issue.
Sign in system is faulty
No cookies are being created. suspected refresh error. (try POSTing data, to auto bake cookies)

ISSUE DEFINED:
system auto fails the login password verification.
ISSUE SOLUTION:
need to space queries to give the database time to input the signup info, before signing in.


FUN BUG NR 2:
WHENEVER THE USER ADDS A TASK THEY GET LOGGED THE FUCK OUT HAHAISN'TTHATSOFUNNY
NVM THEY'RE JUST IN A PERMANENT STATE OF LOGGING IN, BUT ARE NEVER ACTUALLY LOGGED IN
LOLOMGSORANDOMTHISISTHEGREATESTTHINGSINCESLICEDCHEESE
HOHOOHO THE TEMPKEY ISN'T UPDATEING HAHAAA
SIGNIN STILL WORKS FINE BUT THE LOGIN SYSTEM INSISTS ON BEING AN ADRENELIN TURD THAT KEEPS REFRESHING THE TEMPKEY
WELL FOLKS TURNS OUT I DONT KNOW HOW TO PROPERLY SEND DATA TO THE DATABASE, 
AS USERS WHO TRY TO LOGIN WOULD LITERALLY HAVE A BETTER CHANGE CONNECTING TO OUR DATABASE IF THEY PLACED THEIR QUERY
IN A FUCKING TEXT DOCUMENT ON A USB AND TRIED TO THROW IT AT ME.
GOOD. DAY.


ISSUE #3:
upon completing login, the user IS SUCCESFULLY logged in, but inorder to see tasks, a refresh is required.

*/	
?>










