<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");

$config = $_GET['config'];
$date_array = array();
$out_array = array(0,0,0,0,0,0,0,0);
$max_index = sizeof($out_array) - 1;
$file_array = file("user_stats.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$data = "";

if($config == 0) {
        foreach($file_array as $line) {
                array_push($date_array, explode(",", $line));
        }
        foreach($date_array as &$line) {
                //build out array
                for($i = 1; $i < sizeof($line); $i++) {
                        $out_array[$i - 1] += $line[$i];
                }
        }
        $i = 0;
        foreach($out_array as $item) {
                $i == $max_index ? $item .= "\n" : $item .= ",";
                $data .= $item;
                $i++;
        }
} elseif($config == 1) {
        $today = date("m/d/y");
        foreach($file_array as $line) {
                array_push($date_array, explode(",", $line));
        }
        foreach($date_array as &$line) {
                if($line[0] == $today) {
                        for($i = 0; $i < sizeof($line); $i++) {
                                $out_array[$i] = $line[$i];
                        }
                }
        }
        $i = 0;
        foreach($out_array as $item) {
                $i == $max_index + 1 ? $item .= "\n" : $item .= ",";
                $data .= $item;
                $i++;
        }
}
echo $data;
?>
