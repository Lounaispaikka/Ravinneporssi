<?php

// handle login

if ($_POST["rpAction"]=="rpLoginClient") {

	if ($_POST["rpLoginEmail"] != "" && $_POST["rpLoginPassword"] != "") {
		
		$_POST["rpLoginEmail"] = substr($_POST["rpLoginEmail"],0,100);
		$_POST["rpLoginPassword"] = substr($_POST["rpLoginPassword"],0,100);
		
		// check if client exists
						
		$user_result = $rpConnection->query("SELECT id, password, salt, attempt_datetime, confirmed, published, deactivated FROM ".$rpSettings->getValue("clientsTable")." WHERE email='".rpSanitize($_POST["rpLoginEmail"])."' LIMIT 1");
		
		if (mysql_num_rows($user_result)>0) {
		
			if (mysql_result($user_result, 0, "confirmed")==1) {
				
				if (mysql_result($user_result, 0, "deactivated")==0) {
		
					$time_difference = strtotime(date("Y-m-d H:i:s"))-strtotime(mysql_result($user_result, 0, "attempt_datetime"));
				
					if ($time_difference>$rpSettings->getValue("loginInterval")) {
				
						if (mysql_result($user_result, 0, "password") == crypt($rpSettings->getValue("secret").rpSanitize($_POST["rpLoginPassword"]), mysql_result($user_result, 0, "salt"))) {
						
							$rpLoginFailure = 0;
							
							// log in client
							
							$rpConnection->query("UPDATE ".$rpSettings->getValue("clientsTable")." SET logged_datetime='".date("Y-m-d H:i:s")."', logged_ip='".rpSanitize(rpGetIP())."' WHERE id='".mysql_result($user_result, 0, "id")."' LIMIT 1");
						
							$_SESSION["clientID"] = mysql_result($user_result, 0, "id");
							$_SESSION["clientSessionTime"] = time();
							$_SESSION["clientAuthentication"] = md5(mysql_result($user_result, 0, "salt").mysql_result($user_result, 0, "id").$rpSettings->getValue("domain").$rpSettings->getValue("secret").rpGetIP());
							
							if ($_POST["rpLoginRemember"]=="on") {
								
								setcookie("clientID", mysql_result($user_result, 0, "id"), (time()+60*60*24*30));
								setcookie("clientAuthentication", md5(mysql_result($user_result, 0, "salt").mysql_result($user_result, 0, "id").$rpSettings->getValue("domain").$rpSettings->getValue("secret").rpGetIP()), (time()+60*60*24*30));
															
							}
						
							if (mysql_result($user_result, 0, "published")==0) {
								
								// restore profile
								
								// fields
								
								$rpConnection->query("UPDATE ".$rpSettings->getValue("fieldsTable")." SET 
								published='1'
								 WHERE added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."'");
								
								// notices
										
								$rpConnection->query("UPDATE ".$rpSettings->getValue("noticesTable")." SET 
								published='1'
								 WHERE added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."'");	
										
								// client
								
								$rpConnection->query("UPDATE ".$rpSettings->getValue("clientsTable")." SET 
								published='1'
								 WHERE id='".rpSanitize(intval($_SESSION["clientID"]))."' LIMIT 1");
								
								$rpNotify = "Profiilisi on palautettu.";
								
							}
						
						} else {
							
							$rpConnection->query("UPDATE ".$rpSettings->getValue("clientsTable")." SET attempt_datetime='".date("Y-m-d H:i:s")."', attempt_ip='".rpSanitize(rpGetIP())."' WHERE id='".mysql_result($user_result, 0, "id")."' LIMIT 1");
							
							$rpLoginFailure = 3;
							
						}
		
					} else {$rpLoginFailure = 3;}
		
				} else {$rpLoginFailure = 5;}
	
			} else {$rpLoginFailure = 4;}
	
		} else {$rpLoginFailure = 2;}
	
	} else {$rpLoginFailure = 1;}

}

// handle errors

if ($rpLoginFailure>0) {header("Location: http://".$rpSettings->getValue("domain")."/?rpLoginFailure=".$rpLoginFailure);}

?>