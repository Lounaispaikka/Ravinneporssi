<?php

// handle cookies

if (!$_SESSION["clientID"] && $_COOKIE["clientID"] && $_COOKIE["clientAuthentication"] && !rpIsAdmin($_COOKIE["clientID"])) {
	
	$user_result = $rpConnection->query("SELECT salt, confirmed, published FROM ".$rpSettings->getValue("clientsTable")." WHERE id='".rpSanitize(intval($_COOKIE["clientID"]))."' LIMIT 1");
	
	if (mysql_result($user_result, 0, "confirmed")==1 && mysql_result($user_result, 0, "published")==1 && $_COOKIE["clientAuthentication"] == md5(mysql_result($user_result, 0, "salt").$_COOKIE["clientID"].$rpSettings->getValue("domain").$rpSettings->getValue("secret").rpGetIP())) {
	
		$_SESSION["clientID"] = $_COOKIE["clientID"];
		$_SESSION["clientSessionTime"] = time();
		$_SESSION["clientAuthentication"] = $_COOKIE["clientAuthentication"];
	
	}
	
}

// handle authentication & timeout

if ($_SESSION["clientID"]) {
	
	$user_result = $rpConnection->query("SELECT salt, confirmed, published FROM ".$rpSettings->getValue("clientsTable")." WHERE id='".rpSanitize(intval($_SESSION["clientID"]))."' LIMIT 1");
	
	if ($_SESSION["clientAuthentication"] != md5(mysql_result($user_result, 0, "salt").$_SESSION["clientID"].$rpSettings->getValue("domain").$rpSettings->getValue("secret").rpGetIP())) {
	
		$_GET["logout"] = 1;	
		
	}
	
	if (mysql_result($user_result, 0, "confirmed")==0 || mysql_result($user_result, 0, "published")==0) {
		
		$_GET["logout"] = 1;
		
	}
	
	if (time()-$_SESSION["clientSessionTime"]>$rpSettings->getValue("sessionTimeOut") && !$_COOKIE["clientID"]) {
	
		$_GET["logout"] = 1;
	
	} else {$_SESSION["clientSessionTime"] = time();}
	
}

// handle logout

if ($_GET["logout"]==1 && $_SESSION["clientID"]) {
			
	unset($_SESSION["clientID"]);
	unset($_SESSION["clientSessionTime"]);
	unset($_SESSION["clientAuthentication"]);
	
	setcookie("clientID", "", time()-3600);
	setcookie("clientAuthentication", "", time()-3600);
	
	$rpNotify = "Olet kirjautunut ulos.";
	
}

if ($_GET["remove"]==1 && $_SESSION["clientID"]) {
			
	unset($_SESSION["clientID"]);
	unset($_SESSION["clientSessionTime"]);
	unset($_SESSION["clientAuthentication"]);
	
	setcookie("clientID", "", time()-3600);
	setcookie("clientAuthentication", "", time()-3600);
	
	$rpNotify = "Profiili on poistettu.";
	
}

?>