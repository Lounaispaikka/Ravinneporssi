<?php

if ($_POST["rpNoticeTitle"]!="" && $_POST["rpNoticeDescription"]!="" && $_POST["rpNoticeCity"]!="" && $_POST["rpNoticeAddress"]!="" && $_POST["rpNoticeState"]!="" && $_POST["rpAction"]=="rpUpdateNotice" && $_SESSION["clientID"]) {
		
	if ($_POST["rpCheck"] == md5("NOTICE".intval($_POST["rpNoticeID"]).$rpSettings->getValue("secret").$_SESSION["clientID"])) {
	
	$notice_result = $rpConnection->query("SELECT id FROM ".$rpSettings->getValue("noticesTable")." WHERE id='".rpSanitize(intval($_POST["rpNoticeID"]))."' && added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."' LIMIT 1");

		if (mysql_num_rows($notice_result)>0) {
	
			$_POST["rpNoticeTitle"] = substr($_POST["rpNoticeTitle"],0,200);
			$_POST["rpNoticeAddress"] = substr($_POST["rpNoticeAddress"],0,200);
			$_POST["rpNoticeState"] = substr($_POST["rpNoticeState"],0,200);
			$_POST["rpNoticeCity"] = substr($_POST["rpNoticeCity"],0,200);
			$_POST["rpNoticeDescription"] = substr($_POST["rpNoticeDescription"],0,1000);
	
			$contact_via = "";
			
			foreach ($_POST as $var => $value) {
				if (substr($var,0,19) == "rpNoticeContactVia_") {
					if ($value=="on") {
						$contact_via .= "[".str_replace("rpNoticeContactVia_","",$var)."]";
					}
				}
			}
	
			$products = "";
		
			foreach ($_POST as $var => $value) {
				if (substr($var,0,10) == "rpProduct_") {
					if ($value>0) {
						
						$product_array = explode("_",str_replace("rpProduct_","",$var));
						$products .= "rpProduct|".$_POST["rpProductType_".$product_array[0]]."|".$product_array[0]."|".rpGetProductPrefix(intval($value))."|end|";
						
					}
				}
			}
			
			foreach ($_POST as $var => $value) {
				if (substr($var,0,16) == "rpProductOption_") {
					if ($value=="on") {
						
						$product_array = explode("_",str_replace("rpProductOption_","",$var));
						$products .= "rpProductOption|".$_POST["rpProductType_".$product_array[0]]."|".$product_array[0]."|".rpGetProductPrefix(intval($product_array[1]))."|".rpGetProductPrefix(intval($product_array[1]),intval($product_array[2]))."|".str_replace("|","",$_POST["rpProductOptionDescription_".str_replace("rpProductOption_","",$var)])."|end|";
						
					}
				}
			}
			
			foreach ($_POST as $var => $value) {
				if (substr($var,0,21) == "rpProductDescription_") {
					if ($value!="") {
						
						$product_array = explode("_",str_replace("rpProductDescription_","",$var));
						$products .= "rpProductDescription|".$_POST["rpProductType_".$product_array[0]]."|".$product_array[0]."|".rpGetProductPrefix(intval($product_array[1]))."|".rpGetProductPrefix(intval($product_array[1]),intval($product_array[2]))."|".str_replace("|","",$value)."|end|";
						
					}
				}
			}	

			if ($products!="") {
			
				if ($_POST["rpNoticePublishEnd"]=="" || strtotime(rpFormatDate($_POST["rpNoticePublishEnd"]))>strtotime(date("Y-m-d H:i:s"))) {
			
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
										
									} else {$rpNotify = "Virhe tiedoston tallentamisessa.<br />Ilmoitusta ei tallennettu."; $fileprocesserror = true;}
								
								} else {$rpNotify = "Tiedostokoko ylittää sallitun määrän.<br />Ilmoitusta ei tallennettu."; $fileprocesserror = true;}
								
							} else {$rpNotify = "Tiedostotyyppi ei ole sallittu.<br />Ilmoitusta ei tallennettu."; $fileprocesserror = true;}
						
						}
						
					}
			
					if (!$fileprocesserror) {
			
						if ($rpConnection->query("UPDATE ".$rpSettings->getValue("noticesTable")." SET 
						files=concat('".rpSanitize($files)."',files),
						title='".rpSanitize($_POST["rpNoticeTitle"])."',
						description='".rpSanitize($_POST["rpNoticeDescription"])."',
						state='".rpSanitize($_POST["rpNoticeState"])."',
						city='".rpSanitize($_POST["rpNoticeCity"])."',
						address='".rpSanitize($_POST["rpNoticeAddress"])."',
						visibility='".rpSanitize($_POST["rpNoticeVisibility"])."',
						publish_end='".rpSanitize(rpFormatDate($_POST["rpNoticePublishEnd"]))."',
						products='".rpSanitize($products)."',
						value='".rpSanitize(floatval(str_replace(",",".",$_POST["rpNoticeValue"])))."',
						contact_via='".rpSanitize($contact_via)."',		
						modified_datetime='".date("Y-m-d H:i:s")."',
						modified_ip='".rpSanitize(rpGetIP())."',
						modified_userid='0'
						 WHERE id='".rpSanitize(intval($_POST["rpNoticeID"]))."' LIMIT 1")) {$rpNotify = "Ilmoitus tallennettiin.";} else {$rpNotify = "Ongelma ilmoituksen tallentamisessa.";}
			
					}
			
				} else {$rpNotify = "Virheellinen ilmoituksen päättymisaika.";}
	
			} else {$rpNotify = "Ilmoituksessa ei ole yhtään sisältöä.";}
	
		} else {$rpNotify = "Ilmoitusta ei löytynyt.";}
	
	} else {$rpNotify = "Ongelma ilmoituksen tallentamisessa.";}
		
}

if ($_POST["rpNoticeLatitude"]>0 && $_POST["rpNoticeLongitude"]>0 && $_POST["rpNoticePosX"]>0 && $_POST["rpNoticePosY"]>0 && $_POST["rpNoticeTitle"]!="" && $_POST["rpNoticeCity"]!="" && $_POST["rpNoticeState"]!="" && $_POST["rpNoticeDescription"]!="" && $_POST["rpNoticeAddress"]!="" && $_POST["rpAction"]=="rpSaveNotice" && $_SESSION["clientID"]) {

	if ($_POST["rpCheck"] == md5("NOTICE".$rpSettings->getValue("secret").$_SESSION["clientID"])) {

		// save notice
	
		$_POST["rpNoticeTitle"] = substr($_POST["rpNoticeTitle"],0,200);
		$_POST["rpNoticeAddress"] = substr($_POST["rpNoticeAddress"],0,200);
		$_POST["rpNoticeState"] = substr($_POST["rpNoticeState"],0,200);
		$_POST["rpNoticeCity"] = substr($_POST["rpNoticeCity"],0,200);
		$_POST["rpNoticeDescription"] = substr($_POST["rpNoticeDescription"],0,1000);
	
		$notice_result = $rpConnection->query("SELECT id FROM ".$rpSettings->getValue("noticesTable")." WHERE latitude='".rpSanitize($_POST["rpNoticeLatitude"])."' && longitude='".rpSanitize($_POST["rpNoticeLongitude"])."' &&  added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."' LIMIT 1");
	
		if (mysql_num_rows($notice_result)<1) {
	
			$contact_via = "";
			
			foreach ($_POST as $var => $value) {
				if (substr($var,0,19) == "rpNoticeContactVia_") {
					if ($value=="on") {
						$contact_via .= "[".str_replace("rpNoticeContactVia_","",$var)."]";
					}
				}
			}
			
			$products = "";
	
			foreach ($_POST as $var => $value) {
				if (substr($var,0,10) == "rpProduct_") {
					if ($value>0) {
						
						$product_array = explode("_",str_replace("rpProduct_","",$var));
						$products .= "rpProduct|".$_POST["rpProductType_".$product_array[0]]."|".$product_array[0]."|".rpGetProductPrefix(intval($value))."|end|";
						
					}
				}
			}
			
			foreach ($_POST as $var => $value) {
				if (substr($var,0,16) == "rpProductOption_") {
					if ($value=="on") {
						
						$product_array = explode("_",str_replace("rpProductOption_","",$var));
						$products .= "rpProductOption|".$_POST["rpProductType_".$product_array[0]]."|".$product_array[0]."|".rpGetProductPrefix(intval($product_array[1]))."|".rpGetProductPrefix(intval($product_array[1]),intval($product_array[2]))."|".str_replace("|","",$_POST["rpProductOptionDescription_".str_replace("rpProductOption_","",$var)])."|end|";
						
					}
				}
			}
			
			foreach ($_POST as $var => $value) {
				if (substr($var,0,21) == "rpProductDescription_") {
					if ($value!="") {
						
						$product_array = explode("_",str_replace("rpProductDescription_","",$var));
						$products .= "rpProductDescription|".$_POST["rpProductType_".$product_array[0]]."|".$product_array[0]."|".rpGetProductPrefix(intval($product_array[1]))."|".rpGetProductPrefix(intval($product_array[1]),intval($product_array[2]))."|".str_replace("|","",$value)."|end|";
						
					}
				}
			}
			
			if ($products!="") {
			
				if ($_POST["rpNoticePublishEnd"]=="" || strtotime(rpFormatDate($_POST["rpNoticePublishEnd"]))>strtotime(date("Y-m-d H:i:s"))) {
		
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
										
									} else {$rpNotify = "Virhe tiedoston tallentamisessa.<br />Ilmoitusta ei tallennettu."; $fileprocesserror = true;}
								
								} else {$rpNotify = "Tiedostokoko ylittää sallitun määrän.<br />Ilmoitusta ei tallennettu."; $fileprocesserror = true;}
								
							} else {$rpNotify = "Tiedostotyyppi ei ole sallittu.<br />Ilmoitusta ei tallennettu."; $fileprocesserror = true;}
						
						}
						
					}
		
					if (!$fileprocesserror) {
		
						$nextID = rpGetNextID("notices");
					
						if ($rpConnection->query("INSERT INTO ".$rpSettings->getValue("noticesTable")." (".$rpSettings->getValue("noticesTableStructure").") VALUES (
							'".rpSanitize($nextID)."',
							'0',
							'".rpSanitize(rpGetClient("types"))."',
							'".rpSanitize(rpGetClient("types2"))."',
							'".rpSanitize(floatval($_POST["rpNoticeLatitude"]))."',
							'".rpSanitize(floatval($_POST["rpNoticeLongitude"]))."',
							'".rpSanitize(floatval($_POST["rpNoticePosX"]))."',
							'".rpSanitize(floatval($_POST["rpNoticePosY"]))."',
							'".rpSanitize(floatval(str_replace(",",".",$_POST["rpNoticeValue"])))."',
							'".rpSanitize($_POST["rpNoticeTitle"])."',
							'".rpSanitize($_POST["rpNoticeDescription"])."',
							'".rpSanitize(rpGetClient("trades"))."',
							'".rpSanitize($_POST["rpNoticeState"])."',
							'".rpSanitize($_POST["rpNoticeCity"])."',	
							'".rpSanitize($_POST["rpNoticeAddress"])."',				
							'".rpSanitize($products)."',
							'".rpSanitize($files)."',
							'".rpSanitize($contact_via)."',
							'".rpSanitize($_POST["rpNoticeVisibility"])."',
							'',
							'".rpSanitize(rpFormatDate($_POST["rpNoticePublishEnd"]))."',
							'".date("Y-m-d H:i:s")."',
							'".rpSanitize(rpGetIP())."',
							'".rpSanitize($_SESSION["clientID"])."',
							'',
							'',
							'',
							'".rpSanitize($nextID)."',
							'1')")) {
							
							$forceLatitude = floatval($_POST["rpNoticeLatitude"]);
							$forceLongitude = floatval($_POST["rpNoticeLongitude"]);
							
							$rpNotify = "Ilmoitus tallennettiin.";
							
							rpProcessNotifier($nextID);
							
						} else {$rpNotify = "Ongelma ilmoituksen tallentamisessa.";}
		
					}
		
				} else {$rpNotify = "Virheellinen ilmoituksen päättymisaika.";}
	
			} else {$rpNotify = "Ilmoituksessa ei ole yhtään sisältöä.";}
	
		} else {$rpNotify = "Ilmoitus on jo tallennettu kyseiseen paikkaan.";}

	} else {$rpNotify = "Ongelma ilmoituksen tallentamisessa.";}

}

?>