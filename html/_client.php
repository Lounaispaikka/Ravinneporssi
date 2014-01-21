<?php include_once("engine/rp.start.php");

if ($_GET["rpAction"] == "rpGetClient" && $_SESSION["clientID"]) {
	
	if ($_GET["type"]=="name") {echo rpGetOtherClient(intval($_GET["id"]), "name");}
	else if ($_GET["type"]=="address_1") {echo rpGetOtherClient(intval($_GET["id"]), "address_1");}
	else if ($_GET["type"]=="phonenumber") {echo rpGetOtherClient(intval($_GET["id"]), "phonenumber");}
	else if ($_GET["type"]=="email") {echo rpGetOtherClient(intval($_GET["id"]), "email");}
	else if ($_GET["type"]=="bic") {echo rpGetOtherClient(intval($_GET["id"]), "bic");}
	
}

if ($_POST["rpAction"] == "rpRateClient" && $_SESSION["clientID"]) {
	
	if ($_POST["rpCheck"] == md5("RATE".$_POST["rpClient"].$rpSettings->getValue("secret").$_SESSION["clientID"])) {
		
		if ($_POST["rpClient"]!=$_SESSION["clientID"]) {
			
			if (rpGetOtherClient($_POST["rpClient"], "published")==1) {
				
				$_POST["rpRatingTitle"] = substr($_POST["rpRatingTitle"],0,500);
				$_POST["rpRatingMessage"] = substr($_POST["rpRatingMessage"],0,10000);
				
				$nextID = rpGetNextID("ratings");
				
				if ($rpConnection->query("INSERT INTO ".$rpSettings->getValue("ratingsTable")." (".$rpSettings->getValue("ratingsTableStructure").") VALUES (
					'".rpSanitize($nextID)."',
					'0',
					'".rpSanitize(intval($_POST["rpRatingRating"]))."',
					'".rpSanitize($_POST["rpRatingTitle"])."',
					'".rpSanitize(nl2br($_POST["rpRatingMessage"]))."',
					'".date("Y-m-d H:i:s")."',
					'".rpSanitize(rpGetIP())."',
					'".rpSanitize($_SESSION["clientID"])."',
					'".rpSanitize(intval($_POST["rpClient"]))."',
					'',
					'',
					'',
					'".rpSanitize($nextID)."',
					'1')")) {
				
					echo "SUCCESS";
				
				} else {echo "Virhe arvostelun tallentamisessa.";}
				
			} else {echo "Käyttäjää ei löytynyt.";}
			
		} else {echo "Et voi arvostella itseäsi.";}
		
	}	
	
}

if ($_POST["rpAction"] == "rpRemoveProfile" && $_POST["rpRemoveProfilePassword"] != "" && $_SESSION["clientID"]) {
	
	$user_result = $rpConnection->query("SELECT id, password, salt FROM ".$rpSettings->getValue("clientsTable")." WHERE id='".rpSanitize(intval($_SESSION["clientID"]))."' LIMIT 1");
	
	if (mysql_result($user_result, 0, "password") == crypt($rpSettings->getValue("secret").rpSanitize($_POST["rpRemoveProfilePassword"]), mysql_result($user_result, 0, "salt"))) {
		
		// fields
		
		$rpConnection->query("UPDATE ".$rpSettings->getValue("fieldsTable")." SET 
		published='0'
		 WHERE added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."'");
		
		// notices
				
		$rpConnection->query("UPDATE ".$rpSettings->getValue("noticesTable")." SET 
		published='0'
		 WHERE added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."'");
		 
		 // ratings
				
		$rpConnection->query("UPDATE ".$rpSettings->getValue("ratingsTable")." SET 
		published='0'
		 WHERE added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."' OR to_clientid='".rpSanitize(intval($_SESSION["clientID"]))."'");	
				
		// client
		
		if ($rpConnection->query("UPDATE ".$rpSettings->getValue("clientsTable")." SET 
		published='0'
		 WHERE id='".rpSanitize(intval($_SESSION["clientID"]))."' LIMIT 1")) {
		
			rpSendMail(rpGetClient("email"), "Profiili poistettu Ravinnepörssi.fi-palvelusta", "Palauttaaksesi profiilin kirjaudu takaisin palveluun ennen ".rpDate(date("Y-m-d H:i:s", strtotime(rpGetClient("logged_datetime")) + (24*3600*7))).".<br /><a href=\"http://".$rpSettings->getValue("domain")."/\">http://".$rpSettings->getValue("domain")."/</a>");
		
			echo "SUCCESS";
		
		} else {echo "Virhe profiilin poistamisessa.";}
		
	} else {echo "Virheellinen salasana.";}
	
}

if ($_POST["rpAction"] == "rpUpdateClientPosition" && $_POST["currentLatitude"]>0 && $_POST["currentLongitude"]>0 && $_POST["currentZoom"]>0 && $_SESSION["clientID"]) {
			
	if ($rpConnection->query("UPDATE ".$rpSettings->getValue("clientsTable")." SET 
	current_latitude='".rpSanitize(floatval($_POST["currentLatitude"]))."',
	current_longitude='".rpSanitize(floatval($_POST["currentLongitude"]))."',
	current_zoom='".rpSanitize(intval($_POST["currentZoom"]))."',
	current_layers='".rpSanitize($_POST["currentLayers"])."',
	current_annotations='".rpSanitize($_POST["currentAnnotations"])."'
	 WHERE id='".rpSanitize(intval($_SESSION["clientID"]))."' LIMIT 1")) {
	
		echo "SUCCESS";
		
	 }
	
}

if ($_POST["rpAction"] == "rpUpdateClientHomePosition" && $_POST["baseLatitude"]>0 && $_POST["baseLongitude"]>0 && $_SESSION["clientID"]) {
			
	if ($rpConnection->query("UPDATE ".$rpSettings->getValue("clientsTable")." SET 
	base_latitude='".rpSanitize(floatval($_POST["baseLatitude"]))."',
	base_longitude='".rpSanitize(floatval($_POST["baseLongitude"]))."'
	 WHERE id='".rpSanitize(intval($_SESSION["clientID"]))."' LIMIT 1")) {
	
		echo "SUCCESS";
		
	 }
	
}

if ($_GET["rpAction"] == "rpGetClientArsenal" && $_GET["type"]!="" && $_SESSION["clientID"]) {
	
	$client_result = $rpConnection->query("SELECT arsenal FROM ".$rpSettings->getValue("clientsTable")." WHERE id='".rpSanitize(intval($_SESSION["clientID"]))."' LIMIT 1");
	
	$arsenal_title = array();
	$arsenal_description = array();
	
	if (mysql_num_rows($client_result)>0) {
				
		$arsenals_array = explode("|end|", rpUTF8Encode(mysql_result($client_result, 0, "arsenal")));
		
		foreach ($arsenals_array as $value) {
			
			if ($value != "") {
				
				$arsenal_array = explode("|", $value);
				
				if ($arsenal_array[0]=="rpClientArsenal") {
				
					array_push($arsenal_title, $arsenal_array[1]);
					array_push($arsenal_description, $arsenal_array[2]);
				
				}
				
			}
			
		}
		
	}
	
	if ($arsenal_title[intval($_GET["id"])-1]) {
		
		if ($_GET["type"]=="title") {
		
			echo $arsenal_title[intval($_GET["id"])-1];
			
		} else {
			
			echo str_replace("<br />","",$arsenal_description[intval($_GET["id"])-1]);
			
		}
		
	}
	
}

include_once("engine/rp.end.php"); ?>