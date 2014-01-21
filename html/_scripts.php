
<script src="js/jquery-1.8.3.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script src="js/proj4js-combined.js"></script>
<script src="js/rp.setup.js"></script>
<script src="js/rp.functions.js"></script>

<?php

if (rpIsAdmin($_SESSION["clientID"])) {
	
	echo "<script type=\"text/javascript\" src=\"js/tinymce/tinymce.min.js\"></script>
	
	<script type=\"text/javascript\">
	
	function rpAdminPublishNotice(id) {
		
		$.ajax({
			type:\"POST\",
			data: {
			rpAdminAction:\"rpPublishNotice\",
			rpNotice:id
			},
		    url:\"_admin.php\",
		    success: function(data) {
		    	if (data==\"SUCCESS\") {
		    			    		
		    		notify(\"Ilmoitus palautettiin.\");
				
		    	} else {notify(data);}
		    }
		});
		
	}
	
	function rpAdminUnpublishNotice(id) {
		
		$.ajax({
			type:\"POST\",
			data: {
			rpAdminAction:\"rpUnpublishNotice\",
			rpNotice:id
			},
		    url:\"_admin.php\",
		    success: function(data) {
		    	if (data==\"SUCCESS\") {
		    			    		
		    		notify(\"Ilmoitus piilotettiin.\");
				
		    	} else {notify(data);}
		    }
		});
		
	}
	
	function rpAdminPublishRating(id) {
		
		$.ajax({
			type:\"POST\",
			data: {
			rpAdminAction:\"rpPublishRating\",
			rpRating:id
			},
		    url:\"_admin.php\",
		    success: function(data) {
		    	if (data==\"SUCCESS\") {
		    			    		
		    		notify(\"Arvostelu palautettiin.\");
				
		    	} else {notify(data);}
		    }
		});
		
	}
	
	function rpAdminUnpublishRating(id) {
		
		$.ajax({
			type:\"POST\",
			data: {
			rpAdminAction:\"rpUnpublishRating\",
			rpRating:id
			},
		    url:\"_admin.php\",
		    success: function(data) {
		    	if (data==\"SUCCESS\") {
		    			    		
		    		notify(\"Arvostelu piilotettiin.\");
				
		    	} else {notify(data);}
		    }
		});
		
	}
	
	function rpAdminMakeClientAdmin(id) {
		
		$.ajax({
			type:\"POST\",
			data: {
			rpAdminAction:\"rpMakeClientAdmin\",
			rpClient:id
			},
		    url:\"_admin.php\",
		    success: function(data) {
		    	if (data==\"SUCCESS\") {
		    			    		
		    		notify(\"Käyttäjälle annettiin pääkäyttäjän oikeudet.\");
				
		    	} else {notify(data);}
		    }
		});
		
	}
	
	function rpAdminUnmakeClientAdmin(id) {
		
		$.ajax({
			type:\"POST\",
			data: {
			rpAdminAction:\"rpUnmakeClientAdmin\",
			rpClient:id
			},
		    url:\"_admin.php\",
		    success: function(data) {
		    	if (data==\"SUCCESS\") {
		    			    		
		    		notify(\"Käyttäjältä poistettiin pääkäyttäjän oikeudet.\");
				
		    	} else {notify(data);}
		    }
		});
		
	}
	
	function rpAdminActivateClient(id) {
		
		$.ajax({
			type:\"POST\",
			data: {
			rpAdminAction:\"rpActivateClient\",
			rpClient:id
			},
		    url:\"_admin.php\",
		    success: function(data) {
		    	if (data==\"SUCCESS\") {
		    			    		
		    		notify(\"Käyttäjä aktivoitiin.\");
				
		    	} else {notify(data);}
		    }
		});
		
	}
	
	function rpAdminDeactivateClient(id) {
		
		$.ajax({
			type:\"POST\",
			data: {
			rpAdminAction:\"rpDeactivateClient\",
			rpClient:id
			},
		    url:\"_admin.php\",
		    success: function(data) {
		    	if (data==\"SUCCESS\") {
		    			    		
		    		notify(\"Käyttäjä deaktivoitiin.\");
				
		    	} else {notify(data);}
		    }
		});
		
	}
	
	</script>";
	
}

?>

<link rel="stylesheet" href="css/reset.css" />
<link rel="stylesheet" href="css/style.css" />
<link rel="stylesheet" href="css/jquery-ui.min.css" />

<link href="http://fonts.googleapis.com/css?family=Raleway:800,700,300,400" rel="stylesheet" type="text/css">

<!-- Apple touch ikonit -->
<link rel="apple-touch-icon" href="graphics/touch57.png" />
<link rel="apple-touch-icon" sizes="72x72" href="graphics/touch72.png" />
<link rel="apple-touch-icon" sizes="114x114" href="graphics/touch114.png" />

<!-- Favicon -->
<link rel="shortcut icon" href="graphics/favicon.ico" />