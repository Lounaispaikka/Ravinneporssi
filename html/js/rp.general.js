function showInfo(msg) {
	
	if (!isMobile) {
		if (msg == undefined || msg == "") {msg = "Valitse";}
		$("#infoBox").html(msg).show();
	}
		
}

function hideInfo() {
	
	$("#infoBox").hide();
	
}

function submitMapLogin() {
	
	if ($("#rpLoginEmail").val().indexOf("@")>0 && $("#rpLoginPassword").val()!="") {
		
		$("#loginForm").submit();
		
	} else {notify("Puuttuvia kohtia lomakkeessa.");}
	
}

function openRatingForm() {
	
	$("#rateClientFormDiv").removeClass("hideme").addClass("showme");
	$("#openRatingFormButton").hide();	
	
}

function updateNewMessagesIndicator() {
		
	$.ajax({
		type:"POST",
		data: {
		rpAction:"rpGetNewMessages"
		},
	    url:"_message.php",
	    success: function(data) {
	    	
	    	if (data>0) {
	    		
	    		$("#newMessagesIndicatorDiv").addClass("newmessages");
	    		$("#newMessagesIndicatorDiv").html("<div class=\"messagesNumberCount\">"+data+"</div>");
	    		
	    	} else {
	    		
	    		$("#newMessagesIndicatorDiv").removeClass("newmessages");
	    		$("#newMessagesIndicatorDiv").html("");
	    		
	    	}
			
	    }
	});
	
}

function sendClientRating() {
	
	if ($("#rpRatingTitle").val()!="" && $("#rpRatingRating").val()!="" && $("#rpRatingMessage").val()!="") {
		
		$.ajax({
			type:"POST",
			data: $("#rateClientForm").serialize(),
		    url:"_client.php",
		    success: function(data) {
		    	if (data=="SUCCESS") {
		    	
		    		$("#rateClientFormDiv").removeClass("showme").addClass("hideme");
				
					notify("Arvostelu tallennettiin.");
				
		    	} else {notify(data);}
		    }
		});
		
	} else {notify("Puuttuvia kohtia lomakkeessa.");}
	
}

function submitRemoveProfile() {
	
	if ($("#rpRemoveProfilePassword").val()!="") {
		
		$.ajax({
			type:"POST",
			data: $("#removeProfileForm").serialize(),
		    url:"_client.php",
		    success: function(data) {
		    	if (data=="SUCCESS") {
		    	
		    		window.location = "/remove";
				
		    	} else {notify(data);}
		    }
		});
		
	} else {notify("Puuttuvia kohtia lomakkeessa.");}
	
}

function submitMapReset() {
	
	if ($("#rpResetEmail").val().indexOf("@")>0) {
		
		$.ajax({
			type:"POST",
			data: $("#resetForm").serialize(),
		    url:"_reset.php",
		    success: function(data) {
		    	if (data=="SUCCESS") {
		    	
		    		closePage();
		    	
		        	notify("Löydät salasanan vaihtolinkin sähköpostistasi.<br />Jos viesti ei saavu tunnin kuluessa,<br />tarkista roskapostikansiosi.");
				
		    	} else {notify(data);}
		    }
		});
		
	} else {notify("Puuttuvia kohtia lomakkeessa.");}
	
}

function openLogin() {
		
	$("#pageContainer").fadeOut(300, function() {
	
		$("#pageContainerData").load("_forms.php?id=maplogin", function() {
			
			$("#pageContainer").fadeIn(300);
			
		});
				
	});	
	
}

function openPasswordChange() {
		
	$("#pageContainerData").fadeOut(300, function() {
	
		$("#pageContainerData").load("_forms.php?id=mappassword", function() {
			
			$("#pageContainerData").fadeIn(300);
			
		});
				
	});	
	
}

function closeSubPage() {
	
	$("#subPageContainerData").fadeOut(300, function() {
		
		$("#pageContainerData").fadeIn(300);
				
	});
	
}

function showSubProfile(id) {
	
	$("#subPageContainerData").fadeOut(300, function() {
	
		$("#pageContainerData").fadeOut(300, function() {
			
			$("#subPageContainerData").load("_profile.php?return=list&id="+id, function() {
				
				$("#subPageContainerData").fadeIn(300);
				
			});
					
		});
	
	});
	
}

function showSubNotice(id) {
	
	$("#subPageContainerData").fadeOut(300, function() {
	
		$("#pageContainerData").fadeOut(300, function() {
			
			$("#subPageContainerData").load("_notices.php?return=list&id=shownotice&noticeid="+id, function() {
				
				$("#subPageContainerData").fadeIn(300);
				
			});
					
		});
	
	});
	
}

function showProfile(id) {
	
	$("#subPageContainerData").fadeOut(300, function() {
	
		if ($("#pageContainer").is(":visible")) {
	
			$("#pageContainerData").fadeOut(300, function() {
			
				$("#pageContainerData").load("_profile.php?id="+id, function() {
					
					$("#pageContainerData").fadeIn(300);
					
				});
						
			});	
			
		} else {
		
			$("#pageContainer").fadeOut(300, function() {
			
				$("#pageContainerData").load("_profile.php?id="+id, function() {
					
					$("#pageContainer").fadeIn(300);
					
				});
						
			});	
		
		}
	
	});
	
}

function removeClientImage(id,checksum) {
	
	$.ajax({
		type:"POST",
		data: {
			"rpAction":"rpRemoveClientImage",
			"rpImageID":checksum
			},
	    url:"_settings.php",
	    success: function(data) {
	    	if (data=="SUCCESS") {

	        	$("#clientImageDiv_"+id).fadeOut(300);
			
	    	} else {notify(data);}
	    }
	});
	
}

function showNotice(id) {
	
	$("#subPageContainerData").fadeOut(300, function() {
	
		if ($("#pageContainer").is(":visible")) {
			
			$("#pageContainerData").fadeOut(300, function() {
			
				$("#pageContainerData").load("_notices.php?id=shownotice&noticeid="+id, function() {
					
					$("#pageContainerData").fadeIn(300);
					
				});
						
			});
			
		} else {
		
			$("#pageContainer").fadeOut(300, function() {
			
				$("#pageContainerData").load("_notices.php?id=shownotice&noticeid="+id, function() {
					
					$("#pageContainer").fadeIn(300);
					
				});
						
			});	
		
		}
	
	});
	
}

function openPage(id, clientid) {

	if (id == "addnotice") {
		
		doubleClickX = 0;
		doubleClickY = 0;
		overRideDoubleClickLatitude = 0;
		overRideDoubleClickLongitude = 0;
		forceAddNotice = true;
		noticePreDefinedAddress = "";
		
		id = "actions";
		
	}

	$("#subPageContainerData").fadeOut(300, function() {

		if ($("#pageContainer").is(":visible")) {
					
			$("#pageContainerData").fadeOut(300, function() {
				
				$("#pageContainerData").load("_pages.php?id="+id+"&clientid="+clientid, function() {
							
					$("#pageContainerData").fadeIn(300);
					
				});
				
			});
			
		} else {
	
			$("#pageContainer").hide();
	
			$("#pageContainerData").load("_pages.php?id="+id+"&clientid="+clientid, function() {
						
				$("#pageContainer").fadeIn(300);
				$("#pageContainerData").delay(300).fadeIn(300);
				
			});
		
		}
	
	});
	
}

function clearOptions(id) {
	
	$("."+id).each(function() {

		if ($(this).attr("checked")) {
			$(this).attr("checked", false);
		} else {
			$(this).attr("checked", true);
		}
		
	});
	
}

function closePage() {
		
	$("#pageContainer").fadeOut(300);
	
}

function closeFormContainer() {
	
	hideInfo();
	
	clearField();
	clearRoute();
	
	$("#formContainer").fadeOut("fast");
	$("#distanceInformation").fadeOut("fast");
	
}

function listCities(selected) {

	$.ajax({
		type:"POST",
		data: {state:selected},
	    url:"_getpcf.php",
	    success: function(data) {
	    	
	    	var cities = data.split(";");
	    	cities.sort();
	    	
	    	$("#rpClientCity").empty();
	    	$("#rpClientPostalcode").empty();
	    	
	    	$("#rpNoticeCity").empty();
	    	
			for (var i = 0; i < cities.length; i++) {
				
				if (cities[i]!="") {
					
					var selectedstring = "";
					if (cities[i]==userCity) {selectedstring = " SELECTED";}
					
					$("#rpClientCity").append("<option value=\""+cities[i]+"\""+selectedstring+">"+cities[i]+"</option>");
					
					$("#rpNoticeCity").append("<option value=\""+cities[i]+"\""+selectedstring+">"+cities[i]+"</option>");
					
				}
				
			}
	    	
	    	if (userPostalcode!="") {listPostalcodes(userCity);}
	    	    	
	    }
	});
	
}

function showImage(url) {
	
	$("#imageContainer").html("<a onmouseover=\"showInfo('Sulje kuva');\" onmouseout=\"hideInfo();\" href=\"javascript:closeImage();\"><img src=\""+url+"\"></a>");
	
	$("#imageContainer img").each(function() {
		
		$(this).load(function() {
		
			$("#imageContainer img").css({"opacity":"0"});
		
			$("#imageContainer").fadeIn(300, function() {
				
				$("#imageContainer img").css({"marginTop":(($(window).height()-$("#imageContainer img").height())/2)+"px"});
				
				$("#imageContainer img").css({"opacity":"1"});
				
			});
				
		});
		
	});
	
}

function closeImage() {

	$("#imageContainer").fadeOut(300);
	
}

function removeProfile() {
	
	$("#pageContainerData").fadeOut(300, function() {
	
		$("#pageContainerData").load("_forms.php?id=removeprofile", function() {
			
			$("#pageContainerData").fadeIn(300);
			
		});
				
	});
	
}

function listPostalcodes(selected) {

	$.ajax({
		type:"POST",
		data: {
			state:$("#rpClientState").val(),
			city:selected
			},
	    url:"_getpcf.php",
	    success: function(data) {

	    	var codes = data.split(";");
	    	codes.sort();
	    	
	    	$("#rpClientPostalcode").empty();
	    	
			for (var i = 0; i < codes.length; i++) {
				
				if (codes[i]!="") {
					
					var selectedstring = "";
					if (codes[i]==userPostalcode) {selectedstring = " SELECTED";}
					
					$("#rpClientPostalcode").append("<option value=\""+codes[i]+"\""+selectedstring+">"+codes[i]+"</option>");
					
				}
				
			}
	    	
	    }
	});
	
}

function defineHomeWithAddress() {
	
	defineHomePositionWithAddress = true;
	
}

function saveFinalSettings() {
	
	var numOfUploads = 0;
		
	$(".rpClientImageUpload").each(function() {
	
		if ($(this).val()!="") {numOfUploads += 1;}
	
	});
	
	if (numOfUploads>0) {
		
		$("#pageContainerData").fadeOut(300, function() {
			
			notify("Siirretään kuvia...");
			
			$("#saveSettingsForm").submit();
		
		});
		
	} else {
	
		$("#saveSettingsForm").submit();
	
	}
	
}

function saveSettings() {
	
	if ($("#rpClientEmail").val().indexOf("@")>0 && $("#rpClientName").val()!="") {
		
		if ($("#rpClientNotifier").attr("checked") && $("#rpClientNotifierContactScreen").attr("checked")) {notifierEnabled = true;} else {notifierEnabled = false; $("#notifierScreen").fadeOut(300);}
		
		if (defineHomePositionWithAddress) {
											
			$.ajax({
			url: "engine/rp.gmaps.get.target.php?address="+encodeURIComponent($("#rpClientAddress_1").val()+","+$("#rpClientCity").val())+"&sensor=false"
			}).done(function(data) {
								
				var dataString = JSON.stringify(data, escapeLB);
				var parsedData = jQuery.parseJSON(dataString);
				
				if (parsedData["status"]!="ZERO_RESULTS") {
	
					$(".annotation_home").remove();
					
					baseLatitude = parsedData["results"][0]["geometry"]["location"]["lat"];
					baseLongitude = parsedData["results"][0]["geometry"]["location"]["lng"];
					physicalLatitude = baseLatitude;
					physicalLongitude = baseLongitude;
					
					coordinates = transformCoordinates(baseLongitude, baseLatitude);
					
					$.ajax({
					type: "POST",
					url: "_client.php",
					data: {baseLatitude: baseLatitude,
						baseLongitude: baseLongitude,
						rpAction: "rpUpdateClientHomePosition"
						}
					}).done(function(data) {

						saveFinalSettings();
						
					});
			
				}
								
			});	
			
		} else {
			
			saveFinalSettings();
			
		}
				
	} else {notify("Puuttuvia kohtia lomakkeessa.");}
	
	defineHomePositionWithAddress = false;
	
}