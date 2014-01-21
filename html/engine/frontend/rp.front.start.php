<script src="js/rp.start.js"></script>

<script>

$(document).ready(function() {

	if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
	
		isMobile = true;
	
	}

	$("#infoBox").hide();
	
	<?php
	
	$openContainer = "start";
	
	if ($_GET["rpAction"]=="reset" && intval($_GET["clientID"])>0 && $_GET["rpCheck"]!="") {
	
		if (rpIsAlive($_GET["clientID"], "clients")) {
	
			$user_result = $rpConnection->query("SELECT salt FROM ".$rpSettings->getValue("clientsTable")." WHERE id='".rpSanitize(intval($_GET["clientID"]))."' && confirmed='1' && published='1' LIMIT 1");
	
			if ($_GET["rpCheck"]==md5("RESET".$rpSettings->getValue("secret").$_GET["clientID"].mysql_result($user_result, 0, "salt"))) {

				$openContainer = "changepassword";
		
				$_SESSION["resetClientID"] = $_GET["clientID"];
				$_SESSION["resetCheck"] = $_GET["rpCheck"];
		
				echo "$(\"#notifier\").hide();";
		
			} else {echo "notify(\"Salasana on jo vaihdettu.\");";}
	
		} else {echo "notify(\"Käyttäjää ei löytynyt.\");";}
	
	} else if ($_GET["rpAction"]=="confirm" && intval($_GET["clientID"])>0 && $_GET["rpCheck"]!="") {
		
		if (rpIsAlive($_GET["clientID"], "clients")) {
			
			if ($_GET["rpCheck"]==md5("CONFIRM".$rpSettings->getValue("secret").$_GET["clientID"])) {
				
				$user_result = $rpConnection->query("SELECT id FROM ".$rpSettings->getValue("clientsTable")." WHERE id='".rpSanitize(intval($_GET["clientID"]))."' && confirmed='1' LIMIT 1");
				
				if (mysql_num_rows($user_result)<1) {
				
					if ($rpConnection->query("UPDATE ".$rpSettings->getValue("clientsTable")." SET confirmed='1' WHERE id='".rpSanitize(intval($_GET["clientID"]))."' LIMIT 1")) {
						
						echo "notify(\"Käyttäjä vahvistettiin.<br />Voit kirjautua sisään antamillasi tunnuksilla.\");";
						
					} else {echo "notify(\"Virhe vahvistamisessa.\");";}			
				
				} else {echo "notify(\"Käyttäjä on jo vahvistettu.\");";}
				
				$openContainer = "login";
				
			} else {echo "notify(\"Virhe vahvistamisessa.\");";}			
			
		} else {echo "notify(\"Käyttäjää ei löytynyt.\");";}
	
	} else if ($_GET["rpLoginFailure"]==1) {echo "notify(\"Puuttuvia kohtia lomakkeessa.\");"; $openContainer = "login";}
	
	else if ($_GET["rpLoginFailure"]==2) {echo "notify(\"Käyttäjää ei löytynyt.\");"; $openContainer = "login";}
	
	else if ($_GET["rpLoginFailure"]==3) {echo "notify(\"Virheellinen salasana.\");"; $openContainer = "login";}
	
	else if ($_GET["rpLoginFailure"]==4) {echo "notify(\"Käyttäjää ei ole vahvistettu.\");"; $openContainer = "login";}
	
	else if ($_GET["rpLoginFailure"]==5) {echo "notify(\"Käyttäjätunnus on deaktivoitu pääkäyttäjän toimesta.\");"; $openContainer = "login";}
	
	else if ($rpNotify!="") {echo "notify(\"".$rpNotify."\");";} else {echo "$(\"#notifier\").hide();";}
	
	echo "changeContainer(\"".$openContainer."\");";
	
	?>
	
	if (!isMobile) {
	
		$("html").mousemove(function(event) {
					
			if ((event.pageX+$("#infoBox").width()+20)>$(window).width()) {
			
				$("#infoBox").css("left",(event.pageX-10-$("#infoBox").width())+"px");
				$("#infoBox").css("top",(event.pageY+10)+"px");
			
			} else {
			
				$("#infoBox").css("left",(event.pageX+10)+"px");
				$("#infoBox").css("top",(event.pageY+10)+"px");
				
			}
				
		});

	}

	if ($.browser.msie && parseFloat($.browser.version)<8){
	
		$("#browserNotificationContainerDummy").html("<div class=\"blackBox\" id=\"browserNotificationContainer\" name=\"browserNotificationContainer\"><h2>Varoitus!</h2>Selaimesi ei ole tuettu. Ole hyvä ja asenna <a href=\"http://www.mozilla.org/fi/firefox/new/\" target=\"_blank\">uusin selain</a><br />hyödyntääksesi palvelun kaikkia ominaisuuksia tehokkaasti.</div>");
	
	}

});

</script>

<div id="notifier"><div id="notifierMsg"></div><a href="javascript:closeNotifier();" class="formButton">Sulje</a></div>

<div id="infoBox"></div>

<a href="http://<?php echo $rpSettings->getValue("domain"); ?>/map"><div id="mapButton"></div></a>

<div id="startContainer">

	<div id="bigLogo">
	
		<?php
		
		if ($_SESSION["clientID"]) {
			echo "<a href=\"http://".$rpSettings->getValue("domain")."/logout\"><div id=\"logoutButton\" class=\"topButton right\">Kirjaudu ulos</div></a>";
			} else {echo "<a href=\"javascript:changeContainer('login');\"><div id=\"loginButton\" class=\"topButton right\">Kirjaudu sisään</div></a>";}
		
		?>
	
	</div>
	
	<hr />
	
	<?php rpGetContent("<h1>[rp(title)]</h1>[rp(content)]", 9); ?>
	
	<div id="browserNotificationContainerDummy" name="browserNotificationContainerDummy"></div>
	
	<div class="blackBox" id="registrationContainer" name="registrationContainer"></div>

</div>