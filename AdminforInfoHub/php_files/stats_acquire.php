<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");

$date_array = array();
$out_array = array(0,0,0,0,0,0,0,0);
$max_index = sizeof($out_array) - 1;
$file_array = file("user_stats.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$data = "";

foreach($file_array as $line) {
        array_push($date_array, explode(",", $line));
}
foreach($date_array as &$line) {
        //ignore date for now
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
echo $data;
?>
