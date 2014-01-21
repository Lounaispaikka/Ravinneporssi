<?php include_once("rp.start.php");

if (intval($_GET["id"])>0) {

	$field_result = $rpConnection->query("SELECT id, title, latitude, longitude, description, size, added_clientid FROM ".$rpSettings->getValue("fieldsTable")." WHERE id='".rpSanitize(intval($_GET["id"]))."' &&  (added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."' OR visibility='all' OR (".rpSanitize(intval($_SESSION["clientID"])).">0 && visibility='registered')) LIMIT 1");
		
	if (mysql_num_rows($field_result)>0) {
	
		echo "<h1><span class=\"floatleft\" style=\"margin-right: 10px;\">".rpUTF8Encode(mysql_result($field_result, 0, "title"))."</span>";
		
		rpGetClientTrades("<img onmouseover=\"showInfo('[rp(trade_title)]');\" onmouseout=\"hideInfo();\" class=\"icon floatleft\" src=\"graphics/icons/icon_[rp(trade_type)].png\">", mysql_result($field_result, 0, "added_clientid"));
		
		echo "<a href=\"javascript:closeFormContainer();\"><img onmouseover=\"showInfo('Sulje tiedot');\" onmouseout=\"hideInfo();\" class=\"closeButton\" src=\"graphics/buttons/button_close.png\" alt=\"Sulje\" /></a></h1>
		
		<div class=\"clear height5\"></div>

		<div class=\"sideButtons\">";

			if (mysql_result($field_result, 0, "added_clientid") != $_SESSION["clientID"] && strstr(rpGetOtherClient(mysql_result($field_result, 0, "added_clientid"), "contact_via"),"[rpmail]") && $_SESSION["clientID"]) {
				
				echo "<a href=\"javascript:openPage('messages',".mysql_result($field_result, 0, "added_clientid").");\"><img onmouseover=\"showInfo('Lähetä yksityisviesti');\" onmouseout=\"hideInfo();\" class=\"sideButton\" src=\"graphics/buttons/smallbutton_message.png\" /></a><br />";
				
			}
		
			if ($_SESSION["clientID"]) {
		
				echo "<a href=\"javascript:getMapDirections(".mysql_result($field_result, 0, "latitude").",".mysql_result($field_result, 0, "longitude").");\"><img onmouseover=\"showInfo('Hae reittiohjeet');\" onmouseout=\"hideInfo();\" class=\"sideButton\" src=\"graphics/buttons/smallbutton_car.png\" /></a>";
		
			}
		
		echo "</div>

		<h2 style=\"margin-top: 5px;\">Pinta-ala: ".rpArea(mysql_result($field_result, 0, "size"))."</h2>
		Omistaja: <a href=\"javascript:showProfile(".mysql_result($field_result, 0, "added_clientid").");\">".rpGetOtherClient(mysql_result($field_result, 0, "added_clientid"), "name")."</a>

		<div class=\"clear height5\"></div>";
				
		if (mysql_result($field_result, 0, "added_clientid") == $_SESSION["clientID"]) {echo "<a href=\"javascript:openMapField(".mysql_result($field_result, 0, "id").");\" class=\"smallFormButton left\">Muokkaa</a>";}

	} else {echo "Sinulla ei ole oikeuksia tarkastella tätä peltoa.";}

}

include_once("rp.end.php"); ?>