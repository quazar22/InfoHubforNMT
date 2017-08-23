<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");

$config = $_GET['config'];
$today = date("F j, Y");
$date_arr = array();

$data = "";
//return all possible date values, formatted in a dropdown box to choose from
for($i = 0; $i < 7; $i++) {
        array_push($date_arr, date("F j, Y", strtotime($today . "+" . $i . " day")));
}

//config 0 just returns a dropdown box of possible dates to enter data into as well as input forms for placing event text
if($config == 0) {
        $data = "<div class='row' id=\"insert\" style=\"font-family: Viga;\">\n";
        $data .= "<div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\">\n";
        $data .= "<select id=\"date\" class=\"form-control\">\n";

        for($i = 0; $i < sizeof($date_arr); $i++) {
                if($i == 0) {
                        $data .= "<option value=\"" . $date_arr[$i] . "\" selected>" . $date_arr[$i] . "</option>\n";
                } else {
                        $data .= "<option value=\"" . $date_arr[$i] . "\">" . $date_arr[$i] . "</option>\n";
                }

        }
        $data .= "<input type=\"text\" class=\"form-control\" id=\"time\" placeholder=\"Time range (ex. 10pm-5am)\">";
        $data .= "<input type=\"text\" class=\"form-control\" id=\"title\" placeholder=\"Title\">";
        $data .= "<input type=\"text\" class=\"form-control\" id=\"desc\" placeholder=\"Description\">";
        $data .= "</select>\n</div>\n</div>\n";
//config 1 takes in all the values from config 0 and places them into the week_events.txt file, returns "done!" when it is completed.
//might return nothing if it fails. Idk. Haven't tried that yet.
} elseif($config == 1) {

        $date_sel = $_GET['date'];
        $time = $_GET['time'];
        $title = $_GET['title'];
        $desc = $_GET['desc'];
        $file_array = file("week_events.txt");
        $out_array = array();

        for($i = 0; $i < sizeof($file_array); $i++) {
                if(trim($file_array[$i]) == $date_sel) {
                        array_push($out_array, $file_array[$i]);
                        array_push($out_array, $time . " | " . $title . "\n");
                        array_push($out_array, $desc . "\n");
                        if($file_array[$i + 1] != "\n") {
                                array_push($out_array, "\n");
                        }
                        continue;
                }
                array_push($out_array, $file_array[$i]);
        }

        $file = fopen("week_events.txt", "w");

        foreach($out_array as &$line) {
                fwrite($file, $line);
        }
        fclose($file);
        $data = "done!";
}

echo $data;
?>
