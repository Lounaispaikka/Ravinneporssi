<?php

if ($_SESSION["clientID"]) {
	
	echo "<h1>Viestit<a href=\"javascript:closePage();\" class=\"formButton right last\">Sulje</a></h1>
	
	<script>
	
	function changeTab(id, messageid, clientid) {

		$(\".tabButton\").removeClass(\"activeTab\");
		$(\"#tab_\"+id).addClass(\"activeTab\");
	
		$(\"#tabContainer\").fadeOut(100, function() {
			
			$(\"#tabContainer\").load(\"_messages.php?id=\"+id+\"&messageid=\"+messageid+\"&clientid=\"+clientid, function() {
				
				$(\"#tabContainer\").fadeIn(100);
				
			});
			
		});
		
	}
	
	$(document).ready(function() {";
		
		if ($_GET["clientid"]>0) {
			echo "changeTab(\"newmsg\", 0, ".intval($_GET["clientid"]).");";
		} else {
			echo "changeTab(\"inbox\");";
		}
		
	
	echo "});
	
	</script>
	
	<a href=\"javascript:changeTab('inbox');\" id=\"tab_inbox\" class=\"tabButton activeTab\">Saapuneet</a>
	<a href=\"javascript:changeTab('outbox');\" id=\"tab_outbox\" class=\"tabButton\">Lähetetyt</a>
	<a href=\"javascript:changeTab('newmsg');\" id=\"tab_newmsg\" class=\"tabButton\">Uusi viesti</a>
	<a href=\"javascript:changeTab('search');\" id=\"tab_search\" class=\"tabButton\">Haku</a>
	
	<div class=\"greenRuler\"></div>
	
	<div id=\"tabContainer\"></div>
	
	<div class=\"clear height5\"></div>";	
	
}

?>