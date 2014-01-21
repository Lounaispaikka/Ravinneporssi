function submitLogin() {
	
	if ($("#rpLoginEmail").val().indexOf("@")>0 && $("#rpLoginPassword").val()!="") {
		
		$("#loginForm").submit();
		
	} else {notify("Puuttuvia kohtia lomakkeessa.");}
	
}

function submitPasswordUpdate() {
	
	if ($("#rpNewPassword_1").val()!="" && $("#rpNewPassword_2").val()!="" && $("#rpNewPassword_1").val()==$("#rpNewPassword_2").val()) {
	
		$.ajax({
			type:"POST",
			data: $("#resetPasswordForm").serialize(),
		    url:"_reset.php",
		    success: function(data) {
		    	if (data=="SUCCESS") {
		    	
		    		changeContainer("login");
		    	
		        	notify("Salasanasi on nyt vaihdettu.");
				
		    	} else {notify(data);}
		    }
		});
	
	} else {notify("Salasanat eivät täsmää.");}
	
}

function submitReset() {
	
	if ($("#rpResetEmail").val().indexOf("@")>0) {
		
		$.ajax({
			type:"POST",
			data: $("#resetForm").serialize(),
		    url:"_reset.php",
		    success: function(data) {
		    	if (data=="SUCCESS") {
		    	
		    		changeContainer("login");
		    	
		        	notify("Löydät salasanan vaihtolinkin sähköpostistasi.<br />Jos viesti ei saavu tunnin kuluessa,<br />tarkista roskapostikansiosi.");
				
		    	} else {notify(data);}
		    }
		});
		
	} else {notify("Puuttuvia kohtia lomakkeessa.");}
	
}

function showTOU() {
	
	if ($("#touDiv").hasClass("hideme")) {
	
		$("#touDiv").removeClass("hideme").addClass("showme");
	
	} else {
		
		$("#touDiv").removeClass("showme").addClass("hideme");
		
	}
	
}

function submitRegistration() {
	
	if ($("#rpRegisterEmail").val().indexOf("@")>0 && $("#rpRegisterName").val()!="") {
		
		if ($("#rpRegisterPassword_1").val()!="" && $("#rpRegisterPassword_2").val()!="" && $("#rpRegisterPassword_1").val()==$("#rpRegisterPassword_2").val()) {
		
			if ($("#rpAcceptTOU").attr("checked")=="checked") {
		
				var numOfChecked = 0;
			
				$('[id^="rpRegisterType_"]').each(function() {
					
					if ($(this).attr("checked")=="checked") {numOfChecked += 1;}
					
				});
				
				if (numOfChecked>0) {
				
					$.ajax({
						type:"POST",
						data: $("#registerForm").serialize(),
					    url:"_register.php",
					    success: function(data) {
					    	if (data=="SUCCESS") {
					    	
					    		changeContainer("login");
					    	
					        	notify("Kiitos rekisteröitymisestäsi.<br />Löydät aktivointilinkin sähköpostistasi.<br />Jos aktivointiviesti ei saavu tunnin kuluessa,<br />tarkista roskapostikansiosi.");
							
					    	} else {notify(data);}
					    }
					});
			
				} else {notify("Et ole valinnut käyttäjätyyppiä.");}
		
			} else {notify("Et ole hyväksynyt käyttöehtoja.");}
		
		} else {notify("Salasanat eivät täsmää.");}
		
	} else {notify("Puuttuvia kohtia lomakkeessa.");}
	
}

function changeContainer(id) {
	
	if (id=="login") {
		
		$("#loginButton").animate({marginTop:-50}, 300);
		
	} else {
		
		$("#loginButton").animate({marginTop:0}, 300);
		
	}
	
	$("#registrationContainer").fadeOut(300, function() {
		
		$("#registrationContainer").load("_forms.php?id="+id, function() {
			
			$("#registrationContainer").fadeIn(300);
			
		});
		
	});
	
}