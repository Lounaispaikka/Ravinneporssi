<?php include_once("rp.start.php");

header("Content-Type: application/json");

echo file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode(strip_tags($_GET["address"]))."&sensor=".urlencode(strip_tags($_GET["sensor"])));

include_once("rp.end.php"); ?>