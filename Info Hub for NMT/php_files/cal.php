<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");


function get_string_between($string, $start, $end){
        $string = " ".$string;
        $ini = strpos($string,$start);
        if ($ini == 0) return "";
        $ini += strlen($start);
        $len = strpos($string,$end,$ini) - $ini;
        return substr($string,$ini,$len);
}

$config = $_GET['config'];
if($config == 0) {
        $array = file("http://www.nmt.edu/nmt-calendar/week");
} elseif($config == 1) {
        $date = $_GET['date'];
        $nd = explode("/", $date);
        $m = $nd[0];
        $d = $nd[1];
        $y = $nd[2];
        if(strlen($nd[0]) == 1) {
                $m = "0" . $nd[0];
        }
        if(strlen($nd[1]) == 1) {
                $d = "0" . $nd[1];
        }
        if(strlen($nd[2]) == 2) {
                $y = "20" . $nd[2];
        }
        $str = "?date=" . $y . "-" . $m . "-" . $d;
        $url = "http://www.nmt.edu/nmt-calendar/week";
        $url .= $str;
        $array = file($url);
} elseif($config == 2) {
        $date = $_GET['date'];
        $url = "http://www.nmt.edu/nmt-calendar/week?date=";
        $url .= $date;
        $array = file($url);
}

$l_count = 1;
$check = 0;
$e_cnt = 0;
$co = "#collapse";
$prv = "";
$nxt = "";
$events = array();
$en = 0;
//counts number of events for each event
foreach($array as $line) {
        if($l_count > 457) {
                if(strncmp(trim($line), "<h2 class=\"jcl_header\">", 23) == 0) {
                        array_push($events, $en);
                        $en = 0;
                }
                if(strncmp(trim($line), "<div class=\"jcl_message\">", 25) == 0) {
                        $en = 0;
                }
                if(strncmp(trim($line), "<a href=", 8) == 0) {
                        $en++;
                }
                if(strncmp(trim($line), "<h3>Categories</h3>", 19) == 0) {
                        array_push($events, $en);
                        $en = 0;
                        break;
                }
        }
        $l_count++;
}

$l_count = 1;

foreach($array as $line) {
        if($l_count == 453) {
                $tmp = trim($line);
                $rec = 0;
                //grabs "?date=YYYY-MM-DD"
                for($i = 0; $i < strlen($tmp); $i++) {
                        if($tmp[$i] == "?") {
                                $rec = 1;
                                $prv .= $tmp[$i];
                                continue;
                        }
                        if($tmp[$i] == "\"") {
                                $rec = 0;
                                continue;
                        }
                        if($rec == 1) {
                                $prv .= $tmp[$i];
                                continue;
                        }
                }
                //this is the next week in format YYYY-MM-DD
                $new = explode("=", $prv);
                $prv = $new[1];
                $dt = date("Y-m-d", strtotime($prv . "+2 weeks"));
                $nxt = $dt;
        }
        if($l_count == 457) {
                //new
                $data = "";
                $format = "style = \"   background-color: #dddddd;
                                	border-top-left-radius: 6px;
                                	border-top-right-radius: 6px;
                                	border-bottom-right-radius: 6px;
                                	border-bottom-left-radius: 6px;
                                        border: 1px solid #dddddd;
                                        padding: 5px;
                                        min-width: 80px;\"";
                $format2 = "style = \"   background-color: #dddddd;
                                	border-top-left-radius: 6px;
                                	border-top-right-radius: 6px;
                                	border-bottom-right-radius: 6px;
                                	border-bottom-left-radius: 6px;
                                        border: 1px solid #dddddd;
                                        padding: 5px;
                                        min-width: 80px;
                                        float: right;\"";
                $data .= "<div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\">\n";
                $data .= "<div class=\"col-lg-4 col-md-4 col-sm-4 col-xs-4\">\n";
                $data .= "<input id=\"submit1\" type=\"button\" value=\"Prev Week\" class=\"week-button\"" . $format . ">\n";
                $data .= "</div>\n";
                //end
                $data .= "<div class=\"col-lg-4 col-md-4 col-sm-4 col-xs-4\" style=\"text-align: center; font-size: 20px;\"><p style=\"margin-bottom: 10px;\">\n";
                $data .= substr(trim($line), 0, 7);
                $data .= "</p></div>\n";
                //also new
                $data .= "<div class=\"col-lg-4 col-md-4 col-sm-4 col-xs-4\">\n";
                $data .= "<input id=\"submit2\" type=\"button\" value=\"Next Week\" class=\"week-button\"" . $format2 . ">\n";
                $data .= "</div>\n";
                $data .= "</div>\n";
                //end
                $data .= "<hr>\n";
                $data .= "<script>
                $(document).ready(function() {
                        $(\"#submit1\").click(function() {
                                $('#insert').html(\"Loading...\");
                                $.ajax({
                                        type: \"GET\",
                                        url: \"http://infohost.nmt.edu/~osl/cal.php\",
                                        crossDomain: true,
                                        cache: false,
                                        data: {
                                                date : \"" . $prv . "\",
                                                config : 2
                                        },
                                        success: function(msg) {
                                                $(\"#insert\").html(msg);
                                        }
                                });
                        });
                        $(\"#submit2\").click(function() {
                                $('#insert').html(\"Loading...\");
                                $.ajax({
                                        type: \"GET\",
                                        url: \"http://infohost.nmt.edu/~osl/cal.php\",
                                        crossDomain: true,
                                        cache: false,
                                        data: {
                                                date : \"" . $nxt . "\",
                                                config : 2
                                        },
                                        success: function(msg) {
                                                $(\"#insert\").html(msg);
                                        }
                                });
                        });
                });
                </script>";
                //end new
                //new
                /*$data .= "<script>
                        $('.panel-title').click( function(){
                                $(this).find('span.glyphicon.glyphicon-chevron-down.pull-right').toggleClass('glyphicon-chevron-down').toggleClass('glyphicon-chevron-up');
                        });
                        </script>";*/
                //endnew
                $data .= "<div class=\"panel-group\" id=\"accordion\">\n";

        }
        if($l_count > 457) {
                //vvv for sunday only
                if(strncmp(trim($line), "<div id=\"jcl_layout_body\">", 25) == 0) {
                        $tmp = substr(trim($line), 82);
                        $tmp = get_string_between($tmp, ">", "<");
                        $tmp = date("D, M d, Y", strtotime($tmp));
                        $coll = $co . $e_cnt;
                        $data .= "<div class=\"panel panel-default\" style=\"cursor: pointer;\">";//
                        $data .= "<div class=\"panel-heading collapsed\"data-toggle=\"collapse\" data-parent=\"#accordion\" href=\"" . $coll . "\" style=\"text-align: left; padding: 3px 3px 3px 12px; margin-top: -4px;\">\n";
                        $data .= "<p class=\"panel-title\">\n";
                        $data .= "<a data-toggle=\"collapse\" data-parent=\"#accordion\" href=\"" . $coll . "\" style=\"font-size: 20px;\">". $tmp ."</a>\n";
                        $data .= "<span class=\"glyphicon glyphicon-chevron-down pull-right\" style=\"padding: 7px;\"></span>";
                        $data .= "<span class=\"badge pull-right\" style=\"padding: 7px; background-color: white; color: #113e66;\">" . $events[$e_cnt] . "</span>";
                        $data .= "</p>\n";
                        $data .= "</div>\n";
                        $data .= "<div id=\"collapse" . $e_cnt . "\" class=\"panel-collapse collapse\">\n";
                        $data .= "<div class=\"panel-body\" style=\"padding: 3px 3px 3px 10px;\">\n";
                        $data .= "<div class=\"list-group\" style=\"margin: 4px 0px 4px;\">\n";
                        $e_cnt++;
                }
                if(strncmp(trim($line), "<h2 class=\"jcl_header\">", 23) == 0) {
                        if($check > 0) {
                                $data .= "</div>\n</div>\n</div>\n</div>\n";
                                $check = 0;
                        }
                        $tmp = substr(trim($line), 56);
                        $tmp = get_string_between($tmp, ">", "<");
                        $tmp = date("D, M d, Y", strtotime($tmp));
                        $coll = $co . $e_cnt;
                        $data .= "<div class=\"panel panel-default\" style=\"cursor: pointer;\">";//
                        $data .= "<div class=\"panel-heading collapsed\"data-toggle=\"collapse\" data-parent=\"#accordion\" href=\"" . $coll . "\" style=\"text-align: left; padding: 3px 3px 3px 12px; margin-top: -4px;\">\n";
                        $data .= "<p class=\"panel-title\">\n";
                        $data .= "<a data-toggle=\"collapse\" data-parent=\"#accordion\" href=\"" . $coll . "\" style=\"font-size: 20px;\">". $tmp ."</a>\n";
                        $data .= "<span class=\"glyphicon glyphicon-chevron-down pull-right\" style=\"padding: 7px;\"></span>";
                        $data .= "<span class=\"badge pull-right\" style=\"padding: 7px; background-color: white; color: #113e66;\">" . $events[$e_cnt] . "</span>";
                        $data .= "</p>\n";
                        $data .= "</div>\n";
                        $data .= "<div id=\"collapse" . $e_cnt . "\" class=\"panel-collapse collapse\">\n";
                        $data .= "<div class=\"panel-body\" style=\"padding: 3px 3px 3px 10px;\">\n";
                        $data .= "<div class=\"list-group\" style=\"margin: 4px 0px 4px;\">\n";
                        $e_cnt++;
                }

                if(strncmp(trim($line), "<div class=\"jcl_message\">", 25) == 0) {
                        $data .= "<p>There are no events on this day.</p>\n";
                        $data .= "</div>\n</div>\n</div>\n</div>\n";

                }
                if(strncmp(trim($line), "<a href=", 8) == 0) {
                        $tmp = get_string_between(trim($line), ">", "<");
                        $data .= "<div class=\"panel panel-default\" style=\"cursor: default;\">\n";
                        $data .= "<div class=\"panel-heading\" style=\"background-color: #889eb2;\">\n";
                        $data .= "<h3 class=\"panel-title\" style=\"font-size: 16px;\">" . $tmp . "</h3>\n";
                        $data .= "</div>\n";
                        $check++;
                }
                if(strncmp(trim($line), "<div class=\"jcl_event_description\">", 35) == 0) {
                        $rec = 0;
                        $new = trim($line);
                        $tmp = "";
                        for($i = 0; $i < strlen($new); $i++) {
                                if($new[$i] == ">") {
                                        $rec = 1;
                                        continue;
                                }
                                if($new[$i] == "<") {
                                        $rec = 0;
                                        continue;
                                }
                                if($rec == 1) {
                                        $tmp .= $new[$i];
                                        continue;
                                }
                        }
                        $data .= "<div class=\"panel-body\" style=\"font-size: 16px;\">\n";
                        $data .= "<p style=\"color: #113e66;\">\n" . $tmp . "</p>";
                        $data .= "</div>\n</div>\n";
                }
                if(strncmp(trim($line), "<h3>Categories</h3>", 19) == 0) {
                        $data .= "</div>\n";
                        break;
                }
        }
        $l_count++;
}

echo $data;
?>
