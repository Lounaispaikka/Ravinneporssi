<?php include_once("engine/rp.start.php");

if ($_POST["rpNotice"]>0 && $_POST["rpAction"]=="rpRemoveNoticeFile" && $_POST["rpFileID"]!="" && $_SESSION["clientID"]) {

	$notice_result = $rpConnection->query("SELECT files FROM ".$rpSettings->getValue("noticesTable")." WHERE published='1' && added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."' && id='".rpSanitize(intval($_POST["rpNotice"]))."' LIMIT 1");
	
	if (mysql_num_rows($notice_result)>0) {
		
		$files_array = explode("|end|", rpUTF8Encode(mysql_result($notice_result, 0, "files")));
					
		$to_remove_string = "";
		$to_remove_file = "";
		
		foreach ($files_array as $file) {
		
			if ($_POST["rpFileID"] == md5($file)) {
				
				$file_array = explode("|=|", $file);
				
				$to_remove_string = $file."|end|";
				$to_remove_file = $file_array[0];
				
			}
		
		}
		
		if ($to_remove_string != "" && $to_remove_file != "") {
			
			if ($rpConnection->query("UPDATE ".$rpSettings->getValue("noticesTable")." SET 
				files=replace(files, '".rpSanitize($to_remove_string)."', ''),
				modified_datetime='".date("Y-m-d H:i:s")."',
				modified_ip='".rpSanitize(rpGetIP())."',
				modified_userid='0'
				 WHERE id='".rpSanitize(intval($_POST["rpNotice"]))."' && added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."' LIMIT 1")) {
				 				 
				 unlink("files/uploads/".$to_remove_file);
				 
				 echo "SUCCESS";	
				 	
			} else {echo "Ongelma tiedoston poistamisessa.";}
						
		} else {echo "Tiedostoa ei löytynyt.";}
		
	} else {echo "Ilmoitusta ei löytynyt.";}
	
}

if ($_POST["rpNotice"]>0 && $_POST["rpAction"]=="rpUpdateNoticePosition" && $_SESSION["clientID"]) {

	$notice_result = $rpConnection->query("SELECT id FROM ".$rpSettings->getValue("noticesTable")." WHERE id='".rpSanitize(intval($_POST["rpNotice"]))."' && added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."' LIMIT 1");
	
	if ($_POST["rpNoticeLatitude"]>0 && $_POST["rpNoticeLongitude"]>0 && $_POST["rpNoticePosX"]>0 && $_POST["rpNoticePosY"]) {

		if (mysql_num_rows($notice_result)>0) {
			
			if ($rpConnection->query("UPDATE ".$rpSettings->getValue("noticesTable")." SET 
				latitude='".rpSanitize(floatval($_POST["rpNoticeLatitude"]))."',
				longitude='".rpSanitize(floatval($_POST["rpNoticeLongitude"]))."',
				pos_x='".rpSanitize(floatval($_POST["rpNoticePosX"]))."',
				pos_y='".rpSanitize(floatval($_POST["rpNoticePosY"]))."',
				modified_datetime='".date("Y-m-d H:i:s")."',
				modified_ip='".rpSanitize(rpGetIP())."',
				modified_userid='0'
				 WHERE id='".rpSanitize(intval($_POST["rpNotice"]))."' LIMIT 1")) {
				
				echo "SUCCESS";
				
			} else {echo "Ongelma ilmoituksen tallentamisessa.";}
		
		} else {echo "Ongelma ilmoituksen tallentamisessa.";}
		
	} else {echo "Ilmoitusta ei löytynyt.";}

}

if ($_POST["rpWithWhat"]>0 && $_POST["rpWhatWith"]>0 && $_POST["rpAction"]=="rpMoveFavourite" && $_SESSION["clientID"]) {

	$rpConnection->query("UPDATE ".$rpSettings->getValue("clientsTable")." SET favourites = REPLACE(favourites, '(".rpSanitize(intval($_POST["rpWithWhat"])).")', '(x)') WHERE id='".rpSanitize(intval($_SESSION["clientID"]))."' LIMIT 1");
	
	$rpConnection->query("UPDATE ".$rpSettings->getValue("clientsTable")." SET favourites = REPLACE(favourites, '(".rpSanitize(intval($_POST["rpWhatWith"])).")', '(".rpSanitize(intval($_POST["rpWithWhat"])).")') WHERE id='".rpSanitize(intval($_SESSION["clientID"]))."' LIMIT 1");
	
	$rpConnection->query("UPDATE ".$rpSettings->getValue("clientsTable")." SET favourites = REPLACE(favourites, '(x)', '(".rpSanitize(intval($_POST["rpWhatWith"])).")') WHERE id='".rpSanitize(intval($_SESSION["clientID"]))."' LIMIT 1");

	if ($rpConnection->query("UPDATE ".$rpSettings->getValue("clientsTable")." SET 
		modified_datetime='".date("Y-m-d H:i:s")."',
		modified_ip='".rpSanitize(rpGetIP())."',
		modified_userid='0'
		 WHERE id='".rpSanitize(intval($_SESSION["clientID"]))."' LIMIT 1")) {
		
		echo "SUCCESS";
		
	} else {echo "Ongelma suosikin siirtämisessä.";}

}

if ($_POST["rpNotice"]>0 && $_POST["rpAction"]=="rpRemoveFavourite" && $_SESSION["clientID"]) {

	if ($rpConnection->query("UPDATE ".$rpSettings->getValue("clientsTable")." SET 
		favourites = REPLACE(favourites, '(".rpSanitize(intval($_POST["rpNotice"])).")', ''),
		modified_datetime='".date("Y-m-d H:i:s")."',
		modified_ip='".rpSanitize(rpGetIP())."',
		modified_userid='0'
		 WHERE id='".rpSanitize(intval($_SESSION["clientID"]))."' LIMIT 1")) {
		
		echo "SUCCESS";
		
	} else {echo "Ongelma suosikin poistamisessa.";}

}

if ($_POST["rpNotice"]>0 && $_POST["rpAction"]=="rpAddToFavourites" && $_SESSION["clientID"]) {
	
	// save to favourites
	
	$notice_result = $rpConnection->query("SELECT id FROM ".$rpSettings->getValue("noticesTable")." WHERE id='".rpSanitize(intval($_POST["rpNotice"]))."' &&  (added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."' OR visibility='all' OR (".rpSanitize(intval($_SESSION["clientID"])).">0 && visibility='registered')) LIMIT 1");
	
	if (mysql_num_rows($notice_result)>0) {
		
		if (!strstr(rpGetClient("favourites"), "(".intval($_POST["rpNotice"]).")")) {
		
			if ($rpConnection->query("UPDATE ".$rpSettings->getValue("clientsTable")." SET 
				favourites=concat('(".rpSanitize(intval($_POST["rpNotice"])).")',favourites),
				modified_datetime='".date("Y-m-d H:i:s")."',
				modified_ip='".rpSanitize(rpGetIP())."',
				modified_userid='0'
				 WHERE id='".rpSanitize(intval($_SESSION["clientID"]))."' LIMIT 1")) {
				
				echo "SUCCESS";
				
			} else {echo "Ongelma suosikin lisäämisessä.";}
		
		} else {echo "Ilmoitus on jo lisätty suosikkeihin.";}
		
	} else {echo "Ilmoitusta ei löytynyt.";}
	
}

if ($_POST["rpNotice"]>0 && $_POST["rpAction"]=="rpGetNoticePosition") {

	// get notice position

	$notice_result = $rpConnection->query("SELECT latitude, longitude FROM ".$rpSettings->getValue("noticesTable")." WHERE id='".rpSanitize(intval($_POST["rpNotice"]))."' &&  (added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."' OR visibility='all' OR (".rpSanitize(intval($_SESSION["clientID"])).">0 && visibility='registered')) LIMIT 1");

	if (mysql_num_rows($notice_result)>0) {
		
		echo mysql_result($notice_result, 0, "latitude")."|break|".mysql_result($notice_result, 0, "longitude")."|break|";
		
	}

}

if ($_POST["rpNotice"]>0 && $_POST["rpAction"]=="rpRemoveNotice" && $_SESSION["clientID"]) {

	// remove notice

	$notice_result = $rpConnection->query("SELECT files FROM ".$rpSettings->getValue("noticesTable")." WHERE id='".rpSanitize(intval($_POST["rpNotice"]))."' && added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."' LIMIT 1");

	if (mysql_num_rows($notice_result)>0) {
		
		$files_array = explode("|end|", rpUTF8Encode(mysql_result($notice_result, 0, "files")));
		
		foreach ($files_array as $file) {
		
			if ($file!="") {
		
				$file_array = explode("|=|", $file);
			
				unlink("files/uploads/".$file_array[0]);
		
			}
		
		}
		
		if ($rpConnection->query("DELETE FROM ".$rpSettings->getValue("noticesTable")." WHERE id='".rpSanitize(intval($_POST["rpNotice"]))."'")) {
			
			echo "SUCCESS";
			
		} else {echo "Ongelma ilmoituksen poistamisessa.";}
		
	} else {echo "Ilmoitusta ei löytynyt.";}

}

include_once("engine/rp.end.php"); ?>