<?php
	
echo "<h1>Ohjeet<a href=\"javascript:closePage();\" class=\"formButton right last\">Sulje</a></h1>

<script>

function changeHelpTab(id) {

	$(\".tabButton\").removeClass(\"activeTab\");
	$(\"#tab_help_\"+id).addClass(\"activeTab\");

	$(\"#helpTabContainer\").fadeOut(100, function() {
		
		$(\"#helpTabContainer\").load(\"_content.php?id=getcontent&contentid=\"+id, function() {
			
			$(\"#helpTabContainer\").fadeIn(100);
			
		});
		
	});
	
}

$(document).ready(function() {
		
	changeHelpTab(".rpGetFirstContent("help").");
	
});

</script>";

rpGetContents("<a href=\"javascript:changeHelpTab('[rp(id)]');\" id=\"tab_help_[rp(id)]\" class=\"tabButton activeTab\">[rp(name)]</a>", "help");

echo "<div class=\"greenRuler\"></div>

<div id=\"helpTabContainer\"></div>

<div class=\"clear height5\"></div>";	
	

?>