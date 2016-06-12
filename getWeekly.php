<?php

require("config.php");
require("FitBot.php");
mysqli_report(MYSQLI_REPORT_ERROR);
error_reporting(E_ALL);
$mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

$bot = new FitBot($mysqli);
//$data = $bot->getDaily();
$data = $bot->getWeekly();

print json_encode($data, JSON_PRETTY_PRINT);

?>
