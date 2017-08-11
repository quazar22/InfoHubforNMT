<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");

$begin = "<div class='row'><ul class='list-group'>";
$mid1 = "<a href='#' onclick=\"window.open('http://infohost.nmt.edu/~osl/index.php', '_system');\"><li class='list-group-item'>Link to OSL Website</li></a>";
$end =  "</ul></div>";
$data = $begin + $mid1 + $end;
echo $data;

?>
