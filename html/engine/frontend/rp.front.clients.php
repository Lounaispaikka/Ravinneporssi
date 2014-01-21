<?php

echo "<h1>Käyttäjälistaus<a href=\"javascript:closePage();\" class=\"formButton right last\">Sulje</a></h1>

<script>

function searchClients() {
	
	$(\"#searchResults\").html(\"Etsitään käyttäjiä...\");
	
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
			
	searchClients();
	$(\"#updateResultsButton\").hide();
	$(\"#rpClientTypesDiv\").hide();
	$(\"#rpClientTradesDiv\").hide();
			
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
	
	if ($(\"#rpClientTypeMaster\").val()==\"all\") {
		$(\"#rpClientTypesDiv\").hide();
	} else {
		$(\"#rpClientTypesDiv\").show();
	}
		
	if ($(\"#rpClientTradeMaster\").val()==\"all\") {
		$(\"#rpClientTradesDiv\").hide();
	} else {
		$(\"#rpClientTradesDiv\").show();
	}
	
} 

</script>

<form name=\"searchOptionsForm\" id=\"searchOptionsForm\" method=\"POST\">

<input type=\"hidden\" name=\"rpAction\" value=\"rpSearchClients\" />
<input type=\"hidden\" id=\"rpSearchLatitude\" name=\"rpSearchLatitude\" value=\"0\" />
<input type=\"hidden\" id=\"rpSearchLongitude\" name=\"rpSearchLongitude\" value=\"0\" />

<div class=\"col_50 left\">

	<label for=\"rpSearchState\">Käyttäjän maakunta:<select id=\"rpSearchState\" onchange=\"listSearchCities(this.value); triggerSearch();\" name=\"rpSearchState\"><option value=\"all\">Kaikki</option>";
	
		rpGetStates("<option value=\"[rp(title)]\">[rp(title)]</option>");
	
	echo "</select></label>	

</div>

<div class=\"col_50 left\">

	<label for=\"rpSearchOrder\">Käyttäjien lajittelu:<select id=\"rpSearchOrder\" class=\"full\" onchange=\"triggerSearch();\" name=\"rpSearchOrder\">";
	
		rpPrintTypes("<option value=\"[rp(type)]\">[rp(title)]</option>", "clientOrderTypes", $_POST["rpSearchOrder"]);
	
	echo "</select></label>	

</div>

<div class=\"clear\"></div>

<div class=\"col_50 left\">

	<label for=\"rpClientTypeMaster\">Käyttäjän tyyppi:<select id=\"rpClientTypeMaster\" onchange=\"triggerSearch();\" name=\"rpClientTypeMaster\"><option value=\"all\">Kaikki</option><option value=\"custom\">Valitse vaihtoehdoista</option></select></label>	

</div>

<div class=\"col_50 left\">

	<label for=\"rpClientTradeMaster\">Käyttäjän tuotantosuunta:<select id=\"rpClientTradeMaster\" class=\"full\" onchange=\"triggerSearch();\" name=\"rpClientTradeMaster\"><option value=\"all\">Kaikki</option><option value=\"custom\">Valitse vaihtoehdoista</option></select></label>	

</div>

<div class=\"clear height10\"></div>

<div id=\"rpClientTypesDiv\" name=\"rpClientTypesDiv\" class=\"searchOptionsDiv\"><div class=\"clear height5\"></div><h2>Käyttäjätyypit<a href=\"javascript:clearOptions('clientType');triggerSearch();\" class=\"smallFormButton right\">X</a></h2>";

rpPrintTypes("<div class=\"col_33 left mb5\"><input name=\"rpClientTypeHidden_[rp(type)]\" id=\"rpClientTypeHidden_[rp(type)]\" type=\"hidden\" value=\"[rp(type)]\" /><input onchange=\"triggerSearch();\" class=\"css-checkbox client clientType\" name=\"rpClientType_[rp(type)]\" id=\"rpClientType_[rp(type)]\" type=\"checkbox\" CHECKED /><label class=\"css-label\" for=\"rpClientType_[rp(type)]\">[rp(title)]</label></div>", "baseTypes");

echo "<div class=\"clear\"></div>";

rpPrintTypes("<div class=\"col_33 left mb5\"><input name=\"rpClientType2Hidden_[rp(type)]\" id=\"rpClientType2Hidden_[rp(type)]\" type=\"hidden\" value=\"[rp(type)]\" /><input onchange=\"triggerSearch();\" class=\"css-checkbox clientType\" name=\"rpClientType2_[rp(type)]\" id=\"rpClientType2_[rp(type)]\" type=\"checkbox\" CHECKED /><label class=\"css-label\" for=\"rpClientType2_[rp(type)]\">[rp(title)]</label></div>", "subTypes");

echo "<div class=\"clear\"></div></div><div id=\"rpClientTradesDiv\" name=\"rpClientTradesDiv\" class=\"searchOptionsDiv\"><div class=\"clear height5\"></div>

<h2>Käyttäjän tuotantosuunnat<a href=\"javascript:clearOptions('clientTrade');triggerSearch();\" class=\"smallFormButton right\">X</a></h2>";

rpGetTrades("<div class=\"col_33 left mb5\"><input name=\"rpClientTradeHidden_[rp(id)]\" id=\"rpClientTradeHidden_[rp(id)]\" type=\"hidden\" value=\"[rp(prefix)]\" /><input onchange=\"triggerSearch();\" class=\"css-checkbox client clientTrade\" name=\"rpClientTrade_[rp(id)]\" id=\"rpClientTrade_[rp(id)]\" type=\"checkbox\" /><label class=\"css-label\" for=\"rpClientTrade_[rp(id)]\">[rp(title)]</label></div>");

echo "<div class=\"clear\"></div></div>

<div class=\"clear height5\"></div>

<div id=\"cityOptions\" name=\"cityOptions\"></div>

<div class=\"clear height5\"></div></div>

<a href=\"javascript:searchClients();\" id=\"updateResultsButton\" name=\"updateResultsButton\" class=\"formButton left\">Päivitä hakutulokset</a>

</form>

<div class=\"clear\"></div>

<hr />
	
<div id=\"searchResults\" name=\"searchResults\"></div>";
	

?>