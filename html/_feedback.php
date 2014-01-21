<?php include_once("engine/rp.start.php");

if ($_SESSION["clientID"] && $_POST["rpAction"]=="rpSendFeedback") {

	if ($_POST["rpCheck"] == md5("FEEDBACK".$rpSettings->getValue("secret").$_SESSION["clientID"])) {
		
		if ($_POST["rpFeedbackTitle"] != "" && $_POST["rpFeedbackMessage"] != "") {
			
			$_POST["rpFeedbackTitle"] = substr($_POST["rpFeedbackTitle"],0,500);
			$_POST["rpFeedbackMessage"] = substr($_POST["rpFeedbackMessage"],0,10000);
			
			// save feedback
		
			$nextID = rpGetNextID("feedback");
		
			if ($rpConnection->query("INSERT INTO ".$rpSettings->getValue("feedbackTable")." (".$rpSettings->getValue("feedbackTableStructure").") VALUES (
				'".rpSanitize($nextID)."',
				'0',
				'',
				'".rpSanitize($_POST["rpFeedbackTitle"])."',
				'".rpSanitize(nl2br($_POST["rpFeedbackMessage"]))."',
				'".date("Y-m-d H:i:s")."',
				'".rpSanitize(rpGetIP())."',
				'".rpSanitize($_SESSION["clientID"])."',
				'',
				'',
				'0',
				'".rpSanitize($nextID)."')")) {
			
				// send
				
				$admins_result = $rpConnection->query("SELECT email FROM ".$rpSettings->getValue("clientsTable")." WHERE admin='1' && confirmed='1' && published='1'");
				
				for ($i = 0; $i < mysql_num_rows($admins_result); $i += 1) {
					
					if (strstr(mysql_result($admins_result, $i, "email"),"@")) {
					
						rpSendMail(mysql_result($admins_result, $i, "email"), "Uusi palaute Ravinnepörssi.fi-palvelusta", "<h2>".strip_tags($_POST["rpFeedbackTitle"])."</h2>".strip_tags(nl2br($_POST["rpFeedbackMessage"]),"<br>"));
					
					}
					
				}
				
				echo "SUCCESS";
					
			} else {$rpNotify = "Virhe palautteen lähettämisessä.";}
						
		} else {$rpNotify = "Puuttuvia kohtia lomakkeessa.";}
		
	} else {$rpNotify = "Virhe palautteen lähettämisessä.";}

}

include_once("engine/rp.end.php"); ?>