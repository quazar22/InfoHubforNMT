<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");

#$file = fopen("fcopy.txt", "w");

$term_num = array();
$term_name = array();

$depart_code = array();
$depart_name = array();

$array = file("https://banweb7.nmt.edu/pls/PROD/hwzkcrof.p_uncgslctcrsoff");
$term = "<SELECT NAME=\"p_term\" SIZE=\"1\">";
$check = 0;
$count1 = 0;
$count2 = 0;

function get_string_between($string, $start, $end){
     $string = " ".$string;
     $ini = strpos($string,$start);
     if ($ini == 0) return "";
     $ini += strlen($start);
     $len = strpos($string,$end,$ini) - $ini;
     return substr($string,$ini,$len);
}

foreach($array as $line) {
        if(strcmp(trim($line), $term) == 0 && $check == 0) {
                $check++;
                continue;
        }
        if($check == 1 && strncmp(trim($line), "</SELECT>", 9) != 0) {
                $num = get_string_between($line, "\"", "\"");
                //pushes each 'p_term' onto the array 'stack'
                $name = explode(">", $line);
                array_push($term_name, $name[1]);
                array_push($term_num, $num);
                //keeps count of the number of items in stack
                $count1++;
        }
        if(strncmp(trim($line), "</SELECT>", 9) == 0) {
                $check++;
                $term = "<SELECT NAME=\"p_subj\" SIZE=\"1\">";
                continue;
        }
        //now place 'p_subj' into its own array
        if($check == 2 && strncmp(trim($line), "</SELECT>", 9) != 0 && strncmp(trim($line), $term, strlen($term)) != 0) {
                $code = get_string_between($line, "\"", "\"");
                $name = explode(">", $line);
                array_push($depart_name, $name[1]);
                array_push($depart_code, $code);
                $count2++;
        }
}

$data = "<div class='row' id=\"insert\" style=\"font-family: Viga;\">\n";
$data .= "<div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\">\n";
$data .= "<select id=\"p_term\" class=\"form-control\">\n";

for($i = 0; $i < $count1; $i++) {
        if($i == $count1 - 1) {
                $data .= "<option value=\"" . $term_num[$i] . "\" selected>" . $term_name[$i] . "</option>\n";
        } else {
                $data .= "<option value=\"" . $term_num[$i] . "\">" . $term_name[$i] . "</option>\n";
        }
}

$data .= "</select>\n</div>\n</div>\n";
$data .= "<div class='row' style=\"font-family: Viga;\">\n";
$data .= "<div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\">\n";
$data .= "<select id=\"p_subj\" class=\"form-control\">";

for($i = 2; $i < $count2; $i++) {
                $data .= "<option value=\"" . $depart_code[$i] . "\">" . $depart_name[$i] . "</option>\n";
}
#$data .= "</select>\n<input id=\"submit\" type=\"button\" value=\"Submit\" class=\"button-custom\">\n</div>\n</div>\n";
$data .= "</select>\n</div>\n</div>\n";
#fwrite($file, $data);
#fclose($file);

echo $data;



//I could return a formatted dropdown box here for choosing from terms and subjects
//WHICH IS WHAT IMMA DO



//here on up definitely works

/*$url = "https://banweb7.nmt.edu/pls/PROD/hwzkcrof.P_UncgSrchCrsOff";


$data = array('p_term' => '201810', 'p_subj' => 'CSE');

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);



$output = curl_exec($ch);



curl_close($ch);*/

?>
