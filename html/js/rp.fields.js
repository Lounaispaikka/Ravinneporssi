
function clearField() {
	
	currentFieldX = 0;
	currentFieldY = 0;
	drawingField = false;
	reDrawField = 0;
	$("#fieldView").html("");
	fieldArray = new Array();
	fieldIndex = 1;
	
}

function stopFieldDrawing() {
	
	reDrawField = 0;
	
	clearField();
	closeFormContainer();	
	
}

function addField() {
	
	$("#tabContainer").fadeOut(100, function() {
			
		$("#tabContainer").load("_fields.php?id=addfield", function() {
			
				$("#tabContainer").fadeIn(100);
			
			});
		
	});	
	
}

function saveNewFieldSettings() {
	
	if ($("#rpFieldTitle").val()!="") {
		
		if (overRideDoubleClickLatitude>0 && overRideDoubleClickLongitude>0) {
				
			var coordinates3 = transformCoordinates(overRideDoubleClickLongitude, overRideDoubleClickLatitude);
			
			$("#rpFieldLatitude").val(overRideDoubleClickLatitude);
			$("#rpFieldLongitude").val(overRideDoubleClickLongitude);
			$("#rpFieldPosX").val(coordinates3[0]);
			$("#rpFieldPosY").val(coordinates3[1]);
			
		} else {
	
			var coordinates = transformVisibleCoordinates(doubleClickX, doubleClickY);
			var coordinates2 = transformCoordinates(coordinates[0], coordinates[1]);
					
			$("#rpFieldLatitude").val(coordinates[1]);
			$("#rpFieldLongitude").val(coordinates[0]);
			$("#rpFieldPosX").val(coordinates2[0]);
			$("#rpFieldPosY").val(coordinates2[1]);
		
		}
		
		$.ajax({
			type:"POST",
			data: $("#addFieldForm").serialize(),
		    url:"_field.php",
		    success: function(data) {
		    	if (data=="SUCCESS") {

					closePage();
					refreshMap();
					
					closeFormContainer();
	
					$(".annotation_location").remove();
	
					targetLocationTitle = "";
					targetLocationLatitude = "";
					targetLocationLongitude = "";
						
		        	notify("Pelto on tallennettu.");
				
		    	} else {notify(data);}
		    }
		});
		
	} else {notify("Puuttuvia kohtia lomakkeessa.");}
		
}

function addMapField() {
	
	forceAddField = true;
	openPage("actions");	
	
}

function positionField(id) {
	
	rePositionField = id;
	
	closePage();
	hideInfo();
	$("#distanceInformation").hide();	
	
	$("#formContainer").fadeOut("fast", function() {
		
		$("#formContainer").html("<h1>Klikkaa kartalta uusi paikka pellolle<img onmouseover=\"showInfo('Sulje');\" onmouseout=\"hideInfo();\" class=\"closeButton\" onclick=\"closeRepositioning();\" src=\"graphics/buttons/button_close.png\" alt=\"Sulje\" /></h1><a href=\"javascript:closeRepositioning();\" class=\"smallFormButton left\">Sulje</a>");
	
		$("#formContainer").fadeIn("fast");
	
	});
	
}

function startReDrawingField(id) {
	
	reDrawField = id;
	
	drawingField = true;
	
	currentFieldX = 0;
	currentFieldY = 0;
	$("#fieldView").html("");
	fieldArray = new Array();
	fieldIndex = 1;
	
	closePage();
	hideInfo();
	$("#distanceInformation").hide();	
	
	$("#formContainer").fadeOut("fast", function() {
		
		$("#formContainer").html("<h1>Piirrä pellon rajat<a href=\"javascript:stopFieldDrawing();\"><img onmouseover=\"showInfo('Lopeta pellon piirtäminen');\" onmouseout=\"hideInfo();\" class=\"closeButton\" src=\"graphics/buttons/button_close.png\" alt=\"Sulje\" /></a></h1><div id=\"fieldInformation\">Aloita klikkaamalla kartalta aloituspiste</div>");
	
		$("#formContainer").fadeIn("fast");
	
	});
	
}

function saveFieldSettings() {
	
	if ($("#rpFieldTitle").val()!="") {
		
		$.ajax({
			type:"POST",
			data: $("#saveFieldForm").serialize(),
		    url:"_field.php",
		    success: function(data) {
		    	if (data=="SUCCESS") {

					changeTab("fields");
		        	notify("Pelto on tallennettu.");
				
		    	} else {notify(data);}
		    }
		});
		
	} else {notify("Puuttuvia kohtia lomakkeessa.");}
		
}

function removeField(id) {
	
	closeFormContainer();
	
	$.ajax({
		type:"POST",
		data: {
			rpField:id,
			rpAction:"rpRemoveField"
		},
	    url:"_field.php",
	    success: function(data) {
	    	if (data=="SUCCESS") {
	    	
	    	$("#annotation_field_"+id).remove();
	    	
	    	$("#fieldDiv_"+id).fadeOut(300);
	    	
	    	} else {notify(data);} 	    	
	    }
	});
	
}

function openMapField(id) {

	clearRoute();
	forceOpenField = id;
	openPage("actions");	
	
}

function editField(id) {
		
	$("#tabContainer").fadeOut(100, function() {
		
		$("#tabContainer").load("_fields.php?id=editfield&fieldid="+id, function() {
			
			$("#tabContainer").fadeIn(100);
		
		});
	
	});
	
}

function drawFieldLine(x,y,x2,y2,lineColor,lineWidth) {
			
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
		
	$("#fieldView").append("<canvas class=\"fieldCanvas\" style=\"left: "+pos_x+"px; top: "+pos_y+"px;\" id=\"field_"+fieldIndex+"\" width=\""+width+"\" height=\""+height+"\"></canvas>");
	
	var canvas = document.getElementById("field_"+fieldIndex);
		
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
	
	fieldIndex += 1;
			
}

function drawField(id) {
		
	$.ajax({
		type:"POST",
		data: {
			rpField:id,
			rpAction:"rpGetFieldPolygon"
		},
	    url:"_field.php",
	    success: function(data) {
	    		    	
	    	var data_array = data.split("|end|");
	    		    	
	    	if (data_array.length>0) {
				
				for (var i=0;i<data_array.length;i++) {
	    
	    			if (data_array[i]!="") {
	    	    
	    				var polygon_array = data_array[i].split("|");
	    	    
	    				drawFieldLine(polygon_array[0],polygon_array[1],polygon_array[2],polygon_array[3],fieldLineColor,fieldLineWidth);
	    
	    			}
	    
				}
				
	    	}
	    	 	    	    	
	    }
	});
	
}

function toggleField(id) {
	
	clearField();
	closePage();
	hideInfo();
	$("#distanceInformation").hide();	
	drawingField = false;
	
	$("#formContainer").fadeOut("fast");
	
	$.ajax({
		type:"POST",
		data: {
			rpField:id,
			rpAction:"rpGetField"
		},
	    url:"_field.php",
	    success: function(data) {
	    	 
	    	var data_array = data.split("|break|");
	    	 	    	 	    	 
	    	$("#formContainer").html("<h1><a href=\"javascript:closeFormContainer();\"><img onmouseover=\"showInfo('Sulje tiedot');\" onmouseout=\"hideInfo();\" class=\"closeButton\" src=\"graphics/buttons/button_close.png\" alt=\"Sulje\" /></a>"+data_array[0]+"<br /><div class=\"sideButtons\"><a href=\"javascript:getMapDirections("+data_array[2]+","+data_array[3]+");\"><img onmouseover=\"showInfo('Hae reittiohjeet');\" onmouseout=\"hideInfo();\" class=\"sideButton\" src=\"graphics/buttons/smallbutton_car.png\" /></a></div></h1><h2 style=\"margin-top: 5px;\">Pinta-ala: "+sanitizeArea(data_array[1])+" m<sup>2</sup> ("+sanitizeAreaHectare(data_array[1])+" ha)</h2><a href=\"javascript:openMapField("+id+");\" class=\"smallFormButton left\">Muokkaa</a>");
		
			mapPosition(data_array[2], data_array[3]);
	
			$("#formContainer").fadeIn("fast");
	    	
	    	drawField(id);
	    		
	    }
	});
	
}

function fetchFields(x, y, zoom) {

	$.ajax({
		type: "POST",
		url: "engine/rp.wms.get.fields.php",
		data: {tileX: x, tileY: y, tileZoom: zoom}
	}).done(function(data) {
		processAnnotationsXML(data);
	});
	
}

function toggleMapField(x, y, id) {
	
	clearField();	
	hideInfo();
	
	drawField(id);
	
	$("#formContainer").fadeOut("fast", function() {
		
		$("#formContainer").load("engine/rp.wms.get.field.php?id="+id, function() {
			
			$("#formContainer").fadeIn("fast");
			
		});
		
	});
			
}

function saveFieldReDrawing() {
	
	var field = "";
	
	if (fieldArray.length>0) {
		
		for (var i=0;i<fieldArray.length;i++) {
			
			field += fieldArray[i][0]+"|"+fieldArray[i][1]+"|"+fieldArray[i][2]+"|"+fieldArray[i][3]+"|end|";
									
		}
		
	}
	
	var centerPoint = getPolygonCenter(fieldArray);
	var coordinates = transformCoordinatesReverse(centerPoint[0], centerPoint[1]);
	
	$.ajax({
		type:"POST",
		data: {
			rpField:field,
			rpReDrawField:reDrawField,
			rpAction:"rpSaveFieldDrawing",
			rpFieldLatitude:coordinates[1],
			rpFieldLongitude:coordinates[0],
			rpFieldPosX:centerPoint[0],
			rpFieldPosY:centerPoint[1],
			rpFieldArea:getPolygon(fieldArray, "area")
		},
	    url:"_field.php",
	    success: function(data) {
	    	if (data=="SUCCESS") {
	    		
				refreshMap();
		    	stopFieldDrawing();
		    	notify("Pellon rajat tallennettiin.");
	    		    	
	    	} else {notify(data);} 	    	
	    }
	});
	
}

function saveField() {
	
	if ($("#rpFieldTitle").val()!="") {
	
	var field = "";
	
	if (fieldArray.length>0) {
		
		for (var i=0;i<fieldArray.length;i++) {
			
			field += fieldArray[i][0]+"|"+fieldArray[i][1]+"|"+fieldArray[i][2]+"|"+fieldArray[i][3]+"|end|";
									
		}
		
	}
	
	var centerPoint = getPolygonCenter(fieldArray);
	var coordinates = transformCoordinatesReverse(centerPoint[0], centerPoint[1]);
	
	$.ajax({
		type:"POST",
		data: {
			rpField:field,
			rpAction:"rpSaveField",
			rpFieldLatitude:coordinates[1],
			rpFieldLongitude:coordinates[0],
			rpFieldX:centerPoint[0],
			rpFieldY:centerPoint[1],
			rpFieldTitle:$("#rpFieldTitle").val(),
			rpFieldArea:getPolygon(fieldArray, "area")
		},
	    url:"_field.php",
	    success: function(data) {
	    	if (data=="SUCCESS") {
	    		
				refreshMap();
		    	stopFieldDrawing();
		    	notify("Pelto tallennettiin.");
	    		    	
	    	} else {notify(data);} 	    	
	    }
	});

	} else {notify("Pellon nimi puuttuu.");}
	
}

function startFieldDrawing() {
	
	clearField();
	closePage();
	hideInfo();
	$("#distanceInformation").hide();
	
	drawingField = true;
	
	$("#formContainer").fadeOut("fast").html("<h1>Piirrä uusi pelto<a href=\"javascript:stopFieldDrawing();\"><img onmouseover=\"showInfo('Lopeta pellon piirtäminen');\" onmouseout=\"hideInfo();\" class=\"closeButton\" src=\"graphics/buttons/button_close.png\" alt=\"Sulje\" /></a></h1><div id=\"fieldInformation\">Aloita klikkaamalla kartalta aloituspiste</div>");
	
	$("#formContainer").fadeIn("fast");
	
}