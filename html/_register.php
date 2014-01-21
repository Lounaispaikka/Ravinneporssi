<?php include_once("engine/rp.start.php");

if ($_POST["rpAction"]=="rpRegisterClient") {

	if (strstr($_POST["rpRegisterEmail"],"@") && $_POST["rpRegisterName"]!="" && $_POST["rpRegisterPassword_1"]!="" && $_POST["rpRegisterPassword_2"]!="" && $_POST["rpRegisterPassword_1"]==$_POST["rpRegisterPassword_2"]) {
	
		if ($_POST["rpAcceptTOU"]=="on") {
	
			$_POST["rpRegisterEmail"] = substr($_POST["rpRegisterEmail"],0,100);
			$_POST["rpRegisterName"] = substr($_POST["rpRegisterName"],0,100);
			$_POST["rpRegisterPassword_1"] = substr($_POST["rpRegisterPassword_1"],0,100);
			$_POST["rpRegisterPassword_2"] = substr($_POST["rpRegisterPassword_2"],0,100);
			
			// iterate base types
			
			$types = "";
			
			foreach ($_POST as $var => $value) {
				if (substr($var,0,15) == "rpRegisterType_") {
					if ($value=="on") {
						$types .= "[".str_replace("rpRegisterType_","",$var)."]";
					}
				}
			}
			
			if ($types!="") {
			
				// check for duplicates
			
				$same_result = $rpConnection->query("SELECT id FROM ".$rpSettings->getValue("clientsTable")." WHERE email='".rpSanitize($_POST["rpRegisterEmail"])."' LIMIT 1");
			
				$newest_result = $rpConnection->query("SELECT added_datetime FROM ".$rpSettings->getValue("clientsTable")." WHERE added_ip='".rpSanitize(rpGetIP())."' ORDER BY added_datetime DESC LIMIT 1");
	
				if (mysql_num_rows($newest_result)>0) {
				$time_difference = strtotime(date("Y-m-d H:i:s"))-strtotime(mysql_result($newest_result, 0, "added_datetime"));
				} else {$time_difference = $rpSettings->getValue("blockInterval");}
			
				if (mysql_num_rows($same_result)<1 && $time_difference>=$rpSettings->getValue("blockInterval")) {
			
					$salt = rpGenerateSalt(20);
					$password = crypt($rpSettings->getValue("secret").rpSanitize($_POST["rpRegisterPassword_1"]), $salt);
			
					$nextID = rpGetNextID("clients");
			
					// insert into database
			
					if ($rpConnection->query("INSERT INTO ".$rpSettings->getValue("clientsTable")." (".$rpSettings->getValue("clientsTableStructure").") VALUES (
						'".rpSanitize($nextID)."',
						'0',
						'0',
						'',
						'".rpSanitize($types)."',
						'',
						'',
						'',
						'0',
						'',
						'',
						'',
						'',
						'".rpSanitize($_POST["rpRegisterEmail"])."',
						'".rpSanitize($password)."',
						'".rpSanitize($salt)."',
						'',
						'',
						'".rpSanitize($_POST["rpRegisterName"])."',
						'',
						'',
						'',
						'',
						'',
						'',
						'',
						'',
						'',
						'',
						'',
						'',
						'',
						'',
						'".rpSanitize(rpPrintTypes("[[rp(type)]]", "contactTypes", "", false))."',
						'',
						'0',
						'[email][screen]',
						'200',
						'',
						'',
						'".rpSanitize($rpSettings->getValue("clientDefaultVisibility"))."',
						'',
						'0',
						'".rpSanitize(rpGetFirstType("startUpLocationTypes"))."',			
						'".date("Y-m-d H:i:s")."',
						'".rpSanitize(rpGetIP())."',
						'0',
						'".date("Y-m-d H:i:s")."',
						'".rpSanitize(rpGetIP())."',
						'0',
						'',
						'',
						'',
						'',
						'',
						'',
						'".rpSanitize($nextID)."',
						'0',
						'1',
						'0')")) {
					
						if (rpSendMail($_POST["rpRegisterEmail"], "Tervetuloa Ravinnepörssi.fi-palvelun käyttäjäksi", "Vahvista rekisteröitymisesi klikkaamalla oheista linkkiä:<br /><a href=\"http://".$rpSettings->getValue("domain")."/?rpAction=confirm&clientID=".$nextID."&rpCheck=".md5("CONFIRM".$rpSettings->getValue("secret").$nextID)."\">http://".$rpSettings->getValue("domain")."/?rpAction=confirm&clientID=".$nextID."&rpCheck=".md5("CONFIRM".$rpSettings->getValue("secret").$nextID)."</a>")) {
							
							echo "SUCCESS";
							
						} else {echo "Käyttäjä tallennettu.<br />Ongelma vahvistuslinkin lähettämisessä.";}
					
					} else {echo "Ongelma tietojen tallentamisessa.";}
		
				} else {echo "Sähköpostiosoite on jo rekisteröity.";}
		
			} else {echo "Et ole valinnut käyttäjätyyppiä.";}
	
		} else {echo "Et ole hyväksynyt käyttöehtoja.";}
	
	} else {echo "Puuttuvia kohtia lomakkeessa.";}

}

include_once("engine/rp.end.php"); ?>