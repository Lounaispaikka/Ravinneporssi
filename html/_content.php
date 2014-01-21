<?php include_once("engine/rp.start.php");

if ($_POST["rpAction"]=="rpAdminUpdateContent") {
	
	if (rpIsAdmin($_SESSION["clientID"])) {
		
		if ($_POST["rpCheck"]==md5("ADMIN".$rpSettings->getValue("secret").$_SESSION["clientID"])) {
			
			if (rpIsAlive($_POST["rpContent"], "content")) {
			
				if ($rpConnection->query("UPDATE ".$rpSettings->getValue("contentTable")." SET 
				title='".rpSanitize($_POST["rpContentTitle"])."',
				content='".rpAdminSanitize($_POST["rpContentContent"])."'
		 		WHERE id='".rpSanitize(intval($_POST["rpContent"]))."'")) {
			
					echo "SUCCESS";
			
				} else {echo "Virhe sisällön tallentamisessa.";}
			
			} else {echo "Sisältöä ei löytynyt.";}
			
		} else {echo "Virhe sisällön tallentamisessa.";}
		
	} else {echo "Sinulla ei ole pääkäyttäjän oikeuksia.";}
		
}

if ($_GET["rpAction"] == "rpAdminGetContent" && $_SESSION["clientID"] && rpIsAdmin($_SESSION["clientID"])) {
	
	if ($_GET["type"]=="name") {echo rpGetContent("[rp(name)]", intval($_GET["id"]));}
	else if ($_GET["type"]=="title") {echo rpGetContent("[rp(title)]", intval($_GET["id"]));}
	else if ($_GET["type"]=="content") {echo rpGetContent("[rp(content)]", intval($_GET["id"]));}

}

if ($_GET["id"] == "getcontent" && rpIsAlive($_GET["contentid"], "content")) {
	
	rpGetContent("<h2>[rp(title)]</h2>[rp(content)]", $_GET["contentid"]);
	
}

include_once("engine/rp.end.php"); ?>