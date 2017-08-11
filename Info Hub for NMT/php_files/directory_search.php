<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");

$name_file = "fileout.txt";
$array = file($name_file);

$FIRST_NAME = $_GET['fname'];
$LAST_NAME = $_GET['lname'];
$full_name = $FIRST_NAME . " " . $LAST_NAME;
$final = "Test";

$i = 0; //0 = name, 1 = number, 2 = office, 3 = email

foreach ($array as $value) {
	if ($i == 0 && strcmp($value, $full_name) == 0) {
		if ($i == 0) {
		$final .= "<div class=\"panel panel-default\" style=\"margin-top: 15px;\">\n";
		$final .= "<div class=\"panel-heading\" style=\"background-color: #113E66; color: white; padding: 6px;\">" . $value . " </div>\n";
		}
		elseif ($i == 1) {
			$final .= "<div class=\"panel-body\" style=\"font-size: 14px; padding: 6px;\">\n";
			$final .= "<p style=\"margin-bottom: 0px;\">" . " Number: " . $value . "</p></div>\n"; //might be somthing wrong here
			$final .= "<ul class=\"list-group\" style=\"margin-top: 0px;\">\n";
		}
		elseif ($i == 2) {
			$final .= "<li class=\"list-group-item\" style=\"margin-bottom: 0px; text-align: left; font-size: 14px; padding: 6px;\">Office: " . $value . "</li>\n";
		}
		elseif ($i == 3) {
			$final .= "<li class=\"list-group-item\" style=\"margin-bottom: 0px; text-align: left; font-size: 14px; padding: 6px;\">Email: " . $value . "</li>\n";
		}
		elseif ($i == 4) {
			$final .= "</ul>\n</div>\n";
			continue;
		}
	}

	elseif ( ($i == 0) && (abs(strcmp($value, $full_name)) <= 3) ) {
		if ($i == 0) {
		$final .= "<div class=\"panel panel-default\" style=\"margin-top: 15px;\">\n";
		$final .= "<div class=\"panel-heading\" style=\"background-color: #113E66; color: white; padding: 6px;\">" . $value . " </div>\n";
		}
		elseif ($i == 1) {
			$final .= "<div class=\"panel-body\" style=\"font-size: 14px; padding: 6px;\">\n";
			$final .= "<p style=\"margin-bottom: 0px;\">" . " Number: " . $value . "</p></div>\n"; //might be somthing wrong here
			$final .= "<ul class=\"list-group\" style=\"margin-top: 0px;\">\n";
		}
		elseif ($i == 2) {
			$final .= "<li class=\"list-group-item\" style=\"margin-bottom: 0px; text-align: left; font-size: 14px; padding: 6px;\">Office: " . $value . "</li>\n";
		}
		elseif ($i == 3) {
			$final .= "<li class=\"list-group-item\" style=\"margin-bottom: 0px; text-align: left; font-size: 14px; padding: 6px;\">Email: " . $value . "</li>\n";
		}
		elseif ($i == 4) {
			$i = 0;
			$final .= "</ul>\n</div>\n";
		}
	}
	$i++;
}
echo $final;
?>
