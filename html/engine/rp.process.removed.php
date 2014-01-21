<?php include_once("rp.start.php");

if ($_GET["rpCheck"]=="processremoved") {

	$clients_result = $rpConnection->query("SELECT id, email, images, logged_datetime FROM ".$rpSettings->getValue("clientsTable")." WHERE published='0'");

	$num_of_removed = 0;
	$num_of_warnings = 0;

	for ($i = 0; $i < mysql_num_rows($clients_result); $i += 1) {
		
		$client_id = mysql_result($clients_result, $i, "id");
		$client_email = mysql_result($clients_result, $i, "email");
		$images_array = explode("|end|", rpUTF8Encode(mysql_result($clients_result, $i, "images")));
		
		if (strtotime(date("Y-m-d H:i:s"))>strtotime(date("Y-m-d H:i:s", strtotime(mysql_result($clients_result, $i, "logged_datetime"))+(24*3600*7)))) {
			
			// feedback
			
			$rpConnection->query("UPDATE ".$rpSettings->getValue("feedbackTable")." SET 
			added_clientid='0'
			 WHERE added_clientid='".rpSanitize(intval($client_id))."'");
			
			// fields
			
			$rpConnection->query("DELETE FROM ".$rpSettings->getValue("fieldsTable")." WHERE added_clientid='".rpSanitize(intval($client_id))."'");
			
			// messages
			
			$rpConnection->query("DELETE FROM ".$rpSettings->getValue("messagesTable")." WHERE added_clientid='".rpSanitize(intval($client_id))."'");
			
			$rpConnection->query("UPDATE ".$rpSettings->getValue("messagesTable")." SET 
			to_clientid = replace(to_clientid, '[".rpSanitize(intval($client_id))."]', '')
			 WHERE to_clientid LIKE '%[".rpSanitize(intval($client_id))."]%'");
			
			// contracts
			
			$rpConnection->query("UPDATE ".$rpSettings->getValue("contractsTable")." SET 
			to_clientid = replace(to_clientid, '[".rpSanitize(intval($client_id))."]', '')
			 WHERE to_clientid LIKE '%[".rpSanitize(intval($client_id))."]%'");
			
			$rpConnection->query("UPDATE ".$rpSettings->getValue("contractsTable")." SET 
			added_clientid='0'
			 WHERE added_clientid='".rpSanitize(intval($client_id))."'");
			
			// notices
			
			$rpConnection->query("DELETE FROM ".$rpSettings->getValue("noticesTable")." WHERE added_clientid='".rpSanitize(intval($client_id))."'");
			
			// ratings
			
			$rpConnection->query("DELETE FROM ".$rpSettings->getValue("ratingsTable")." WHERE added_clientid='".rpSanitize(intval($client_id))."' OR to_clientid='".rpSanitize(intval($client_id))."'");
			
			// routes
			
			$rpConnection->query("DELETE FROM ".$rpSettings->getValue("routesTable")." WHERE added_clientid='".rpSanitize(intval($client_id))."'");
			
			// images
			
			foreach ($images_array as $value) {
				
				if ($value != "") {
					
					$image_array = explode("|", $value);
					
					unlink("../files/images/thumbnail/".$image_array[0]);
					unlink("../files/images/fullsize/".$image_array[0]);
			
				}
				
			}
			
			// client
		
			if ($rpConnection->query("DELETE FROM ".$rpSettings->getValue("clientsTable")." WHERE id='".rpSanitize(intval($client_id))."' LIMIT 1")) {
			
				rpSendMail($client_email, "Profiilinne on poistettu pysyvästi Ravinnepörssi.fi-palvelusta", "<a href=\"http://".$rpSettings->getValue("domain")."/\">http://".$rpSettings->getValue("domain")."/</a>");	
				
				$num_of_removed += 1;
				
			}			
			
		} else if (strtotime(date("Y-m-d H:i:s"))>strtotime(date("Y-m-d H:i:s", strtotime(mysql_result($clients_result, $i, "logged_datetime"))+(24*3600)))) {
		
			rpSendMail($client_email, "Profiilinne poistetaan Ravinnepörssi.fi-palvelusta 24 tunnin kuluessa", "Peruaksesi profiilin poistamisen, kirjaudu takaisin palveluun.<br /><a href=\"http://".$rpSettings->getValue("domain")."/\">http://".$rpSettings->getValue("domain")."/</a>");	
			
			$num_of_warnings += 1;
			
		}
		
	}

	echo "Removed: ".$num_of_removed."<br />Warnings: ".$num_of_warnings;

}

include_once("rp.end.php"); ?>