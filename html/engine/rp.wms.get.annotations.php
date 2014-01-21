<?php include_once("rp.start.php");

if ($_POST["tileX"]>0 && $_POST["tileY"]>0 && $_POST["tileZoom"]>0) {

	$notices_result = $rpConnection->query("SELECT id, pos_x, pos_y, value, products, types2, publish_end FROM ".$rpSettings->getValue("noticesTable")." WHERE (pos_x>='".floatval($_POST["tileX"])."' && pos_y>='".floatval($_POST["tileY"])."' && pos_x<'".floatval($_POST["tileX"]+$_POST["tileZoom"])."' && pos_y<'".floatval($_POST["tileY"]+$_POST["tileZoom"])."') && (visibility='all' OR (".rpSanitize(intval($_SESSION["clientID"])).">0 && visibility='registered') OR added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."') && published='1'");

	for ($i = 0; $i < mysql_num_rows($notices_result); $i += 1) {

		if (rpDate(mysql_result($notices_result, $i, "publish_end")) == "" || strtotime(date("Y-m-d"))<strtotime(mysql_result($notices_result, $i, "publish_end"))) {

			echo "<annotation>
				<id>".mysql_result($notices_result, $i, "id")."</id>
				<x>".mysql_result($notices_result, $i, "pos_x")."</x>
				<y>".mysql_result($notices_result, $i, "pos_y")."</y>
				<type>".rpGetNoticeType(mysql_result($notices_result, $i, "products"))."</type>
				<image>"; if (strstr(mysql_result($notices_result, $i, "types2"),"[organic]")) {echo "organic_";} echo rpGetNoticeType(mysql_result($notices_result, $i, "products"))."</image>
				<size>".rpGetNoticeSize(mysql_result($notices_result, $i, "value"))."</size>
				<enabled>true</enabled>
			</annotation>";

		}

	}

}

include_once("rp.end.php"); ?>