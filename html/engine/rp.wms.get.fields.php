<?php include_once("rp.start.php");

if ($_POST["tileX"]>0 && $_POST["tileY"]>0 && $_POST["tileZoom"]>0) {

	$fields_result = $rpConnection->query("SELECT id, pos_x, pos_y, size FROM ".$rpSettings->getValue("fieldsTable")." WHERE (pos_x>='".floatval($_POST["tileX"])."' && pos_y>='".floatval($_POST["tileY"])."' && pos_x<'".floatval($_POST["tileX"]+$_POST["tileZoom"])."' && pos_y<'".floatval($_POST["tileY"]+$_POST["tileZoom"])."') && (visibility='all' OR (".rpSanitize(intval($_SESSION["clientID"])).">0 && visibility='registered') OR added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."') && published='1'");

	for ($i = 0; $i < mysql_num_rows($fields_result); $i += 1) {

		echo "<annotation>
			<id>".mysql_result($fields_result, $i, "id")."</id>
			<x>".mysql_result($fields_result, $i, "pos_x")."</x>
			<y>".mysql_result($fields_result, $i, "pos_y")."</y>
			<type>field</type>
			<image>field</image>
			<size>".rpGetFieldSize(mysql_result($fields_result, $i, "size"))."</size>
			<enabled>true</enabled>
		</annotation>";

	}

}

include_once("rp.end.php"); ?>