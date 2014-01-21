<?php include_once("engine/rp.start.php");

if ($_GET["rpAction"]=="rpRemoveMessage" && $_GET["id"]>0 && $_SESSION["clientID"]) {
	
	// hide message
	
	$message_result = $rpConnection->query("SELECT id FROM ".$rpSettings->getValue("messagesTable")." WHERE id='".rpSanitize(intval($_GET["id"]))."' && (added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."' OR to_clientid LIKE '%[".rpSanitize(intval($_SESSION["clientID"]))."]%') LIMIT 1");
	
	if (mysql_num_rows($message_result)>0) {
		
		if ($rpConnection->query("UPDATE ".$rpSettings->getValue("messagesTable")." SET 
			hide_clientid=concat('[".rpSanitize(intval($_SESSION["clientID"]))."]',hide_clientid)
			 WHERE id='".rpSanitize(intval($_GET["id"]))."' LIMIT 1")) {
			
			echo "SUCCESS";
			
		} else {echo "Virhe viestin poistamisessa.";}
	
	} else {echo "Viestiä ei löytynyt.";}
	
}

if ($_POST["rpAction"]=="rpGetNewMessages" && $_SESSION["clientID"]) {
	
	echo rpGetNumOfMessages($_SESSION["clientID"], 0, true);
	
}

if ($_GET["rpAction"]=="rpSearchMessages" && $_GET["rpSearchString"]!="" && $_SESSION["clientID"]) {
	
	$_GET["rpSearchString"] = substr($_GET["rpSearchString"], 0, 100);
	
	rpGetMessages("<div class=\"messageDiv\" id=\"message_[rp(id)]\"><h2><a onmouseover=\"showInfo('Näytä viesti');\" onmouseout=\"hideInfo();\" href=\"javascript:toggleMessage([rp(id)]);\">[rp(title)]</a><a href=\"javascript:removeMessage([rp(id)]);\" class=\"smallFormButton right last\">Poista</a><a href=\"javascript:changeTab('newmsg', [rp(id)]);\" class='smallFormButton right'>Vastaa</a></h2><div id=\"fullMessageDiv_[rp(id)]\"></div>Lähettäjä: [rp(/from)]<a onmouseover=\"showInfo('Näytä käyttäjän profiili');\" onmouseout=\"hideInfo();\" href=\"javascript:showProfile([rp(from_id)]);\">[rp(from_name)]</a>[rp(from/)] ([rp(added_datetime)])<br />Vastaanottajat: [rp(/to)][rp(to)][rp(to/)]</div>", 0, 0, "by added_datetime DESC", "Hakusanalla ei löytynyt viestejä.", $_GET["rpSearchString"]);
	
}

if ($_GET["rpAction"]=="rpGetMessage" && $_GET["id"]>0 && $_SESSION["clientID"]) {
	
	// get message
	
	$message_result = $rpConnection->query("SELECT message, files, seen_clientid, added_clientid, to_clientid FROM ".$rpSettings->getValue("messagesTable")." WHERE id='".rpSanitize(intval($_GET["id"]))."' && (to_clientid LIKE '%[".rpSanitize(intval($_SESSION["clientID"]))."]%' OR added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."') LIMIT 1");
	
	if (mysql_num_rows($message_result)>0) {
		
		echo rpUTF8Encode(mysql_result($message_result, 0, "message"));
		
		if ($_SESSION["clientID"] != mysql_result($message_result, 0, "added_clientid") && !strstr(mysql_result($message_result, 0, "seen_clientid"), "[".$_SESSION["clientID"]."]")) {

			$rpConnection->query("UPDATE ".$rpSettings->getValue("messagesTable")." SET 
			seen_clientid=concat('[".rpSanitize(intval($_SESSION["clientID"]))."]',seen_clientid)
			 WHERE id='".rpSanitize(intval($_GET["id"]))."' LIMIT 1");

		}
		
		if (rpUTF8Encode(mysql_result($message_result, 0, "files"))!="") {
			
			echo "<div class=\"clear height10\"></div>";
			
			$files_array = explode("|end|", rpUTF8Encode(mysql_result($message_result, 0, "files")));
						
			foreach ($files_array as $file) {
			
				if ($file!="") {
			
					$file_array = explode("|=|", $file);
				
					echo "Liitetiedosto: <a href=\"_getfile.php?messageid=".intval($_GET["id"])."&checksum=".md5($file)."\" target=\"_blank\">".$file_array[1]."</a><br />";
							
				}
			
			}
			
		}
		
		echo "<div class=\"clear height10\"></div>";
		
	} else {echo "Viestiä ei löytynyt.";}
	
}

include_once("engine/rp.end.php"); ?>