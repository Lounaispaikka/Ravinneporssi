<?php

if ($_SESSION["clientID"]) {
	
	echo "<h1>Oma profiili<a href=\"javascript:closePage();\" class=\"formButton right\" style=\"margin-right: 18px;\">Sulje</a></h1>
	
	<script>
	
	var userState = \"".rpGetClient("state")."\";
	var userCity = \"".rpGetClient("city")."\";
	var userPostalcode = \"".rpGetClient("postalcode")."\";
	
	if (userCity!=\"\") {listCities(userState);}
	
	</script>
	
	<form id=\"saveSettingsForm\" name=\"saveSettingsForm\" action=\"http://".$rpSettings->getValue("domain")."/map\" method=\"POST\" enctype=\"multipart/form-data\">
		
	<input type=\"hidden\" name=\"rpAction\" value=\"rpSaveSettings\" />
	<input type=\"hidden\" name=\"rpCheck\" value=\"".md5("SETTINGS".$rpSettings->getValue("secret").$_SESSION["clientID"])."\" />
	
	<h2>Yhteystiedot</h2>
	
	<div class=\"col_50 left\">
	
		<label for=\"rpClientEmail\">Sähköpostiosoite:<input name=\"rpClientEmail\" id=\"rpClientEmail\" type=\"text\" value=\"".rpGetClient("email")."\" /></label>	
	
	</div>
	
	<div class=\"col_50 left\">
	
		<label for=\"rpClientName\">Nimi:<input name=\"rpClientName\" id=\"rpClientName\" type=\"text\" value=\"".rpGetClient("name")."\" /></label>	
	
	</div>
	
	<div class=\"clear\"></div>
	
	<div class=\"col_50 left\">
	
		<label for=\"rpClientPassword_1\">Salasana:<input name=\"rpClientPassword_1\" id=\"rpClientPassword_1\" type=\"password\" /></label>	
	
	</div>
	
	<div class=\"col_50 left\">
	
		<label for=\"rpClientPassword_2\">Salasana uudestaan:<input name=\"rpClientPassword_2\" id=\"rpClientPassword_2\" type=\"password\" /></label>	
	
	</div>
	
	<div class=\"clear\"></div>
	
	<div class=\"col_50 left\">
	
		<label for=\"rpClientCompany\">Yrityksen/tilan nimi:<input name=\"rpClientCompany\" id=\"rpClientCompany\" type=\"text\" value=\"".rpGetClient("company")."\" /></label>	
	
	</div>
	
	<div class=\"col_50 left\">
	
		<label for=\"rpClientBIC\">Y-tunnus/tilatunnus:<input name=\"rpClientBIC\" id=\"rpClientBIC\" type=\"text\" value=\"".rpGetClient("bic")."\" /></label>	
	
	</div>
	
	<div class=\"clear\"></div>
	
	<div class=\"col_50 left\">
	
		<label for=\"rpClientAddress_1\">Osoite:<input name=\"rpClientAddress_1\" onkeypress=\"javascript:defineHomeWithAddress();\" id=\"rpClientAddress_1\" type=\"text\" value=\"".rpGetClient("address_1")."\" /></label>	
	
	</div>
	
	<div class=\"col_50 left\">
	
		<label for=\"rpClientAddress_2\">Yrityksen/tilan osoite:<input name=\"rpClientAddress_2\" id=\"rpClientAddress_2\" type=\"text\" value=\"".rpGetClient("address_2")."\" /></label>	
	
	</div>
	
	<div class=\"clear\"></div>
	
	<div class=\"col_50 left\">
	
		<label for=\"rpClientState\">Maakunta:<select onchange=\"listCities(this.value);\" id=\"rpClientState\" name=\"rpClientState\">";
		
			rpGetStates("<option value=\"[rp(title)]\" SELECTED>[rp(title)]</option>", rpGetClient("state"));
		
		echo "</select></label>	
	
	</div>
	
	<div class=\"col_50 left\">
	
		<label for=\"rpClientCity\">Paikkakunta:<select onchange=\"listPostalcodes(this.value);\" id=\"rpClientCity\" name=\"rpClientCity\"></select></label>	
	
	</div>
	
	<div class=\"clear\"></div>
	
	<div class=\"col_50 left\">
	
		<label for=\"rpClientPostalcode\">Postinumero:<select id=\"rpClientPostalcode\" name=\"rpClientPostalcode\"></select></label>	
	
	</div>
	
	<div class=\"col_50 left\">
	
		<label for=\"rpClientPhonenumber\">Puhelinnumero:<input name=\"rpClientPhonenumber\" id=\"rpClientPhonenumber\" type=\"text\" value=\"".rpGetClient("phonenumber")."\" /></label>	
	
	</div>
	
	<div class=\"clear\"></div>
	
	<div class=\"col_50 left\">
	
		<label for=\"rpClientFax\">Fax:<input name=\"rpClientFax\" id=\"rpClientFax\" type=\"text\" value=\"".rpGetClient("fax")."\" /></label>	
	
	</div>
	
	<div class=\"col_50 left\">
	
		<label for=\"rpClientGSM\">Matkapuhelinnumero:<input name=\"rpClientGSM\" id=\"rpClientGSM\" type=\"text\" value=\"".rpGetClient("gsm")."\" /></label>	
	
	</div>
	
	<div class=\"clear\"></div>
	
	<div class=\"col_50 left\">
	
	<label for=\"rpClientDescription\">Lisätiedot:<textarea name=\"rpClientDescription\" id=\"rpClientDescription\">".rpGetClient("description")."</textarea></label>
	
	</div>
	
	<div class=\"col_50 left\">
	
		<label for=\"rpClientVisibility\">Profiilin näkyvyys:<select id=\"rpClientVisibility\" name=\"rpClientVisibility\">";
		
			rpPrintTypes("<option value=\"[rp(type)]\" SELECTED>[rp(title)]</option>", "visibilityTypes", rpGetClient("visibility"));
		
		echo "</select></label>
		
		<label for=\"rpClientStartupLocation\">Palaa kirjauduttaessa:<select id=\"rpClientStartupLocation\" name=\"rpClientStartupLocation\">";
		
			rpPrintTypes("<option value=\"[rp(type)]\" SELECTED>[rp(title)]</option>", "startUpLocationTypes", rpGetClient("startup_location"));
		
		echo "</select></label>
	
	</div>
	
	<div class=\"clear height5\"></div>
	
	<h2>Käyttäjätyyppi</h2>";	
	
	rpPrintTypes("<div class=\"col_33 left mb5\"><input class=\"css-checkbox\" name=\"rpClientType_[rp(type)]\" id=\"rpClientType_[rp(type)]\" type=\"checkbox\" CHECKED /><label class=\"css-label\" for=\"rpClientType_[rp(type)]\">[rp(title)]</label></div>", "baseTypes", rpGetClient("types"));
	
	echo "<div class=\"clear\"></div>";
	
	rpPrintTypes("<div class=\"col_33 left mb5\"><input class=\"css-checkbox\" name=\"rpClientType2_[rp(type)]\" id=\"rpClientType2_[rp(type)]\" type=\"checkbox\" CHECKED /><label class=\"css-label\" for=\"rpClientType2_[rp(type)]\">[rp(title)]</label></div>", "subTypes", rpGetClient("types2"));
		
	echo "<div class=\"clear height5\"></div>
	
	<h2>Tuotantosuunnat</h2>
	
	<script>
	
	var rIndex = 1;
	var aIndex = 1;
	var iIndex = 1;
	
	function tradeUsed(id) {
	
		var whattoreturn = 0;
	
		$(\".tradeSelector\").each(function () {
			
			if ($(this).val()==id) {whattoreturn += 1;}
			
		});
		
		return whattoreturn;
		
	}
	
	function removeTrade(id) {
		
		$(\"#trade_\"+id).remove();
		
	}
	
	function changeTrade(id) {
		
		if (tradeUsed($(\"#rpClientTrade_\"+id).val())>1 && $(\"#rpClientTrade_\"+id).val()!=\"\") {
		
			$(\"#rpClientTrade_\"+id).val('');
			$(\"#tradeOptions_\"+id).html(\"\");
			notify(\"Olet valinnut jo kyseessä olevan tuotantosuunnan.<br />Ole hyvä ja valitse toinen.\");
		
		} else {
		
			$(\"#tradeOptions_\"+id).load(\"_trades.php?client_id=".$_SESSION["clientID"]."&id=\"+$(\"#rpClientTrade_\"+id).val());
		
		}
		
	}
	
	function addTrade(id) {
	
		$(\"#tradesContainer\").append(\"<div id='trade_\"+rIndex+\"' class='tradeDiv'><select onchange='changeTrade(\"+rIndex+\")' class='tradeSelector' name='rpClientTrade_\"+rIndex+\"' id='rpClientTrade_\"+rIndex+\"'><option value=''>Valitse tuotantosuunta...</option>";
		
			rpGetTrades("<option value='[rp(id)]'>[rp(title)]</option>");
		
		echo "</select><a href='javascript:removeTrade(\"+rIndex+\");' class='smallFormButton right'>Poista</a><div id='tradeOptions_\"+rIndex+\"'></div></div>\");
		
		$(\"#rpClientTrade_\"+rIndex).val(id);
		
		if (id>0) {changeTrade(rIndex);}
				
		rIndex+=1;
		
	}
	
	function addArsenal(id) {
	
		if (id<1) {id = aIndex;}
	
		$(\"#arsenalContainer\").append(\"<div id='arsenal_\"+id+\"' class='arsenalDiv'><a href='javascript:removeArsenal(\"+id+\");' class='smallFormButton right'>Poista</a><label for='rpArsenalTitle_\"+id+\"'>Kaluston nimi: *<br /><input name='rpArsenalTitle_\"+id+\"' id='rpArsenalTitle_\"+id+\"' type='text' /></label><br /><label for='rpArsenalDescription_\"+id+\"'>Lisätiedot:<br /><textarea name='rpArsenalDescription_\"+id+\"' id='rpArsenalDescription_\"+id+\"'></textarea></label></div>\");
		
		if (id>0) {
			
			$.get(\"_client.php?rpAction=rpGetClientArsenal&type=title&id=\"+id, function(result) {
				$(\"#rpArsenalTitle_\"+id).val(result);
			});
			
			$.get(\"_client.php?rpAction=rpGetClientArsenal&type=description&id=\"+id, function(result) {
				$(\"#rpArsenalDescription_\"+id).val(result);
			});
			
		}
		
		aIndex = id+1;
		
	}
	
	function removeArsenal(id) {
		
		$(\"#arsenal_\"+id).remove();
		
	}
	
	function addImage() {
		
		$(\"#imageUploadContainer\").append(\"<div id='rpClientImageUploadDiv_\"+iIndex+\"' name='rpClientImageUploadDiv_\"+iIndex+\"'><a href='javascript:removeClientImageUpload(\"+iIndex+\");' style='margin-top: 0px;' class='smallFormButton right'>Poista</a><input type='file' class='rpClientImageUpload' id='rpClientImageUpload_\"+iIndex+\"' name='rpClientImageUpload_\"+iIndex+\"' /><div class='clear height5'></div></div>\");
						
		iIndex += 1;
		
	}
	
	function removeClientImageUpload(id) {
				
		$(\"#rpClientImageUploadDiv_\"+id).fadeOut(300);	
		$(\"#rpClientImageUpload_\"+id).val(\"\");
		$(\"#rpClientImageUpload_\"+id).remove();
		
	}
	
	";
	
	rpGetClientTrades("addTrade([rp(id)]);", $_SESSION["clientID"]);
	
	rpGetClientArsenal("addArsenal([rp(id)]);", $_SESSION["clientID"]);
	
	echo "</script>

	<div id=\"tradesContainer\"></div>
	
	<div class=\"clear height5\"></div>
	
	<a href=\"javascript:addTrade();\" class=\"formButton left\">Lisää uusi tuotantosuunta</a>
	
	<div class=\"clear height15\"></div>";
	
	if (strstr(rpGetClient("types"), "[contractor]")) {
		
		echo "<div class=\"clear height5\"></div>
	
		<h2>Kalusto</h2>
		
		<div id=\"arsenalContainer\"></div>
	
		<div class=\"clear height5\"></div>
		
		<a href=\"javascript:addArsenal();\" class=\"formButton left\">Lisää uusi kalusto</a>
		
		<div class=\"clear height15\"></div>";
		
	}
	
	echo "<div class=\"clear height5\"></div>
	
		<h2>Kuvat</h2>";
		
		rpGetClientImages("<div class=\"clientImageDiv\" id=\"clientImageDiv_[rp(id)]\" name=\"clientImageDiv_[rp(id)]\"><a href=\"javascript:showImage('files/images/fullsize/[rp(filename)]');\"><img class=\"thumbnail\" src=\"files/images/thumbnail/[rp(filename)]\" /></a><br /><a href=\"javascript:removeClientImage([rp(id)],'[rp(checksum)]');\">Poista</a></div>", $_SESSION["clientID"]);
		
		echo "<div class=\"clear height5\"></div>
		
		<div id=\"imageUploadContainer\"></div>
	
		<div class=\"clear height5\"></div>
		
		<a href=\"javascript:addImage();\" class=\"formButton left\">Lisää uusi kuva</a>
		
		<div class=\"clear height15\"></div>";
	
	echo "<h2>Ravinnereino</h2><p>Ravinnereino lähettää sähköpostitse uusimmat lähialueesi kiinnostavat ilmoitukset perustuen määrittelemiisi kriteereihin.<br />Ehdotuksia kiinnostavista ilmoituksista näytetään myös karttanäkymässä.</p><div class=\"clear height10\"></div>";
	
	echo "<script>
	
	function triggerNotifier() {
		
		if ($(\"#rpClientNotifier\").attr(\"checked\")) {
		
			$(\"#notifierProductsDiv\").removeClass(\"hideme\").addClass(\"showme\");
		
		} else {
			
			$(\"#notifierProductsDiv\").removeClass(\"showme\").addClass(\"hideme\");
			
		}
		
	}
	
	</script>
	
	<input onchange=\"triggerNotifier();\" class=\"css-checkbox\" name=\"rpClientNotifier\" id=\"rpClientNotifier\" type=\"checkbox\""; if (rpGetClient("notifier")==1) {echo "CHECKED";} echo " /><label class=\"css-label\" for=\"rpClientNotifier\">Aktivoi Ravinnereino</label>
	
	<div class=\"notifierProductsDiv "; if (rpGetClient("notifier")==1) {echo "showme";} else {echo "hideme";} echo "\" id=\"notifierProductsDiv\" name=\"notifierProductsDiv\">";
	
	echo "<h2>Kiinnostavat ilmoitustyypit</h2>";
	
	rpPrintTypes("<div class=\"col_33 left mb5\"><input class=\"css-checkbox\" name=\"rpClientNotifierType_[rp(type)]\" id=\"rpClientNotifierType_[rp(type)]\" type=\"checkbox\" CHECKED /><label class=\"css-label\" for=\"rpClientNotifierType_[rp(type)]\">[rp(title)]</label></div>", "baseTypes", rpGetClient("notifier_types"));
	
	echo "<div class=\"clear height5\"></div>";
	
	echo "<h2>Kiinnostavat ilmoitussisällöt</h2>";
	
	rpGetProducts("<div class=\"col_33 left mb5\"><input class=\"css-checkbox\" name=\"rpClientNotifierProduct_[rp(prefix)]\" id=\"rpClientNotifierProduct_[rp(prefix)]\" type=\"checkbox\" CHECKED /><label class=\"css-label\" for=\"rpClientNotifierProduct_[rp(prefix)]\">[rp(title)]</label></div>", "", 0, 0, rpGetClient("notifier_products"));
	
	echo "<div class=\"clear height5\"></div>";
	
	echo "<h2>Ilmoitusasetukset</h2>";
	
	echo "<div class=\"col_33 left mb5\"><input class=\"css-checkbox\" name=\"rpClientNotifierContactEmail\" id=\"rpClientNotifierContactEmail\" type=\"checkbox\" "; if (strstr(rpGetClient("notifier_contact"),"[email]")) {echo "CHECKED";} echo " /><label class=\"css-label\" for=\"rpClientNotifierContactEmail\">Ilmoita sähköpostitse</label></div>";
	
	echo "<div class=\"col_33 left mb5\"><input class=\"css-checkbox\" name=\"rpClientNotifierContactScreen\" id=\"rpClientNotifierContactScreen\" type=\"checkbox\" "; if (strstr(rpGetClient("notifier_contact"),"[screen]")) {echo "CHECKED";} echo " /><label class=\"css-label\" for=\"rpClientNotifierContactScreen\">Ilmoita karttanäkymässä</label></div>";
	
	echo "<div class=\"clear height5\"></div>";
	
	echo "<h2>Maksimietäisyys ilmoitukseen (km)</h2>";
	
	echo "<input style=\"width: 150px; margin-top: 0px;\" type=\"text\" id=\"rpClientNotifierThreshold\" name=\"rpClientNotifierThreshold\" value=\"".rpGetClient("notifier_threshold")."\" />";
	
	echo "<div class=\"clear height5\"></div></div>";
	
	echo "<div class=\"clear height15\"></div>";
	
	echo "<h2>Sallitut yhteydenottotavat</h2>";
	
	rpPrintTypes("<div class=\"col_33 left mb5\"><input class=\"css-checkbox\" name=\"rpClientContactVia_[rp(type)]\" id=\"rpClientContactVia_[rp(type)]\" type=\"checkbox\" CHECKED /><label class=\"css-label\" for=\"rpClientContactVia_[rp(type)]\">[rp(title)]</label></div>", "contactTypes", rpGetClient("contact_via"));
	
	echo "<div class=\"clear height5\"></div>
		
	<a href=\"javascript:saveSettings();\" class=\"formButton left\">Tallenna</a>
	
	<a href=\"javascript:removeProfile();\" class=\"formButton left\">Poista profiili</a>
		
	<div class=\"clear\"></div>
	
	</form>";
	
}

?>