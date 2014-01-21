
function foundLocation(position) {
	currentLatitude = position.coords.latitude;
	currentLongitude = position.coords.longitude;
	viewLatitude = currentLatitude;
	viewLongitude = currentLongitude;
	physicalLatitude = currentLatitude;
	physicalLongitude = currentLongitude;
	refreshMap();
}

function foundFixedLocation(position) {
	fixedPhysicalLatitude = position.coords.latitude;
	fixedPhysicalLongitude = position.coords.longitude;
}

function errorHandler(error) {
	
	if (error.PERMISSION_DENIED){
		alert("Et ole hyväksynyt paikannusta. Ole hyvä ja salli paikannus selaimesi asetuksista.");
	}
	
	currentLatitude = fallBackLatitude;
	currentLongitude = fallBackLongitude;
	currentZoom = fallBackZoom;
	viewLatitude = currentLatitude;
	viewLongitude = currentLongitude;
	physicalLatitude = currentLatitude;
	physicalLongitude = currentLongitude;
	refreshMap();
	
}

function fixedErrorHandler(error) {
	
	fixedPhysicalLatitude = fallBackLatitude;
	fixedPhysicalLongitude = fallBackLongitude;
	currentZoom = fallBackZoom;
	
}

function getLocation() {
	if (navigator.geolocation) {
		var options = {timeout:60000};
		navigator.geolocation.getCurrentPosition(foundLocation, errorHandler, options);
	} else {
		alert("Virhe paikannuksessa.");
	}
}

function getFixedLocation() {
	if (navigator.geolocation) {
		var options = {timeout:60000};
		navigator.geolocation.getCurrentPosition(foundFixedLocation, fixedErrorHandler, options);
	} else {
		alert("Virhe paikannuksessa.");
	}
}

function updateClient() {
	
	var map_layers = "";
	
	if ($("#toggleLayer_1_1").attr("checked")) {map_layers += "1_1|";}
	if ($("#toggleLayer_2_1").attr("checked")) {map_layers += "2_1|";}
	if ($("#toggleLayer_2_2").attr("checked")) {map_layers += "2_2|";}
	if ($("#toggleLayer_2_3").attr("checked")) {map_layers += "2_3|";}
	
	var annotations = "";
	
	if ($("#toggleAnnotations_output").attr("checked")) {annotations += "output|";}
	if ($("#toggleAnnotations_input").attr("checked")) {annotations += "input|";}
	if ($("#toggleAnnotations_contractor").attr("checked")) {annotations += "contractor|";}
	if ($("#toggleAnnotations_field").attr("checked")) {annotations += "field|";}
	
	$.ajax({
	type: "POST",
	url: "_client.php",
	data: {currentLatitude: viewLatitude,
		currentLongitude: viewLongitude,
		currentZoom: currentZoom,
		currentLayers: map_layers,
		currentAnnotations: annotations,
		rpAction: "rpUpdateClientPosition"
		}
	}).done(function(data) {

	});

}

function closeRepositioning() {
	
	rePositionNotice = 0;
	rePositionField = 0;
		
	$("#formContainer").fadeOut("fast");
	
}


function placeHome() {
	
	$(".annotation_home").remove();
	
	var coordinates;
	var coordinates2;
	
	if (overRideDoubleClickLatitude>0 && overRideDoubleClickLongitude>0) {
		
		coordinates = new Array(overRideDoubleClickLongitude, overRideDoubleClickLatitude);
		coordinates2 = transformCoordinates(overRideDoubleClickLongitude, overRideDoubleClickLatitude);
		
		$(".annotation_location").remove();
		
		targetLocationTitle = "";
		targetLocationLatitude = "";
		targetLocationLongitude = "";
		
	} else {
		
		coordinates = transformVisibleCoordinates(doubleClickX, doubleClickY);
		coordinates2 = transformCoordinates(coordinates[0], coordinates[1]);
		
	}
	
	baseLatitude = coordinates[1];
	baseLongitude = coordinates[0];
	physicalLatitude = baseLatitude;
	physicalLongitude = baseLongitude;
	
	$.ajax({
	type: "POST",
	url: "_client.php",
	data: {baseLatitude: baseLatitude,
		baseLongitude: baseLongitude,
		rpAction: "rpUpdateClientHomePosition"
		}
	}).done(function(data) {
		closeFormContainer();
	});
	
	placeAnnotation(coordinates2[0], coordinates2[1], 80, "home", "home", 0, false);
	
	closePage();
	
}

function mapShift(x,y) {
	
	var horizontalShift = ($(window).width()/2)*x;
	var verticalShift = ($(window).height()/2)*y;
	
	$("#mapView").animate({"left": "+="+horizontalShift+"px", "top": "+="+verticalShift+"px"}, "slow", function() {iterateTiles();});
	
}

function mapZoom(volume) {
	
	if (volume>0) {
		currentZoom = (currentZoom*zoomStepping);
	} else {
		currentZoom = (currentZoom/zoomStepping);
	}
	
	currentZoom = Math.round(currentZoom/minimumZoom)*minimumZoom;
				
	if (currentZoom>maximumZoom) {currentZoom = maximumZoom;}
	if (currentZoom<minimumZoom) {currentZoom = minimumZoom;}
		
	refreshMap();
	
}

function toggleMenuControls() {
	
	hideInfo();
	
	if ($("#menuControls").position().left<0) {
		
		$("#menuControls").animate({"left": "0px"}, "slow");
		$("#toggleMenuControlsButton").animate({"left": "295px"}, "slow");
		$("#toggleMenuControlsButton").attr("src","graphics/buttons/button_back.png");
		
	} else {
		
		$("#menuControls").animate({"left": "-290px"}, "slow");
		$("#toggleMenuControlsButton").animate({"left": "5px"}, "slow");
		$("#toggleMenuControlsButton").attr("src","graphics/buttons/button_front.png");
		
	}	

}

function mapPosition(lat, lon) {
	
	if (lat>0 && lon>0) {
	
		currentLatitude = lat;
		currentLongitude = lon;
		viewLatitude = lat;
		viewLongitude = lon;
		
		refreshMap();
	
	} else {notify("Valittua paikkaa ei ole määritelty.");}
	
}

function showDistanceInformation(distance, time, x, y) {
	
	$("#distanceInformation").html("<div class=\"distanceButtons\"><img onmouseover=\"showInfo('Maanteitse');\" onmouseout=\"hideInfo();\" class=\"distanceButton\" src=\"graphics/buttons/smallbutton_car_a.png\" /></div><h3>Etäisyys maanteitse</h3>"+distance+" ~ "+time);
	
	var coordinates = transformVisibleCoordinatesRev(x, y);
		
	$("#distanceInformation").css("top", coordinates[1]-160);
	$("#distanceInformation").css("left", coordinates[0]-85);
	
	$("#distanceInformation").fadeIn("fast");
	
}

function toggleLayer(level, layer) {
	
	clearField();
	clearRoute();
	
	if (level==1) {
		
		if ($("#toggleLayer_"+level+"_"+layer).attr("checked")) {
		
			currentForeGroundLayer = mapLayers[level+"_"+layer];	
	
		} else {
			
			currentForeGroundLayer = "";
			
		}
	
	} else if (level==2) {
	
		currentBackGroundLayer = mapLayers[level+"_"+layer];
	
		$(".buttonlevel_"+level).attr("checked", false);
		$("#toggleLayer_"+level+"_"+layer).attr("checked", true);
	
	}
	
	refreshMap();
	
}

function fetchAnnotations(x, y, zoom) {

	$.ajax({
		type: "POST",
		url: "engine/rp.wms.get.annotations.php",
		data: {tileX: x, tileY: y, tileZoom: zoom}
	}).done(function(data) {
		processAnnotationsXML(data);
	});
	
}

function processAnnotationsXML(data) {
	
	var xmlData = data.split("</annotation>");
	
	for (var i in xmlData) {
		
		if (xmlData[i]!="") {
		
			placeAnnotation(xmlData[i].between("<x>", "</x>"),
				xmlData[i].between("<y>", "</y>"),
				xmlData[i].between("<size>", "</size>"),
				xmlData[i].between("<type>", "</type>"),
				xmlData[i].between("<image>", "</image>"),
				xmlData[i].between("<id>", "</id>"),
				xmlData[i].between("<enabled>", "</enabled>"));
				
		}
		
	}
	
}

function refreshAnnotations() {
		
	$("#annotationView").children("img").each(function () {
		
		if ($(this).hasClass("annotation_input")) {
			
			if ($("#toggleAnnotations_input").is(':checked')) {$(this).show();} else {$(this).hide();}
			
		}
		
		if ($(this).hasClass("annotation_output")) {
			
			if ($("#toggleAnnotations_output").is(':checked')) {$(this).show();} else {$(this).hide();}
			
		}
		
		if ($(this).hasClass("annotation_contractor")) {
			
			if ($("#toggleAnnotations_contractor").is(':checked')) {$(this).show();} else {$(this).hide();}
			
		}
		
		if ($(this).hasClass("annotation_field")) {
			
			if ($("#toggleAnnotations_field").is(':checked')) {$(this).show();} else {$(this).hide();}
			
		}
		
	});
	
}

function toggleAnnotation(x, y, id) {
	
	hideInfo();
	
	$("#formContainer").fadeOut("fast", function() {
		
		$("#formContainer").load("engine/rp.wms.get.annotation.php?id="+id, function() {
			
			$("#formContainer").fadeIn("fast");
			
		});
		
	});
			
}

function placeAnnotation(x, y, size, type, image, id, enabled) {
		
	var coordinates = transformVisibleCoordinatesRev(x, y);
	var coordinates2 = transformCoordinatesReverse(x, y);
	
	if (type!="field") {
	
		if (enabled=="true") {
		
			$("#annotationView").append("<img id=\"annotation_notice_"+id+"\" onmouseover=\"showInfo('Näytä tiedot');\" onmouseout=\"hideInfo();\" onclick=\"toggleAnnotation("+coordinates[0]+", "+coordinates[1]+", "+id+"); getMapDistance("+x+", "+y+", '"+physicalLatitude+","+physicalLongitude+"','"+coordinates2[1]+","+coordinates2[0]+"');\" class=\"annotation annotation_"+type+"\" style=\"top: "+(coordinates[1]-((size/100)*annotationMaxHeight))+"px; left: "+(coordinates[0]-(((size/100)*annotationMaxWidth))/2)+"px; width: "+((size/100)*annotationMaxWidth)+"px; height: "+((size/100)*annotationMaxHeight)+"px;\" src=\"graphics/annotations/annotation_"+image+".png\" />");
		
		} else if (type=="home") {
					
			$("#annotationView").append("<img onmouseover=\"showInfo('Kotipaikka');\" onmouseout=\"hideInfo();\" class=\"annotation annotation_"+type+"\" style=\"top: "+(coordinates[1]-((size/100)*annotationMaxHeight))+"px; left: "+(coordinates[0]-(((size/100)*annotationMaxWidth))/2)+"px; width: "+((size/100)*annotationMaxWidth)+"px; height: "+((size/100)*annotationMaxHeight)+"px;\" src=\"graphics/annotations/annotation_"+image+".png\" />");
			
		} else if (type=="gps") {
					
			$("#annotationView").append("<img onmouseover=\"showInfo('Olet tässä');\" onmouseout=\"hideInfo();\" class=\"annotation annotation_"+type+"\" style=\"top: "+(coordinates[1]-((size/100)*annotationMaxHeight))+"px; left: "+(coordinates[0]-(((size/100)*annotationMaxWidth))/2)+"px; width: "+((size/100)*annotationMaxWidth)+"px; height: "+((size/100)*annotationMaxHeight)+"px;\" src=\"graphics/annotations/annotation_"+image+".png\" />");
			
		} else {
			
			$("#annotationView").append("<img onmouseover=\"showInfo('"+targetLocationTitle+"');\" onmouseout=\"hideInfo();\" onclick=\"openTargetLocationForm(); getDirections("+x+", "+y+", '"+physicalLatitude+","+physicalLongitude+"','"+coordinates2[1]+","+coordinates2[0]+"');\" class=\"annotation annotation_"+type+"\" style=\"top: "+(coordinates[1]-((size/100)*annotationMaxHeight))+"px; left: "+(coordinates[0]-(((size/100)*annotationMaxWidth))/2)+"px; width: "+((size/100)*annotationMaxWidth)+"px; height: "+((size/100)*annotationMaxHeight)+"px;\" src=\"graphics/annotations/annotation_"+image+".png\" />");
			
		}
	
	} else {
		
		$("#annotationView").append("<img id=\"annotation_field_"+id+"\" onmouseover=\"showInfo('Näytä pellon tiedot');\" onmouseout=\"hideInfo();\" onclick=\"toggleMapField("+coordinates[0]+", "+coordinates[1]+", "+id+"); getMapDistance("+x+", "+y+", '"+physicalLatitude+","+physicalLongitude+"','"+coordinates2[1]+","+coordinates2[0]+"');\" class=\"annotation annotation_"+type+"\" style=\"top: "+(coordinates[1]-((size/100)*annotationMaxHeight))+"px; left: "+(coordinates[0]-(((size/100)*annotationMaxWidth))/2)+"px; width: "+((size/100)*annotationMaxWidth)+"px; height: "+((size/100)*annotationMaxHeight)+"px;\" src=\"graphics/annotations/annotation_"+image+".png\" />");	
		
	}
	
	refreshAnnotations();
	
}

function centerMap() {
	
	$("#mapView").css("left", parseInt(($(window).width()/2)));
	$("#mapView").css("top", parseInt(($(window).height()/2)-tileSize));
		
}

function iterateTiles() {
	
	var currentVisiblePosition = $("#mapView").position();
	
	var currentVisibleX = currentVisiblePosition.left;
	var currentVisibleY = currentVisiblePosition.top;

	var coordinates = transformCoordinates(currentLongitude, currentLatitude);

	var centerX = parseInt(($(window).width()/2));
	var centerY = parseInt(($(window).height()/2)-tileSize);

	var currentX = coordinates[0]+((centerX-currentVisibleX)*(currentZoom/tileSize));
	var currentY = coordinates[1]+((currentVisibleY-centerY)*(currentZoom/tileSize));

	var currentPosition = transformCoordinatesReverse(currentX, currentY);

	viewLongitude = currentPosition[0];
	viewLatitude = currentPosition[1];

	for (var b=0;b<iterationSpace;b++) {

		for (var i=0;i<(b*2+1);i++) {
				
			for (var a=0;a<(b*2+1);a++) {
	
				if ((b*-1+i)==b || (b*-1+i)==-b || (b*-1+a)==b || (b*-1+a)==-b) {
	
					if (mapTiles[(b*-1+i)+","+(b*-1+a)]==undefined) {
			
						var tileVisibleY = currentVisibleY+(b*-1+i)*tileSize;
						var tileVisibleX = currentVisibleX+(b*-1+a)*tileSize;
						
						if (tileVisibleX>-tileSize && tileVisibleX<$(window).width() && tileVisibleY>-tileSize && tileVisibleY<$(window).height()) {
							
							var tileY = parseInt(coordinates[0])+(b*-1+a)*currentZoom;
							var tileX = parseInt(coordinates[1])-(b*-1+i)*currentZoom;
							
							if (currentBackGroundLayer!="") {
																
								$("#backGroundView").append("<img class=\"mapTile\" style=\"top: "+(tileVisibleY-currentVisibleY)+"px; left: "+(tileVisibleX-currentVisibleX)+"px;\" src=\"engine/rp.wms.get.map.php?source=mml&layer="+currentBackGroundLayer+"&zoom="+currentZoom+"&size="+tileSize+"&x="+tileX+"&y="+tileY+"\" />");
							
							}
							
							if (currentForeGroundLayer!="") {
							
								$("#foreGroundView").append("<img class=\"mapTile\" style=\"top: "+(tileVisibleY-currentVisibleY)+"px; left: "+(tileVisibleX-currentVisibleX)+"px;\" src=\"engine/rp.wms.get.map.php?source=plr&layer="+currentForeGroundLayer+"&zoom="+currentZoom+"&size="+tileSize+"&x="+((tileX+compensateX)*multiplyCompensateX)+"&y="+((tileY+compensateY)*multiplyCompensateY)+"\" />");
							
							}
							
							fetchAnnotations(tileY, tileX, currentZoom);
							
							fetchFields(tileY, tileX, currentZoom);
														
							mapTiles[(b*-1+i)+","+(b*-1+a)] = tileSize;
							
						}	

					}
	
				}
	
			}
		
		}	
	
	}
		
}

function getPolygon(parray, type) {
	
	var tooutput = 0;
	
	if (type=="length") {
		
		if (parray.length>0) {
		
			for (var i=0;i<parray.length;i++) {
		
			var coordinates = transformCoordinatesReverse(parray[i][0],parray[i][1]);
			var coordinates2 = transformCoordinatesReverse(parray[i][2],parray[i][3]);
				
			tooutput += getDistance(coordinates[1], coordinates[0], coordinates2[1], coordinates2[0]);
					
			}
			
		}
		
	} else if (type=="area") {
		
		var custom_array = new Array();
		
		if (parray.length>0) {
		
			for (var i=0;i<parray.length;i++) {
		
			custom_array.push(new Array(parray[i][0],parray[i][1]));
					
			}
			
			custom_array.push(new Array(parray[parray.length-1][2],parray[parray.length-1][3]));
			custom_array.push(new Array(parray[0][0],parray[0][1]));
			
			var sum_x = 0;
			var sum_y = 0;
			
			for (var i=0;i<custom_array.length-1;i++) {
				
				sum_x += custom_array[i][0]*custom_array[i+1][1];
				sum_y += custom_array[i][1]*custom_array[i+1][0];
				
			}
			
			tooutput = (sum_x-sum_y)/2;
				
		} else {tooutput = 0;}
		
	}
	
	return Math.sqrt(tooutput*tooutput);
	
}

function refreshMap(delay) {

	if (currentZoom>=mapMaxZoom[currentBackGroundLayer]) {notify("Karttapohja ei toimi valitulla zoomaustasolla.");}

	currentLatitude = viewLatitude;
	currentLongitude = viewLongitude;

	mapTiles = new Array();
	$("#foreGroundView").html("");
	$("#backGroundView").html("");
	$("#annotationView").html("");
	$("#routeView").html("");
	$("#distanceInformation").hide();

	clearField();
	
	if (delay) {
		window.setTimeout(function() {centerMap();}, 1000);
	} else {
		centerMap();
	}
	
	iterateTiles();
	drawRoute();
	
	if (baseLatitude>0 && baseLongitude>0) {
		
		physicalLatitude = baseLatitude;
		physicalLongitude = baseLongitude;
		
		var coordinates = transformCoordinates(baseLongitude, baseLatitude);
		
		placeAnnotation(coordinates[0], coordinates[1], 80, "home", "home", 0, false);
		
	}
	
	if (fixedPhysicalLatitude>0 && fixedPhysicalLongitude>0) {
		
		var coordinates3 = transformCoordinates(fixedPhysicalLongitude, fixedPhysicalLatitude);
		
		placeAnnotation(coordinates3[0], coordinates3[1], 50, "gps", "gps", 0, false);
		
	}
	
	if (targetLocationTitle!="" && targetLocationLatitude>0 && targetLocationLongitude>0) {
			
		var coordinates2 = transformCoordinates(targetLocationLongitude, targetLocationLatitude);
		
		placeAnnotation(coordinates2[0], coordinates2[1], 80, "location", "location", 0, false);
		
	}
	
}