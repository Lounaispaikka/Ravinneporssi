<?php include_once("engine/rp.start.php");

if ($_POST["rpAction"]=="rpUpdatePassword") {
	
	if (rpIsAlive($_SESSION["resetClientID"], "clients")) {
	
		$user_result = $rpConnection->query("SELECT salt FROM ".$rpSettings->getValue("clientsTable")." WHERE id='".rpSanitize(intval($_SESSION["resetClientID"]))."' && confirmed='1' && published='1' LIMIT 1");

		if ($_SESSION["resetCheck"]==md5("RESET".$rpSettings->getValue("secret").$_SESSION["resetClientID"].mysql_result($user_result, 0, "salt"))) {
			
			if ($_POST["rpNewPassword_1"]!="" && $_POST["rpNewPassword_2"]!="" && $_POST["rpNewPassword_1"]==$_POST["rpNewPassword_2"]) {
			
				$salt = rpGenerateSalt(20);
				$password = crypt($rpSettings->getValue("secret").rpSanitize($_POST["rpNewPassword_1"]), $salt);
		 		
		 		if ($rpConnection->query("UPDATE ".$rpSettings->getValue("clientsTable")." SET 
				salt='".rpSanitize($salt)."',
				password='".rpSanitize($password)."'
				 WHERE id='".rpSanitize(intval($_SESSION["resetClientID"]))."' LIMIT 1")) {
			
					$_SESSION["resetCheck"]="";
					$_SESSION["resetClientID"]="";
				
					echo "SUCCESS";
			
				} else {echo "Ongelma salasanan vaihtamisessa.";}
			
			} else {echo "Salasanat eivät täsmää.";}
			
		} else {echo "Varmennusvirhe.";}
			
	} else {echo "Käyttäjää ei löytynyt.";}
	
}

if ($_POST["rpAction"]=="rpResetPassword") {
	
	if (strstr($_POST["rpResetEmail"],"@")) {
		
		$user_result = $rpConnection->query("SELECT id, email, salt FROM ".$rpSettings->getValue("clientsTable")." WHERE email!='' && email='".rpSanitize($_POST["rpResetEmail"])."' && confirmed='1' && published='1' LIMIT 1");
		
		if (mysql_num_rows($user_result)>0) {
			
			if (rpSendMail(mysql_result($user_result, 0, "email"), "Salasanan vaihtoviesti Ravinnepörssi.fi", "Vaihda salasana klikkaamalla oheista linkkiä:<br /><a href=\"http://".$rpSettings->getValue("domain")."/?rpAction=reset&clientID=".mysql_result($user_result, 0, "id")."&rpCheck=".md5("RESET".$rpSettings->getValue("secret").mysql_result($user_result, 0, "id").mysql_result($user_result, 0, "salt"))."\">http://".$rpSettings->getValue("domain")."/?rpAction=reset&clientID=".mysql_result($user_result, 0, "id")."&rpCheck=".md5("RESET".$rpSettings->getValue("secret").mysql_result($user_result, 0, "id").mysql_result($user_result, 0, "salt"))."</a>")) {
			
				echo "SUCCESS";
			
			} else {echo "Ongelma sähköpostin lähettämisessä.";}
			
		} else {echo "Käyttäjää ei löytynyt.";}
		
	} else {echo "Puuttuvia kohtia lomakkeessa.";}
		
}

include_once("engine/rp.end.php"); ?>