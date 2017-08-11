<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");

$file = file("week_events.txt");

//some other format would be better for checks
$today = date("F j, Y");
$bound = date("F j, Y", strtotime($today . "+7 day"));
//$data = "";
$i = 0;
$config = $_GET['config'];
$rec = 0;
$data = "";

//$dt = date("Y-m-d", strtotime($prv . "+2 weeks"));
if($config == 1) {
        //echo trim($file[0]) . $today;
        foreach($file as $line) {
                if(strcmp(trim($line), $today) == 0) {
                        $rec = 1;
                        $data .= "<div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\" style=\"text-align: center;\"><h4>" . $today . "</h4></div><hr>";
                        $data .= "<div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\"></div>";
                        $data .= "<div class=\"list-group\" style=\"margin: 4px 0px 4px;\">";
                        $i++;
                        continue;
                }
                if(strcmp(trim($line), date("F j, Y", strtotime($today . "+1 day"))) == 0) {
                        $data .= "</div>";
                        $rec = 0;
                        break;
                }
                //title and time
                if($i == 1) {
                        $tmp = explode("|", trim($line));
                        $data .= "<div class=\"panel panel-default\" style=\"cursor: default; margin-bottom: 4px;\">";
                        $data .= "<div class=\"panel-heading\" style=\"background-color: #889eb2;\">";
                        $data .= "<h3 class=\"panel-title\" style=\"font-size: 16px;\">" . $tmp[1] . "  |  " . $tmp[0] . "</h3>";
                        $data .= "</div>";
                        //maybe not...this format is kinda ugly
                        $i++;
                        continue;
                }
                //event description, need to add to the <div>
                if($i == 2) {
                        $data .= "<div class=\"panel-body\" style=\"font-size: 16px;\">";
                        $data .= "<p  style=\"color: #113e66;\">" . $line . "<p></div>\n";
                        $data .= "</div>";
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
        $j = 0;
        $cnt = 0;
        $week_list = array();
        $events = array();

        array_push($week_list, $today);
        for($j = 1; $j < 7; $j++) {
                array_push($week_list, date("F j, Y", strtotime($today . "+" . $j . " day")));
        }
        $j = 0;
        //start formatting
        foreach($file as $line) {
                if($j == 7) {
                        if(strcmp(trim($line), $bound) == 0) {
                                array_push($events, $cnt);
                                break;
                        }
                        if($i == 0) {
                                if($line == "\n") {
                                        $i = 0;
                                        $cnt = 0;
                                        continue;
                                }
                                $i++;
                                $cnt++;
                                continue;
                        }
                        if($i == 1) {
                                $i++;
                                continue;
                        }
                        if($i == 2) {
                                $i = 0;
                                continue;
                        }
                }
                if(strcmp(trim($line), $week_list[$j]) == 0) {
                        array_push($events, $cnt);
                        $cnt = 0;
                        $j++;
                        continue;
                }
                if($i == 0) {
                        if($line == "\n") {
                                $i = 0;
                                $cnt = 0;
                                continue;
                        }
                        $i++;
                        $cnt++;
                        continue;
                }
                if($i == 1) {
                        $i++;
                        continue;
                }
                if($i == 2) {
                        $i = 0;
                        continue;
                }
        }

        $j = 0;
        $i = 0;
        $cnt = 0;
        $data .= "<div class=\"panel-group\" id=\"accordion\">\n";

        foreach($file as $line) {
                #the entire week
                if($j == 7) {

                        if(strcmp(trim($line), $bound) == 0) {
                                $data .= "</div>";
                                break;
                        }
                        if($i == 0) {
                                if($line == "\n") {
                                        $data .= "<p>There are no events on this day.</p>\n";
                                        $data .= "</div>\n</div>\n</div>\n</div>\n";
                                        $i = 0;
                                        continue;
                                }
                                $tmp = explode("|", trim($line));
                                $data .= "<div class=\"panel panel-default\" style=\"cursor: default;\">\n";
                                $data .= "<div class=\"panel-heading\" style=\"background-color: #889eb2;\">\n";
                                $data .= "<h3 class=\"panel-title\" style=\"font-size: 16px;\">" . $tmp[1] . "  |  " . $tmp[0] . "</h3>\n";
                                $data .= "</div>\n";
                                $i++;
                                continue;
                        }
                        if($i == 1) {
                                $data .= "<div class=\"panel-body\" style=\"font-size: 16px;\">\n";
                                $data .= "<p style=\"color: #113e66;\">\n" . $line . "</p>";
                                $data .= "</div>\n</div>\n";
                                $i++;
                                continue;
                        }
                        if($i == 2) {
                                $i = 0;
                                continue;
                        }
                }
                if(strcmp(trim($line), $week_list[$j]) == 0) {
                        if($cnt > 0) {
                                $data .= "</div>\n</div>\n</div>\n</div>\n";
                                $cnt = 0;
                        }
                        $data .= "<div class=\"panel panel-default\" style=\"cursor: pointer;\">";
                        $coll = "#collapse" . $j;
                        $data .= "<div class=\"panel-heading collapsed\"data-toggle=\"collapse\" data-parent=\"#accordion\" href=\"" . $coll . "\" style=\"text-align: left; padding: 3px 3px 3px 12px; margin-top: -4px;\">\n";
                        $data .= "<p class=\"panel-title\">\n";
                        $data .= "<a data-toggle=\"collapse\" data-parent=\"#accordion\" href=\"" . $coll . "\" style=\"font-size: 20px;\">". trim($line) ."</a>\n";
                        $data .= "<span class=\"glyphicon glyphicon-chevron-down pull-right\" style=\"padding: 7px;\"></span>";
                        $data .= "<span class=\"badge pull-right\" style=\"padding: 7px; background-color: white; color: #113e66;\">" . $events[$j + 1] . "</span>";
                        $data .= "</p>\n";
                        $data .= "</div>\n";
                        $data .= "<div id=\"collapse" . $j . "\" class=\"panel-collapse collapse\">\n";
                        $data .= "<div class=\"panel-body\" style=\"padding: 3px 3px 3px 10px;\">\n";
                        $data .= "<div class=\"list-group\" style=\"margin: 4px 0px 4px;\">\n";
                        $cnt = 0;
                        $i = 0;
                        $j++;
                        continue;
                }
                if($i == 0) {
                        if($line == "\n") {
                                $data .= "<p>There are no events on this day.</p>\n";
                                $data .= "</div>\n</div>\n</div>\n</div>\n";
                                $i = 0;
                                continue;
                        }
                        $tmp = explode("|", trim($line));
                        $data .= "<div class=\"panel panel-default\" style=\"cursor: default;\">\n";
                        $data .= "<div class=\"panel-heading\" style=\"background-color: #889eb2;\">\n";
                        $data .= "<h3 class=\"panel-title\" style=\"font-size: 16px;\">" . $tmp[1] . "  |  " . $tmp[0] . "</h3>\n";
                        $data .= "</div>\n";
                        $i++;
                        $cnt++;
                        continue;
                }
                if($i == 1) {
                        $data .= "<div class=\"panel-body\" style=\"font-size: 16px;\">\n";
                        $data .= "<p style=\"color: #113e66;\">\n" . $line . "</p>";
                        $data .= "</div>\n</div>\n";
                        $i++;
                        continue;
                }
                if($i == 2) {
                        $i = 0;
                        continue;
                }
        }
}
//fclose($file);
echo $data;

?>
