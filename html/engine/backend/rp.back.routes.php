<?php

function rpGetRoutes($html, $order="by added_datetime ASC", $nohtml="") {
	
	global $rpConnection;
	global $rpSettings;
	
	$routes_result = $rpConnection->query("SELECT id, distance, title, added_datetime, added_clientid FROM ".$rpSettings->getValue("routesTable")." WHERE added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."' ORDER ".rpSanitize($order));
	
	if (mysql_num_rows($routes_result) > 0) {

		for ($i = 0; $i < mysql_num_rows($routes_result); $i += 1) {
		
			$output = $html;
	
			$output = str_replace("[rp(id)]", mysql_result($routes_result, $i, "id"), $output);
			$output = str_replace("[rp(title)]", rpUTF8Encode(mysql_result($routes_result, $i, "title")), $output);
			$output = str_replace("[rp(distance)]", rpDistance(mysql_result($routes_result, $i, "distance")), $output);
			
			$output = str_replace("[rp(added_datetime)]", rpFullTime(mysql_result($routes_result, $i, "added_datetime")), $output);
	
			echo $output;
	
		}
		
	} else {echo $nohtml;}
	
}

?>