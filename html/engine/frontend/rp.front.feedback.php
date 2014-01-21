<div class="defaultPageContent">

<?php

if ($_SESSION["clientID"]) {

	rpGetContent("<h1>[rp(title)]<a href=\"javascript:closePage();\" class=\"formButton right last\">Sulje</a></h1>[rp(content)]<div class=\"clear height15\"></div>", 11);

	echo "<script>
	
	function sendFeedback() {
					
		if ($(\"#rpFeedbackTitle\").val()!=\"\" && $(\"#rpFeedbackMessage\").val()!=\"\") {

			$.ajax({
				type:\"POST\",
				data: $(\"#sendFeedbackForm\").serialize(),
			    url:\"_feedback.php\",
			    success: function(data) {
			    	if (data==\"SUCCESS\") {
		
						closePage();
							
			        	notify(\"Palautteesi lähetettiin.\");
					
			    	} else {notify(data);}
			    }
			});

		} else {notify(\"Puuttuvia kohtia lomakkeessa.\");}	
		
	}
	
	</script>
	
	<h2>Lähetä palautetta oheisella lomakkeella</h2>
	
	<form name=\"sendFeedbackForm\" id=\"sendFeedbackForm\" action=\"http://".$rpSettings->getValue("domain")."/map\" method=\"POST\">
		
	<input type=\"hidden\" name=\"rpCheck\" value=\"".md5("FEEDBACK".$rpSettings->getValue("secret").$_SESSION["clientID"])."\" />
	<input type=\"hidden\" name=\"rpAction\" value=\"rpSendFeedback\" />
	
	<label for=\"rpFeedbackTitle\">Palautteen otsikko: *<input name=\"rpFeedbackTitle\" id=\"rpFeedbackTitle\" type=\"text\" class=\"full\" /></label>	
		
	<label for=\"rpFeedbackMessage\">Palautteen sisältö: *<textarea name=\"rpFeedbackMessage\" id=\"rpFeedbackMessage\" class=\"bigText\"></textarea></label>

	<a href=\"javascript:sendFeedback();\" class=\"formButton left\">Lähetä palaute</a></form>";

}

?>

</div>