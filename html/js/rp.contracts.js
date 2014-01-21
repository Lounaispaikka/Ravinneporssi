function addContract() {
	
	$("#tabContainer").fadeOut(100, function() {
			
		$("#tabContainer").load("_contracts.php?id=addcontract", function() {
			
				$("#tabContainer").fadeIn(100);
			
			});
		
	});	
	
}

function editContract(id) {
	
	$("#tabContainer").fadeOut(100, function() {
			
		$("#tabContainer").load("_contracts.php?id=editcontract&contractid="+id, function() {
			
				$("#tabContainer").fadeIn(100);
			
			});
		
	});	
	
}

function saveContractSettings() {
	
	$.ajax({
		type:"POST",
		data: $("#editContractForm").serialize(),
	    url:"_contract.php",
	    success: function(data) {
	    	if (data=="SUCCESS") {

				changeTab("contracts");
					
	        	notify("Sopimus on tallennettu.");
			
	    	} else {notify(data);}
	    }
	});
	
}

function saveNewContract() {
	
	$.ajax({
		type:"POST",
		data: $("#addContractForm").serialize(),
	    url:"_contract.php",
	    success: function(data) {
	    	if (data=="SUCCESS") {

				changeTab("contracts");
					
	        	notify("Sopimus on tallennettu.");
			
	    	} else {notify(data);}
	    }
	});
		
}

function removeContract(id) {
		
	$.ajax({
		type:"POST",
		data: {
			rpContract:id,
			rpAction:"rpRemoveContract"
		},
	    url:"_contract.php",
	    success: function(data) {
	    	if (data=="SUCCESS") {
	    		    	
	    	$("#contractDiv_"+id).fadeOut(300);
	    	
	    	} else {notify(data);} 	    	
	    }
	});
	
}

function showContract(id) {
		
	$("#tabContainer").fadeOut(100, function() {
		
		$("#tabContainer").load("_contracts.php?id=showcontract&contractid="+id, function() {
			
			$("#tabContainer").fadeIn(100);
		
		});
	
	});
	
}