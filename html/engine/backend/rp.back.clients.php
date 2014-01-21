<?php

function rpIsClientRated($from, $to) {
	
	global $rpConnection;
	global $rpSettings;
	
	$rating_result = $rpConnection->query("SELECT id FROM ".$rpSettings->getValue("ratingsTable")." WHERE published='1' && added_clientid='".rpSanitize(intval($from))."' && to_clientid='".rpSanitize(intval($to))."' LIMIT 1");	
	
	if (mysql_num_rows($rating_result)>0) {return true;} else {return false;}
	
}

function rpGetClientNumOfRatings($id, $all=false) {
	
	global $rpConnection;
	global $rpSettings;
	
	if ($all) {
		$ratings_result = $rpConnection->query("SELECT id FROM ".$rpSettings->getValue("ratingsTable")." WHERE to_clientid='".rpSanitize(intval($id))."'");
	} else {
		$ratings_result = $rpConnection->query("SELECT id FROM ".$rpSettings->getValue("ratingsTable")." WHERE published='1' && rating>=0 && to_clientid='".rpSanitize(intval($id))."'");	
	}
	
	return mysql_num_rows($ratings_result);
	
}

function rpGetClientRatings($html, $id, $all=false) {
	
	global $rpConnection;
	global $rpSettings;
	
	if ($all) {
		$ratings_result = $rpConnection->query("SELECT id, rating, title, content, published, added_clientid, added_datetime FROM ".$rpSettings->getValue("ratingsTable")." WHERE to_clientid='".rpSanitize(intval($id))."'");	
	} else {
		$ratings_result = $rpConnection->query("SELECT id, rating, title, content, published, added_clientid, added_datetime FROM ".$rpSettings->getValue("ratingsTable")." WHERE published='1' && rating>=0 && to_clientid='".rpSanitize(intval($id))."'");
	}	
	
	for ($i = 0; $i < mysql_num_rows($ratings_result); $i += 1) {
	
		$from_result = $rpConnection->query("SELECT name FROM ".$rpSettings->getValue("clientsTable")." WHERE id='".rpSanitize(intval(mysql_result($ratings_result, $i, "added_clientid")))."' LIMIT 1");
	
		$output = $html;
		
		$output = str_replace("[rp(from_name)]", rpUTF8Encode(mysql_result($from_result, 0, "name")), $output);
		
		$output = str_replace("[rp(added_datetime)]", rpFullTime(mysql_result($ratings_result, $i, "added_datetime")), $output);
		
		$output = str_replace("[rp(title)]", rpUTF8Encode(mysql_result($ratings_result, $i, "title")), $output);
		
		if (rpIsAdmin($_SESSION["clientID"])) {
			
			if (mysql_result($ratings_result, $i, "published")==1) {
				
				$output = str_replace("[rp(published)]", "<a href=\"javascript:rpAdminUnpublishRating(".mysql_result($ratings_result, $i, "id").");\" class=\"smallFormButton right\">Piilota</a>", $output);
				
			} else {
			
				$output = str_replace("[rp(published)]", " <span style=\"color:#FF0000;\">(julkaisematon)</span><a href=\"javascript:rpAdminPublishRating(".mysql_result($ratings_result, $i, "id").");\" class=\"smallFormButton right\">Näytä</a>", $output);
				
			}
			
		} else {
			
			$output = str_replace("[rp(published)]", "", $output);
			
		}		
		
		$output = str_replace("[rp(content)]", rpUTF8Encode(mysql_result($ratings_result, $i, "content")), $output);
		
		echo $output;
	
	}
	
}

function rpGetClientImages($html, $id) {
	
	global $rpConnection;
	global $rpSettings;
	
	$client_result = $rpConnection->query("SELECT images FROM ".$rpSettings->getValue("clientsTable")." WHERE id='".rpSanitize(intval($id))."' LIMIT 1");
	
	$runner = 1; $splitter = 0;
	
	if (mysql_num_rows($client_result)>0) {
	
		$images_array = explode("|end|", rpUTF8Encode(mysql_result($client_result, 0, "images")));
	
		foreach ($images_array as $value) {
			
			if ($value != "") {
				
				$image_array = explode("|", $value);

				$output = $html;
				
				$output = str_replace("[rp(id)]", $runner, $output);
				
				$output = str_replace("[rp(checksum)]", md5($value), $output);
				
				$output = str_replace("[rp(filename)]", $image_array[0], $output);
				
				$output = str_replace("[rp(title)]", $image_array[1], $output);
				
				if ($splitter>4) {
					
					$splitter = 0;
					$output = str_replace("class=\"clientImageDiv\"", "class=\"clientImageDiv last\"", $output);
					$output = str_replace("class=\"profileImageDiv\"", "class=\"profileImageDiv last\"", $output);
					
				} else {$splitter += 1;}
										
				$runner += 1;

				echo $output;
				
			}
			
		}
	
	}
	
}

function rpIsAdmin($id) {
	
	global $rpConnection;
	global $rpSettings;
	
	$client_result = $rpConnection->query("SELECT admin FROM ".$rpSettings->getValue("clientsTable")." WHERE id='".rpSanitize(intval($id))."' LIMIT 1");
	
	if (mysql_result($client_result, 0, "admin")==1) {return true;} else {return false;}
	
}

function rpGetOtherClient($id, $row) {
		
	global $rpConnection;
	global $rpSettings;
	
	$client_result = $rpConnection->query("SELECT ".rpSanitize($row)." FROM ".$rpSettings->getValue("clientsTable")." WHERE id='".rpSanitize(intval($id))."' LIMIT 1");
	
	if (mysql_num_rows($client_result)>0) {
		return rpUTF8Encode(mysql_result($client_result, 0, $row));
	}
	
}

function rpGetClient($row) {
	
	global $rpConnection;
	global $rpSettings;
	
	$client_result = $rpConnection->query("SELECT ".rpSanitize($row)." FROM ".$rpSettings->getValue("clientsTable")." WHERE id='".rpSanitize(intval($_SESSION["clientID"]))."' LIMIT 1");
	
	if (mysql_num_rows($client_result)>0) {
		return rpUTF8Encode(mysql_result($client_result, 0, $row));
	}
	
}

function rpGetClientArsenal($html, $id) {
	
	global $rpConnection;
	global $rpSettings;
	
	$client_result = $rpConnection->query("SELECT arsenal FROM ".$rpSettings->getValue("clientsTable")." WHERE id='".rpSanitize(intval($id))."' LIMIT 1");
	
	$runner = 1;
	
	if (mysql_num_rows($client_result)>0) {
				
		$arsenals_array = explode("|end|", rpUTF8Encode(mysql_result($client_result, 0, "arsenal")));
		
		foreach ($arsenals_array as $value) {
			
			if ($value != "") {
				
				$arsenal_array = explode("|", $value);
				
				if ($arsenal_array[0]=="rpClientArsenal") {
				
					$output = $html;
					
					$output = str_replace("[rp(id)]", $runner, $output);
					
					$output = str_replace("[rp(title)]", $arsenal_array[1], $output);
					
					$output = str_replace("[rp(description)]", $arsenal_array[2], $output);
							
					$runner += 1;
				
					echo $output;
				
				}
				
			}
			
		}
		
	}
	
}

function rpGetClientTypes($html, $id, $print=true) {
	
	global $rpConnection;
	global $rpSettings;
	
	$client_result = $rpConnection->query("SELECT types, types2 FROM ".$rpSettings->getValue("clientsTable")." WHERE id='".rpSanitize(intval($id))."' LIMIT 1");
	
	$tooutput = "";
	
	if (mysql_num_rows($client_result)>0) {
		
		$types_array = explode("][", mysql_result($client_result, 0, "types"));
		$types2_array = explode("][", mysql_result($client_result, 0, "types2"));
						
		foreach ($types_array as $type) {

			if (rpCleanBraces($type)!="") {
		
				$output = $html;
				
				$output = str_replace("[rp(title)]",rpGetTypeTitle(rpCleanBraces($type), "baseTypes"), $output);
				
				$tooutput .= $output;		
		
			}
		
		}
		
		foreach ($types2_array as $type) {

			if (rpCleanBraces($type)!="") {
		
				$output = $html;
				
				$output = str_replace("[rp(title)]",rpGetTypeTitle(rpCleanBraces($type), "subTypes"), $output);
				
				$tooutput .= $output;		
		
			}
		
		}
		
	}
	
	if ($print) {echo $tooutput;} else {return $tooutput;}
	
}

?>