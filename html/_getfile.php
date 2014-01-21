<?php include_once("engine/rp.start.php");

if ($_GET["messageid"]>0 && $_GET["checksum"]!="" && $_SESSION["clientID"]) {
	
	$message_result = $rpConnection->query("SELECT files FROM ".$rpSettings->getValue("messagesTable")." WHERE id='".rpSanitize(intval($_GET["messageid"]))."' && (to_clientid LIKE '%[".rpSanitize(intval($_SESSION["clientID"]))."]%' OR added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."') LIMIT 1");
	
	if (mysql_num_rows($message_result)>0) {
		
		$files_array = explode("|end|", rpUTF8Encode(mysql_result($message_result, 0, "files")));
		
		$to_open = "";
		
		foreach ($files_array as $file) {
		
			if ($_GET["checksum"] == md5($file)) {
				
				$file_array = explode("|=|", $file);
				$to_open = $file_array[0];
				
			}
			
		}
		
		if ($to_open != "") {
						
			$finfo = finfo_open();
			$fileinfo = finfo_file($finfo, "files/uploads/".$to_open, FILEINFO_MIME);
			finfo_close($finfo);
						
			header("Content-Type: ".$fileinfo);
			header("Content-Disposition: attachment; filename=".$file_array[1]);
			header("Content-Length: " . filesize("files/uploads/".$file_array[0]));
			ob_clean();
			flush();
			readfile("files/uploads/".$file_array[0]);
			exit;
			
		} else {echo "Tiedostoa ei löytynyt.";}
		
	} else {echo "Viestiä ei löytynyt.";}
	
} else if ($_GET["noticeid"]>0 && $_GET["checksum"] != "" && $_SESSION["clientID"]) {
	
	$notice_result = $rpConnection->query("SELECT files FROM ".$rpSettings->getValue("noticesTable")." WHERE id='".rpSanitize(intval($_GET["noticeid"]))."' && (visibility='all' OR (".rpSanitize(intval($_SESSION["clientID"])).">0 && visibility='registered') OR added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."') LIMIT 1");
	
	if (mysql_num_rows($notice_result)>0) {
		
		$files_array = explode("|end|", rpUTF8Encode(mysql_result($notice_result, 0, "files")));
		
		$to_open = "";
		
		foreach ($files_array as $file) {
		
			if ($_GET["checksum"] == md5($file)) {
				
				$file_array = explode("|=|", $file);
				$to_open = $file_array[0];
				
			}
			
		}
		
		if ($to_open != "") {
						
			$finfo = finfo_open();
			$fileinfo = finfo_file($finfo, "files/uploads/".$to_open, FILEINFO_MIME);
			finfo_close($finfo);
						
			header("Content-Type: ".$fileinfo);
			header("Content-Disposition: attachment; filename=".$file_array[1]);
			header("Content-Length: " . filesize("files/uploads/".$file_array[0]));
			ob_clean();
			flush();
			readfile("files/uploads/".$file_array[0]);
			exit;
			
		} else {echo "Tiedostoa ei löytynyt.";}
		
	} else {echo "Ilmoitusta ei löytynyt.";}
	
}

include_once("engine/rp.end.php"); ?>