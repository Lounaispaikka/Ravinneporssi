<?php include_once("rp.start.php");

header("Content-Type: application/json");

if ($_GET["waypoints"]!="") {
	
	echo file_get_contents("http://maps.googleapis.com/maps/api/directions/json?origin=".urlencode(strip_tags($_GET["origin"]))."&destination=".urlencode(strip_tags($_GET["destination"]))."&waypoints=".urlencode(strip_tags($_GET["waypoints"]))."&sensor=".urlencode(strip_tags($_GET["sensor"]))."&mode=".urlencode(strip_tags($_GET["mode"])));
	
} else {

	echo file_get_contents("http://maps.googleapis.com/maps/api/directions/json?origin=".urlencode(strip_tags($_GET["origin"]))."&destination=".urlencode(strip_tags($_GET["destination"]))."&sensor=".urlencode(strip_tags($_GET["sensor"]))."&mode=".urlencode(strip_tags($_GET["mode"])));

}

include_once("rp.end.php"); ?>