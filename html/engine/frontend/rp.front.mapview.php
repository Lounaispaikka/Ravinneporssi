<script src="js/rp.routes.js"></script>
<script src="js/rp.fields.js"></script>
<script src="js/rp.notices.js"></script>
<script src="js/rp.mapview.js"></script>
<script src="js/rp.general.js"></script>
<script src="js/rp.contracts.js"></script>

<script>

$(document).ready(function() {

	if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
	
		isMobile = true;
	
	}

	$("#imageContainer").hide();
	$("#formContainer").hide();
	$("#distanceInformation").hide();
	$("#infoBox").hide();
	$("#pageContainer").hide();
	$("#notifierScreen").hide();

	<?php if ($rpNotify!="") {echo "notify(\"".$rpNotify."\");";} else {echo "$(\"#notifier\").hide();";} ?>

	<?php if ($loopBackPage!="") {echo "openPage('".$loopBackPage."');";} ?>

	<?php
	
	if ($_SESSION["clientID"]) {
		
		if (strstr(rpGetClient("current_layers"),"1_1|")) {echo "currentForeGroundLayer = \"1_1\";";}
		
		if (strstr(rpGetClient("current_layers"),"2_1|")) {echo "currentBackGroundLayer = mapLayers[\"2_1\"];";}
		else if (strstr(rpGetClient("current_layers"),"2_2|")) {echo "currentBackGroundLayer = mapLayers[\"2_2\"];";}
		else if (strstr(rpGetClient("current_layers"),"2_3|")) {echo "currentBackGroundLayer = mapLayers[\"2_3\"];";}
		
		echo "clientLoggedIn = true;";
		
		if (rpGetClient("base_latitude")>0 && rpGetClient("base_longitude")>0) {
			
			echo "baseLatitude = ".rpGetClient("base_latitude").";
			baseLongitude = ".rpGetClient("base_longitude").";";
			
		}		
		
		if (rpGetClient("current_zoom")>0) {echo "currentZoom = ".rpGetClient("current_zoom").";";}
		
		if ($forceLatitude>0 && $forceLongitude>0) {
			
			echo "currentLatitude = ".$forceLatitude.";
			currentLongitude = ".$forceLongitude.";
			viewLatitude = currentLatitude;
			viewLongitude = currentLongitude;
			physicalLatitude = currentLatitude;
			physicalLongitude = currentLongitude;
			refreshMap(true);";
			
		} else if (rpGetClient("startup_location")=="home" && rpGetClient("base_latitude")>0 && rpGetClient("base_longitude")>0) {
			
			echo "currentLatitude = ".rpGetClient("base_latitude").";
			currentLongitude = ".rpGetClient("base_longitude").";
			viewLatitude = currentLatitude;
			viewLongitude = currentLongitude;
			physicalLatitude = currentLatitude;
			physicalLongitude = currentLongitude;
			refreshMap(true);";
			
		} else if (rpGetClient("startup_location")=="previous" && rpGetClient("current_latitude")>0 && rpGetClient("current_longitude")>0) {
			
			echo "currentLatitude = ".rpGetClient("current_latitude").";
			currentLongitude = ".rpGetClient("current_longitude").";
			viewLatitude = currentLatitude;
			viewLongitude = currentLongitude;
			physicalLatitude = currentLatitude;
			physicalLongitude = currentLongitude;
			refreshMap(true);";
			
		} else {
			
			echo "getLocation();";
			
		}
		
		echo "window.setInterval(function() {updateClient();}, 60000); window.setInterval(function() {updateNewMessagesIndicator();}, 60000);";
		
		if (rpGetClient("notifier")==1 && strstr(rpGetClient("notifier_contact"),"[screen]")) {
			
			echo "window.setInterval(function() {getNewNotification();}, notifierInterval*1000);
			notifierEnabled = true;
			getNewNotification();";
			
		}
		
	} else {
		
		echo "clientLoggedIn = false;";
		
		echo "getLocation();";
		
	}
	
	?>
	
	window.setTimeout(function() {iterateTiles();}, 1000);
	
	getFixedLocation();
	
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
		
	$("#mapView").draggable({
      stop: function() {
        
        iterateTiles();
                
      }
    });

	$("#mapView").dblclick(function(e) {
		
		noticePreDefinedAddress = "";
		
		overRideDoubleClickLatitude = 0;
		overRideDoubleClickLongitude = 0;
		
		doubleClickX = e.pageX;
		doubleClickY = e.pageY;
		
		clearField();
		clearRoute();
		closeRepositioning();

		<?php
		
		if ($_SESSION["clientID"]) {
			echo "openPage(\"actions\");";
		}
		
		?>

	});
	
	$("#mapView").bind("DOMMouseScroll", function(e) {
		
		if (e.originalEvent.detail > 0) {
			mapZoom(1);	
		} else {
			mapZoom(-1);		
		}
		
	});
	
	$("#mapView").bind("mousewheel", function(e) {
		
		if (e.originalEvent.wheelDelta > 0) {
			mapZoom(-1);
		}
		else {
			mapZoom(1);
		}
		
	});
	
	$("#mapView").click(function(e) {

		if (rePositionNotice>0) {
			
			var coordinates = transformVisibleCoordinates(e.pageX, e.pageY);
			var coordinates2 = transformCoordinates(coordinates[0], coordinates[1]);
			
			$.ajax({
				type:"POST",
				data: {
					rpNotice:rePositionNotice,
					rpAction:"rpUpdateNoticePosition",
					rpNoticeLatitude:coordinates[1],
					rpNoticeLongitude:coordinates[0],
					rpNoticePosX:coordinates2[0],
					rpNoticePosY:coordinates2[1]
				},
			    url:"_notice.php",
			    success: function(data) {
			
					if (data=="SUCCESS") {
						
						refreshMap();
						notify("Ilmoituksen uusi paikka tallennettiin.");
						
					} else {notify(data);}
			
				}
			});
			
			closeRepositioning();
			
		}
		
		if (rePositionField>0) {
			
			var coordinates3 = transformVisibleCoordinates(e.pageX, e.pageY);
			var coordinates4 = transformCoordinates(coordinates3[0], coordinates3[1]);
			
			$.ajax({
				type:"POST",
				data: {
					rpField:rePositionField,
					rpAction:"rpUpdateFieldPosition",
					rpFieldLatitude:coordinates3[1],
					rpFieldLongitude:coordinates3[0],
					rpFieldPosX:coordinates4[0],
					rpFieldPosY:coordinates4[1]
				},
			    url:"_field.php",
			    success: function(data) {
			
					if (data=="SUCCESS") {
						
						refreshMap();
						notify("Pellon uusi paikka tallennettiin.");
						
					} else {notify(data);}
			
				}
			});
			
			closeRepositioning();
			
		}

		if (drawingRoute) {
		
			var coordinates = transformVisibleCoordinates(e.pageX-routeLineWidth, e.pageY-routeLineWidth);
			var coordinates2 = transformCoordinates(coordinates[0], coordinates[1]);
				
			if (currentRouteX==0 && currentRouteY==0) {
				
				currentRouteX = coordinates2[0];
				currentRouteY = coordinates2[1];
				
				$("#routeInformation").html("Jatka valitsemalla kartalta seuraava piste.");
						
			} else {
				
				// draw
				
				routeArray.push(new Array(currentRouteX, currentRouteY, coordinates2[0], coordinates2[1]));
				
				var routebroken = 1;
				
				while (routebroken>0) {
					routebroken = breakRoute();
				}
				
				drawRouteLine(currentRouteX, currentRouteY, coordinates2[0], coordinates2[1], routeLineColor, routeLineWidth, "route");
				
				currentRouteX = coordinates2[0];
				currentRouteY = coordinates2[1];
				
				$("#routeInformation").html("Jatka valitsemalla kartalta seuraava piste tai tallenna reitti.<h2 style=\"margin-top: 5px;\">Kokonaispituus: "+sanitizeDistance(getPolygon(routeArray, "length"))+"</h2><div class=\"clear height5\"></div><label for=\"rpRouteTitle\">Reitin nimi: *<br /><input name=\"rpRouteTitle\" id=\"rpRouteTitle\" type=\"text\" class=\"routeTitleText\" /></label><div class=\"clear\"></div><a href=\"javascript:saveRoute();\" class=\"smallFormButton left\">Tallenna reitti</a>");
							
			}
		
		}
		
		if (drawingField) {
		
			var coordinates = transformVisibleCoordinates(e.pageX-fieldLineWidth, e.pageY-fieldLineWidth);
			var coordinates2 = transformCoordinates(coordinates[0], coordinates[1]);
				
			if (currentFieldX==0 && currentFieldY==0) {
				
				currentFieldX = coordinates2[0];
				currentFieldY = coordinates2[1];
				
				$("#fieldInformation").html("Jatka valitsemalla kartalta seuraava piste.");
						
			} else {
				
				// draw
				
				fieldArray.push(new Array(currentFieldX, currentFieldY, coordinates2[0], coordinates2[1]));
				
				drawFieldLine(currentFieldX, currentFieldY, coordinates2[0], coordinates2[1], fieldLineColor, fieldLineWidth);
				
				currentFieldX = coordinates2[0];
				currentFieldY = coordinates2[1];
				
				if (fieldArray.length>1) {
				
					if (reDrawField>0) {
						
						$("#fieldInformation").html("Jatka valitsemalla kartalta seuraava piste tai tallenna pellon rajat.<h2 style=\"margin-top: 5px;\">Pinta-ala: "+sanitizeArea(getPolygon(fieldArray, "area"))+" m<sup>2</sup> ("+sanitizeAreaHectare(getPolygon(fieldArray, "area"))+" ha)</h2><div class=\"clear height5\"></div><a href=\"javascript:saveFieldReDrawing();\" class=\"smallFormButton left\">Tallenna pellon rajat</a>");
						
					} else {
						
						$("#fieldInformation").html("Jatka valitsemalla kartalta seuraava piste tai tallenna pelto.<h2 style=\"margin-top: 5px;\">Pinta-ala: "+sanitizeArea(getPolygon(fieldArray, "area"))+" m<sup>2</sup> ("+sanitizeAreaHectare(getPolygon(fieldArray, "area"))+" ha)</h2><div class=\"clear height5\"></div><label for=\"rpFieldTitle\">Pellon nimi: *<br /><input name=\"rpFieldTitle\" id=\"rpFieldTitle\" type=\"text\" class=\"fieldTitleText\" /></label><div class=\"clear\"></div><a href=\"javascript:saveField();\" class=\"smallFormButton left\">Tallenna pelto</a>");
				
					}
				
				} else {
					
					$("#fieldInformation").html("Jatka valitsemalla kartalta seuraava piste.<div class=\"clear height5\"></div>");
					
				}
							
			}
		
		}
		
	});

	<?php
	
	if (!$_SESSION["clientID"] || rpGetClient("first_login")==0) {
		
		echo "openPage(\"help\");";
		
		if ($_SESSION["clientID"]) {
			
			$rpConnection->query("UPDATE ".$rpSettings->getValue("clientsTable")." SET 
			first_login='1'
			 WHERE id='".rpSanitize(intval($_SESSION["clientID"]))."' LIMIT 1");
			
		}
		
	}
	
	if (intval($_GET["noticeID"])>0) {echo "showNotice(".intval($_GET["noticeID"]).");";}
	
	?>

});

</script>

<div id="imageContainer"></div>

<div id="notifier"><div id="notifierMsg"></div><a href="javascript:closeNotifier();" class="formButton">Sulje</a></div>

<div id="infoBox"></div>

<div id="pageContainer">

	<div class="pageContainerData" id="pageContainerData"></div>

	<div class="pageContainerData" id="subPageContainerData"></div>

</div>

<div id="formContainer"></div>

<div id="annotationInformation"></div>

<img onmouseover="showInfo('Piilota/näytä valikko');" onmouseout="hideInfo();" id="toggleMenuControlsButton" onclick="toggleMenuControls();" src="graphics/buttons/button_back.png" />

<div id="menuControls">

	<a href="http://<?php echo $rpSettings->getValue("domain"); ?>/"><img id="logo" src="graphics/logo.png" alt="Ravinnepörssi"/></a>
	
	<hr />
	
	<h1>Karttapohjat</h1>

	<input onclick="toggleLayer(1,1);" class="css-checkbox buttonlevel_1" id="toggleLayer_1_1" type="checkbox" <?php if (strstr(rpGetClient("current_layers"),"1_1|")) {echo "CHECKED";} ?> /><label onmouseover="showInfo('Valitse karttapohja');" onmouseout="hideInfo();" class="css-label" for="toggleLayer_1_1">Peltolohkorekisteri</label><br />
	
	<div class="clear height5"></div>
	
	<input onclick="toggleLayer(2,1);" class="css-checkbox buttonlevel_2" id="toggleLayer_2_1" type="checkbox" <?php if (strstr(rpGetClient("current_layers"),"2_1|")) {echo "CHECKED";} ?> /><label onmouseover="showInfo('Valitse karttapohja');" onmouseout="hideInfo();" class="css-label" for="toggleLayer_2_1">Peruskartta</label><br />
	<input onclick="toggleLayer(2,2);" class="css-checkbox buttonlevel_2" id="toggleLayer_2_2" type="checkbox" <?php if (strstr(rpGetClient("current_layers"),"2_2|")) {echo "CHECKED";} ?> /><label onmouseover="showInfo('Valitse karttapohja');" onmouseout="hideInfo();" class="css-label" for="toggleLayer_2_2">Ortokuva</label><br />
	<input onclick="toggleLayer(2,3);" class="css-checkbox buttonlevel_2" id="toggleLayer_2_3" type="checkbox" <?php if (!$_SESSION["clientID"] || rpGetClient("current_layers")=="" || strstr(rpGetClient("current_layers"),"2_3|")) {echo "CHECKED";} ?> /><label onmouseover="showInfo('Valitse karttapohja');" onmouseout="hideInfo();" class="css-label" for="toggleLayer_2_3">Taustakartta</label><br />

	<hr />
	
	<h1>Näkymävalinnat</h1>

	<input onclick="closeFormContainer(); refreshAnnotations();" class="css-checkbox" id="toggleAnnotations_output" type="checkbox" <?php if (!$_SESSION["clientID"] || rpGetClient("current_annotations")=="" || strstr(rpGetClient("current_annotations"),"output|")) {echo "CHECKED";} ?> /><label onmouseover="showInfo('Valitse näkymä');" onmouseout="hideInfo();" class="css-label" for="toggleAnnotations_output">Lannoitteen luovuttajat</label><br />
	<input onclick="closeFormContainer(); refreshAnnotations();" class="css-checkbox" id="toggleAnnotations_input" type="checkbox" <?php if (!$_SESSION["clientID"] || rpGetClient("current_annotations")=="" || strstr(rpGetClient("current_annotations"),"input|")) {echo "CHECKED";} ?> /><label onmouseover="showInfo('Valitse näkymä');" onmouseout="hideInfo();" class="css-label" for="toggleAnnotations_input">Lannoitteen vastaanottajat</label><br />
	<input onclick="closeFormContainer(); refreshAnnotations();" class="css-checkbox" id="toggleAnnotations_contractor" type="checkbox" <?php if (!$_SESSION["clientID"] || rpGetClient("current_annotations")=="" || strstr(rpGetClient("current_annotations"),"contractor|")) {echo "CHECKED";} ?> /><label onmouseover="showInfo('Valitse näkymä');" onmouseout="hideInfo();" class="css-label" for="toggleAnnotations_contractor">Urakoitsijat</label><br />
	<input onclick="closeFormContainer(); refreshAnnotations();" class="css-checkbox" id="toggleAnnotations_field" type="checkbox" <?php if (!$_SESSION["clientID"] || rpGetClient("current_annotations")=="" || strstr(rpGetClient("current_annotations"),"field|")) {echo "CHECKED";} ?> /><label onmouseover="showInfo('Valitse näkymä');" onmouseout="hideInfo();" class="css-label" for="toggleAnnotations_field">Pellot</label><br />

	<hr />
	
	<a href="http://<?php echo $rpSettings->getValue("domain"); ?>/"><img onmouseover="showInfo('Etusivu');" onmouseout="hideInfo();" class="button floatleft" src="graphics/buttons/button_home.png" /></a>
	
	<a href="javascript:openPage('help');"><img onmouseover="showInfo('Ohjeet');" onmouseout="hideInfo();" class="button floatleft" src="graphics/buttons/button_help.png" /></a>
	
	<a href="javascript:openPage('listview');"><img onmouseover="showInfo('Listanäkymä');" onmouseout="hideInfo();" class="button floatleft" src="graphics/buttons/button_settings.png" /></a>
	
	<?php
	
	if ($_SESSION["clientID"]) {
		
		echo "<a href=\"javascript:openPage('clients');\"><img onmouseover=\"showInfo('Käyttäjälistaus');\" onmouseout=\"hideInfo();\" class=\"button floatleft\" src=\"graphics/buttons/button_clients.png\" /></a>";
		
		echo "<a href=\"javascript:openPage('profile')\"><img onmouseover=\"showInfo('Oma profiili');\" onmouseout=\"hideInfo();\" class=\"button floatleft\" src=\"graphics/buttons/button_contacts.png\" /></a>
		<a href=\"javascript:openPage('messages')\"><div onmouseover=\"showInfo('Viestit');\" onmouseout=\"hideInfo();\" id=\"newMessagesIndicatorDiv\" name=\"newMessagesIndicatorDiv\" class=\"messagesButton "; if (rpGetNumOfMessages($_SESSION["clientID"],0,true)>0) {echo "newmessages ";} echo "floatleft\">";
		if (rpGetNumOfMessages($_SESSION["clientID"],0,true)>0) {
			echo "<div class=\"messagesNumberCount\">".rpGetNumOfMessages($_SESSION["clientID"],0,true)."</div>";
		}
		echo "</div></a>

		<div class=\"clear\"></div>
		
		<a href=\"javascript:openPage('links')\"><img onmouseover=\"showInfo('Tietopörssi');\" onmouseout=\"hideInfo();\" class=\"button floatleft\" src=\"graphics/buttons/button_link.png\" /></a>
		
		<a href=\"javascript:openPage('feedback')\"><img onmouseover=\"showInfo('Lähetä palautetta');\" onmouseout=\"hideInfo();\" class=\"button floatleft\" src=\"graphics/buttons/button_feedback.png\" /></a>
		
		<a href=\"javascript:openPage('addnotice')\"><img onmouseover=\"showInfo('Lisää uusi ilmoitus');\" onmouseout=\"hideInfo();\" class=\"button floatleft\" src=\"graphics/buttons/button_add.png\" /></a>
	
		<a href=\"javascript:openPage('actions')\"><img onmouseover=\"showInfo('Toiminnot');\" onmouseout=\"hideInfo();\" class=\"button floatleft\" src=\"graphics/buttons/button_actions.png\" /></a>";
		
		if (rpIsAdmin($_SESSION["clientID"])) {
			
			echo "<a href=\"javascript:openPage('content')\"><img onmouseover=\"showInfo('Sisältö');\" onmouseout=\"hideInfo();\" class=\"button floatleft\" src=\"graphics/buttons/button_content.png\" /></a>";
			
		}
		
		echo "<a href=\"http://".$rpSettings->getValue("domain")."/logout\"><img onmouseover=\"showInfo('Kirjaudu ulos');\" onmouseout=\"hideInfo();\" class=\"button floatleft\" src=\"graphics/buttons/button_logout.png\" /></a>";
	
		echo "<div class=\"clear height10\"></div>Kaksoisklikkaa karttaa avataksesi karttatoiminnot.";
		
	} else {
		
		echo "<a href=\"javascript:openLogin();\"><img onmouseover=\"showInfo('Kirjaudu sisään');\" onmouseout=\"hideInfo();\" class=\"button floatleft\" src=\"graphics/buttons/button_logout.png\" /></a>";
		
	}
	
	?>

</div>

<?php

if ($_SESSION["clientID"]) {

	echo "<div id=\"searchBar\">
	
		<input autocomplete=\"on\" onmouseover=\"showInfo('Kirjoita hakusana ja paina Enter');\" onmouseout=\"hideInfo();\" id=\"searchBarText\" name=\"searchBarText\" type=\"text\" name=\"search\" onkeypress=\"if (event.keyCode==13) {searchBarSubmit();}\" onfocus=\"$('#searchBarText').val('');\" value=\"Etsi osoite tai paikkakunta...\" />
	
		<a href=\"javascript:searchBarSubmit();\"><img onmouseover=\"showInfo('Etsi kohde');\" onmouseout=\"hideInfo();\" src=\"graphics/buttons/button_search.png\" /></a>
	
		<a href=\"javascript:mapPosition(physicalLatitude, physicalLongitude);\"><img onmouseover=\"showInfo('Palaa kotipaikkaan');\" onmouseout=\"hideInfo();\" src=\"graphics/buttons/button_gohome.png\" /></a>
	
	</div>";

}

?>

<div id="notifierScreen"></div>

<div id="navigationControls">

	<a href="javascript:mapShift(0,1);"><img onmouseover="showInfo('Liikuta karttaa ylös');" onmouseout="hideInfo();" class="absolute_button button_up" src="graphics/buttons/button_up.png" /></a>
	<a href="javascript:mapShift(1,0);"><img onmouseover="showInfo('Liikuta karttaa vasemmalle');" onmouseout="hideInfo();" class="absolute_button button_left" src="graphics/buttons/button_left.png" /></a>
	<a href="javascript:mapShift(-1,0);"><img onmouseover="showInfo('Liikuta karttaa oikealle');" onmouseout="hideInfo();" class="absolute_button button_right" src="graphics/buttons/button_right.png" /></a>
	<a href="javascript:mapShift(0,-1);"><img onmouseover="showInfo('Liikuta karttaa alas');" onmouseout="hideInfo();" class="absolute_button button_down" src="graphics/buttons/button_down.png" /></a>
	
	<a href="javascript:mapZoom(-1);"><img onmouseover="showInfo('Lähennä karttaa');" onmouseout="hideInfo();" class="absolute_button button_plus" src="graphics/buttons/button_plus.png" /></a>
	<a href="javascript:mapZoom(1);"><img onmouseover="showInfo('Loitonna karttaa');" onmouseout="hideInfo();" class="absolute_button button_minus" src="graphics/buttons/button_minus.png" /></a>
	
	<a href="javascript:mapPosition(fixedPhysicalLatitude, fixedPhysicalLongitude);"><img onmouseover="showInfo('Palaa omaan sijaintiin');" onmouseout="hideInfo();" class="absolute_button button_center" src="graphics/buttons/button_center.png" /></a>

</div>

<div id="mapView">

	<div id="distanceInformation"></div>

	<div id="routeView"></div>
	
	<div id="fieldView"></div>

	<div id="annotationView"></div>

	<div id="foreGroundView"></div>

	<div id="backGroundView"></div>
	
</div>