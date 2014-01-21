<?php include_once("engine/rp.start.php");

if ($_GET["type"]=="base") {

	$products_result = $rpConnection->query("SELECT id FROM ".$rpSettings->getValue("productsTable")." WHERE published='1' && types LIKE '%[".rpSanitize($_GET["id"])."]%' LIMIT 1");
	
	if (mysql_num_rows($products_result)>0) {

		echo "<select onchange='changeProductOption(".intval($_GET["rid"]).")' class=\"productOptionSelector\" id=\"rpProduct_".intval($_GET["rid"])."\" name=\"rpProduct_".intval($_GET["rid"])."\"><option value=\"0\">Valitse tuote...</option>";

		rpGetProducts("<option value=\"[rp(id)]\" SELECTED>[rp(title)]</option>", $_GET["id"], $_GET["nid"], $_GET["pid"]);
		
		echo "</select>";

	} else {echo "Sisältöä ei löytynyt tyypistä.<div class=\"clear height10\"></div>";}

} else if ($_GET["type"]=="options") {
	
	rpGetProductOptions("<input class=\"css-checkbox\" name=\"rpProductOption_".intval($_GET["rid"])."_".intval($_GET["id"])."_[rp(id)]\" id=\"rpProductOption_".intval($_GET["rid"])."_".intval($_GET["id"])."_[rp(id)]\" type=\"checkbox\" CHECKED /><label class=\"css-label\" for=\"rpProductOption_".intval($_GET["rid"])."_".intval($_GET["id"])."_[rp(id)]\">[rp(title)]</label><div class=\"clear height5\"></div>",
"<input class=\"css-checkbox\" name=\"rpProductOption_".intval($_GET["rid"])."_".intval($_GET["id"])."_[rp(id)]\" id=\"rpProductOption_".intval($_GET["rid"])."_".intval($_GET["id"])."_[rp(id)]\" type=\"checkbox\" CHECKED /><label class=\"css-label\" for=\"rpProductOption_".intval($_GET["rid"])."_".intval($_GET["id"])."_[rp(id)]\">[rp(title)]</label><input class=\"optionText\" type=\"text\" onchange=\"$('#rpProductOption_".intval($_GET["rid"])."_".intval($_GET["id"])."_[rp(id)]').attr('checked', true);\" name=\"rpProductOptionDescription_".intval($_GET["rid"])."_".intval($_GET["id"])."_[rp(id)]\" id=\"rpProductOptionDescription_".intval($_GET["rid"])."_".intval($_GET["id"])."_[rp(id)]\" value=\"[rp(value)]\" /><div class=\"clear height5\"></div>", "[rp(title)]<br /><input class=\"optionText\" type=\"text\" name=\"rpProductDescription_".intval($_GET["rid"])."_".intval($_GET["id"])."_[rp(id)]\" id=\"rpProductDescription_".intval($_GET["rid"])."_".intval($_GET["id"])."_[rp(id)]\" value=\"[rp(value)]\" /><div class=\"clear height5\"></div>", $_GET["id"], $_GET["nid"], $_GET["pid"]);
	
}

include_once("engine/rp.end.php"); ?>