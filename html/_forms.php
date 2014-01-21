<?php include_once("engine/rp.start.php");

if ($_GET["id"]=="login") {

	echo "<h1>Kirjaudu sisään täyttämällä tietosi</h1><form action=\"http://".$rpSettings->getValue("domain")."/map\" id=\"loginForm\" method=\"POST\">
	
	<input type=\"hidden\" name=\"rpAction\" value=\"rpLoginClient\" />
	
	<div class=\"col_50 left\">
	
		<label for=\"rpLoginEmail\">Sähköpostiosoite:<input name=\"rpLoginEmail\" id=\"rpLoginEmail\" type=\"text\" /></label>	
	
	</div>
	
	<div class=\"col_50 left\">
	
		<label for=\"rpLoginPassword\">Salasana:<input name=\"rpLoginPassword\" id=\"rpLoginPassword\" class=\"full\" type=\"password\" onkeypress=\"if (event.keyCode==13) {submitLogin();}\" /></label>
	
	</div>
	
	<div class=\"clear\"></div>
	
	<a href=\"javascript:submitLogin();\" class=\"formButton left\">Kirjaudu</a>
	
	<a href=\"javascript:changeContainer('start');\" class=\"formButton left\">Peruuta</a>
	
	<div style=\"margin-top: 13px; margin-left: 5px;\"><input class=\"css-checkbox\" name=\"rpLoginRemember\" id=\"rpLoginRemember\" type=\"checkbox\" CHECKED /><label class=\"css-label\" for=\"rpLoginRemember\">Muista minut (voimassa kuukauden)</label><span class=\"right\"><a href=\"javascript:changeContainer('password');\">Oletko unohtanut salasanasi?</a></span></div>
	
	</form><div class=\"clear\"></div>";	
	
} else if ($_GET["id"]=="password") {
	
	echo "<h1>Oletko unohtanut salasanasi?</h1>Kirjoita sähköpostiosoitteesi ja saat hetken kuluttua linkin, jonka kautta voit vaihtaa salasanasi.<form action=\"#\" id=\"resetForm\" method=\"POST\">
	
	<input type=\"hidden\" name=\"rpAction\" value=\"rpResetPassword\" />
	
	<div class=\"col_50 left\">
	
		<label for=\"rpResetEmail\"><input name=\"rpResetEmail\" id=\"rpResetEmail\" type=\"text\" onkeypress=\"if (event.keyCode==13) {submitReset();return false;}\" /></label>	
	
	</div>
	
	<div class=\"clear\"></div>
	
	<a href=\"javascript:submitReset();\" class=\"formButton left\">Lähetä</a>
	
	<a href=\"javascript:changeContainer('start');\" class=\"formButton left\">Peruuta</a>
	
	</form><div class=\"clear\"></div>";
	
} else if ($_GET["id"]=="mappassword") {
	
	echo "<h1>Oletko unohtanut salasanasi?</h1>Kirjoita sähköpostiosoitteesi ja saat hetken kuluttua linkin, jonka kautta voit vaihtaa salasanasi.<form action=\"#\" id=\"resetForm\" method=\"POST\">
	
	<input type=\"hidden\" name=\"rpAction\" value=\"rpResetPassword\" />
	
	<div class=\"col_50 left\">
	
		<label for=\"rpResetEmail\"><input name=\"rpResetEmail\" id=\"rpResetEmail\" type=\"text\" onkeypress=\"if (event.keyCode==13) {submitMapReset();return false;}\" /></label>	
	
	</div>
	
	<div class=\"clear\"></div>
	
	<a href=\"javascript:submitMapReset();\" class=\"formButton left\">Lähetä</a>
	
	<a href=\"javascript:closePage();\" class=\"formButton left\">Peruuta</a>
	
	</form><div class=\"clear\"></div>";
	
} else if ($_GET["id"]=="changepassword") {
	
	echo "<h1>Kirjoita uusi salasanasi</h1><form action=\"#\" id=\"resetPasswordForm\" method=\"POST\">
	
	<input type=\"hidden\" name=\"rpAction\" value=\"rpUpdatePassword\" />
	
	<div class=\"col_50 left\">
	
		<label for=\"rpNewPassword_1\">Salasana: *<input name=\"rpNewPassword_1\" id=\"rpNewPassword_1\" type=\"password\" /></label>
	
	</div>
	
	<div class=\"col_50 left\">
	
		<label for=\"rpNewPassword_2\">Salasana uudelleen: *<input name=\"rpNewPassword_2\" id=\"rpNewPassword_2\" class=\"full\" type=\"password\" onkeypress=\"if (event.keyCode==13) {submitPasswordUpdate();return false;}\" /></label>
	
	</div>
	
	<div class=\"clear\"></div>
	
	<a href=\"javascript:submitPasswordUpdate();\" class=\"formButton left\">Vaihda</a>
	
	<a href=\"javascript:changeContainer('start');\" class=\"formButton left\">Peruuta</a>
	
	</form><div class=\"clear\"></div>";
	
} else if ($_GET["id"]=="maplogin") {

	echo "<h1>Kirjaudu sisään täyttämällä tietosi</h1><form action=\"http://".$rpSettings->getValue("domain")."/map\" id=\"loginForm\" method=\"POST\">
	
	<input type=\"hidden\" name=\"rpAction\" value=\"rpLoginClient\" />
	
	<div class=\"col_50 left\">
	
		<label for=\"rpLoginEmail\">Sähköpostiosoite:<input name=\"rpLoginEmail\" id=\"rpLoginEmail\" type=\"text\" /></label>	
	
	</div>
	
	<div class=\"col_50 left\">
	
		<label for=\"rpLoginPassword\">Salasana:<input name=\"rpLoginPassword\" id=\"rpLoginPassword\" class=\"full\" type=\"password\" onkeypress=\"if (event.keyCode==13) {submitMapLogin();}\" /></label>
	
	</div>
	
	<div class=\"clear\"></div>
	
	<a href=\"javascript:submitMapLogin();\" class=\"formButton left\">Kirjaudu</a>
	
	<a href=\"javascript:closePage();\" class=\"formButton left\">Peruuta</a>
	
	<div style=\"margin-top: 13px; margin-left: 5px;\"><input class=\"css-checkbox\" name=\"rpLoginRemember\" id=\"rpLoginRemember\" type=\"checkbox\" CHECKED /><label class=\"css-label\" for=\"rpLoginRemember\">Muista minut (voimassa kuukauden)</label><span class=\"right\"><a href=\"javascript:openPasswordChange();\">Oletko unohtanut salasanasi?</a></span></div>
	
	</form><div class=\"clear\"></div>";	
	
} else if ($_GET["id"]=="start") {
	
	echo "<h1>Rekisteröidy palvelun käyttäjäksi:</h1>";

	rpPrintTypes("<a href=\"javascript:changeContainer('[rp(type)]');\"><div class=\"bigButton [rp(type)]_bg left\">[rp(title)]</div></a>", "baseTypes");

	echo "<div class=\"clear\"></div>";
	
} else if ($_GET["id"]=="input" || $_GET["id"]=="output" || $_GET["id"]=="contractor") {
	
	echo "<h1>Rekisteröidy käyttäjäksi täyttämällä tietosi</h1><form id=\"registerForm\">
	
	<input type=\"hidden\" name=\"rpAction\" value=\"rpRegisterClient\" />
	
	<div class=\"col_50 left\">
	
		<label for=\"rpRegisterEmail\">Sähköpostiosoite: *<input name=\"rpRegisterEmail\" id=\"rpRegisterEmail\" type=\"text\" /></label>
	
	</div>
	
	<div class=\"col_50 left\">
	
		<label for=\"rpRegisterName\">Nimi: *<input name=\"rpRegisterName\" id=\"rpRegisterName\" type=\"text\" /></label>	
	
	</div>
	
	<div class=\"clear\"></div>
	
	<div class=\"col_50 left\">
	
		<label for=\"rpRegisterPassword_1\">Salasana: *<input name=\"rpRegisterPassword_1\" id=\"rpRegisterPassword_1\" type=\"password\" /></label>
	
	</div>
	
	<div class=\"col_50 left\">
	
		<label for=\"rpRegisterPassword_2\">Salasana uudestaan: *<input name=\"rpRegisterPassword_2\" id=\"rpRegisterPassword_2\" type=\"password\" /></label>
	
	</div>
	
	<div class=\"clear height5\"></div>";
	
	rpPrintTypes("<div class=\"col_33 left\"><input class=\"css-checkbox\" name=\"rpRegisterType_[rp(type)]\" id=\"rpRegisterType_[rp(type)]\" type=\"checkbox\" CHECKED /><label class=\"css-label\" for=\"rpRegisterType_[rp(type)]\">[rp(title)]</label></div>", "baseTypes", $_GET["id"]);
	
	echo "<div class=\"clear height5\"></div>
		
	<a href=\"javascript:submitRegistration();\" class=\"formButton left\">Rekisteröidy</a>
	
	<a href=\"javascript:changeContainer('start');\" class=\"formButton left\">Peruuta</a>
	
	<div style=\"margin-top: 13px; margin-left: 5px;\"><input class=\"css-checkbox\" name=\"rpAcceptTOU\" id=\"rpAcceptTOU\" type=\"checkbox\" /><label class=\"css-label\" for=\"rpAcceptTOU\">Hyväksyn <a href=\"javascript:showTOU();\">käyttöehdot</a></label></div>
	
	</form>
	
	<div id=\"touDiv\" name=\"touDiv\" class=\"hideme\">
	
	<div class=\"clear height15\"></div>";
	
	rpGetContent("<h2>[rp(title)]</h2>[rp(content)]", 12);
	
	echo "</div>
	
	<div class=\"clear\"></div>";
	
} else if ($_GET["id"]=="removeprofile") {
	
	echo "<h1>Poista profiili</h1>Profiilin poistamisen yhteydessä kaikki tiedot ilmoituksineen, viesteineen, peltoineen ja reitteineen piiloitetaan Ravinnepörssi.fi-palvelusta. Voit palauttaa tiedot kirjautumalla viikon sisällä profiilin poistamisesta. Jos profiiliin ei kirjauduta viikon kuluessa, kaikki tiedot poistetaan pysyvästi. Vahvista profiilin poistaminen kirjoittamalla salasanasi:<form action=\"#\" id=\"removeProfileForm\" method=\"POST\">
	
	<input type=\"hidden\" name=\"rpAction\" value=\"rpRemoveProfile\" />
	
	<div class=\"col_50 left\">
	
		<label for=\"rpRemoveProfilePassword\"><input name=\"rpRemoveProfilePassword\" id=\"rpRemoveProfilePassword\" type=\"password\" onkeypress=\"if (event.keyCode==13) {submitRemoveProfile();return false;}\" /></label>	
	
	</div>
	
	<div class=\"clear\"></div>
	
	<a href=\"javascript:submitRemoveProfile();\" class=\"formButton left\">Poista profiili</a>
	
	<a href=\"javascript:closePage();\" class=\"formButton left\">Peruuta</a>
	
	</form><div class=\"clear\"></div>";
	
}

include_once("engine/rp.end.php"); ?>