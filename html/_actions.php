<?php include_once("engine/rp.start.php");

if ($_GET["id"]=="general" && $_SESSION["clientID"]) {

	if ($_GET["clickX"]>0 && $_GET["clickY"]>0) {

		echo "<a href=\"javascript:placeHome();\" class=\"formButton left\">Siirrä kotipaikka tähän</a>
		
		<div class=\"clear height5\"></div>
		
		<hr />";
	
	}
	
	echo "<h2>Etsi kohde</h2>
	<label for=\"rpSearchTarget\">Osoite:<input name=\"rpSearchTarget\" id=\"rpSearchTarget\" type=\"text\" class=\"full\" onkeypress=\"if (event.keyCode==13) {searchTarget();}\" /></label>
	
	<a href=\"javascript:searchTarget();\" class=\"formButton left\">Etsi</a>";

	
} else if ($_GET["id"]=="notices" && $_SESSION["clientID"]) {
	
	rpGetNotices("<div class=\"noticeDiv\" id=\"notice_[rp(id)]\"><h2><a onmouseover=\"showInfo('Näytä ilmoitus kartalla');\" onmouseout=\"hideInfo();\" href=\"javascript:toggleNotice([rp(id)]);\">[rp(title)]</a><a href=\"javascript:removeNotice([rp(id)])\" class=\"smallFormButton right\">Poista</a><a href=\"javascript:editNotice([rp(id)])\" class=\"smallFormButton right\">Muokkaa</a></h2>[rp(products)]<div class=\"clear height5\"></div></div>", "by added_datetime DESC", "Ilmoituksia ei löytynyt.");
	
	echo "<div class=\"clear height5\"></div>
	
	<hr />";
	
	if ($_GET["clickX"]>0 && $_GET["clickY"]>0) {
	
		echo "<a href=\"javascript:addNotice();\" class=\"formButton left\">Luo uusi ilmoitus tähän</a>";
	
	} else {
		
		echo "<a href=\"javascript:addNotice();\" class=\"formButton left\">Luo uusi ilmoitus</a>";
		
	}
	
} else if ($_GET["id"]=="fields" && $_SESSION["clientID"]) {
	
	rpGetFields("<div class=\"fieldDiv\" id=\"fieldDiv_[rp(id)]\"><h2><a onmouseover=\"showInfo('Näytä pelto kartalla');\" onmouseout=\"hideInfo();\" href=\"javascript:toggleField([rp(id)]);\">[rp(title)]</a><a href=\"javascript:removeField([rp(id)])\" class=\"smallFormButton right\">Poista</a><a href=\"javascript:editField([rp(id)])\" class=\"smallFormButton right\">Muokkaa</a></h2>Pinta-ala: [rp(size)]</div>", "by added_datetime DESC", "Peltoja ei löytynyt.");
	
	echo "<div class=\"clear height5\"></div>
	
	<hr />";
	
	if ($_GET["clickX"]>0 && $_GET["clickY"]>0) {
	
		echo "<a href=\"javascript:addField();\" class=\"formButton left\">Luo uusi pelto tähän</a>";
	
	}
	
	echo "<a href=\"javascript:startFieldDrawing();\" class=\"formButton left\">Piirrä uusi pelto</a>";
	
} else if ($_GET["id"]=="routes" && $_SESSION["clientID"]) {
	
	// routes
	
	rpGetRoutes("<div class=\"routeDiv\" id=\"routeDiv_[rp(id)]\"><h2><a onmouseover=\"showInfo('Näytä reitti kartalla');\" onmouseout=\"hideInfo();\" href=\"javascript:toggleRoute([rp(id)]);\">[rp(title)]</a><a href=\"javascript:removeRoute([rp(id)])\" class=\"smallFormButton right\">Poista</a></h2>Kokonaispituus: [rp(distance)]</div>", "by added_datetime DESC", "Reittejä ei löytynyt.");
	
	echo "<div class=\"clear height5\"></div>
	
	<hr />
	
	<h2>Luo uusi reitti</h2>
	
	<div class=\"col_50 left\">
	
		<label for=\"rpRouteOrigin\">Mistä:<input name=\"rpRouteOrigin\" id=\"rpRouteOrigin\" type=\"text\" value=\"Kotipaikka\" /></label>	
	
	</div>
	
	<div class=\"col_50 left\">
	
		<label for=\"rpRouteDestination\">Mihin:<input name=\"rpRouteDestination\" id=\"rpRouteDestination\" type=\"text\" onkeypress=\"if (event.keyCode==13) {searchRoute();}\" /></label>	
	
	</div>
	
	<div class=\"clear\"></div>

	<a href=\"javascript:searchRoute();\" class=\"formButton left\">Hae reitti</a>
	
	<a href=\"javascript:startRouteDrawing();\" class=\"formButton left\">Piirrä uusi reitti</a>";
	
} else if ($_GET["id"]=="contracts" && $_SESSION["clientID"]) {
	
	// contracts
	
	rpGetContracts("<div class=\"contractDiv\" id=\"contractDiv_[rp(id)]\"><h2><a onmouseover=\"showInfo('Näytä sopimus');\" onmouseout=\"hideInfo();\" href=\"javascript:showContract([rp(id)]);\">[rp(added_datetime)]</a>[removable/]<a href=\"javascript:removeContract([rp(id)]);\" class=\"smallFormButton right\">Poista</a>[/removable][editable/]<a href=\"javascript:editContract([rp(id)]);\" class=\"smallFormButton right\">Muokkaa</a>[/editable]</h2>Sopimuksen tekijä: <a href=\"javascript:showProfile([rp(added_id)]);\">[rp(added_name)]</a><br />Muut osapuolet: [rp(participants)]<div class=\"clear height5\"></div></div>", "by added_datetime DESC", "Sopimuksia ei löytynyt.");
	
	echo "<div class=\"clear height5\"></div>
	
	<hr />
	
	<a href=\"javascript:addContract();\" class=\"formButton left\">Luo uusi sopimus</a>";
	
} else if ($_GET["id"]=="favourites" && $_SESSION["clientID"]) {
	
	echo "<script>
		
		$(document).ready(function() {
							
			$.ajax({
				type:\"POST\",
				data: {
					rpAction:\"rpGetFavourites\"
				},
			    url:\"_search.php\",
			    success: function(data) {
			    	
			    	$(\"#clientFavouritesDiv\").html(data);
			    					    	
			    }
			});	
			
		});
	
	</script>
	
	<div id=\"clientFavouritesDiv\" name=\"clientFavouritesDiv\"></div><div class=\"clear height5\"></div>";
	
}

include_once("engine/rp.end.php"); ?>