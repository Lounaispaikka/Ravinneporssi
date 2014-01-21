<?php

if ($_SESSION["clientID"] && $_POST["rpAction"]=="rpSendMessage") {

	if ($_POST["rpCheck"] == md5("MESSAGE".$rpSettings->getValue("secret").$_SESSION["clientID"])) {
		
		$_POST["rpMessageTitle"] = substr($_POST["rpMessageTitle"],0,500);
		$_POST["rpMessageMessage"] = substr($_POST["rpMessageMessage"],0,10000);
		
		if ($_POST["rpMessageTitle"] != "" && $_POST["rpMessageMessage"] != "") {
			
			$recipients = "";
			
			foreach ($_POST as $var => $value) {
				if (substr($var,0,13) == "rpToClientID_") {
					if ($value==intval(str_replace("rpToClientID_","",$var))) {
						if (rpIsAlive(intval($value), "clients") && rpGetOtherClient(intval($value), "published")==1) {
							$recipients .= "[".intval($value)."]";
						}
					}
				}
			}
			
			if ($recipients!="") {
			
				$fileprocesserror = false;
				$files = "";
				
				foreach ($_FILES as $file) {
					
					if ($file["name"]!="") {
					
						if (stristr($rpSettings->getValue("allowFileTypes"), rpExtension(basename($file["name"])))) {
												
							if (filesize($file["tmp_name"])<$rpSettings->getValue("maximumFileSize")) {
							
								// process file
								
								$generated_filename = md5(rand(0,1000000)).".".rpExtension($file["name"]);
									
								while (file_exists("files/uploads/".$generated_filename)) {
								
									$generated_filename = md5(rand(0,1000000)).".".rpExtension($file["name"]);
									
								}
															
								if (move_uploaded_file($file["tmp_name"], "files/uploads/".$generated_filename)) {
									
									$files .= $generated_filename."|=|".strtolower(preg_replace("([^\w\s\d\.\-_~,;:\[\]\(\]]|[\.]{2,})", '', $file["name"]))."|end|";
									
								} else {$rpNotify = "Virhe tiedoston tallentamisessa.<br />Viestiä ei lähetetty."; $fileprocesserror = true;}
							
							} else {$rpNotify = "Tiedostokoko ylittää sallitun määrän.<br />Viestiä ei lähetetty."; $fileprocesserror = true;}
							
						} else {$rpNotify = "Tiedostotyyppi ei ole sallittu.<br />Viestiä ei lähetetty."; $fileprocesserror = true;}
					
					}
					
				}
				
				if (!$fileprocesserror) {
				
					// save message
				
					$nextID = rpGetNextID("messages");
				
					if ($_POST["rpToMessageID"]>0) {
						
						$message_result = $rpConnection->query("SELECT id FROM ".$rpSettings->getValue("messagesTable")." WHERE id='".rpSanitize(intval($_POST["rpToMessageID"]))."' && (to_clientid='".rpSanitize(intval($_SESSION["clientID"]))."' OR added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."') LIMIT 1");
						
						if (mysql_num_rows($message_result)<1) {
							
							$_POST["rpToMessageID"] = 0;
							
						}
						
					}
				
					if ($rpConnection->query("INSERT INTO ".$rpSettings->getValue("messagesTable")." (".$rpSettings->getValue("messagesTableStructure").") VALUES (
						'".rpSanitize($nextID)."',
						'".rpSanitize(intval($_POST["rpToMessageID"]))."',
						'',
						'".rpSanitize($_POST["rpMessageTitle"])."',
						'".rpSanitize(nl2br($_POST["rpMessageMessage"]))."',
						'".rpSanitize($files)."',
						'".date("Y-m-d H:i:s")."',
						'".rpSanitize(rpGetIP())."',
						'".rpSanitize($_SESSION["clientID"])."',
						'".rpSanitize($recipients)."',
						'',
						'',
						'',
						'0',
						'',
						'".rpSanitize($nextID)."')")) {
					
					$rpNotify = "Viestisi lähetettiin."; $loopBackPage = "messages";
				
					} else {$rpNotify = "Virhe viestin lähettämisessä."; $loopBackPage = "messages";}
				
				}
				
			} else {$rpNotify = "Vastaanottajia ei löytynyt.";}
			
		} else {$rpNotify = "Puuttuvia kohtia lomakkeessa."; $loopBackPage = "messages";}
		
	} else {$rpNotify = "Virhe viestin lähettämisessä."; $loopBackPage = "messages";}

}

?>