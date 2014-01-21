<?php include_once("engine/rp.start.php");

if ($_GET["id"]=="editfield" && $_SESSION["clientID"] && rpIsAlive($_GET["fieldid"], "fields")) {
	
	if (rpGetField($_GET["fieldid"], "added_clientid") == $_SESSION["clientID"]) {
		
		echo "<form name=\"saveFieldForm\" id=\"saveFieldForm\" action=\"http://".$rpSettings->getValue("domain")."/map\" method=\"POST\">
		
		<input type=\"hidden\" name=\"rpCheck\" value=\"".md5("FIELD".intval($_GET["fieldid"]).$rpSettings->getValue("secret").$_SESSION["clientID"])."\" />
		<input type=\"hidden\" name=\"rpAction\" value=\"rpSaveFieldSettings\" />
		<input type=\"hidden\" name=\"rpFieldID\" value=\"".intval($_GET["fieldid"])."\" />
				
		<label for=\"rpFieldTitle\">Pellon nimi: *<input name=\"rpFieldTitle\" id=\"rpFieldTitle\" type=\"text\" class=\"full\" value=\"".rpGetField($_GET["fieldid"], "title")."\" /></label>	
		
		<label for=\"rpFieldDescription\">Pellon tiedot:<textarea name=\"rpFieldDescription\" id=\"rpFieldDescription\" class=\"bigText\">".rpGetField($_GET["fieldid"], "description")."</textarea></label>
		
		<div class=\"clear\"></div>
			
		<div class=\"col_50 left\">
		
			<label for=\"rpFieldVisibility\">Pellon näkyvyys:<select id=\"rpFieldVisibility\" name=\"rpFieldVisibility\">";
			
				rpPrintTypes("<option value=\"[rp(type)]\" SELECTED>[rp(title)]</option>", "visibilityTypes", rpGetField($_GET["fieldid"], "visibility"));
			
			echo "</select></label>	
		
		</div>

		<div class=\"col_50 left\">
	
			<label for=\"rpFieldSize\">Pellon koko (m<sup>2</sup>):<input name=\"rpFieldSize\" id=\"rpFieldSize\" type=\"text\" class=\"full\" value=\"".str_replace(".",",",rpGetField($_GET["fieldid"], "size"))."\" /></label>	
		
		</div>

		<div class=\"clear height5\"></div>
		
		<a href=\"javascript:saveFieldSettings();\" class=\"formButton left\">Tallenna pelto</a>
		
		<a href=\"javascript:positionField(".intval($_GET["fieldid"]).");\" class=\"formButton left\">Muuta pellon paikkaa</a>
		
		<a href=\"javascript:startReDrawingField(".intval($_GET["fieldid"]).");\" class=\"formButton left\">Piirrä pellon rajat</a>
		
		<a href=\"javascript:changeTab('fields');\" class=\"formButton left\">Palaa takaisin</a>
		
		</form>";
	
	} else {echo "notify(\"Peltoa ei löytynyt.\");";}
	
}

if ($_GET["id"]=="addfield" && $_SESSION["clientID"]) {
	
	echo "<form name=\"addFieldForm\" id=\"addFieldForm\" action=\"http://".$rpSettings->getValue("domain")."/map\" method=\"POST\">
	
	<input type=\"hidden\" name=\"rpCheck\" value=\"".md5("FIELD".$rpSettings->getValue("secret").$_SESSION["clientID"])."\" />
	<input type=\"hidden\" name=\"rpAction\" value=\"rpAddField\" />
	
	<input type=\"hidden\" id=\"rpFieldLatitude\" name=\"rpFieldLatitude\" value=\"0\" />
	<input type=\"hidden\" id=\"rpFieldLongitude\" name=\"rpFieldLongitude\" value=\"0\" />
	<input type=\"hidden\" id=\"rpFieldPosX\" name=\"rpFieldPosX\" value=\"0\" />
	<input type=\"hidden\" id=\"rpFieldPosY\" name=\"rpFieldPosY\" value=\"0\" />
			
	<label for=\"rpFieldTitle\">Pellon nimi: *<input name=\"rpFieldTitle\" id=\"rpFieldTitle\" type=\"text\" class=\"full\" /></label>	
	
	<label for=\"rpFieldDescription\">Pellon tiedot:<textarea name=\"rpFieldDescription\" id=\"rpFieldDescription\" class=\"bigText\"></textarea></label>
	
	<div class=\"clear\"></div>
		
	<div class=\"col_50 left\">
	
		<label for=\"rpFieldVisibility\">Pellon näkyvyys:<select id=\"rpFieldVisibility\" name=\"rpFieldVisibility\">";
		
			rpPrintTypes("<option value=\"[rp(type)]\" SELECTED>[rp(title)]</option>", "visibilityTypes", $rpSettings->getValue("fieldDefaultVisibility"));
		
		echo "</select></label>	
	
	</div>

	<div class=\"col_50 left\">
	
		<label for=\"rpFieldSize\">Pellon koko (m<sup>2</sup>):<input name=\"rpFieldSize\" id=\"rpFieldSize\" type=\"text\" class=\"full\" /></label>	
	
	</div>

	<div class=\"clear height5\"></div>
	
	<a href=\"javascript:saveNewFieldSettings();\" class=\"formButton left\">Tallenna pelto</a>
	
	<a href=\"javascript:changeTab('fields');\" class=\"formButton left\">Palaa takaisin</a>
	
	</form>";
		
}

include_once("engine/rp.end.php"); ?>