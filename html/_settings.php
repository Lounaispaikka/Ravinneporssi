<?php include_once("engine/rp.start.php");

if ($_POST["rpAction"] == "rpRemoveClientImage" && $_SESSION["clientID"] && $_POST["rpImageID"]!="") {
	
	$client_result = $rpConnection->query("SELECT images FROM ".$rpSettings->getValue("clientsTable")." WHERE id='".rpSanitize(intval($_SESSION["clientID"]))."' LIMIT 1");
	
	if (mysql_num_rows($client_result)>0) {
	
		$images_array = explode("|end|", rpUTF8Encode(mysql_result($client_result, 0, "images")));
	
		$to_remove_string = "";
		$to_remove_file = "";
		
		foreach ($images_array as $file) {
		
			if ($_POST["rpImageID"] == md5($file)) {
				
				$file_array = explode("|=|", $file);
				
				$to_remove_string = $file."|end|";
				$to_remove_file = $file_array[0];
				
			}
		
		}
		
		if ($to_remove_string != "" && $to_remove_file != "") {
			
			if ($rpConnection->query("UPDATE ".$rpSettings->getValue("clientsTable")." SET 
				images=replace(images, '".rpSanitize($to_remove_string)."', ''),
				modified_datetime='".date("Y-m-d H:i:s")."',
				modified_ip='".rpSanitize(rpGetIP())."',
				modified_userid='0'
				 WHERE id='".rpSanitize($_SESSION["clientID"])."' LIMIT 1")) {
				 				 
				 unlink("files/images/thumbnail/".$to_remove_file);
				 unlink("files/images/fullsize/".$to_remove_file);
				 
				 echo "SUCCESS";	
				 	
			} else {echo "Ongelma kuvan poistamisessa.";}
						
		} else {echo "Kuvaa ei löytynyt.";}
				
	} else {echo "Käyttäjää ei löytynyt.";}
	
}

include_once("engine/rp.end.php"); ?>