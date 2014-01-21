<?php

echo "<script>

function changeSearchOptions() {
	
	$.ajax({
		type:\"POST\",
		data: {
			rpAction:\"rpGetSearchOptions\",
			rpSearchType:$(\"#rpSearchType\").val()
		},
	    url:\"_search.php\",
	    success: function(data) {

	    	$(\"#searchOptions\").html(data);
	    	
	    	triggerSearch();
	    		
	    }
	});
	
}

function searchNotices() {
	
	$(\"#searchResults\").html(\"Etsitään ilmoituksia...\");
	
	$(\"#rpSearchLatitude\").val(physicalLatitude);
	$(\"#rpSearchLongitude\").val(physicalLongitude);
	
	$.ajax({
		type:\"POST\",
		data: $(\"#searchOptionsForm\").serialize(),
	    url:\"_search.php\",
	    success: function(data) {
	    	
	    	$(\"#searchResults\").html(data);
	    	$(\"#updateResultsButton\").hide();	    	
	    	
	    }
	});		
	
}

$(document).ready(function() {
			
	changeSearchOptions();
	searchNotices();
	$(\"#updateResultsButton\").hide();
			
});

function clearCities() {
	
	clearOptions(\"cityOption\");
	
}

function listSearchCities(selected) {

	if (selected==\"all\") {
		
		$(\"#cityOptions\").html(\"\");
		
	} else {

		$.ajax({
			type:\"POST\",
			data: {state:selected},
		    url:\"_getpcf.php\",
		    success: function(data) {
		    	
		    	var cities = data.split(\";\");
		    	cities.sort();
		    	
		    	var citiesHTML = \"\";
		    	
		    	if (cities.length>0) {
		    	
			    	citiesHTML += \"<div class='searchOptionsDiv'><div class='clear height5'></div><h2>Valitse paikkakunnat<a href='javascript:clearCities();triggerSearch();' class='smallFormButton right'>X</a></h2>\";
			    		    	
					for (var i = 0; i < cities.length; i++) {
						
						if (cities[i]!=\"\") {
							
							citiesHTML += \"<div class='cityOptionDiv'><input name='rpCityHidden_\"+i+\"' id='rpCityHidden_\"+i+\"' type='hidden' value='\"+cities[i]+\"' /><input class='css-checkbox cityOption' name='rpCity_\"+i+\"' onchange='triggerSearch();' id='rpCity_\"+i+\"' type='checkbox' CHECKED /><label class='css-label' for='rpCity_\"+i+\"'>\"+cities[i]+\"</label></div>\";
							
						}
						
					}
			    	
			    	citiesHTML += \"<div class='clear'></div></div>\";
	
		    	}
	
		    	$(\"#cityOptions\").html(citiesHTML);
		    	
		    }
		});

	}

}

function triggerSearch() {
	
	$(\"#updateResultsButton\").show();
	
} 

function changeSearchTypeTab(type) {

	$(\".tabButton\").removeClass(\"activeTab\");
	$(\"#tab_searchtype_\"+type).addClass(\"activeTab\");

	$(\"#rpSearchType\").val(type);
	
	changeSearchOptions();
	
}

$(document).ready(function() {
	
	changeSearchTypeTab('all');
	
});

</script>

<h1>Listanäkymä<a href=\"javascript:closePage();\" class=\"formButton right last\">Sulje</a></h1>

<a href=\"javascript:changeSearchTypeTab('all');\" id=\"tab_searchtype_all\" class=\"tabButton\">Kaikki</a>";

rpPrintTypes("<a href=\"javascript:changeSearchTypeTab('[rp(type)]');\" id=\"tab_searchtype_[rp(type)]\" class=\"tabButton\">[rp(title)]</a>", "baseTypes", $_POST["rpSearchType"]);

echo "<div class=\"greenRuler\"></div>

<form name=\"searchOptionsForm\" id=\"searchOptionsForm\" method=\"POST\">

<input type=\"hidden\" name=\"rpAction\" value=\"rpSearchNotices\" />
<input type=\"hidden\" id=\"rpSearchLatitude\" name=\"rpSearchLatitude\" value=\"0\" />
<input type=\"hidden\" id=\"rpSearchLongitude\" name=\"rpSearchLongitude\" value=\"0\" />
<input type=\"hidden\" id=\"rpSearchType\" name=\"rpSearchType\" value=\"all\" />

<div class=\"col_50 left\">

	<label for=\"rpSearchOrder\">Ilmoitusten lajittelu:<select id=\"rpSearchOrder\" onchange=\"changeSearchOptions(); triggerSearch();\" name=\"rpSearchOrder\">";
	
		rpPrintTypes("<option value=\"[rp(type)]\">[rp(title)]</option>", "noticeOrderTypes", $_POST["rpSearchOrder"]);
	
	echo "</select></label>	

</div>

<div class=\"col_50 left\">

	<label for=\"rpSearchState\">Ilmoittajan maakunta:<select id=\"rpSearchState\" class=\"full\" onchange=\"listSearchCities(this.value); triggerSearch();\" name=\"rpSearchState\"><option value=\"all\">Kaikki</option>";
	
		rpGetStates("<option value=\"[rp(title)]\">[rp(title)]</option>");
	
	echo "</select></label>	

</div>

<div class=\"clear height5\"></div>

<div id=\"cityOptions\" name=\"cityOptions\"></div>

<div class=\"clear height5\"></div>

<div id=\"searchOptions\" name=\"searchOptions\"></div>

<div class=\"clear height5\"></div>

<a href=\"javascript:searchNotices();\" id=\"updateResultsButton\" name=\"updateResultsButton\" class=\"formButton left\">Päivitä hakutulokset</a>

</form>

<div class=\"clear\"></div>

<hr />
	
<div id=\"searchResults\" name=\"searchResults\"></div>";
	

?>