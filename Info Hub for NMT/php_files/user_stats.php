<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");

//+1 as 0 index is the date in this 2D array
$page = $_GET['page'] + 1;
$check = 0;

$today = date("m/d/y");

//first dimension of array
$file_lines = array();
//second dimension initialization
$file_arr = file("user_stats.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

//creates new date and stats if nothing exist in the file
if(sizeof($file_arr) == 0) {
        array_push($file_arr, $today . "," . "0,0,0,0,0,0,0,0");
}
// up from here works
// adds second dimension to first, checks todays date is in file
foreach($file_arr as $line) {
        $add = explode(",", $line);
        if($add[0] == $today) { $check  = 1;}
        array_push($file_lines, $add);
}
//adds todays date and zero values if not in the file
if($check == 0) {
        $new = $today . "," . "0,0,0,0,0,0,0,0";
        $new_arr = explode(",", $new);
        array_push($file_lines, $new_arr);
}
//perform addition based on day and page index (0-7, 8 total pages i'm interested in)
//then write 2D array to file
$file = fopen("user_stats.txt", "w");
foreach($file_lines as &$line) {
        $i = 0;
        if($line[0] == $today) {
                $line[$page] += 1;
        }
        foreach($line as $item) {
                $i == 8 ? $item .= "\n" : $item .= ",";
                fwrite($file, $item);
                $i++;
                if($i == 9) break;
        }
}
fclose($file);

?>
