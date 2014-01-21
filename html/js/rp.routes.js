function clearRoute() {
	
	currentRouteX = 0;
	currentRouteY = 0;
	drawingRoute = false;
	$("#routeView").html("");
	routeArray = new Array();
	routeIndex = 1;
	
}

function openTargetLocationForm() {
	
	overRideDoubleClickLatitude = targetLocationLatitude;
	overRideDoubleClickLongitude = targetLocationLongitude;
	
	$("#formContainer").fadeOut("fast", function() {
		
		$("#formContainer").html("<h1><a href=\"javascript:closeFormContainer();\"><img onmouseover=\"showInfo('Sulje tiedot');\" onmouseout=\"hideInfo();\" class=\"closeButton\" src=\"graphics/buttons/button_close.png\" alt=\"Sulje\" /></a>"+targetLocationTitle+"<br /><a href=\"javascript:placeHome();\" class=\"smallFormButton left\">Siirrä kotipaikka tähän</a><a href=\"javascript:addMapNotice();\" class=\"smallFormButton left\">Luo ilmoitus tähän</a><a href=\"javascript:addMapField();\" class=\"smallFormButton left\">Luo pelto tähän</a><div class=\"sideButtons\"><a href=\"javascript:getMapDirections("+targetLocationLatitude+","+targetLocationLongitude+");\"><img onmouseover=\"showInfo('Hae reittiohjeet');\" onmouseout=\"hideInfo();\" class=\"sideButton\" src=\"graphics/buttons/smallbutton_car.png\" /></a></div></h1>");
		
		$("#formContainer").fadeIn("fast");
		
	});	
	
}

function searchBarSubmit() {
	
	if ($("#searchBarText").val()!="" && $("#searchBarText").val()!="Etsi osoite tai paikkakunta...") {
		
			$.ajax({
				url: "engine/rp.gmaps.get.target.php?address="+encodeURIComponent($("#searchBarText").val())+"&sensor=false"
			}).done(function(data) {
				
				var dataString = JSON.stringify(data, escapeLB);
				var parsedData = jQuery.parseJSON(dataString);
				
				if (parsedData["status"]!="ZERO_RESULTS") {
					
					viewLatitude = parsedData["results"][0]["geometry"]["location"]["lat"];
					viewLongitude = parsedData["results"][0]["geometry"]["location"]["lng"];
					
					targetLocationTitle = parsedData["results"][0]["formatted_address"];
					targetLocationLatitude = viewLatitude;
					targetLocationLongitude = viewLongitude;
					
					currentZoom = minimumZoom*zoomStepping*zoomStepping;
					
					refreshMap();
					openTargetLocationForm();
			
				} else {notify("Kohdetta ei löytynyt.");}
								
			});
		
		} else {notify("Hakusana puuttuu.");}	
	
}

function searchTarget() {
	
	if ($("#rpSearchTarget").val()!="") {
		
		$.ajax({
			url: "engine/rp.gmaps.get.target.php?address="+encodeURIComponent($("#rpSearchTarget").val())+"&sensor=false"
		}).done(function(data) {
			
			var dataString = JSON.stringify(data, escapeLB);
			var parsedData = jQuery.parseJSON(dataString);
			
			if (parsedData["status"]!="ZERO_RESULTS") {
				
				viewLatitude = parsedData["results"][0]["geometry"]["location"]["lat"];
				viewLongitude = parsedData["results"][0]["geometry"]["location"]["lng"];
				
				targetLocationTitle = parsedData["results"][0]["formatted_address"];
				targetLocationLatitude = viewLatitude;
				targetLocationLongitude = viewLongitude;
				
				currentZoom = minimumZoom*zoomStepping*zoomStepping;
				
				closePage();
				refreshMap();
				openTargetLocationForm();
		
			} else {notify("Kohdetta ei löytynyt.");}
							
		});
		
	} else {notify("Kohteen osoite puuttuu.");}	
	
}

function positionRouteCenter() {
		
	var screenMin;
	
	if ($(window).width()>$(window).height()) {screenMin = $(window).height();} else {screenMin = $(window).width();}
		
	currentZoom = sanitizeZoom(1.1*(tileSize/screenMin)*getPolygonMaxSize(routeArray));
		
	var center = getPolygonCenter(routeArray);
	var coordinates = transformCoordinatesReverse(center[0], center[1]);
	
	mapPosition(coordinates[1], coordinates[0]);	
	
}

function toggleRoute(id) {
		
	clearRoute();
	closePage();
	hideInfo();
	$("#distanceInformation").hide();
	drawingRoute = false;
	
	$("#formContainer").fadeOut("fast");
	
	$.ajax({
		type:"POST",
		data: {
			rpRoute:id,
			rpAction:"rpGetRoute"
		},
	    url:"_route.php",
	    success: function(data) {
			
			var data_array = data.split("|break|");
			
			var routes_array = data_array[2].split("|end|");
			
			for (var i=0;i<routes_array.length;i++) {
			
				if (routes_array[i]!="") {
			
					var route_array = routes_array[i].split("|");
					
						routeArray.push(new Array(route_array[0], route_array[1], route_array[2], route_array[3]));
					
					}
				
				}
				
				var coordinates = transformCoordinatesReverse(routeArray[0][0], routeArray[0][1]);
				var coordinates2 = transformCoordinatesReverse(routeArray[routeArray.length-1][2], routeArray[routeArray.length-1][3]);
								
				$("#formContainer").html("<h1><a href=\"javascript:closeFormContainer();\"><img onmouseover=\"showInfo('Sulje tiedot');\" onmouseout=\"hideInfo();\" class=\"closeButton\" src=\"graphics/buttons/button_close.png\" alt=\"Sulje\" /></a>"+data_array[0].replace(" - ","<br />")+"<br /><div class=\"sideButtons\"><a href=\"https://maps.google.fi/maps?saddr="+coordinates[1]+","+coordinates[0]+"&daddr="+coordinates2[1]+","+coordinates2[0]+"&hl=fi\" target=\"_blank\"><img onmouseover=\"showInfo('Näytä tarkemmat ohjeet');\" onmouseout=\"hideInfo();\" class=\"sideButton\" src=\"graphics/buttons/smallbutton_directions.png\" /></a></div></h1><h2 style=\"margin-top: 5px;\">Kokonaispituus: "+sanitizeDistance(data_array[1])+"</h2>");
				
				$("#formContainer").fadeIn("fast");
				
				drawRoute();
			
			}
	});
	
}

function searchRoute() {
	
	if ($("#rpRouteOrigin").val()!="" && $("#rpRouteDestination").val()!="") {
		
			$("#tabContainer").fadeOut(300, function() {
				
				if ($("#rpRouteOrigin").val()=="Kotipaikka") {$("#rpRouteOrigin").val(physicalLatitude+","+physicalLongitude);}
				
				getDirections(0, 0, $("#rpRouteOrigin").val(), $("#rpRouteDestination").val());	
				
			});
		
		} else {notify("Määrittele reitin alku- ja loppukohdat.");}	
	
}

function generateWaypointRoute(string) {
		
	if ($("#waypointRouteStartHome").is(":checked")) {string = physicalLatitude+","+physicalLongitude+";"+string;}
	if ($("#waypointRouteEndHome").is(":checked")) {string = string+physicalLatitude+","+physicalLongitude+";";}
		
	var routes_string_array = string.split(";");
	routes_string_array.pop();	
	
	var waypoints_array = new Array();
	
	for (var i=0;i<routes_string_array.length;i++) {
	
		if (i>0 && i<routes_string_array.length-1) {waypoints_array.push(routes_string_array[i]);}
	
	}
		
	if (routes_string_array.length>1) {
		
		$("#tabContainer").fadeOut(300, function() {
		
			getDirections(0, 0, routes_string_array[0], routes_string_array[routes_string_array.length-1], waypoints_array);
		
		});
		
	}

}

function getMapDistance(x, y, origin, destination) {
		
	if (clientLoggedIn) {
	
		$.ajax({
			url: "engine/rp.gmaps.get.directions.php?origin="+encodeURIComponent(origin)+"&destination="+encodeURIComponent(destination)+"&sensor=false&mode=driving"
		}).done(function(data) {
			outputDirections(data, x, y, "distance");
		}).fail(function() {

  		});
	
	}
	
}

function getDirections(x, y, origin, destination, waypoints) {
				
	if (clientLoggedIn) {
	
		var waypoints_string = "";
	
		if (waypoints) {
			
			if (waypoints.length>0) {
			
				waypoints_string = "&waypoints=";
							
				for (var i=0;i<waypoints.length;i++) {
					
					waypoints_string += waypoints[i]+"|";
					
				}
				
				waypoints_string = waypoints_string.substring(0, waypoints_string.length-1);
			
			}
			
		}

		$.ajax({
			url: "engine/rp.gmaps.get.directions.php?origin="+encodeURIComponent(origin)+"&destination="+encodeURIComponent(destination)+waypoints_string+"&sensor=false&mode=driving"
		}).done(function(data) {
			outputDirections(data, x, y, "menu");
		}).fail(function() {

  		});
	
	}
	
}

function getMapDirections(latitude, longitude) {
	
	$.ajax({
		url: "engine/rp.gmaps.get.directions.php?origin="+encodeURIComponent(physicalLatitude+","+physicalLongitude)+"&destination="+encodeURIComponent(latitude+","+longitude)+"&sensor=false&mode=driving"
	}).done(function(data) {
		outputDirections(data, 0, 0, "map");
	}).fail(function(errori) {

  	});
	
}

function showRoute(center) {
		
	if (routeArray.length>0) {
				
		$("#routeView").html("");
		closePage();
		hideInfo();
		$("#distanceInformation").hide();
		
		$("#formContainer").fadeOut("fast", function() {
			
			$("#formContainer").html("<h1><a href=\"javascript:closeFormContainer();\"><img onmouseover=\"showInfo('Sulje tiedot');\" onmouseout=\"hideInfo();\" class=\"closeButton\" src=\"graphics/buttons/button_close.png\" alt=\"Sulje\" /></a>"+currentRouteStart+"<br />"+currentRouteEnd+"<div class=\"sideButtons\"><a href=\"https://maps.google.fi/maps?saddr="+encodeURIComponent(currentRouteStart)+"&daddr="+currentRouteWaypointsURL+encodeURIComponent(currentRouteEnd)+"&hl=fi\" target=\"_blank\"><img onmouseover=\"showInfo('Näytä tarkemmat ohjeet');\" onmouseout=\"hideInfo();\" class=\"sideButton\" src=\"graphics/buttons/smallbutton_directions.png\" /></a></div></h1><h2 style=\"margin-top: 5px;\">Kokonaispituus: "+sanitizeDistance(currentRouteDistance)+"</h2><div class=\"clear height5\"></div><label for=\"rpRouteTitle\">Reitin nimi: *<br /><input name=\"rpRouteTitle\" id=\"rpRouteTitle\" type=\"text\" class=\"routeTitleText\" value=\""+currentRouteStart+" - "+currentRouteEnd+"\" /></label><div class=\"clear\"></div><a href=\"javascript:saveRoute();\" class=\"smallFormButton left\">Tallenna reitti</a>");
			
			$("#formContainer").fadeIn("fast");
			
		});
		
		if (center) {positionRouteCenter();} else {drawRoute();}
		
	} else {notify("Reittiä ei löytynyt.");}	
	
}

function outputDirections(data, x, y, type) {

	var pointX = new Array();
	var pointY = new Array();
	routeArray = new Array();

	var dataString = JSON.stringify(data, escapeLB);

	var parsedData = jQuery.parseJSON(dataString);

	if (type!="distance") {

		if (parsedData["routes"][0]["overview_polyline"]["points"]) {
	
			var pointsArray = decodePolyLine(parsedData["routes"][0]["overview_polyline"]["points"]);
	
			pointsArray = convertPoints(pointsArray);
	
			for (var b=0;b<pointsArray.length;b+=routeStepper) {
	
				if (pointsArray[b+routeStepper]) {
	
					routeArray.push(new Array(pointsArray[b][0],pointsArray[b][1],pointsArray[b+routeStepper][0],pointsArray[b+routeStepper][1]));
	
				}
	
			}
	
		}
	
	    var routebroken = 1;
					
		while (routebroken>0) {
			routebroken = breakRoute();
		}

	}

	currentRouteDistance = 0;
	currentRouteTime = 0;
	currentRouteWaypointsURL = "";

	var via_string = "";
	    	
   	for (var i=0;i<parsedData["routes"][0]["legs"].length;i++) {
   		
   		currentRouteDistance += parsedData["routes"][0]["legs"][i]["distance"]["value"];
   		currentRouteTime += parsedData["routes"][0]["legs"][i]["duration"]["value"];
   		
   		if (i>0) {
   			
   			currentRouteWaypointsURL += encodeURIComponent(parsedData["routes"][0]["legs"][i]["start_address"])+"+to:";
   			via_string += "<h3>Kautta: "+parsedData["routes"][0]["legs"][i]["start_address"]+"</h3>";
   			
   			}
   		
   	}
   	
   	currentRouteDistance = currentRouteDistance/1000;
   	
   	if (via_string!="") {via_string += "<div class=\"clear height5\"></div>";}

    if (x==0 && y==0) {
        
	    if (routeArray.length>0) {
	    	
	    	currentRouteStart = parsedData["routes"][0]["legs"][0]["start_address"];
		    currentRouteEnd = parsedData["routes"][0]["legs"][parsedData["routes"][0]["legs"].length-1]["end_address"];
	    	
	    	if (type=="menu") {
		    	
		    	$("#tabContainer").html("<h2>Reitti: "+parsedData["routes"][0]["legs"][0]["start_address"]+" - "+parsedData["routes"][0]["legs"][parsedData["routes"][0]["legs"].length-1]["end_address"]+"</h2>"+via_string+"Kokonaispituus: "+sanitizeDistance(currentRouteDistance)+"<br />Kokonaiskesto: "+sanitizeTime(currentRouteTime)+"<div class=\"clear height10\"></div><a href=\"javascript:showRoute(true);\" class=\"formButton left\">Näytä reitti</a><a href=\"javascript:changeTab('routes');\" class=\"formButton left\">Uusi haku</a>");
		    	$("#tabContainer").fadeIn(300);
	    	
	    	} else {

	    		showRoute();
	    		
	    	}
	    	
	    } else {
	    
	    	notify("Reittiä ei löytynyt.");    
	    	$("#tabContainer").fadeIn(300);	
	    	
	    }
    
    } else {
    	
		showDistanceInformation(sanitizeMinimalDistance(currentRouteDistance), sanitizeMinimalTime(currentRouteTime), x, y);
    	
    }
      
}

function removeRoute(id) {
	
	$.ajax({
		type:"POST",
		data: {
			rpRoute:id,
			rpAction:"rpRemoveRoute"
		},
	    url:"_route.php",
	    success: function(data) {
	    	if (data=="SUCCESS") {
	    	
	    	$("#routeDiv_"+id).fadeOut(300);
	    	
	    	} else {notify(data);} 	    	
	    }
	});
	
}

function saveRoute() {
	
	if ($("#rpRouteTitle").val()!="") {
	
	var route = "";
	
	if (routeArray.length>0) {
		
		for (var i=0;i<routeArray.length;i++) {
			
			route += routeArray[i][0]+"|"+routeArray[i][1]+"|"+routeArray[i][2]+"|"+routeArray[i][3]+"|end|";
									
		}
		
	}
	
	$.ajax({
		type:"POST",
		data: {
			rpRoute:route,
			rpAction:"rpSaveRoute",
			rpRouteTitle:$("#rpRouteTitle").val(),
			rpRouteDistance:getPolygon(routeArray, "length")
		},
	    url:"_route.php",
	    success: function(data) {
	    	if (data=="SUCCESS") {
	    		
	    	closeFormContainer();
	    	notify("Reitti tallennettiin.");
	    	
	    	} else {notify(data);}
	    }
	});

	} else {notify("Reitin nimi puuttuu.");}
	
}

function stopRouteDrawing() {
	
	clearRoute();
	closeFormContainer();	
	
}

function drawRouteLine(x,y,x2,y2,lineColor,lineWidth) {
			
	var vcoordinates = transformVisibleCoordinatesRev(x, y);
	var vcoordinates2 = transformVisibleCoordinatesRev(x2, y2);
	
	x = vcoordinates[0];
	y = vcoordinates[1];
	x2 = vcoordinates2[0];
	y2 = vcoordinates2[1];
	
	var width = Math.sqrt(Math.pow((x-x2),2))+lineWidth;
	var height = Math.sqrt(Math.pow((y-y2),2))+lineWidth;
		
	var pos_x = x;
	var pos_y = y;
	
	if (x2<x) {
		
		pos_x = pos_x - width + lineWidth;
		
	}
	
	if (y2<y) {
		
		pos_y = pos_y - height + lineWidth;
		
	}
		
	$("#routeView").append("<canvas class=\"routeCanvas\" style=\"left: "+pos_x+"px; top: "+pos_y+"px;\" id=\"route_"+routeIndex+"\" width=\""+width+"\" height=\""+height+"\"></canvas>");
	
	var canvas = document.getElementById("route_"+routeIndex);
		
	var context = canvas.getContext("2d");
	
	var xx; var yy; var xx2; var yy2;
	
	xx = (lineWidth/2); yy = (lineWidth/2); xx2 = width-(lineWidth/2); yy2 = height-(lineWidth/2);
	
	if (x2>x) {
		
		xx = width-(lineWidth/2);
		xx2 = (lineWidth/2);
		
	}
	
	if (y2>y) {
		
		yy = height-(lineWidth/2);
		yy2 = (lineWidth/2);
		
	}
		
	context.beginPath();
	context.moveTo(xx,yy);
	context.lineTo(xx2,yy2);
	context.closePath();
	context.lineWidth = lineWidth;
	context.strokeStyle = lineColor;
	context.stroke();
	
	routeIndex += 1;
			
}

function breakRoute() {

	var breaksfound = 0;

	if (routeArray.length>0) {
		
		var newRouteArray = new Array();
		
		for (var i=0;i<routeArray.length;i++) {
	
			newRouteArray.push(routeArray[i]);
	
			if (getPointDistance(newRouteArray[i][0],newRouteArray[i][1],newRouteArray[i][2],newRouteArray[i][3])>breakDistance) {
				
				var new_x = newRouteArray[i][0]+(newRouteArray[i][2]-newRouteArray[i][0])/2;
				var new_y = newRouteArray[i][1]+(newRouteArray[i][3]-newRouteArray[i][1])/2;
										
				newRouteArray.push(new Array(new_x,new_y,newRouteArray[i][2],newRouteArray[i][3]));
				
				newRouteArray[i][2] = new_x;
				newRouteArray[i][3] = new_y;
				
				breaksfound += 1;
				
			}
			
		}
		
		routeArray = newRouteArray;
				
	}
	
	return breaksfound;
	
}

function startRouteDrawing() {
	
	clearRoute();
	closePage();
	hideInfo();
	$("#distanceInformation").hide();
	
	drawingRoute = true;
	
	$("#formContainer").fadeOut("fast").html("<h1>Piirrä uusi reitti<a href=\"javascript:stopRouteDrawing();\"><img onmouseover=\"showInfo('Lopeta reitin piirtäminen');\" onmouseout=\"hideInfo();\" class=\"closeButton\" src=\"graphics/buttons/button_close.png\" alt=\"Sulje\" /></a></h1><div id=\"routeInformation\">Aloita klikkaamalla kartalta aloituspiste</div>");
	
	$("#formContainer").fadeIn("fast");
	
}

function drawRoute() {
	
	if (routeArray.length>0) {
				
		for (var i=0;i<routeArray.length;i++) {
						
			drawRouteLine(routeArray[i][0],routeArray[i][1],routeArray[i][2],routeArray[i][3],routeLineColor,routeLineWidth);
						
		}
				
	}
		
}