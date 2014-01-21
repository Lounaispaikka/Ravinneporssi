<?php

function rpProcessNotifier($id) {

	global $rpConnection;
	global $rpSettings;
	
	$notice_result = $rpConnection->query("SELECT products, added_clientid, latitude, longitude FROM ".$rpSettings->getValue("noticesTable")." WHERE published='1' && visibility!='administrators' && id='".rpSanitize(intval($id))."' LIMIT 1");
	
	$products_array = explode("|end|",rpUTF8Encode(mysql_result($notice_result, 0, "products")));
	
	$notifier_types = "";
	$notifier_products = "";
	
	foreach ($products_array as $product) {
	
		if (substr($product,0,10)=="rpProduct|") {
						
			$product_array = explode("|",$product);
			
			$notifier_types .= "notifier_types LIKE '%".rpSanitize($product_array[1])."|%' OR ";
			$notifier_products .= "notifier_products LIKE '%".rpSanitize($product_array[3])."|%' OR ";
			
		}
	
	}
	
	$notifier_types = substr($notifier_types, 0, -4);
	$notifier_products = substr($notifier_products, 0, -4);
		
	$clients_result = $rpConnection->query("SELECT id, email, notifier_threshold, base_latitude, base_longitude FROM ".$rpSettings->getValue("clientsTable")." WHERE (".$notifier_types.") && (".$notifier_products.") && notifier_contact LIKE '%[email]%' && notifier='1' && published='1' && confirmed='1' && id!='".rpSanitize(intval(mysql_result($notice_result, 0, "added_clientid")))."'");
	
	for ($i = 0; $i < mysql_num_rows($clients_result); $i += 1) {
		
		if (strstr(mysql_result($clients_result, $i, "email"),"@")) {
			
			if ((mysql_result($clients_result, $i, "base_latitude")==0 && mysql_result($clients_result, $i, "base_longitude")==0) || mysql_result($clients_result, $i, "notifier_threshold")==0 || rpGeoDistance(mysql_result($notice_result, 0, "latitude"), mysql_result($notice_result, 0, "longitude"), mysql_result($clients_result, $i, "base_latitude"), mysql_result($clients_result, $i, "base_longitude"))<mysql_result($clients_result, $i, "notifier_threshold")) {
				
				rpSendNoticeViaEmail($id, mysql_result($clients_result, $i, "email"), "Ravinnereino ilmoittaa! Ravinnepörssi-palveluun on lisätty uusi, kiinnostava ilmoitus.");
				
			}
			
		}
		
	}
	
}

function rpSendNoticeViaEmail($nid, $email, $title) {
	
	global $rpSettings;
	
	$message = "<h2>".rpGetNotice($nid, "title")."</h2>";
	$message .= "<p>".rpGetNotice($nid, "description")."</p>";
	$message .= "<p>".rpGetNotice($nid, "address")."<br />".rpGetNotice($nid, "city")."</p>";
	
	$message .= "<p><a href=\"http://".$rpSettings->getValue("domain")."/?noticeID=".intval($nid)."\" target=\"_blank\">http://".$rpSettings->getValue("domain")."/?noticeID=".intval($nid)."</a></p>";
	
	if (rpSendMail($email, $title, $message)) {return true;} else {return false;}
	
}

function rpGetNoticeFiles($html, $id) {
	
	global $rpConnection;
	global $rpSettings;
	
	$notice_result = $rpConnection->query("SELECT files FROM ".$rpSettings->getValue("noticesTable")." WHERE published='1' && (visibility='all' OR (".rpSanitize(intval($_SESSION["clientID"])).">0 && visibility='registered') OR added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."') && id='".rpSanitize(intval($id))."' LIMIT 1");
	
	if (mysql_num_rows($notice_result)>0) {
				
		if (rpUTF8Encode(mysql_result($notice_result, 0, "files"))!="") {

			$files_array = explode("|end|", rpUTF8Encode(mysql_result($notice_result, 0, "files")));
			
			$runner = 1;
			
			foreach ($files_array as $file) {
			
				if ($file!="") {
			
					$file_array = explode("|=|", $file);
				
					$output = $html;
					
					$output = str_replace("[rp(id)]",$runner,$output);
					$output = str_replace("[rp(checksum)]",md5($file),$output);
					$output = str_replace("[rp(link)]","_getfile.php?noticeid=".intval($id)."&checksum=".md5($file),$output);
					$output = str_replace("[rp(filename)]",$file_array[1],$output);
				
					$runner += 1;
			
					echo $output;
			
				}
			
			}
			
		}
		
	}
	
}

function rpGetCounterNoticeType($products, $title=false) {
	
	global $rpConnection;
	global $rpSettings;
	
	$types_array = $rpSettings->getValue("counterNotices");
	
	foreach($types_array as $value) {

		$type_array = explode(",",$value[0]);
		
		foreach($type_array as $value2) {
			
			if (strstr($products, "rpProduct|".$value2."|")) {
				
				if ($title) {return $value[2];} else {return $value[1];}
				
			}
			
		}
		
	}
	
}

function rpGetCleanFavourites() {
	
	global $rpConnection;
	global $rpSettings;
	
	$favourites_array = explode(")(", rpGetClient("favourites"));
	
	$tooutput = "";
	$waypoint_string = "";
	$tooutput = array();
	
	foreach ($favourites_array as $value) {
		
		if (intval(rpCleanBraces($value))>0) {
			
			$missing = true;
			
			$notice_query = "SELECT id, publish_end FROM ".$rpSettings->getValue("noticesTable")." WHERE published='1' && (visibility='all' OR (".rpSanitize(intval($_SESSION["clientID"])).">0 && visibility='registered') OR added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."') && id='".rpSanitize(intval(rpCleanBraces($value)))."' LIMIT 1";
			
			$notice_result = $rpConnection->query($notice_query);
			
			if (mysql_num_rows($notice_result)>0) {
			
				if (rpDate(mysql_result($notice_result, 0, "publish_end")) == "" || strtotime(date("Y-m-d"))<strtotime(mysql_result($notice_result, 0, "publish_end"))) {
					
					$missing = false;
					
				}
				
			}
						
			if ($missing) {
				
				$rpConnection->query("UPDATE ".$rpSettings->getValue("clientsTable")." SET 
				favourites = REPLACE(favourites, '(".rpSanitize(intval(rpCleanBraces($value))).")', ''),
				modified_datetime='".date("Y-m-d H:i:s")."',
				modified_ip='".rpSanitize(rpGetIP())."',
				modified_userid='0'
				 WHERE id='".rpSanitize(intval($_SESSION["clientID"]))."' LIMIT 1");
				
			} else {array_push($tooutput, intval(rpCleanBraces($value)));}
			
		}
		
	}
		
	return $tooutput;
	
}

function rpIsSearchResultValid($products, $type, $options) {
	
	$products_array = explode("|end|", $products);
	
	$tooutput = false;
	
	foreach ($products_array as $value) {
	
		$product_array = explode("|", $value);
	
		if ($product_array[1] == $type && (strstr($options, $product_array[4]) || strstr($options, $product_array[3]))) {$tooutput = true;}	
		
	}
	
	return $tooutput;
	
}

function rpGetNoticeSize($value) {
	
	global $rpSettings;
	
	$values_array = $rpSettings->getValue("noticeValuePercentages");
	
	$percentual = 50;
	
	foreach ($values_array as $value_array) {
		
		if ($value>$value_array[0]) {$percentual = $value_array[1];}
		
	}	
	
	if ($value<1) {$percentual = 70;}
	
	return $percentual;
		
}

function rpGetNoticeType($products) {
	
	global $rpSettings;
	
	$products_array = explode("|end|", $products);
	
	$types = ""; $num_of_types = 0;
	
	foreach ($products_array as $value) {
		
		$product_array = explode("|", $value);
	
		if ($product_array[0]=="rpProduct") {$types .= "[".$product_array[1]."]"; $num_of_types += 1;}
		
	}
	
	$whattooutput = "";
	
	$types_array = $rpSettings->getValue("baseTypes");
	
	foreach ($types_array as $value) {
		
		if ($num_of_types==substr_count($types, "[".$value[0]."]") && strstr($types, "[".$value[0]."]")) {$whattooutput = $value[0];}
		
	}
	
	if ($whattooutput == "") {
		
		if (strstr($types, "[".$types_array[0][0]."]") && strstr($types, "[".$types_array[1][0]."]") && !strstr($types, "[".$types_array[2][0]."]")) {
			
			$whattooutput = "input_output";
			
		} else {
			
			$whattooutput = "mixed";
			
		}
		
		}
	
	return $whattooutput;
	
}

function rpGetNotice($id, $row) {
	
	global $rpConnection;
	global $rpSettings;
	
	$notice_result = $rpConnection->query("SELECT ".rpSanitize($row).", added_clientid, visibility FROM ".$rpSettings->getValue("noticesTable")." WHERE id='".rpSanitize(intval($id))."' LIMIT 1");
	
	if (mysql_num_rows($notice_result)>0) {
		
		if (mysql_result($notice_result, 0, "added_clientid") == $_SESSION["clientID"] || mysql_result($notice_result, 0, "visibility")=="all" || (mysql_result($notice_result, 0, "visibility")=="registered" && $_SESSION["clientID"]>0)) {
		
			return rpUTF8Encode(mysql_result($notice_result, 0, $row));
		
		}
		
	}
	
}

function rpGetNotices($html, $order="by added_datetime ASC", $nohtml="") {
	
	global $rpConnection;
	global $rpSettings;
	
	$notices_result = $rpConnection->query("SELECT id, title, added_datetime, added_clientid FROM ".$rpSettings->getValue("noticesTable")." WHERE added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."' ORDER ".rpSanitize($order));
	
	if (mysql_num_rows($notices_result) > 0) {

		for ($i = 0; $i < mysql_num_rows($notices_result); $i += 1) {
		
			$output = $html;
	
			$output = str_replace("[rp(id)]", mysql_result($notices_result, $i, "id"), $output);
			$output = str_replace("[rp(title)]", rpUTF8Encode(mysql_result($notices_result, $i, "title")), $output);
			
			$output = str_replace("[rp(added_datetime)]", rpFullTime(mysql_result($notices_result, $i, "added_datetime")), $output);
	
			$output = str_replace("[rp(products)]",rpGetNoticeProducts("&bull; [rp(type_title)]: [rp(title)]<br />", mysql_result($notices_result, $i, "id"), false), $output);
	
			echo $output;
			
			
	
		}
		
	} else {echo $nohtml;}
	
}

?>