<?php
	
if (MAGICKEY != "haangon") {
	die("Pathway Forbidden.");
} else {
	
	// Global functions: 
	function clean_input($string) { //strips any threatening characters from stings
		$string = str_replace("//", "", $string);
		$string = str_replace("/*", "", $string);
		// $string = str_replace("*/", "", $string);
		$string = str_replace("';", "", $string);
		$string = str_replace('";', "", $string);
		$string = str_replace("?>", "", $string);
		$string = str_replace("<!--", "", $string);
		return($string);
		
	}
	
	function day_array_spit($array) { //spits out days from a numerical days array
	
		//overriding $days, for small compact strings
		$days = array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
		$string = "";
		
		foreach ($array as $arrayitem) {
			$string .= $days[$arrayitem] . " ";
		}
		
		return $string;
	}
	
}
	
?>