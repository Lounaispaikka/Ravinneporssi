<?php

function rpAdminGetFirstContent() {
	
	global $rpConnection;
	global $rpSettings;
	
	$content_result = $rpConnection->query("SELECT id FROM ".$rpSettings->getValue("contentTable")." ORDER by priority ASC LIMIT 1");
	
	return mysql_result($content_result, 0, "id");
	
}

function rpGetContent($html, $id) {
	
	global $rpConnection;
	global $rpSettings;
	
	$help_result = $rpConnection->query("SELECT name, title, content FROM ".$rpSettings->getValue("contentTable")." WHERE id='".rpSanitize(intval($id))."' && published='1' LIMIT 1");
			
	if (mysql_num_rows($help_result)>0) {
		
		$html = str_replace("[rp(name)]",rpUTF8Encode(mysql_result($help_result, 0, "name")),$html);
		$html = str_replace("[rp(title)]",rpUTF8Encode(mysql_result($help_result, 0, "title")),$html);
		$html = str_replace("[rp(content)]",rpUTF8Encode(mysql_result($help_result, 0, "content")),$html);
		
		echo $html;
		
	}
	
}

function rpGetContents($html, $type, $selected=0, $all=false) {
	
	global $rpConnection;
	global $rpSettings;
	
	if ($all) {
		$helps_result = $rpConnection->query("SELECT id, name, title, content FROM ".$rpSettings->getValue("contentTable")." ORDER by priority ASC");
	} else {
		$helps_result = $rpConnection->query("SELECT id, name, title, content FROM ".$rpSettings->getValue("contentTable")." WHERE type='".rpSanitize($type)."' && published='1' ORDER by priority ASC");
	}

	if (mysql_num_rows($helps_result)>0) {

		for ($i = 0; $i < mysql_num_rows($helps_result); $i += 1) {
		
			$output = $html;
		
			$output = str_replace("[rp(id)]",mysql_result($helps_result, $i, "id"),$output);
			$output = str_replace("[rp(name)]",rpUTF8Encode(mysql_result($helps_result, $i, "name")),$output);
			$output = str_replace("[rp(title)]",rpUTF8Encode(mysql_result($helps_result, $i, "title")),$output);
			$output = str_replace("[rp(content)]",rpUTF8Encode(mysql_result($helps_result, $i, "content")),$output);
			
			if (mysql_result($helps_result, $i, "id")!=$selected) {$output = str_replace(" SELECTED","",$output);}
			
			echo $output;
		
		}
		
	}

}

function rpGetFirstContent($type) {
	
	global $rpConnection;
	global $rpSettings;
	
	$content_result = $rpConnection->query("SELECT id FROM ".$rpSettings->getValue("contentTable")." WHERE type='".rpSanitize($type)."' && published='1' ORDER by priority ASC LIMIT 1");
	
	if (mysql_num_rows($content_result)>0) {
	
		return mysql_result($content_result, 0, "id");
	
	}

}

?>