<?php include_once("engine/rp.start.php");

if ($_POST["rpReDrawField"]>0 && $_POST["rpAction"]=="rpSaveFieldDrawing" && $_SESSION["clientID"]) {

	$field_result = $rpConnection->query("SELECT id FROM ".$rpSettings->getValue("fieldsTable")." WHERE id='".rpSanitize(intval($_POST["rpReDrawField"]))."' && added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."' LIMIT 1");
	
	if ($_POST["rpFieldLatitude"]>0 && $_POST["rpFieldLongitude"]>0 && $_POST["rpFieldPosX"]>0 && $_POST["rpFieldPosY"]) {

		if (mysql_num_rows($field_result)>0) {
			
			if ($rpConnection->query("UPDATE ".$rpSettings->getValue("fieldsTable")." SET 
				latitude='".rpSanitize(floatval($_POST["rpFieldLatitude"]))."',
				longitude='".rpSanitize(floatval($_POST["rpFieldLongitude"]))."',
				pos_x='".rpSanitize(floatval($_POST["rpFieldPosX"]))."',
				pos_y='".rpSanitize(floatval($_POST["rpFieldPosY"]))."',
				size='".rpSanitize($_POST["rpFieldArea"])."',
				polygon='".rpSanitize($_POST["rpField"])."',				
				modified_datetime='".date("Y-m-d H:i:s")."',
				modified_ip='".rpSanitize(rpGetIP())."',
				modified_userid='0'
				 WHERE id='".rpSanitize(intval($_POST["rpReDrawField"]))."' LIMIT 1")) {
				
				echo "SUCCESS";
				
			} else {echo "Ongelma pellon rajojen tallentamisessa.";}
		
		} else {echo "Ongelma pellon rajojen tallentamisessa.";}
		
	} else {echo "Peltoa ei löytynyt.";}

}

if ($_POST["rpField"]>0 && $_POST["rpAction"]=="rpUpdateFieldPosition" && $_SESSION["clientID"]) {

	$field_result = $rpConnection->query("SELECT id FROM ".$rpSettings->getValue("fieldsTable")." WHERE id='".rpSanitize(intval($_POST["rpField"]))."' && added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."' LIMIT 1");
	
	if ($_POST["rpFieldLatitude"]>0 && $_POST["rpFieldLongitude"]>0 && $_POST["rpFieldPosX"]>0 && $_POST["rpFieldPosY"]) {

		if (mysql_num_rows($field_result)>0) {
			
			if ($rpConnection->query("UPDATE ".$rpSettings->getValue("fieldsTable")." SET 
				latitude='".rpSanitize(floatval($_POST["rpFieldLatitude"]))."',
				longitude='".rpSanitize(floatval($_POST["rpFieldLongitude"]))."',
				pos_x='".rpSanitize(floatval($_POST["rpFieldPosX"]))."',
				pos_y='".rpSanitize(floatval($_POST["rpFieldPosY"]))."',
				modified_datetime='".date("Y-m-d H:i:s")."',
				modified_ip='".rpSanitize(rpGetIP())."',
				modified_userid='0'
				 WHERE id='".rpSanitize(intval($_POST["rpField"]))."' LIMIT 1")) {
				
				echo "SUCCESS";
				
			} else {echo "Ongelma pellon tallentamisessa.";}
		
		} else {echo "Ongelma pellon tallentamisessa.";}
		
	} else {echo "Peltoa ei löytynyt.";}

}

if ($_POST["rpFieldTitle"]!="" && $_POST["rpAction"]=="rpSaveFieldSettings" && $_SESSION["clientID"]) {
	
	$_POST["rpFieldTitle"] = substr($_POST["rpFieldTitle"],0,200);
	$_POST["rpFieldDescription"] = substr($_POST["rpFieldDescription"],0,1000);
	$_POST["rpFieldVisibility"] = substr($_POST["rpFieldVisibility"],0,100);
	
	// save field settings
	
	if ($_POST["rpCheck"]==md5("FIELD".intval($_POST["rpFieldID"]).$rpSettings->getValue("secret").$_SESSION["clientID"])) {
		
		if (rpGetField($_POST["rpFieldID"], "added_clientid")==$_SESSION["clientID"]) {
			
			if ($rpConnection->query("UPDATE ".$rpSettings->getValue("fieldsTable")." SET 
					title='".rpSanitize($_POST["rpFieldTitle"])."',
					description='".rpSanitize($_POST["rpFieldDescription"])."',
					visibility='".rpSanitize($_POST["rpFieldVisibility"])."',
					size='".rpSanitize(floatval(str_replace(",",".",$_POST["rpFieldSize"])))."',				
					modified_datetime='".date("Y-m-d H:i:s")."',
					modified_ip='".rpSanitize(rpGetIP())."',
					modified_userid='0'
					 WHERE id='".rpSanitize(intval($_POST["rpFieldID"]))."' LIMIT 1")) {
			
			echo "SUCCESS";
			
			} else {echo "Ongelma pellon tallentamisessa.";}
			
		} else {echo "Sinulla ei ole oikeuksia muokata tätä peltoa.";}
		
	} else {echo "Ongelma pellon tallentamisessa.";}
	
}

if ($_POST["rpField"]>0 && $_POST["rpAction"]=="rpRemoveField" && $_SESSION["clientID"]) {

	// remove field

	$field_result = $rpConnection->query("SELECT id FROM ".$rpSettings->getValue("fieldsTable")." WHERE id='".rpSanitize(intval($_POST["rpField"]))."' && added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."' LIMIT 1");

	if (mysql_num_rows($field_result)>0) {
		
		if ($rpConnection->query("DELETE FROM ".$rpSettings->getValue("fieldsTable")." WHERE id='".rpSanitize(intval($_POST["rpField"]))."'")) {
			
			echo "SUCCESS";
			
		} else {echo "Ongelma pellon poistamisessa.";}
		
	} else {echo "Peltoa ei löytynyt.";}

}

if ($_POST["rpField"]>0 && $_POST["rpAction"]=="rpGetFieldPolygon") {

	// get field polygon

	$field_result = $rpConnection->query("SELECT polygon FROM ".$rpSettings->getValue("fieldsTable")." WHERE id='".rpSanitize(intval($_POST["rpField"]))."' &&  (added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."' OR visibility='all' OR (".rpSanitize(intval($_SESSION["clientID"])).">0 && visibility='registered')) LIMIT 1");

	if (mysql_num_rows($field_result)>0) {
		
		echo mysql_result($field_result, 0, "polygon");
		
	}

}

if ($_POST["rpField"]>0 && $_POST["rpAction"]=="rpGetField" && $_SESSION["clientID"]) {

	// get field

	$field_result = $rpConnection->query("SELECT title, size, latitude, longitude FROM ".$rpSettings->getValue("fieldsTable")." WHERE id='".rpSanitize(intval($_POST["rpField"]))."' &&  (added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."' OR visibility='all' OR (".rpSanitize(intval($_SESSION["clientID"])).">0 && visibility='registered')) LIMIT 1");

	if (mysql_num_rows($field_result)>0) {
		
		echo rpUTF8Encode(mysql_result($field_result, 0, "title"))."|break|".mysql_result($field_result, 0, "size")."|break|".mysql_result($field_result, 0, "latitude")."|break|".mysql_result($field_result, 0, "longitude");
		
	}

}

if ($_POST["rpFieldTitle"]!="" && $_POST["rpField"]!="" && $_POST["rpFieldArea"]>0 && $_POST["rpAction"]=="rpSaveField" && $_SESSION["clientID"]) {

	// save field

	$_POST["rpFieldTitle"] = substr($_POST["rpFieldTitle"],0,200);

	$field_result = $rpConnection->query("SELECT id FROM ".$rpSettings->getValue("fieldsTable")." WHERE polygon='".rpSanitize($_POST["rpField"])."' &&  added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."' LIMIT 1");

	if (mysql_num_rows($field_result)<1) {

		$nextID = rpGetNextID("fields");
	
		if ($rpConnection->query("INSERT INTO ".$rpSettings->getValue("fieldsTable")." (".$rpSettings->getValue("fieldsTableStructure").") VALUES (
			'".rpSanitize($nextID)."',
			'0',
			'',
			'".rpSanitize(floatval($_POST["rpFieldLatitude"]))."',
			'".rpSanitize(floatval($_POST["rpFieldLongitude"]))."',
			'".rpSanitize(floatval($_POST["rpFieldX"]))."',
			'".rpSanitize(floatval($_POST["rpFieldY"]))."',
			'".rpSanitize($_POST["rpFieldArea"])."',
			'".rpSanitize($_POST["rpField"])."',
			'".rpSanitize($_POST["rpFieldTitle"])."',
			'',
			'".rpSanitize($rpSettings->getValue("fieldDefaultVisibility"))."',
			'".date("Y-m-d H:i:s")."',
			'".rpSanitize(rpGetIP())."',
			'".rpSanitize($_SESSION["clientID"])."',
			'',
			'',
			'',
			'".rpSanitize($nextID)."',
			'1')")) {
			
			echo "SUCCESS";
			
		} else {echo "Ongelma pellon tallentamisessa.";}

	} else {echo "Pelto on jo tallennettu.";}

}

if ($_POST["rpAction"]=="rpAddField" && $_SESSION["clientID"]) {

	if ($_POST["rpFieldTitle"]!="" && floatval($_POST["rpFieldLatitude"])>0 && floatval($_POST["rpFieldLongitude"])>0 && floatval($_POST["rpFieldPosX"])>0 && floatval($_POST["rpFieldPosY"])>0) {

		// add field

		$_POST["rpFieldTitle"] = substr($_POST["rpFieldTitle"],0,200);

		$nextID = rpGetNextID("fields");
	
		if ($rpConnection->query("INSERT INTO ".$rpSettings->getValue("fieldsTable")." (".$rpSettings->getValue("fieldsTableStructure").") VALUES (
			'".rpSanitize($nextID)."',
			'0',
			'',
			'".rpSanitize(floatval($_POST["rpFieldLatitude"]))."',
			'".rpSanitize(floatval($_POST["rpFieldLongitude"]))."',
			'".rpSanitize(floatval($_POST["rpFieldPosX"]))."',
			'".rpSanitize(floatval($_POST["rpFieldPosY"]))."',
			'".rpSanitize(floatval(str_replace(",",".",$_POST["rpFieldSize"])))."',
			'',
			'".rpSanitize($_POST["rpFieldTitle"])."',
			'".rpSanitize($_POST["rpFieldDescription"])."',
			'".rpSanitize($_POST["rpFieldVisibility"])."',
			'".date("Y-m-d H:i:s")."',
			'".rpSanitize(rpGetIP())."',
			'".rpSanitize($_SESSION["clientID"])."',
			'',
			'',
			'',
			'".rpSanitize($nextID)."',
			'1')")) {
			
			echo "SUCCESS";
			
		} else {echo "Ongelma pellon tallentamisessa.";}

	} else {echo "Puuttuvia kohtia lomakkeessa.";}

}

include_once("engine/rp.end.php"); ?>