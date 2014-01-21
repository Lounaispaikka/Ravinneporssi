<?php

function rpGetMessage($html, $id) {
	
	global $rpConnection;
	global $rpSettings;
	
	$message_result = $rpConnection->query("SELECT id, parent, title, message, files, added_datetime, added_clientid, to_clientid, seen_clientid FROM ".$rpSettings->getValue("messagesTable")." WHERE id='".rpSanitize(intval($id))."' && (added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."' OR to_clientid LIKE '%[".rpSanitize(intval($_SESSION["clientID"]))."]%') LIMIT 1");
	
	if (mysql_num_rows($message_result)>0) {
		
		if ($_SESSION["clientID"] != mysql_result($message_result, 0, "added_clientid") && !strstr(mysql_result($message_result, 0, "seen_clientid"), "[".$_SESSION["clientID"]."]")) {

			$rpConnection->query("UPDATE ".$rpSettings->getValue("messagesTable")." SET 
			seen_clientid=concat('[".rpSanitize(intval($_SESSION["clientID"]))."]',seen_clientid)
			 WHERE id='".rpSanitize(intval($id))."' LIMIT 1");

		}
		
		$files = "";
		
		if (rpUTF8Encode(mysql_result($message_result, 0, "files"))!="") {
			
			$files .= "<div class=\"clear height10\"></div>";
			
			$files_array = explode("|end|", rpUTF8Encode(mysql_result($message_result, 0, "files")));
						
			foreach ($files_array as $file) {
			
				if ($file!="") {
			
					$file_array = explode("|=|", $file);
				
					$files .= "Liitetiedosto: <a href=\"_getfile.php?messageid=".intval($id)."&checksum=".md5($file)."\" target=\"_blank\">".$file_array[1]."</a><br />";
							
				}
			
			}
			
		}
		
		$html = str_replace("[rp(title)]", rpUTF8Encode(mysql_result($message_result, 0, "title")), $html);
		$html = str_replace("[rp(message)]", rpUTF8Encode(mysql_result($message_result, 0, "message")), $html);
		$html = str_replace("[rp(files)]", $files, $html);
		
		echo $html;
		
	}
	
}

function rpGetMessages($html, $toid, $fromid, $order="by added_datetime ASC", $nohtml="", $search="") {
	
	global $rpConnection;
	global $rpSettings;
	
	if ($search!="") {
		
		$messages_result = $rpConnection->query("SELECT id, parent, title, files, added_datetime, added_clientid, to_clientid, seen_clientid FROM ".$rpSettings->getValue("messagesTable")." WHERE ((to_clientid LIKE '%[".rpSanitize(intval($_SESSION["clientID"]))."]%' && hide_clientid NOT LIKE '%[".rpSanitize(intval($_SESSION["clientID"]))."]%') OR (added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."' && hide_clientid NOT LIKE '%[".rpSanitize(intval($_SESSION["clientID"]))."]%')) && (title LIKE '%".rpSanitize($search)."%' OR message LIKE '%".rpSanitize($search)."%') ORDER ".rpSanitize($order));

	} else {
	
		if ($toid>0) {
		
			if ($new) {
	
				$messages_result = $rpConnection->query("SELECT id, parent, title, files, added_datetime, added_clientid, to_clientid, seen_clientid FROM ".$rpSettings->getValue("messagesTable")." WHERE to_clientid LIKE '%[".rpSanitize(intval($toid))."]%' && hide_clientid NOT LIKE '%[".rpSanitize(intval($toid))."]%' && seen_clientid NOT LIKE '%[".rpSanitize(intval($toid))."]%' ORDER ".rpSanitize($order));
		
			} else {
				
				$messages_result = $rpConnection->query("SELECT id, parent, title, files, added_datetime, added_clientid, to_clientid, seen_clientid FROM ".$rpSettings->getValue("messagesTable")." WHERE to_clientid LIKE '%[".rpSanitize(intval($toid))."]%' && hide_clientid NOT LIKE '%[".rpSanitize(intval($toid))."]%' ORDER ".rpSanitize($order));
				
			}
		
		} else if ($fromid>0) {
	
			if ($new) {
	
				$messages_result = $rpConnection->query("SELECT id, parent, title, files, added_datetime, added_clientid, to_clientid, seen_clientid FROM ".$rpSettings->getValue("messagesTable")." WHERE added_clientid='".rpSanitize(intval($fromid))."' && hide_clientid NOT LIKE '%[".rpSanitize(intval($fromid))."]%' && seen_clientid NOT LIKE '%[".rpSanitize(intval($fromid))."]%' ORDER ".rpSanitize($order));
		
			} else {
	
				$messages_result = $rpConnection->query("SELECT id, parent, title, files, added_datetime, added_clientid, to_clientid, seen_clientid FROM ".$rpSettings->getValue("messagesTable")." WHERE added_clientid='".rpSanitize(intval($fromid))."' && hide_clientid NOT LIKE '%[".rpSanitize(intval($fromid))."]%' ORDER ".rpSanitize($order));
				
			}
			
		}
	
	}
	
	if (mysql_num_rows($messages_result) > 0) {

		for ($i = 0; $i < mysql_num_rows($messages_result); $i += 1) {
	
			$from_result = $rpConnection->query("SELECT name FROM ".$rpSettings->getValue("clientsTable")." WHERE id='".rpSanitize(intval(mysql_result($messages_result, $i, "added_clientid")))."' LIMIT 1");
			
			$to_result = $rpConnection->query("SELECT id, name FROM ".$rpSettings->getValue("clientsTable")." WHERE '".rpSanitize(mysql_result($messages_result, $i, "to_clientid"))."' LIKE CONCAT('%[', id, ']%')");
	
			$output = $html;
	
			$newstring = "";
			
			if (mysql_result($messages_result, $i, "added_clientid") != $_SESSION["clientID"] && !strstr(mysql_result($messages_result, $i, "seen_clientid"), "[".$_SESSION["clientID"]."]")) {
				
				$newstring = "<span style=\"color: #FFFFFF; font-weight: 700;\">&#9733;</span> ";
				
			}
	
			$output = str_replace("[rp(id)]", mysql_result($messages_result, $i, "id"), $output);
			$output = str_replace("[rp(title)]", $newstring.rpUTF8Encode(mysql_result($messages_result, $i, "title")), $output);
			
			
			$output = str_replace("[rp(added_datetime)]", rpFullTime(mysql_result($messages_result, $i, "added_datetime")), $output);
	
			if (mysql_num_rows($from_result)>0) {
				
				$output = str_replace("[rp(from_name)]", rpUTF8Encode(mysql_result($from_result, 0, "name")), $output);
				$output = str_replace("[rp(from_id)]", mysql_result($messages_result, $i, "added_clientid"), $output);
				
				$output = str_replace("[rp(/from)]", "", $output);
				$output = str_replace("[rp(from/)]", "", $output);
				
			} else {
				
				$output = str_replace("[rp(/from)]".rpGetBetween($output, "[rp(/from)]", "[rp(from/)]")."[rp(from/)]", "Profiili on poistettu", $output);
				
			}
	
			if (mysql_num_rows($to_result)>0) {
				
				$to_string = "";
				
				for ($a = 0; $a < mysql_num_rows($to_result); $a += 1) {
				
					$to_string .= "<a onmouseover=\"showInfo('Näytä käyttäjän profiili');\" onmouseout=\"hideInfo();\" href=\"javascript:showProfile(".mysql_result($to_result, $a, "id").");\">".rpUTF8Encode(mysql_result($to_result, $a, "name"))."</a>, ";
					
				}
				
				$to_string = substr($to_string, 0, -2)." ";
				
				$output = str_replace("[rp(to)]", $to_string, $output);
				
				$output = str_replace("[rp(/to)]", "", $output);
				$output = str_replace("[rp(to/)]", "", $output);
				
				if (mysql_num_rows($to_result)==1) {
					
					$output = str_replace("Vastaanottajat:","Vastaanottaja:",$output);
					
				}
				
			} else {
				
				$output = str_replace("[rp(/to)]".rpGetBetween($output, "[rp(/to)]", "[rp(to/)]")."[rp(to/)]", "Profiili on poistettu", $output);
				
			}

			echo $output;
	
		}
		
	} else {echo $nohtml;}
	
}

function rpGetNumOfMessages($toid, $fromid, $new=false) {
	
	global $rpConnection;
	global $rpSettings;
	
	if ($toid>0) {
	
		if ($new) {

			$messages_result = $rpConnection->query("SELECT id FROM ".$rpSettings->getValue("messagesTable")." WHERE to_clientid LIKE '%[".rpSanitize(intval($toid))."]%' && hide_clientid NOT LIKE '%[".rpSanitize(intval($toid))."]%' && seen_clientid NOT LIKE '%[".rpSanitize(intval($toid))."]%'");
	
		} else {
			
			$messages_result = $rpConnection->query("SELECT id FROM ".$rpSettings->getValue("messagesTable")." WHERE to_clientid LIKE '%[".rpSanitize(intval($toid))."]%' && hide_clientid NOT LIKE '%[".rpSanitize(intval($toid))."]%'");
			
		}
	
	} else if ($fromid>0) {
		
		if ($new) {

			$messages_result = $rpConnection->query("SELECT id FROM ".$rpSettings->getValue("messagesTable")." WHERE added_clientid='".rpSanitize(intval($fromid))."' && hide_clientid NOT LIKE '%[".rpSanitize(intval($fromid))."]%' && seen_clientid NOT LIKE '%[".rpSanitize(intval($fromid))."]%'");
	
		} else {
			
			$messages_result = $rpConnection->query("SELECT id FROM ".$rpSettings->getValue("messagesTable")." WHERE added_clientid='".rpSanitize(intval($fromid))."' && hide_clientid NOT LIKE '%[".rpSanitize(intval($fromid))."]%'");
			
		}
		
	}

	return mysql_num_rows($messages_result);
	
}

?>