<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");

$file = file("week_events.txt");

//some other format would be better for checks
$today = date("F j, Y");
//$data = "";
$i = 0;
$config = $_GET['config'];
$rec = 0;

//$dt = date("Y-m-d", strtotime($prv . "+2 weeks"));
if($config == 1) {
        //echo trim($file[0]) . $today;
        foreach($file as $line) {
                //if(strcmp(trim($line), date("F j, Y", strtotime($today . "+1 day"))) == 0) {
                //        $data .= "<br>get rekt";
                //        continue;
                //} else {
                //        $data .= $line;
                //}
                if(strcmp(trim($line), $today) == 0) {
                        $rec = 1;
                        $i++;
                        continue;
                }
                if(strcmp(trim($line), date("F j, Y", strtotime($today . "+1 day"))) == 0) {
                        $rec = 0;
                        break;
                }
                //title and time
                if($i == 1) {
                        $tmp = explode("|", trim($line));
                        $data .= "<div class=\"well\"><strong>" . $tmp[1] . "</strong> |"  . $tmp[0] . "</div>";
                        //maybe not...this format is kinda ugly
                        $i++;
                        continue;
                }
                //event description, need to add to the <div>
                if($i == 2) {
                        $data .= $line;
                        $i++;
                        continue;
                }
                if($i == 3) {
                        $i = 1;
                        continue;
                }
        }
}

if($config == 2) {
        foreach($file as $line) {
                #the entire week
                if($i == 0) {
                        $data .= $line;
                        continue;
                }
                if($i == 1) {
                        $data .= $line;
                        continue;
                }
                if($i == 2) {
                        $data .= $line;
                        continue;
                }
                if($i == 3) {
                        $i = 0;
                        continue;
                }
        }
}
fclose($file);
echo $data;

?>
