<?php include_once("engine/rp.start.php");

if (rpIsAlive($_GET["id"], "clients")) {

	echo "<h1>Käyttäjän profiili";
	
	if ($_GET["return"]=="list") {
		echo "<a href=\"javascript:closeSubPage();\" class=\"formButton right last\">Takaisin</a>";
	} else {
		echo "<a href=\"javascript:closePage();\" class=\"formButton right last\">Sulje</a>";
	}
	
	echo "</h1>";

	if (rpGetOtherClient($_GET["id"], "visibility") == "all" || $_SESSION["clientID"]>0) {

		echo "<h2>".rpGetOtherClient($_GET["id"], "name")."</h2>";
		
		if (rpGetOtherClient($_GET["id"], "published")==1 || rpIsAdmin($_SESSION["clientID"])) {
		
			rpGetClientTypes("<h3>&bull; [rp(title)]</h3>", $_GET["id"]);
			
			echo "<div class=\"clear height5\"></div>".rpGetOtherClient($_GET["id"], "description")."<div class=\"clear height15\"></div>";
		
			echo "<div class=\"noticeInformationDiv\">
			
			<div class=\"col_50 left\">
			
				<h3>Yrityksen/tilan nimi:</h3>".rpGetOtherClient($_GET["id"], "company");
				
				if (rpGetOtherClient($_GET["id"], "company")=="") {echo "-";} echo "
			
			</div>
			
			<div class=\"col_50 left\">
			
				<h3>Y-tunnus/tilatunnus:</h3>".rpGetOtherClient($_GET["id"], "bic");
				
				if (rpGetOtherClient($_GET["id"], "bic")=="") {echo "-";} echo "
			
			</div>
			
			<div class=\"clear height5\"></div>
			
			<div class=\"col_50 left\">
			
				<h3>Osoite:</h3>".rpGetOtherClient($_GET["id"], "address_1");
				
				if (rpGetOtherClient($_GET["id"], "address_1")=="") {echo "-";} echo "
			
			</div>
			
			<div class=\"col_50 left\">
			
				<h3>Yrityksen/tilan osoite:</h3>".rpGetOtherClient($_GET["id"], "address_2");
				
				if (rpGetOtherClient($_GET["id"], "address_2")=="") {echo "-";} echo "
			
			</div>
			
			<div class=\"clear height5\"></div>
			
			<div class=\"col_50 left\">
			
				<h3>Maakunta:</h3>".rpGetOtherClient($_GET["id"], "state");
				
				if (rpGetOtherClient($_GET["id"], "state")=="") {echo "-";} echo "
			
			</div>
			
			<div class=\"col_50 left\">
			
				<h3>Paikkakunta:</h3>".rpGetOtherClient($_GET["id"], "city");
				
				if (rpGetOtherClient($_GET["id"], "city")=="") {echo "-";} echo "
			
			</div>
			
			<div class=\"clear height5\"></div>
			
			<div class=\"col_50 left\">
			
				<h3>Postinumero:</h3>".rpGetOtherClient($_GET["id"], "postalcode");
				
				if (rpGetOtherClient($_GET["id"], "postalcode")=="") {echo "-";} echo "
			
			</div>
			
			<div class=\"col_50 left\">
			
				<h3>Puhelinnumero:</h3>"; if (strstr(rpGetOtherClient($_GET["id"], "contact_via"), "[phone]")) {echo rpGetOtherClient($_GET["id"], "phonenumber");}
				
				if (!strstr(rpGetOtherClient($_GET["id"], "contact_via"),"[phone]") || rpGetOtherClient($_GET["id"], "phonenumber")=="") {echo "-";} echo "
			
			</div>
			
			<div class=\"clear height5\"></div>
			
			<div class=\"col_50 left\">
			
				<h3>Fax:</h3>".rpGetOtherClient($_GET["id"], "fax");
				
				if (rpGetOtherClient($_GET["id"], "fax")=="") {echo "-";} echo "
			
			</div>
			
			<div class=\"col_50 left\">
			
				<h3>Matkapuhelinnumero:</h3>"; if (strstr(rpGetOtherClient($_GET["id"], "contact_via"), "[gsm]")) {echo rpGetOtherClient($_GET["id"], "gsm");}
				
				if (!strstr(rpGetOtherClient($_GET["id"], "contact_via"),"[gsm]") || rpGetOtherClient($_GET["id"], "gsm")=="") {echo "-";} echo "
			
			</div>
			
			<div class=\"clear height5\"></div>
			
			</div>
			
			<div class=\"clear height5\"></div>";
			
			if (rpGetOtherClient($_GET["id"], "trades")!="") {
			
				echo "<div class=\"clear height5\"></div><h2>Käyttäjän tuotantosuunnat</h2>";
				
				rpGetClientTrades("<div class=\"tradeDiv\"><h2>[rp(trade_title)]</h2>[rp(trade_options)]<div class=\"clear height5\"></div></div>", $_GET["id"]);
				
				echo "<div class=\"clear height5\"></div>";
			
			}
			
			if ($_GET["id"] != $_SESSION["clientID"] && strstr(rpGetOtherClient($_GET["id"], "contact_via"),"[email]") && strstr(rpGetOtherClient($_GET["id"], "email"),"@")) {
				
				echo "<a href=\"mailto:".rpGetOtherClient($_GET["id"], "email")."\" class=\"formButton left\" target=\"_blank\">Lähetä sähköpostia</a>";
				
			}
			
			if ($_GET["id"] != $_SESSION["clientID"] && strstr(rpGetOtherClient($_GET["id"], "contact_via"),"[rpmail]") && $_SESSION["clientID"]) {
				
				echo "<a href=\"javascript:openPage('messages', ".intval($_GET["id"]).");\" class=\"formButton left\">Lähetä yksityisviesti</a>";
				
			}
			
			if ($_GET["id"] != $_SESSION["clientID"] && $_SESSION["clientID"] && !rpIsClientRated($_SESSION["clientID"], $_GET["id"])) {
				
				echo "<a href=\"javascript:openRatingForm();\" id=\"openRatingFormButton\" name=\"openRatingFormButton\" class=\"formButton left\">Arvostele käyttäjä</a>";
				
			}
			
			echo "<div class=\"clear height10\"></div>";
			
			if ($_GET["id"] != $_SESSION["clientID"] && $_SESSION["clientID"] && !rpIsClientRated($_SESSION["clientID"], $_GET["id"])) {
			
				echo "<div class=\"hideme\" id=\"rateClientFormDiv\" name=\"rateFormDiv\"><div class=\"clear height10\"></div><form name=\"rateClientForm\" id=\"rateClientForm\" action=\"http://".$rpSettings->getValue("domain")."/map\" method=\"POST\">
			
				<input type=\"hidden\" name=\"rpCheck\" value=\"".md5("RATE".$_GET["id"].$rpSettings->getValue("secret").$_SESSION["clientID"])."\" />
				<input type=\"hidden\" name=\"rpAction\" value=\"rpRateClient\" />
				<input type=\"hidden\" name=\"rpClient\" value=\"".intval($_GET["id"])."\" />
				
				<div class=\"col_50 left\">
	
					<label for=\"rpRatingTitle\">Arvostelun otsikko: *<input name=\"rpRatingTitle\" id=\"rpRatingTitle\" type=\"text\" /></label>	
				
				</div>
				
				<div class=\"col_50 left\">
				
					<label for=\"rpRatingRating\">Käyttäjäarvio: *<select id=\"rpRatingRating\" class=\"full\" name=\"rpRatingRating\">";
		
					rpPrintTypes("<option value=\"[rp(type)]\">[rp(title)]</option>", "ratingTypes");
				
				echo "</select></label>	
				
				</div>
				
				<div class=\"clear\"></div>
					
				<label for=\"rpRatingMessage\">Arvostelun sisältö: *<textarea name=\"rpRatingMessage\" id=\"rpRatingMessage\" class=\"bigText\"></textarea></label>
			
				<a href=\"javascript:sendClientRating();\" class=\"formButton left\">Lähetä arvostelu</a></form><div class=\"clear height10\"></div></div>";
			
			}
			
			if (rpGetClientNumOfRatings($_GET["id"])>0 || rpIsAdmin($_SESSION["clientID"])) {
				
				echo "<div class=\"clear height5\"></div>
			
				<h2>Käyttäjän saama palaute</h2>";

				if (rpIsAdmin($_SESSION["clientID"])) {

					rpGetClientRatings("<div class=\"ratingDiv\"><h2>[rp(title)][rp(published)]</h2>[rp(content)]<div class=\"clear height5\"></div><span class=\"smalltext\">Kirjoittaja: [rp(from_name)] ([rp(added_datetime)])</span><div class=\"clear height5\"></div></div>", $_GET["id"], true);
					
				} else {
				
					rpGetClientRatings("<div class=\"ratingDiv\"><h2>[rp(title)]</h2>[rp(content)]<div class=\"clear height5\"></div><span class=\"smalltext\">Kirjoittaja: [rp(from_name)] ([rp(added_datetime)])</span><div class=\"clear height5\"></div></div>", $_GET["id"]);
				
				}
				
			}
			
			if (rpGetOtherClient($_GET["id"], "arsenal")!="") {
				
				echo "<div class=\"clear height5\"></div>
			
				<h2>Käyttäjän kalusto</h2>";
				
				rpGetClientArsenal("<div class=\"tradeDiv\"><h2>[rp(title)]</h2>[rp(description)]<div class=\"clear height5\"></div></div>", $_GET["id"]);
				
				echo "<div class=\"clear height5\"></div>";
				
			}
			
			if (rpGetOtherClient($_GET["id"], "images")!="") {
				
				echo "<div class=\"clear height5\"></div>";
				
				rpGetClientImages("<div class=\"profileImageDiv\" id=\"clientImageDiv_[rp(id)]\" name=\"clientImageDiv_[rp(id)]\"><a href=\"javascript:showImage('files/images/fullsize/[rp(filename)]');\"><img class=\"thumbnail\" src=\"files/images/thumbnail/[rp(filename)]\" /></a></div>", $_GET["id"]);
		
				echo "<div class=\"clear height5\"></div>";
				
			}
			
			echo "<div class=\"clear height10\"></div><h2>Käyttäjän ilmoitukset</h2>
		
			<script>
			
				$(document).ready(function() {
									
					$.ajax({
						type:\"POST\",
						data: {
							rpAction:\"rpSearchNotices\",
							rpSearchClient:".intval($_GET["id"])."				
						},
					    url:\"_search.php\",
					    success: function(data) {
					    	
					    	$(\"#clientNoticesDiv\").html(data);
					    					    	
					    }
					});	
					
				});
			
			</script>
			
			<div id=\"clientNoticesDiv\" name=\"clientNoticesDiv\"></div>
			
			<div class=\"clear height10\"></div>";

			if (rpIsAdmin($_SESSION["clientID"])) {
				
				echo "<div class=\"clear height10\"></div><h2>Pääkäyttäjän toiminnot</h2>
				<div class=\"noticeInformationDiv\">
				
				<div class=\"clear height5\"></div>
				
				Käyttäjän deaktivoiminen piilottaa käyttäjän tiedot, ilmoitukset ja pellot palvelusta kaikilta muilta paitsi pääkäyttäjiltä, sekä estää käyttäjän kirjautumisen. Käyttäjän aktivoiminen palauttaa käyttäjän kaikkien tietojen, ilmoitusten ja peltojen näkyvyyden palveluun, sekä mahdollistaa kirjautumisen.
				
				<div class=\"clear height5\"></div>";			
				
				if (rpGetOtherClient($_GET["id"], "published")==1) {echo "<a href=\"javascript:rpAdminDeactivateClient(".intval($_GET["id"]).");\" class=\"formButton left\">Deaktivoi käyttäjä</a>";}
				
				else if (rpGetOtherClient($_GET["id"], "published")==0) {echo "<a href=\"javascript:rpAdminActivateClient(".intval($_GET["id"]).");\" class=\"formButton left\">Aktivoi käyttäjä</a>";}
				
				if (rpGetOtherClient($_GET["id"], "admin")==1) {echo "<a href=\"javascript:rpAdminUnmakeClientAdmin(".intval($_GET["id"]).");\" class=\"formButton left\">Poista pääkäyttäjän oikeudet</a>";}
				
				else if (rpGetOtherClient($_GET["id"], "admin")==0) {echo "<a href=\"javascript:rpAdminMakeClientAdmin(".intval($_GET["id"]).");\" class=\"formButton left\">Anna pääkäyttäjän oikeudet</a>";}
				
				echo "<div class=\"clear height10\"></div></div>";
				
			}

		} else {echo "Käyttäjäprofiili on poistettu.";}

	} else {echo "Sinun tulee rekisteröityä tarkastellaksesi käyttäjän profiilia.";}

}

include_once("engine/rp.end.php"); ?>