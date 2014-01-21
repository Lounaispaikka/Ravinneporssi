<div class="defaultPageContent">

<?php

if ($_SESSION["clientID"]) {

	if (rpIsAdmin($_SESSION["clientID"])) {

		echo "<h1>Muokkaa palvelun sisältöjä<a href=\"javascript:closePage();\" class=\"formButton right last\">Sulje</a></h1><div class=\"clear height15\"></div>";
	
		echo "<script>
		
		tinymce.init({
		
			selector: \".wysiwyg\",
			language : \"fi\",
			menubar:false,
  			statusbar: false,
  			plugins: \"link\",
  			toolbar: \"link unlink | formatselect\",
  			block_formats: \"Leipäteksti=p;Iso otsikko=h2;Pieni otsikko=h3\"
			
		});
		
		function rpAdminUpdateContent() {
			
			$(\"#rpContentContent\").val(tinymce.get(\"rpContentContent\").getContent());
			
			if ($(\"#rpContentTitle\").val()!=\"\" && $(\"#rpContentContent\").val()!=\"\") {
	
				$.ajax({
					type:\"POST\",
					data: $(\"#rpAdminUpdateContent\").serialize(),
				    url:\"_content.php\",
				    success: function(data) {
				    	if (data==\"SUCCESS\") {
											
				        	notify(\"Sisältö tallennettiin.\");
						
				    	} else {notify(data);}
				    }
				});
	
			} else {notify(\"Puuttuvia kohtia lomakkeessa.\");}	
			
		}
		
		function updateContentFields(id) {
			
			$.get(\"_content.php?rpAction=rpAdminGetContent&type=title&id=\"+id, function(result) {
				
				$(\"#rpContentTitle\").val(result);
				
			});
			
			$.get(\"_content.php?rpAction=rpAdminGetContent&type=content&id=\"+id, function(result) {
				
				tinymce.get(\"rpContentContent\").setContent(result);
								
			});
			
			$(\"#rpContent\").val(id);
			
		}
		
		updateContentFields(".rpAdminGetFirstContent().");
		
		</script>

		<form name=\"rpAdminUpdateContent\" id=\"rpAdminUpdateContent\" action=\"http://".$rpSettings->getValue("domain")."/map\" method=\"POST\">

		<input type=\"hidden\" name=\"rpCheck\" value=\"".md5("ADMIN".$rpSettings->getValue("secret").$_SESSION["clientID"])."\" />
		<input type=\"hidden\" name=\"rpAction\" value=\"rpAdminUpdateContent\" />
		<input type=\"hidden\" id=\"rpContent\" name=\"rpContent\" value=\"".rpAdminGetFirstContent()."\" />
		
		<div class=\"col_50 left\">
		
			<label for=\"rpContentTitle\">Otsikko: *<input name=\"rpContentTitle\" id=\"rpContentTitle\" type=\"text\" /></label>
		
		</div>
		
		<div class=\"col_50 left\">
		
			<label for=\"rpSelectContent\">Valitse sisältö:<select id=\"rpSelectContent\" class=\"full\" onchange=\"updateContentFields(this.value);\" name=\"rpSelectContent\">";
			
			rpGetContents("<option value=\"[rp(id)]\">[rp(name)]</option>", "", 0, true);
			
			echo "</select></label>
		
		</div>
		
		<div class=\"clear height5\"></div>
		
		<label for=\"rpContentContent\">Sisältö: *
		
		<div class=\"clear height10\"></div>
		
		<textarea class=\"wysiwyg\" name=\"rpContentContent\" id=\"rpContentContent\" class=\"bigText\" style=\"height: 400px !important;\"></textarea></label>
	
		<div class=\"clear height10\"></div>
	
		<a href=\"javascript:rpAdminUpdateContent();\" class=\"formButton left\">Tallenna sisältö</a></form>
		
		<div class=\"clear height10\"></div>";

	} else {echo "Sinulla ei ole pääkäyttäjän oikeuksia.";}

}

?>

</div>