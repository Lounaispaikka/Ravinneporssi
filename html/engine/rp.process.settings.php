<?php

if ($_POST["rpAction"] == "rpSaveSettings" && $_SESSION["clientID"]) {

	if ($_POST["rpCheck"] == md5("SETTINGS".$rpSettings->getValue("secret").$_SESSION["clientID"])) {

		if (strstr($_POST["rpClientEmail"],"@") && $_POST["rpClientName"]!="") {
				
			if (($_POST["rpClientPassword_1"]=="" && $_POST["rpClientPassword_2"]=="") || $_POST["rpClientPassword_1"]==$_POST["rpClientPassword_2"]) {
		
				$_POST["rpClientEmail"] = substr($_POST["rpClientEmail"],0,100);
				$_POST["rpClientName"] = substr($_POST["rpClientName"],0,100);
				$_POST["rpClientPassword_1"] = substr($_POST["rpClientPassword_1"],0,100);
				$_POST["rpClientPassword_2"] = substr($_POST["rpClientPassword_2"],0,100);
				
				$_POST["rpClientCompany"] = substr($_POST["rpClientCompany"],0,100);
				$_POST["rpClientBIC"] = substr($_POST["rpClientBIC"],0,100);
				$_POST["rpClientAddress_1"] = substr($_POST["rpClientAddress_1"],0,100);
				$_POST["rpClientAddress_2"] = substr($_POST["rpClientAddress_2"],0,100);		
				
				$_POST["rpClientState"] = substr($_POST["rpClientState"],0,100);
				$_POST["rpClientCity"] = substr($_POST["rpClientCity"],0,100);
				$_POST["rpClientPostalcode"] = substr($_POST["rpClientPostalcode"],0,100);
				
				$_POST["rpClientPhonenumber"] = substr($_POST["rpClientPhonenumber"],0,100);
				$_POST["rpClientFax"] = substr($_POST["rpClientFax"],0,100);
				$_POST["rpClientGSM"] = substr($_POST["rpClientGSM"],0,100);
				
				$_POST["rpClientDescription"] = substr($_POST["rpClientDescription"],0,1000);
				$_POST["rpClientVisibility"] = substr($_POST["rpClientVisibility"],0,100);
				
				$types = "";
		
				foreach ($_POST as $var => $value) {
					if (substr($var,0,13) == "rpClientType_") {
						if ($value=="on") {
							$types .= "[".str_replace("rpClientType_","",$var)."]";
						}
					}
				}
				
				$types2 = "";
		
				foreach ($_POST as $var => $value) {
					if (substr($var,0,14) == "rpClientType2_") {
						if ($value=="on") {
							$types2 .= "[".str_replace("rpClientType2_","",$var)."]";
						}
					}
				}
				
				$contact_via = "";
		
				foreach ($_POST as $var => $value) {
					if (substr($var,0,19) == "rpClientContactVia_") {
						if ($value=="on") {
							$contact_via .= "[".str_replace("rpClientContactVia_","",$var)."]";
						}
					}
				}
				
				$trades = "";
		
				foreach ($_POST as $var => $value) {
					if (substr($var,0,14) == "rpClientTrade_") {
						if ($value>0) {
							$trades .= "rpClientTrade|".rpGetTradePrefix(intval($value))."|end|";
						}
					}
				}
				
				foreach ($_POST as $var => $value) {
					if (substr($var,0,20) == "rpClientTradeOption_") {
						
						if ($value=="on") {
							
							$trade_array = explode("_",str_replace("rpClientTradeOption_","",$var));
							
							$trades .= "rpClientTradeOption|".rpGetTradePrefix(intval($trade_array[0]))."|".rpGetTradePrefix(intval($trade_array[0]),intval($trade_array[1]))."|".str_replace("|","",$_POST["rpClientTradeOptionDescription_".intval($trade_array[0])."_".intval($trade_array[1])])."|end|";						
						}
					}
				}
				
				foreach ($_POST as $var => $value) {
					if (substr($var,0,25) == "rpClientTradeDescription_") {
						
						if ($value!="") {

							$trade_array = explode("_",str_replace("rpClientTradeDescription_","",$var));					
						
							$trades .= "rpClientTradeDescription|".rpGetTradePrefix(intval($trade_array[0]))."|".rpGetTradePrefix(intval($trade_array[0]),intval($trade_array[1]))."|".str_replace("|","",$value)."|end|";
												
						}
					}
				}
				
				$arsenal = "";
		
				foreach ($_POST as $var => $value) {
					if (substr($var,0,15) == "rpArsenalTitle_") {
						if ($value!="") {
							
							$arsenal_id = str_replace("rpArsenalTitle_","",$var);
							$arsenal .= "rpClientArsenal|".str_replace("|","",$value)."|".str_replace("|","",nl2br($_POST["rpArsenalDescription_".$arsenal_id]))."|end|";
							
						}
					}
				}
				
				$notifier_types = "";
		
				foreach ($_POST as $var => $value) {
					if (substr($var,0,21) == "rpClientNotifierType_") {
						if ($value=="on") {
							
							$notifier_types .= str_replace("|","",str_replace("rpClientNotifierType_","",$var))."|";
							
						}
					}
				}
				
				$notifier_products = "";
		
				foreach ($_POST as $var => $value) {
					if (substr($var,0,24) == "rpClientNotifierProduct_") {
						if ($value=="on") {
							
							$notifier_products .= str_replace("|","",str_replace("rpClientNotifierProduct_","",$var))."|";
							
						}
					}
				}
				
				$notifier_contact = "";
				
				if ($_POST["rpClientNotifierContactEmail"]=="on") {$notifier_contact .= "[email]";}
				if ($_POST["rpClientNotifierContactScreen"]=="on") {$notifier_contact .= "[screen]";}
				
				$user_result = $rpConnection->query("SELECT id FROM ".$rpSettings->getValue("clientsTable")." WHERE email='".rpSanitize($_POST["rpClientEmail"])."' && id!='".rpSanitize($_SESSION["clientID"])."' LIMIT 1");

				if (mysql_num_rows($user_result)<1) {
				
					// save basic settings
								
					if ($rpConnection->query("UPDATE ".$rpSettings->getValue("clientsTable")." SET 
					types='".rpSanitize($types)."',
					types2='".rpSanitize($types2)."',
					contact_via='".rpSanitize($contact_via)."',
					trades='".rpSanitize($trades)."',
					arsenal='".rpSanitize(str_replace('"','',str_replace("'","",$arsenal)))."',
					email='".rpSanitize($_POST["rpClientEmail"])."',
					name='".rpSanitize($_POST["rpClientName"])."',
					company='".rpSanitize($_POST["rpClientCompany"])."',
					bic='".rpSanitize($_POST["rpClientBIC"])."',
					address_1='".rpSanitize($_POST["rpClientAddress_1"])."',
					address_2='".rpSanitize($_POST["rpClientAddress_2"])."',					
					state='".rpSanitize($_POST["rpClientState"])."',
					city='".rpSanitize($_POST["rpClientCity"])."',
					postalcode='".rpSanitize($_POST["rpClientPostalcode"])."',		
					phonenumber='".rpSanitize($_POST["rpClientPhonenumber"])."',
					fax='".rpSanitize($_POST["rpClientFax"])."',
					gsm='".rpSanitize($_POST["rpClientGSM"])."',
					notifier='".rpSanitize(rpBoolean($_POST["rpClientNotifier"]))."',
					notifier_contact='".rpSanitize($notifier_contact)."',
					notifier_threshold='".rpSanitize(intval($_POST["rpClientNotifierThreshold"]))."',
					notifier_types='".rpSanitize($notifier_types)."',
					notifier_products='".rpSanitize($notifier_products)."',
					startup_location='".rpSanitize($_POST["rpClientStartupLocation"])."',
					description='".rpSanitize($_POST["rpClientDescription"])."',
					visibility='".rpSanitize($_POST["rpClientVisibility"])."',					
					modified_datetime='".date("Y-m-d H:i:s")."',
					modified_ip='".rpSanitize(rpGetIP())."',
					modified_userid='0'
					 WHERE id='".rpSanitize($_SESSION["clientID"])."' LIMIT 1")) {
					 	
					 	if ($_POST["rpClientPassword_1"]!="" && $_POST["rpClientPassword_2"]!="" && $_POST["rpClientPassword_1"]==$_POST["rpClientPassword_2"]) {
					 		
					 		$salt = rpGenerateSalt(20);
							$password = crypt($rpSettings->getValue("secret").rpSanitize($_POST["rpClientPassword_1"]), $salt);
					 		
					 		if ($rpConnection->query("UPDATE ".$rpSettings->getValue("clientsTable")." SET 
							salt='".rpSanitize($salt)."',
							password='".rpSanitize($password)."'
							 WHERE id='".rpSanitize($_SESSION["clientID"])."' LIMIT 1")) {
							 	
							 	$rpNotify = "Asetukset on tallennettu.";
							 	
							 } else {$rpNotify = "Ongelma salasanan vaihtamisessa.";}
					 		
					 	} else {$rpNotify = "Asetukset on tallennettu.";}
					 	
					} else {$rpNotify = "Ongelma asetusten tallentamisessa.";}
		
				} else {$rpNotify = "Sähköpostiosoite on jo käytössä.";}
		
			} else {$rpNotify = "Salasanat eivät täsmää.";}
		
		} else {$rpNotify = "Puuttuvia kohtia lomakkeessa.";}

	} else {$rpNotify = "Ongelma asetusten tallentamisessa.";}

}

if ($_SESSION["clientID"] && $_POST["rpAction"]=="rpSaveSettings") {

	include("class.upload.php");

	$fileprocesserror = false;
	$files = "";

	foreach ($_FILES as $file) {
					
		if ($file["name"]!="") {
		
			if (stristr($rpSettings->getValue("allowImageTypes"), rpExtension(basename($file["name"])))) {
								
				if (filesize($file["tmp_name"])<$rpSettings->getValue("maximumFileSize")) {
				
					// process file
					
					$generated_filename = md5(rand(0,1000000)).".".rpExtension($file["name"]);
						
					while (file_exists("files/images/fullsize/".$generated_filename) || file_exists("files/images/thumbnail/".$generated_filename)) {
					
						$generated_filename = md5(rand(0,1000000)).".".rpExtension($file["name"]);
						
					}
					
					$fullsize = new Upload($file["tmp_name"]);
					
					if ($fullsize->uploaded) {
						
						$exif = exif_read_data($file["tmp_name"]);
									
						$fullsize->file_new_name_body = $generated_filename;
						$fullsize->file_force_extension = false;

						if ($exif["Orientation"]==6) {
							
							$image_rotation = 90;
											
						} else if ($exif["Orientation"]==8) {
							
							$image_rotation = 270;
							
						} else if ($exif["Orientation"]==3) {
							
							$image_rotation = 180;
							
						} else {$image_rotation = 0;}

						if ($image_rotation>0) {$fullsize->image_rotate = $image_rotation;}

						$fullsize->image_resize = true;
						$fullsize->image_ratio = true;
						
						if ($fullsize->image_src_x>$rpSettings->getValue("maxImageSide") || $fullsize->image_src_y>$rpSettings->getValue("maxImageSide")) {
							
							$fullsize->image_x = $rpSettings->getValue("maxImageSide");
							$fullsize->image_y = $rpSettings->getValue("maxImageSide");
							
						} else {
							
							$fullsize->image_x = $fullsize->image_src_x;
							$fullsize->image_y = $fullsize->image_src_y;
							
						}

						$fullsize->Process("files/images/fullsize/");
						
						if ($fullsize->processed) {
							
							$fullsize->file_new_name_body = $generated_filename;
							$fullsize->file_force_extension = false;
							
							if ($image_rotation>0) {$fullsize->image_rotate = $image_rotation;}
							
							$fullsize->image_resize = true;
							$fullsize->image_ratio_crop = true;
							
							if ($image_rotation==90 || $image_rotation==270) {
							
								$fullsize->image_y = $rpSettings->getValue("thumbnailWidth");
								$fullsize->image_x = $rpSettings->getValue("thumbnailHeight");
							
							} else {
							
								$fullsize->image_x = $rpSettings->getValue("thumbnailWidth");
								$fullsize->image_y = $rpSettings->getValue("thumbnailHeight");
								
							}
							
							$fullsize->Process("files/images/thumbnail/");
							
							if ($fullsize->processed) {
							
								$files .= $generated_filename."|=|".strtolower(preg_replace("([^\w\s\d\.\-_~,;:\[\]\(\]]|[\.]{2,})", '', $file["name"]))."|end|";
						
							} else {$rpNotify = "Virhe tiedoston tallentamisessa."; $fileprocesserror = true;}
						
						} else {$rpNotify = "Virhe tiedoston tallentamisessa."; $fileprocesserror = true;}
						
					} else {$rpNotify = "Virhe tiedoston tallentamisessa."; $fileprocesserror = true;}
			
				} else {$rpNotify = "Virhe tiedoston tallentamisessa."; $fileprocesserror = true;}
			
			} else {$rpNotify = "Tiedostokoko ylittää sallitun määrän."; $fileprocesserror = true;}
			
		} else {$rpNotify = "Tiedostotyyppi ei ole sallittu."; $fileprocesserror = true;}
	
	}
	
	if ($files!="" && !$fileprocesserror) {
		
		if ($rpConnection->query("UPDATE ".$rpSettings->getValue("clientsTable")." SET 
			images=concat('".rpSanitize($files)."',images),				
			modified_datetime='".date("Y-m-d H:i:s")."',
			modified_ip='".rpSanitize(rpGetIP())."',
			modified_userid='0'
			 WHERE id='".rpSanitize($_SESSION["clientID"])."' LIMIT 1")) {
			 	
			 $rpNotify = "Kuvat ja asetukset tallennettiin.";
			 	
		} else {$rpNotify = "Virhe tiedostojen tallentamisessa.";}
		
	}
	
}

?>