<?php

$_GET["size"] = intval($_GET["size"]);
$_GET["zoom"] = intval($_GET["zoom"]);

$expires = 60*60*24*365;
header("Pragma: public");
header("Cache-Control: max-age=".$expires);
header("Expires: ".gmdate("D, d M Y H:i:s", time()+$expires)." GMT");
header("Content-Type: image/png");

if ($_GET["source"]=="mml") {

	readfile("https://username:password@ws.nls.fi/rasteriaineistot/image?SERVICE=WMS&VERSION=1.1.1&REQUEST=GetMap&BGCOLOR=0xffffff&STYLES=normal&WIDTH=".$_GET["size"]."&HEIGHT=".$_GET["size"]."&SRS=EPSG%3A3067&TRANSPARENT=false&BBOX=".$_GET["y"].",".$_GET["x"].",".($_GET["y"]+$_GET["zoom"]).",".($_GET["x"]+$_GET["zoom"])."&LAYERS=".$_GET["layer"]."&FORMAT=image/jpeg");

} else if ($_GET["source"]=="plr") {
	
	readfile("http://85.17.207.20:8080/geoserver/peltolohkorekisteri/wms?service=WMS&version=1.1.0&request=GetMap&layers=".$_GET["layer"]."&WIDTH=".$_GET["size"]."&HEIGHT=".$_GET["size"]."&BBOX=".$_GET["y"].",".$_GET["x"].",".($_GET["y"]+$_GET["zoom"]).",".($_GET["x"]+$_GET["zoom"])."&SRS=EPSG%3A3067&TRANSPARENT=true&format=image/png");
	
}

?>