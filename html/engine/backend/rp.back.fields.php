<?php

function rpGetFieldSize($size) {
	
	global $rpSettings;
	
	$sizes_array = $rpSettings->getValue("fieldSizePercentages");
	
	$percentual = 50;
	
	foreach ($sizes_array as $size_array) {
		
		if ($size>$size_array[0]) {$percentual = $size_array[1];}
		
	}	
		
	return $percentual;
	
}

function rpGetField($id, $row) {
	
	global $rpConnection;
	global $rpSettings;
	
	$field_result = $rpConnection->query("SELECT ".rpSanitize($row).", added_clientid, visibility FROM ".$rpSettings->getValue("fieldsTable")." WHERE id='".rpSanitize(intval($id))."' LIMIT 1");
	
	if (mysql_num_rows($field_result)>0) {
		
		if (mysql_result($field_result, 0, "added_clientid") == $_SESSION["clientID"] || mysql_result($field_result, 0, "visibility")==0 || (mysql_result($field_result, 0, "visibility")==1 && $_SESSION["clientID"])) {
		
			return rpUTF8Encode(mysql_result($field_result, 0, $row));
		
		}
		
	}
	
}

function rpGetFields($html, $order="by added_datetime ASC", $nohtml="") {
	
	global $rpConnection;
	global $rpSettings;
	
	$fields_result = $rpConnection->query("SELECT id, size, title, added_datetime, added_clientid FROM ".$rpSettings->getValue("fieldsTable")." WHERE added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."' ORDER ".rpSanitize($order));
	
	if (mysql_num_rows($fields_result) > 0) {

		for ($i = 0; $i < mysql_num_rows($fields_result); $i += 1) {
		
			$output = $html;
	
			$output = str_replace("[rp(id)]", mysql_result($fields_result, $i, "id"), $output);
			$output = str_replace("[rp(title)]", rpUTF8Encode(mysql_result($fields_result, $i, "title")), $output);
			$output = str_replace("[rp(size)]", rpArea(mysql_result($fields_result, $i, "size")), $output);
			
			$output = str_replace("[rp(added_datetime)]", rpFullTime(mysql_result($fields_result, $i, "added_datetime")), $output);
	
			echo $output;
	
		}
		
	} else {echo $nohtml;}
	
}

?>