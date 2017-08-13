<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");

class struct {
        public $title;
        public $crn;
        public $course;
        public $campus;
        public $time;
        public $day;
        public $credits;
        public $inst;
        public $seats;
}

function get_string_between($string, $start, $end){
     $string = " ".$string;
     $ini = strpos($string,$start);
     if ($ini == 0) return "";
     $ini += strlen($start);
     $len = strpos($string,$end,$ini) - $ini;
     return substr($string,$ini,$len);
}

$url = "http://banweb7.nmt.edu/pls/PROD/hwzkcrof.P_UncgSrchCrsOff";

$items = array('p_term' => $_GET['term'], 'p_subj' => $_GET['subj']);

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POSTFIELDS, $items);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$obj = new struct();
$output = curl_exec($ch);
$array = explode("\n", $output);
$count = 0;
$data = "";

foreach($array as $line) {
        if(strncmp(trim($line), "<TR>", 4) == 0 && $count == 0) {
                $count++;
                continue;
        }
        if(strncmp(trim($line), "<TD COLSPAN=\"12\"><br></TD>", 28) == 0) {
                $count = 0;
                continue;
        }
        if(strncmp(trim($line), "</TABLE>", 8) == 0) {
                break;
        }
        if($count > 0 && strncmp(trim($line), "<TD", 3) == 0) {
                switch($count) {
                case 1:
                        $obj->crn = get_string_between($line, ">", "<");
                        $count++;
                        continue;
                case 2:
                        $obj->course = get_string_between($line, ">", "<");
                        $count++;
                        continue;
                case 3:
                        $obj->campus = get_string_between($line, ">", "<");
                        $count++;
                        continue;
                case 4:
                        $obj->day = get_string_between($line, ">", "<");
                        $count++;
                        continue;;
                case 5:
                        $obj->time = get_string_between($line, ">", "<");
                        $count++;
                        continue;
                case 6:
                        $count++;
                        continue;
                case 7:
                        $obj->credits = get_string_between($line, ">", "<");
                        $count++;
                        continue;
                case 8:
                        $tmp = get_string_between($line, ">", "<");
                        if(strlen($tmp) > 2) {
                                $obj->title = $tmp;
                        }
                        $count++;
                        continue;
                case 9:
                        $obj->inst = get_string_between($line, ">", "<");
                        $count++;
                        continue;
                case 10:
                        $obj->seats = get_string_between($line, ">", "<");
                        $data .= "<div class=\"panel panel-default\" style=\"margin-top: 15px;\">\n";
                        if(strlen($obj->crn) > 2) {
                                $data .= "<div class=\"panel-heading\" style=\"background-color: #113E66; color: white; padding: 6px;\">" . $obj->crn . " - " . $obj->title . " (" . $obj->credits . " Credits)\n</div>\n";
                        } else {
                                $data .= "<div class=\"panel-heading\" style=\"background-color: #113E66; color: white; padding: 6px;\">" . $obj->title . " Recitation </div>\n";
                        }
                        $data .= "<div class=\"panel-body\" style=\"font-size: 14px; padding: 6px;\">\n";
                        $data .= "<p style=\"margin-bottom: 0px;\">" . $obj->course . " Instructor: " . $obj->inst . "</p></div>\n";
                        $data .= "<ul class=\"list-group\" style=\"margin-top: 0px;\">\n";
                        $data .= "<li class=\"list-group-item\" style=\"margin-bottom: 0px; text-align: left; font-size: 14px; padding: 6px;\">Campus: " . $obj->campus . "</li>\n";
                        if(strlen($obj->day) >= 2 && strlen($obj->time) >= 2) {
                                $data .= "<li class=\"list-group-item\" style=\"margin-bottom: 0px; text-align: left;  font-size: 14px; padding: 6px;\">" . $obj->day . " at " . $obj->time . "</li>\n";
                        }
                        $data .= "<li class=\"list-group-item\" style=\"margin-bottom: 0px; text-align: left; font-size: 14px; padding: 6px;\">Remaining Seats: " . $obj->seats . "</li>\n";
                        $data .= "</ul>\n</div>\n";

                        $count = 0;
                        continue;
                }
        }
}

curl_close($ch);
echo $data;

?>
