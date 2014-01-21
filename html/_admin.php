<?php include_once("engine/rp.start.php");

if (rpIsAdmin($_SESSION["clientID"])) {
	
	// process admin functions
	
	if ($_POST["rpAdminAction"]=="rpPublishNotice") {
		
		if (rpIsAlive($_POST["rpNotice"], "notices")) {
		
			if ($rpConnection->query("UPDATE ".$rpSettings->getValue("noticesTable")." SET published='1' WHERE id='".rpSanitize(intval($_POST["rpNotice"]))."' LIMIT 1")) {
				
				echo "SUCCESS";
				
			} else {echo "Virhe ilmoituksen muokkaamisessa.";}
		
		} else {echo "Ilmoitusta ei löytynyt.";}
		
	}
	
	if ($_POST["rpAdminAction"]=="rpUnpublishNotice") {
		
		if (rpIsAlive($_POST["rpNotice"], "notices")) {
		
			if ($rpConnection->query("UPDATE ".$rpSettings->getValue("noticesTable")." SET published='0' WHERE id='".rpSanitize(intval($_POST["rpNotice"]))."' LIMIT 1")) {
				
				echo "SUCCESS";
				
			} else {echo "Virhe ilmoituksen muokkaamisessa.";}
		
		} else {echo "Ilmoitusta ei löytynyt.";}
		
	}
	
	if ($_POST["rpAdminAction"]=="rpPublishRating") {
		
		if (rpIsAlive($_POST["rpRating"], "ratings")) {
		
			if ($rpConnection->query("UPDATE ".$rpSettings->getValue("ratingsTable")." SET published='1' WHERE id='".rpSanitize(intval($_POST["rpRating"]))."' LIMIT 1")) {
				
				echo "SUCCESS";
				
			} else {echo "Virhe arvostelun muokkaamisessa.";}
		
		} else {echo "Arvostelua ei löytynyt.";}
		
	}
	
	if ($_POST["rpAdminAction"]=="rpUnpublishRating") {
		
		if (rpIsAlive($_POST["rpRating"], "ratings")) {
		
			if ($rpConnection->query("UPDATE ".$rpSettings->getValue("ratingsTable")." SET published='0' WHERE id='".rpSanitize(intval($_POST["rpRating"]))."' LIMIT 1")) {
				
				echo "SUCCESS";
				
			} else {echo "Virhe arvostelun muokkaamisessa.";}
		
		} else {echo "Arvostelua ei löytynyt.";}
		
	}
	
	if ($_POST["rpAdminAction"]=="rpMakeClientAdmin") {
		
		if (rpIsAlive($_POST["rpClient"], "clients")) {
		
			if ($rpConnection->query("UPDATE ".$rpSettings->getValue("clientsTable")." SET admin='1' WHERE id='".rpSanitize(intval($_POST["rpClient"]))."' LIMIT 1")) {
				
				echo "SUCCESS";
				
			} else {echo "Virhe käyttäjäasetusten muokkaamisessa.";}
		
		} else {echo "Käyttäjää ei löytynyt.";}
		
	}
	
	if ($_POST["rpAdminAction"]=="rpUnmakeClientAdmin") {
		
		if (rpIsAlive($_POST["rpClient"], "clients")) {
		
			if ($rpConnection->query("UPDATE ".$rpSettings->getValue("clientsTable")." SET admin='0' WHERE id='".rpSanitize(intval($_POST["rpClient"]))."' LIMIT 1")) {
				
				echo "SUCCESS";
				
			} else {echo "Virhe käyttäjäasetusten muokkaamisessa.";}
		
		} else {echo "Käyttäjää ei löytynyt.";}
		
	}

	if ($_POST["rpAdminAction"]=="rpDeactivateClient") {
		
		if (rpIsAlive($_POST["rpClient"], "clients")) {
		
			if ($rpConnection->query("UPDATE ".$rpSettings->getValue("clientsTable")." SET published='0', deactivated='1' WHERE id='".rpSanitize(intval($_POST["rpClient"]))."' LIMIT 1")) {
				
				// fields
								
				$rpConnection->query("UPDATE ".$rpSettings->getValue("fieldsTable")." SET 
				published='0'
				 WHERE added_clientid='".rpSanitize(intval($_POST["rpClient"]))."'");
				
				// notices
						
				$rpConnection->query("UPDATE ".$rpSettings->getValue("noticesTable")." SET 
				published='0'
				 WHERE added_clientid='".rpSanitize(intval($_POST["rpClient"]))."'");
				
				echo "SUCCESS";
				
			} else {echo "Virhe käyttäjäasetusten muokkaamisessa.";}
		
		} else {echo "Käyttäjää ei löytynyt.";}
		
	}
	
	if ($_POST["rpAdminAction"]=="rpActivateClient") {
		
		if (rpIsAlive($_POST["rpClient"], "clients")) {
		
			if ($rpConnection->query("UPDATE ".$rpSettings->getValue("clientsTable")." SET published='1', deactivated='0' WHERE id='".rpSanitize(intval($_POST["rpClient"]))."' LIMIT 1")) {
				
				// fields
								
				$rpConnection->query("UPDATE ".$rpSettings->getValue("fieldsTable")." SET 
				published='1'
				 WHERE added_clientid='".rpSanitize(intval($_POST["rpClient"]))."'");
				
				// notices
						
				$rpConnection->query("UPDATE ".$rpSettings->getValue("noticesTable")." SET 
				published='1'
				 WHERE added_clientid='".rpSanitize(intval($_POST["rpClient"]))."'");
				
				echo "SUCCESS";
				
			} else {echo "Virhe käyttäjäasetusten muokkaamisessa.";}
		
		} else {echo "Käyttäjää ei löytynyt.";}
		
	}

	
	
	
	
	
} else {echo "Sinulla ei ole pääkäyttäjän oikeuksia.";}

include_once("engine/rp.end.php"); ?>