<?php include_once("engine/rp.start.php");


if ($_POST["rpRoute"]>0 && $_POST["rpAction"]=="rpRemoveRoute" && $_SESSION["clientID"]) {

	// remove route

	$route_result = $rpConnection->query("SELECT id FROM ".$rpSettings->getValue("routesTable")." WHERE id='".rpSanitize(intval($_POST["rpRoute"]))."' &&  added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."' LIMIT 1");

	if (mysql_num_rows($route_result)>0) {
		
		if ($rpConnection->query("DELETE FROM ".$rpSettings->getValue("routesTable")." WHERE id='".rpSanitize(intval($_POST["rpRoute"]))."'")) {
			
			echo "SUCCESS";
			
		} else {echo "Ongelma reitin poistamisessa.";}
		
	} else {echo "Reittiä ei löytynyt.";}

}

if ($_POST["rpRoute"]>0 && $_POST["rpAction"]=="rpGetRoute" && $_SESSION["clientID"]) {

	// get route

	$route_result = $rpConnection->query("SELECT title, distance, route FROM ".$rpSettings->getValue("routesTable")." WHERE id='".rpSanitize(intval($_POST["rpRoute"]))."' &&  added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."' LIMIT 1");

	if (mysql_num_rows($route_result)>0) {
		
		echo rpUTF8Encode(mysql_result($route_result, 0, "title"))."|break|".mysql_result($route_result, 0, "distance")."|break|".mysql_result($route_result, 0, "route");
		
	}

}

if ($_POST["rpRouteTitle"]!="" && $_POST["rpRoute"]!="" && $_POST["rpRouteDistance"]>0 && $_POST["rpAction"]=="rpSaveRoute") {

	if ($_SESSION["clientID"]) {

		// save route
	
		$_POST["rpRouteTitle"] = substr($_POST["rpRouteTitle"],0,100);
	
		$route_result = $rpConnection->query("SELECT id FROM ".$rpSettings->getValue("routesTable")." WHERE route='".rpSanitize($_POST["rpRoute"])."' &&  added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."' LIMIT 1");
	
		if (mysql_num_rows($route_result)<1) {
	
			$nextID = rpGetNextID("routes");
		
			if ($rpConnection->query("INSERT INTO ".$rpSettings->getValue("routesTable")." (".$rpSettings->getValue("routesTableStructure").") VALUES (
				'".rpSanitize($nextID)."',
				'0',
				'',
				'".rpSanitize($_POST["rpRouteTitle"])."',
				'".rpSanitize($_POST["rpRoute"])."',
				'".rpSanitize($_POST["rpRouteDistance"])."',
				'".date("Y-m-d H:i:s")."',
				'".rpSanitize(rpGetIP())."',
				'".rpSanitize($_SESSION["clientID"])."',
				'',
				'',
				'0',
				'".rpSanitize($nextID)."')")) {
				
				echo "SUCCESS";
				
			} else {echo "Ongelma reitin tallentamisessa.";}
	
		} else {echo "Reitti on jo tallennettu.";}

	} else {echo "Sinun tulee rekisteröityä tallentaaksesi reittejä.";}

}

include_once("engine/rp.end.php"); ?>