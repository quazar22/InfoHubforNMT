<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");

$array = file("names_info.txt", FILE_IGNORE_NEW_LINES);

$final = "";
$final_not_found = "";
$final_last = "";

$FIRST_NAME = $_GET['fname'];
$LAST_NAME = $_GET['lname'];
$full_name = $FIRST_NAME . " " . $LAST_NAME;

$not_found = "Could not find: " . $full_name . "\n Please check spelling. \n";
$not_found .= "If you are unsure of first name you may enter in only a last name.\n";

$found = 0;
$found_mis = 0;
$found_last = 0;

$i = 0; /* 0 = name, 1 = number, 2 = office, 3 = email */
$j = 0;
$k = 0;

foreach ($array as $value) {
	
	/* A check to make sure the user has entered input in both both boxes to proceed */
	if (strlen($FIRST_NAME) > 1 && strlen($LAST_NAME) > 1) {
		
		/* Builds html code for the exact name the user had entered in. Not case sensitive */
		if ($i == 0 && strcasecmp($value, $full_name) == 0 && $found == 0) {
			$final .= "<div class=\"panel panel-default\" style=\"margin-top: 15px;\">\n";
			$final .= "<div class=\"panel-heading\" style=\"background-color: #113E66; color: white; padding: 6px;\">" . $value . " </div>\n";
			$i++;
			$found = 1;

		} elseif ($i == 1) {
			$final .= "<div class=\"panel-body\" style=\"font-size: 14px; padding: 6px;\">\n";
			$final .= "<p style=\"margin-bottom: 0px;\">" . " Number: " . $value . "</p></div>\n";
			$final .= "<ul class=\"list-group\" style=\"margin-top: 0px;\">\n";
			$i++;

		} elseif ($i == 2) {
			$final .= "<li class=\"list-group-item\" style=\"margin-bottom: 0px; text-align: left; font-size: 14px; padding: 6px;\">Office: " . $value . "</li>\n";
			$i++;

		} elseif ($i == 3) {
			$final .= "<li class=\"list-group-item\" style=\"margin-bottom: 0px; text-align: left; font-size: 14px; padding: 6px;\">Email: " . $value . "</li>\n";
			$i++;

		} elseif ($i == 4) {
			$final .= "</ul>\n</div>\n";
			continue;
		}
		
		/* Builds html code for a number of tabs for names 85% (or more) close to the user entered name. Not case sensitive. */
		similar_text(strtolower($value), strtolower($full_name), $percent); /* returnes a percentage to $percent */
		if ($percent >= 85.0 && $found == 0 && $j == 0) {
			
			$final_not_found .= "<div class=\"panel panel-default\" style=\"margin-top: 15px;\">\n";
			$final_not_found .= "<div class=\"panel-heading\" style=\"background-color: #113E66; color: white; padding: 6px;\">" . $value . " </div>\n";
			$j++;
			$found_mis++;

		} elseif ($j == 1) {
			$final_not_found .= "<div class=\"panel-body\" style=\"font-size: 14px; padding: 6px;\">\n";
			$final_not_found .= "<p style=\"margin-bottom: 0px;\">" . " Number: " . $value . "</p></div>\n";
			$final_not_found .= "<ul class=\"list-group\" style=\"margin-top: 0px;\">\n";
			$j++;

		} elseif ($j == 2) {
			$final_not_found .= "<li class=\"list-group-item\" style=\"margin-bottom: 0px; text-align: left; font-size: 14px; padding: 6px;\">Office: " . $value . "</li>\n";
			$j++;

		} elseif ($j == 3) {
			$final_not_found .= "<li class=\"list-group-item\" style=\"margin-bottom: 0px; text-align: left; font-size: 14px; padding: 6px;\">Email: " . $value . "</li>\n";
			$j++;

		} elseif ($j == 4) {
			$j = 0;
			$final_not_found .= "</ul>\n</div>\n";
		}
		
	/* If the user has not entered in a first name but has entered in a last name. */
	} elseif (strlen($FIRST_NAME) == 0 && strlen($LAST_NAME) > 1) {
		
		if ($k == 0) {
			$value_last = substr($value, strpos($value, " ") + 1, strlen($value) - strpos($value, " "));
			similar_text(strtolower($value_last), strtolower($LAST_NAME), $percent);
			
			if ($percent > 90) {
				$final_last .= "<div class=\"panel panel-default\" style=\"margin-top: 15px;\">\n";
				$final_last .= "<div class=\"panel-heading\" style=\"background-color: #113E66; color: white; padding: 6px;\">" . $value . " </div>\n";
				$match = 1;
			}
			
			$k++;
			
		} elseif ($k == 1) {
			
			if ($match == 1) {
				$final_last .= "<div class=\"panel-body\" style=\"font-size: 14px; padding: 6px;\">\n";
				$final_last .= "<p style=\"margin-bottom: 0px;\">" . " Number: " . $value . "</p></div>\n";
				$final_last .= "<ul class=\"list-group\" style=\"margin-top: 0px;\">\n";
			}
			
			$k++;
		} elseif ($k == 2) {
			if ($match == 1) {
				$final_last .= "<li class=\"list-group-item\" style=\"margin-bottom: 0px; text-align: left; font-size: 14px; padding: 6px;\">Office: " . $value . "</li>\n";	
			}
			
			$k++;
		} elseif ($k == 3 ) {
			if ($match == 1) {
				$final_last .= "<li class=\"list-group-item\" style=\"margin-bottom: 0px; text-align: left; font-size: 14px; padding: 6px;\">Email: " . $value . "</li>\n";	
			}

			$k++;
		} elseif ($k == 4) {
			
			if ($match == 1) {
				$final_last .= "</ul>\n</div>\n";
				$k = 0;
				$match = 0;
				$found_last++;
			}
			$k = 0;
			
		}

	}
	
}

/* if found with a perfect match return that perfect match */
if ($found == 1 && strlen($full_name) !== 1) {
	echo $final;

/* if user did not place in any input */
} elseif (strlen($full_name) == 1) { 
	echo "<h4> Input Required... </h4>";

/* if user did not enter in a last name */
} elseif (strlen($LAST_NAME) == 0) {
	echo "<h4> 'Last Name' Field Requires Input... </h4>";


} elseif (strlen($FIRST_NAME) == 0 && strlen($LAST_NAME) > 1) {
	if ($found_last > 0) {
		echo $final_last;
	} else {
		echo "Could not find a last name similar enough to " . $LAST_NAME . " ...\n" . "Please check your spelling and retry.\n";
	}
	
	
	
/* if user entered in a mispelled name, display names 85% or more close to the mispelled word */
} elseif ($found_mis > 0) {
	echo "Here are/is " . $found_mis . " name(s) close to " . $full_name . "...\n";
	echo $final_not_found;

/* if user's input was not found or any names were not found close to user's word, display error message */
} else {
	echo $not_found;
}

?>