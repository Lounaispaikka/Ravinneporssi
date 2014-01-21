function addNotice() {
	
	$("#tabContainer").fadeOut(100, function() {
			
		$("#tabContainer").load("_notices.php?id=addnotice&address="+encodeURIComponent(noticePreDefinedAddress), function() {
			
				$("#tabContainer").fadeIn(100);
			
			});
		
	});	
	
}

function getNewNotification() {
		
	if (notifierEnabled) {
		
		$.ajax({
			type:"POST",
			data: {
				rpAction:"rpGetNotifierNotice"
			},
		    url:"_search.php",
		    success: function(data) {
		    			    				    			    			    			    	
		    	if ($("#notifierScreen").html()!=data && data!="") {
		    	
		    		$("#notifierScreen").html(data);
		    		$("#notifierScreen").fadeIn(300).delay(notifierDelay*1000).fadeOut(300);
		    	
		    	}
		    	 	
		    }
		});	
				
	}
	
}

function generateNewWaypointLink() {
	
	$.ajax({
		type:"POST",
		data: {
			rpAction:"rpGetFavourites",
			rpGenerateWaypointString:"true"
		},
	    url:"_search.php",
	    success: function(data) {
	    	
	    	$("#waypointRouteButton").attr("href", "javascript:generateWaypointRoute('"+data+"');");
	    		    	
	    }
	});	
	
}

function moveFavourite(whatwith, withwhat) {
	
	$.ajax({
		type:"POST",
		data: {
			rpWhatWith:whatwith,
			rpWithWhat:withwhat,
			rpAction:"rpMoveFavourite"
		},
	    url:"_notice.php",
	    success: function(data) {
	    	
	    	if (data=="SUCCESS") {
	    			    		
				$("#clientFavouritesDiv").fadeOut(300, function() {
					
					$.ajax({
						type:"POST",
						data: {
							rpAction:"rpGetFavourites"
						},
					    url:"_search.php",
					    success: function(data) {
					    	
					    	$("#clientFavouritesDiv").html(data);
					    	$("#clientFavouritesDiv").fadeIn(300);
					    	generateNewWaypointLink();
					    		    	
					    }
					});	
					
				});
			
	    	} else {notify(data);}
	    	
	    }
	});
	
}

function addToFavourites(id) {
	
	$.ajax({
		type:"POST",
		data: {
			rpNotice:id,
			rpAction:"rpAddToFavourites"
		},
	    url:"_notice.php",
	    success: function(data) {
	    	
	    	if (data=="SUCCESS") {
	    			    		
				notify("Ilmoitus tallennettiin suosikkeihin.");
			
	    	} else {notify(data);}
	    	
	    }
	});	
	
}

function removeFavourite(id) {
	
	$.ajax({
		type:"POST",
		data: {
			rpNotice:id,
			rpAction:"rpRemoveFavourite"
		},
	    url:"_notice.php",
	    success: function(data) {
	    	
	    	if (data=="SUCCESS") {
	    		
	    		$("#searchResultDiv_"+id).fadeOut(300);
	    		generateNewWaypointLink();
	    							
	    	} else {notify(data);}
	    	
	    }
	});	
	
}

function openMapNotice(id) {

	forceOpenNotice = id;
	openPage("actions");	
	
}

function addMapNotice() {
	
	noticePreDefinedAddress = targetLocationTitle;
	forceAddNotice = true;
	openPage("actions");	
	
}

function editNotice(id) {
		
	$("#tabContainer").fadeOut(100, function() {
		
		$("#tabContainer").load("_notices.php?id=editnotice&noticeid="+id, function() {
			
			$("#tabContainer").fadeIn(100);
		
		});
	
	});
	
}

function positionNotice(id) {
	
	rePositionNotice = id;
	
	closePage();
	hideInfo();
	$("#distanceInformation").hide();	
	
	$("#formContainer").fadeOut("fast", function() {
		
		$("#formContainer").html("<h1>Klikkaa kartalta uusi paikka ilmoitukselle<img onmouseover=\"showInfo('Sulje');\" onmouseout=\"hideInfo();\" class=\"closeButton\" onclick=\"closeRepositioning();\" src=\"graphics/buttons/button_close.png\" alt=\"Sulje\" /></h1><a href=\"javascript:closeRepositioning();\" class=\"smallFormButton left\">Sulje</a>");
	
		$("#formContainer").fadeIn("fast");
	
	});
	
}

function toggleNotice(id) {
	
	clearRoute();
	closePage();
	hideInfo();
	$("#distanceInformation").hide();	
	
	$("#formContainer").fadeOut("fast");
	
	$.ajax({
		type:"POST",
		data: {
			rpNotice:id,
			rpAction:"rpGetNoticePosition"
		},
	    url:"_notice.php",
	    success: function(data) {
	    	 
			var data_array = data.split("|break|");
			
			mapPosition(data_array[0], data_array[1]);
				    	 
			$("#formContainer").load("engine/rp.wms.get.annotation.php?id="+id, function() {
			
				$("#formContainer").fadeIn("fast");	
			
			});
			    		
	    }
	});
	
}

function updateNotice() {
	
	if ($("#rpNoticeTitle").val()!="" && $("#rpNoticeDescription").val()!="" && $("#rpNoticeAddress").val()!="" && $("#rpNoticeState").val()!="" && $("#rpNoticeCity").val()!="") {
		
		if ($("#productsContainer").html()!="") {
		
			var numOfUploads = 0;
		
			$(".rpNoticeFileUpload").each(function() {
			
				if ($(this).val()!="") {numOfUploads += 1;}
			
			});
			
			if (numOfUploads>0) {
				
				$("#pageContainerData").fadeOut(300, function() {
						
					notify("Siirretään tiedostoja...");
					
					$("#updateNoticeForm").submit();
				
				});
				
			} else {
			
				$("#updateNoticeForm").submit();
		
			}
		
		} else {notify("Ilmoituksessa ei ole yhtään sisältöä.");}
		
	} else {notify("Puuttuvia kohtia lomakkeessa.");}	
	
}

function submitNotice() {
	
	var numOfUploads = 0;
		
	$(".rpNoticeFileUpload").each(function() {
	
		if ($(this).val()!="") {numOfUploads += 1;}
	
	});
	
	if (numOfUploads>0) {
		
		$("#pageContainerData").fadeOut(300, function() {
				
			notify("Siirretään tiedostoja...");
			
			$("#saveNoticeForm").submit();
		
		});
		
	} else {
	
		$("#saveNoticeForm").submit();

	}
	
}

function saveNotice() {
	
	if ($("#rpNoticeTitle").val()!="" && $("#rpNoticeDescription").val()!="" && $("#rpNoticeAddress").val()!="" && $("#rpNoticeState").val()!="" && $("#rpNoticeCity").val()!="") {
		
		if ($("#productsContainer").html()!="") {
		
			if (overRideDoubleClickLatitude>0 && overRideDoubleClickLongitude>0) {
				
				var coordinates3 = transformCoordinates(overRideDoubleClickLongitude, overRideDoubleClickLatitude);
				
				$("#rpNoticeLatitude").val(overRideDoubleClickLatitude);
				$("#rpNoticeLongitude").val(overRideDoubleClickLongitude);
				$("#rpNoticePosX").val(coordinates3[0]);
				$("#rpNoticePosY").val(coordinates3[1]);
				
				submitNotice();
				
			} else if (doubleClickX>0 && doubleClickY>0) {
		
				var coordinates = transformVisibleCoordinates(doubleClickX, doubleClickY);
				var coordinates2 = transformCoordinates(coordinates[0], coordinates[1]);
						
				$("#rpNoticeLatitude").val(coordinates[1]);
				$("#rpNoticeLongitude").val(coordinates[0]);
				$("#rpNoticePosX").val(coordinates2[0]);
				$("#rpNoticePosY").val(coordinates2[1]);
			
				submitNotice();
			
			} else {

				$.ajax({
				url: "engine/rp.gmaps.get.target.php?address="+encodeURIComponent($("#rpNoticeAddress").val()+", "+$("#rpNoticeCity").val())+"&sensor=false"
				}).done(function(data) {
					
					var dataString = JSON.stringify(data, escapeLB);
					var parsedData = jQuery.parseJSON(dataString);
					
					if (parsedData["status"]!="ZERO_RESULTS") {
						
						var coordinates4 = transformCoordinates(parsedData["results"][0]["geometry"]["location"]["lng"], parsedData["results"][0]["geometry"]["location"]["lat"]);
						
						$("#rpNoticeLatitude").val(parsedData["results"][0]["geometry"]["location"]["lat"]);
						$("#rpNoticeLongitude").val(parsedData["results"][0]["geometry"]["location"]["lng"]);
						$("#rpNoticePosX").val(coordinates4[0]);
						$("#rpNoticePosY").val(coordinates4[1]);
				
						submitNotice();
				
					} else {notify("Osoitetta \""+$("#rpNoticeAddress").val()+", "+$("#rpNoticeCity").val()+"\" ei löytynyt.");}
									
				});
				
			}
		
		} else {notify("Ilmoituksessa ei ole yhtään sisältöä.");}
		
	} else {notify("Puuttuvia kohtia lomakkeessa.");}	
	
}

function removeNotice(id) {
	
	closeFormContainer();
	
	$.ajax({
		type:"POST",
		data: {
			rpNotice:id,
			rpAction:"rpRemoveNotice"
		},
	    url:"_notice.php",
	    success: function(data) {
	    	if (data=="SUCCESS") {
	    	
	    	$("#annotation_notice_"+id).remove();
	    	
	    	$("#notice_"+id).fadeOut(300);
	    	
	    	} else {notify(data);} 	    	
	    }
	});
	
}