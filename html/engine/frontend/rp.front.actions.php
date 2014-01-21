<?php

if ($_SESSION["clientID"]) {
	
	echo "<h1>Toiminnot<a href=\"javascript:closePage();\" class=\"formButton right last\">Sulje</a></h1>
	
	<script>
	
	function changeTab(id) {
	
		$(\".tabButton\").removeClass(\"activeTab\");
		$(\"#tab_\"+id).addClass(\"activeTab\");
	
		$(\"#tabContainer\").fadeOut(100, function() {
			
			$(\"#tabContainer\").load(\"_actions.php?id=\"+id+\"&clickX=\"+doubleClickX+\"&clickY=\"+doubleClickY, function() {
				
				$(\"#tabContainer\").fadeIn(100);
			
			});
			
		});
	
	}
	
	$(document).ready(function() {
		
		if (forceOpenField>0) {
			
			$(\"#tab_fields\").addClass(\"activeTab\");
			
			editField(forceOpenField);
			
			forceOpenField = 0;
			
		} else if (forceOpenNotice>0) {
			
			$(\"#tab_notices\").addClass(\"activeTab\");
			
			editNotice(forceOpenNotice);
			
			forceOpenNotice = 0;
	
		} else if (forceAddNotice) {
			
			addNotice();
			forceAddNotice = false;
			
		} else if (forceAddField) {
			
			addField();
			forceAddField = false;
			
		} else {
		
			changeTab(\"general\");
	
		}
	
	});
	
	</script>
	
	<a href=\"javascript:changeTab('general');\" id=\"tab_general\" class=\"tabButton\">Yleiset</a>
	<a href=\"javascript:changeTab('notices');\" id=\"tab_notices\" class=\"tabButton\">Ilmoitukset</a>
	<a href=\"javascript:changeTab('fields');\" id=\"tab_fields\" class=\"tabButton\">Pellot</a>
	<a href=\"javascript:changeTab('routes');\" id=\"tab_routes\" class=\"tabButton\">Reitit</a>
	<a href=\"javascript:changeTab('contracts');\" id=\"tab_contracts\" class=\"tabButton\">Sopimukset</a>
	<a href=\"javascript:changeTab('favourites');\" id=\"tab_favourites\" class=\"tabButton\">Suosikit</a>
	
	<div class=\"greenRuler\"></div>
	
	<div id=\"tabContainer\"></div>
	
	<div class=\"clear height5\"></div>";	
	
}

?>