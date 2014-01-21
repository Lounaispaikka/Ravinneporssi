<?php include_once("engine/rp.start.php");

if ($_POST["rpAction"]=="rpGetNotifierNotice" && $_SESSION["clientID"]) {
	
	$notifier_types_array = explode("|", rpGetClient("notifier_types"));
	$notifier_products_array = explode("|", rpGetClient("notifier_products"));
	
	$notifier_types = "";
	$notifier_products = "";
	
	foreach ($notifier_types_array as $type) {
		
		if ($type != "") {
			
			$notifier_types .= "products LIKE '%rpProduct|".rpSanitize($type)."|%' OR ";
			
		}
		
	}
	
	foreach ($notifier_products_array as $product) {
		
		if ($product != "") {
			
			$notifier_products .= "products LIKE '%".rpSanitize($product)."|end|%' OR ";
			
		}
		
	}
	
	$notifier_types = substr($notifier_types, 0, -4);
	$notifier_products = substr($notifier_products, 0, -4);
	
	$notices_result = $rpConnection->query("SELECT id, title, latitude, longitude FROM ".$rpSettings->getValue("noticesTable")." WHERE (".$notifier_types.") && (".$notifier_products.") && published='1' && added_clientid!='".rpSanitize(intval($_SESSION["clientID"]))."' && visibility!='administrators'");
	
	$notifications_array = array();
	
	for ($i = 0; $i < mysql_num_rows($notices_result); $i += 1) {
	
		if ((rpGetClient("base_latitude")==0 && rpGetClient("base_longitude")==0) || rpGetClient("notifier_threshold")==0 || rpGeoDistance(mysql_result($notices_result, $i, "latitude"), mysql_result($notices_result, $i, "longitude"), rpGetClient("base_latitude"), rpGetClient("base_longitude"))<rpGetClient("notifier_threshold")) {
			
			array_push($notifications_array, "<h2>".rpUTF8Encode(mysql_result($notices_result, $i, "title"))."</h2><a href=\"javascript:showNotice(".mysql_result($notices_result, $i, "id").");\">Näytä ilmoitus</a>");
			
		}
		
	}
	
	shuffle($notifications_array);
	
	echo $notifications_array[0];
	
}

if ($_POST["rpAction"]=="rpGetClients") {

	$clients_query = "SELECT id, name FROM ".$rpSettings->getValue("clientsTable")." ".$distance_sql_post." WHERE confirmed='1' && published='1' && (visibility='all' OR (".rpSanitize(intval($_SESSION["clientID"])).">0 && visibility='registered') OR id='".rpSanitize(intval($_SESSION["clientID"]))."') && id!='".rpSanitize(intval($_SESSION["clientID"]))."' ORDER by name ASC";

	$clients_result = $rpConnection->query($clients_query);

	for ($i = 0; $i < mysql_num_rows($clients_result); $i += 1) {
		
		echo mysql_result($clients_result, $i, "id")."|".rpUTF8Encode(mysql_result($clients_result, $i, "name"))."|end|";
		
	}

}

if ($_POST["rpAction"]=="rpSearchClients") {
	
	$additionals = "";
	
	if ($_POST["rpSearchOrder"]=="newest") {$order = "by added_datetime DESC";}
	else if ($_POST["rpSearchOrder"]=="oldest") {$order = "by added_datetime ASC";}
	else if ($_POST["rpSearchOrder"]=="name_ascending") {$order = "by name ASC";}
	else if ($_POST["rpSearchOrder"]=="name_descending") {$order = "by name DESC";}
	else {$order = "by added_datetime DESC";}

	$cities = "";
	
	if ($_POST["rpSearchState"]!="all") {
		
		foreach ($_POST as $var => $value) {
			if (substr($var,0,13) == "rpCityHidden_") {
				$city = str_replace("rpCityHidden_","",$var);
	
				if ($_POST["rpCity_".$city]=="on") {
				
					$cities .= " OR city = '".rpSanitize($_POST["rpCityHidden_".$city])."'";
					
				}
				
			}
		}
		
		$cities = substr($cities, 4);
	
		$cities = " && (".$cities;
	
		$cities .= ")";
		
	}	

	// base types

	$types = "";
	
	if ($_POST["rpClientTypeMaster"]!="all") {
	
		foreach ($_POST as $var => $value) {
			
			if (substr($var,0,19) == "rpClientTypeHidden_") {
				$type = str_replace("rpClientTypeHidden_","",$var);
	
				if ($_POST["rpClientType_".$type]=="on") {
				
					$types .= " && types LIKE '%[".rpSanitize($value)."]%'";
					
				}
				
			}
			
		}
	
		$types = substr($types, 4);
	
		$types = " && (".$types;
	
		$types .= ")";
		
		if ($types == " && ()") {$types = "";}
	
	}
	
	if ($_POST["rpClientTypeMaster"]!="all" && $types=="") {$types=" && types=''";}
	
	// sub types
	
	$types2 = "";
	
	if ($_POST["rpClientTypeMaster"]!="all") {
	
		foreach ($_POST as $var => $value) {
			
			if (substr($var,0,20) == "rpClientType2Hidden_") {
				$type2 = str_replace("rpClientType2Hidden_","",$var);
	
				if ($_POST["rpClientType2_".$type2]=="on") {
				
					$types2 .= " && types2 LIKE '%[".rpSanitize($value)."]%'";
					
				}
				
			}
			
		}
	
		$types2 = substr($types2, 4);
	
		$types2 = " && (".$types2;
	
		$types2 .= ")";
		
		if ($types2 == " && ()") {$types2 = "";}
	
	}
	
	if ($_POST["rpClientTypeMaster"]!="all" && $types2=="") {$types2=" && types2=''";}
	
	// trades
	
	$trades = "";
	
	if ($_POST["rpClientTradeMaster"]!="all") {
	
		foreach ($_POST as $var => $value) {
			
			if (substr($var,0,20) == "rpClientTradeHidden_") {
				$trade = str_replace("rpClientTradeHidden_","",$var);
	
				if ($_POST["rpClientTrade_".$trade]=="on") {
				
					$trades .= " && trades LIKE '%|".rpSanitize($value)."|%'";
					
				}
				
			}
			
		}
	
		$trades = substr($trades, 4);
	
		$trades = " && (".$trades;
	
		$trades .= ")";
		
		if ($trades == " && ()") {$trades = "";}
	
	}
	
	if ($_POST["rpClientTradeMaster"]!="all" && $trades=="") {$trades=" && trades=''";}
	
	if ($_POST["rpSearchLatitude"]>0 && $_POST["rpSearchLatitude"]>0 && ($_POST["rpSearchOrder"]=="closest" || $_POST["rpSearchOrder"]=="furthest")) {
		
		$distance_sql_pre = ", 
			(6378.10 * ACOS(COS(RADIANS(latpoint)) 
			* COS(RADIANS(base_latitude)) 
			* COS(RADIANS(longpoint) - RADIANS(base_longitude)) 
			+ SIN(RADIANS(latpoint)) 
			* SIN(RADIANS(base_latitude)))) AS distance_in_km";
		
		$distance_sql_post = "JOIN (
			SELECT ".rpSanitize(floatval($_POST["rpSearchLatitude"]))." AS latpoint, ".rpSanitize(floatval($_POST["rpSearchLongitude"]))." AS longpoint
			) AS p";
		
		if ($_POST["rpSearchOrder"]=="closest") {
			
			$order = "by distance_in_km ASC";
			
		} else if ($_POST["rpSearchOrder"]=="furthest") {
			
			$order = "by distance_in_km DESC";
			
		}
		
	} else {
		
		$distance_sql_pre = "";
		$distance_sql_post = "";
		
	}

	$tooutput = "";

	if (rpIsAdmin($_SESSION["clientID"])) {$published = "";} else {$published = " && published='1'";}

	$clients_query = "SELECT id, name, added_datetime, published, state, city".$distance_sql_pre." FROM ".$rpSettings->getValue("clientsTable")." ".$distance_sql_post." WHERE confirmed='1'".$published.$types." ".$types2." ".$trades." ".$cities." && (visibility='all' OR (".rpSanitize(intval($_SESSION["clientID"])).">0 && visibility='registered') OR id='".rpSanitize(intval($_SESSION["clientID"]))."')".$additionals." ORDER ".$order." LIMIT 100";

	$clients_result = $rpConnection->query($clients_query);
		
	if (mysql_num_rows($clients_result)>0) {
	
		for ($i = 0; $i < mysql_num_rows($clients_result); $i += 1) {
	
			$tooutput .= "<div id=\"searchResultDiv_".mysql_result($clients_result, $i, "id")."\" name=\"searchResultDiv_".mysql_result($clients_result, $i, "id")."\" class=\"searchResultDiv\"><h2><a onmouseover=\"showInfo('Näytä käyttäjän profiili');\" onmouseout=\"hideInfo();\" href=\"javascript:showSubProfile(".mysql_result($clients_result, $i, "id").");\">".rpUTF8Encode(mysql_result($clients_result, $i, "name"));
			
			if (rpIsAdmin($_SESSION["clientID"]) && mysql_result($clients_result, $i, "published")==0) {$tooutput .= " <span style=\"color:#FF0000;\">(julkaisematon)</span>";}
			
			$tooutput .= "</a>";
			
			if (mysql_result($clients_result, $i, "id") != $_SESSION["clientID"] && strstr(rpGetOtherClient(mysql_result($clients_result, $i, "id"), "contact_via"),"[rpmail]") && $_SESSION["clientID"]) {
			
				$tooutput .= "<a href=\"javascript:openPage('messages', ".mysql_result($clients_result, $i, "id").");\" class=\"smallFormButton right\">Lähetä yksityisviesti</a>";
				
			}
			
			$tooutput .= "</h2>";
			
			$tooutput .= "<h3>".substr(rpGetClientTypes("[rp(title)], ", mysql_result($clients_result, $i, "id"), false), 0, -2)."</h3>";
			
			if (rpUTF8Encode(mysql_result($clients_result, $i, "state"))!="" || rpUTF8Encode(mysql_result($clients_result, $i, "city"))!="") {
				
				if (rpUTF8Encode(mysql_result($clients_result, $i, "city"))!="") {
					
					$tooutput .= rpUTF8Encode(mysql_result($clients_result, $i, "state")).", ".rpUTF8Encode(mysql_result($clients_result, $i, "city"));
				
				} else {
					
					$tooutput .= rpUTF8Encode(mysql_result($clients_result, $i, "state"));
					
				}
				
			}
			
			$tooutput .= "</div>";
	
		}
		
	}
	
	if ($tooutput!="") {
		
		echo $tooutput;
		
	} else {echo "Käyttäjiä ei löytynyt.";}
	
}

if ($_POST["rpAction"]=="rpGetFavourites" && $_SESSION["clientID"]) {

	$favourites_array = rpGetCleanFavourites();
		
	$tooutput = "";
	
	$waypoint_string = "";
	
	for ($i = 0; $i < count($favourites_array); $i += 1) {
			
		$notice_query = "SELECT id, title, latitude, longitude, added_clientid, added_datetime, publish_end, state, city FROM ".$rpSettings->getValue("noticesTable")." WHERE published='1' && (visibility='all' OR (".rpSanitize(intval($_SESSION["clientID"])).">0 && visibility='registered') OR added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."') && id='".rpSanitize(intval($favourites_array[$i]))."' LIMIT 1";
		
		$notice_result = $rpConnection->query($notice_query);
		
		if (mysql_num_rows($notice_result)>0) {
		
			if (rpDate(mysql_result($notice_result, 0, "publish_end")) == "" || strtotime(date("Y-m-d"))<strtotime(mysql_result($notice_result, 0, "publish_end"))) {
				
				$tooutput .= "<div id=\"searchResultDiv_".mysql_result($notice_result, 0, "id")."\" name=\"searchResultDiv_".mysql_result($notice_result, 0, "id")."\" class=\"searchResultDiv\"><h2><a onmouseover=\"showInfo('Näytä ilmoituksen tiedot');\" onmouseout=\"hideInfo();\" href=\"javascript:showSubNotice(".mysql_result($notice_result, 0, "id").");\">".rpUTF8Encode(mysql_result($notice_result, 0, "title"))."</a>";
				
				$tooutput .= "<a href=\"javascript:removeFavourite(".mysql_result($notice_result, 0, "id").");\" class=\"smallFormButton right\">Poista</a>";
				
				$tooutput .= "<a href=\"javascript:toggleNotice(".mysql_result($notice_result, 0, "id").");\" class=\"smallFormButton right\">Näytä kartalla</a></h2>".rpGetNoticeProducts("&bull; [rp(type_title)]: [rp(title)]<br />", mysql_result($notice_result, 0, "id"), false)."<h3><a onmouseover=\"showInfo('Näytä käyttäjän profiili');\" onmouseout=\"hideInfo();\" href=\"javascript:showProfile(".mysql_result($notice_result, 0, "added_clientid").");\">".rpGetOtherClient(mysql_result($notice_result, 0, "added_clientid"), "name")."</a> (".rpDate(mysql_result($notice_result, 0, "added_datetime")).")";
				
				$tooutput .= "<div class=\"right\">";
				
				if ($favourites_array[$i-1]) {
				
					$tooutput .= "<a href=\"javascript:moveFavourite(".$favourites_array[$i].",".$favourites_array[$i-1].");\" onmouseover=\"showInfo('Siirrä ylemmäs');\" onmouseout=\"hideInfo();\"><img src=\"graphics/buttons/smallbutton_arrow_up.png\" /></a><br />";
				
				}
				
				if ($favourites_array[$i+1]) {
				
					$tooutput .= "<a href=\"javascript:moveFavourite(".$favourites_array[$i].",".$favourites_array[$i+1].");\" onmouseover=\"showInfo('Siirrä alemmas');\" onmouseout=\"hideInfo();\"><img src=\"graphics/buttons/smallbutton_arrow_down.png\" /></a>";
				
				}
				
				$tooutput .= "</div>";
				
				$tooutput .= "</h3>";
				
				$waypoint_string .= mysql_result($notice_result, 0, "latitude").",".mysql_result($notice_result, 0, "longitude").";";
				
				if (rpUTF8Encode(mysql_result($notice_result, 0, "state"))!="" || rpUTF8Encode(mysql_result($notice_result, 0, "city"))!="") {
					
					if (rpUTF8Encode(mysql_result($notice_result, 0, "city"))!="") {
						
						$tooutput .= rpUTF8Encode(mysql_result($notice_result, 0, "state")).", ".rpUTF8Encode(mysql_result($notice_result, 0, "city"));
					
					} else {
						
						$tooutput .= rpUTF8Encode(mysql_result($notice_result, 0, "state"));
						
					}
					
				}
				
				$tooutput .= "</div>";
				
			}
			
		}
		
	}
	
	if ($_POST["rpGenerateWaypointString"]=="true") {
		
		echo $waypoint_string;
		
	} else {
	
		if ($tooutput!="") {
			
			echo $tooutput;
			
			echo "<a id=\"waypointRouteButton\" name=\"waypointRouteButton\" href=\"javascript:generateWaypointRoute('".$waypoint_string."');\" class=\"formButton left\">Hae reitti suosikkien välille</a><div style=\"margin-top: 19px;\">&nbsp;
			
			<input class=\"css-checkbox\" name=\"waypointRouteStartHome\" id=\"waypointRouteStartHome\" type=\"checkbox\" /><label class=\"css-label\" for=\"waypointRouteStartHome\"> Kotipaikka aloituspisteenä</label>&nbsp;&nbsp;&nbsp;
			
			<input class=\"css-checkbox\" name=\"waypointRouteEndHome\" id=\"waypointRouteEndHome\" type=\"checkbox\" /><label class=\"css-label\" for=\"waypointRouteEndHome\"> Kotipaikka päätöspisteenä</label>
			
			</div>";
			
		} else {echo "Ilmoituksia ei löytynyt.";}

	}

}

if ($_POST["rpAction"]=="rpSearchNotices") {

	$additionals = "";
	
	if ($_POST["rpSearchType"]!="all") {$additionals .= " && products LIKE '%rpProduct|".rpSanitize($_POST["rpSearchType"])."|%'";}

	if ($_POST["rpSearchOrder"]=="newest") {$order = "by added_datetime DESC";}
	else if ($_POST["rpSearchOrder"]=="oldest") {$order = "by added_datetime ASC";}
	else if ($_POST["rpSearchOrder"]=="title_ascending") {$order = "by title ASC";}
	else if ($_POST["rpSearchOrder"]=="title_descending") {$order = "by title DESC";}
	else if ($_POST["rpSearchOrder"]=="value_ascending") {$order = "by value ASC";}
	else if ($_POST["rpSearchOrder"]=="value_descending") {$order = "by value DESC";}
	else {$order = "by added_datetime DESC";}

	if ($_POST["rpSearchCounterNotices"]!="") {$_POST["rpSearchOrder"]="closest";}

	$cities = "";
	
	if ($_POST["rpSearchState"]!="all") {
		
		foreach ($_POST as $var => $value) {
			if (substr($var,0,13) == "rpCityHidden_") {
				$city = str_replace("rpCityHidden_","",$var);
	
				if ($_POST["rpCity_".$city]=="on") {
				
					$cities .= " OR city = '".rpSanitize($_POST["rpCityHidden_".$city])."'";
					
				}
				
			}
		}
		
		$cities = substr($cities, 4);
	
		$cities = " && (".$cities;
	
		$cities .= ")";
		
	}	

	$options = "";
	
	if ($_POST["rpSearchType"]!="all") {
	
		foreach ($_POST as $var => $value) {
			if (substr($var,0,22) == "rpProductHiddenOption_") {
				$option = str_replace("rpProductHiddenOption_","",$var);
	
				if ($_POST["rpProductOption_".$option]=="on") {
				
					$options .= " OR products LIKE '%|".rpSanitize($option)."|%'";
					
				}
				
			}
		}
		
		foreach ($_POST as $var => $value) {
			if (substr($var,0,16) == "rpProductHidden_") {
				$product = str_replace("rpProductHidden_","",$var);
	
				if ($_POST["rpProduct_".$product]=="on") {
				
					$options .= " OR products LIKE '%|".rpSanitize($product)."|%'";
					
				}
				
			}
		}
	
		$options = substr($options, 4);
	
		$options = " && (".$options;
	
		$options .= ")";

	}

	if ($_POST["rpSearchLatitude"]>0 && $_POST["rpSearchLatitude"]>0 && ($_POST["rpSearchOrder"]=="closest" || $_POST["rpSearchOrder"]=="furthest")) {
		
		$distance_sql_pre = ", 
			(6378.10 * ACOS(COS(RADIANS(latpoint)) 
			* COS(RADIANS(latitude)) 
			* COS(RADIANS(longpoint) - RADIANS(longitude)) 
			+ SIN(RADIANS(latpoint)) 
			* SIN(RADIANS(latitude)))) AS distance_in_km";
		
		$distance_sql_post = "JOIN (
			SELECT ".rpSanitize(floatval($_POST["rpSearchLatitude"]))." AS latpoint, ".rpSanitize(floatval($_POST["rpSearchLongitude"]))." AS longpoint
			) AS p";
		
		if ($_POST["rpSearchOrder"]=="closest") {
			
			$order = "by distance_in_km ASC";
			
		} else if ($_POST["rpSearchOrder"]=="furthest") {
			
			$order = "by distance_in_km DESC";
			
		}
		
	} else {
		
		$distance_sql_pre = "";
		$distance_sql_post = "";
		
	}

	if (rpIsAdmin($_SESSION["clientID"])) {$published = " (published='1' OR published='0')";} else {$published = " published='1'";}

	if ($_POST["rpSearchClient"]>0) {

		$notices_query = "SELECT id, title, latitude, longitude, added_clientid, added_datetime, publish_end, published, state, city FROM ".$rpSettings->getValue("noticesTable")." WHERE".$published." && (visibility='all' OR (".rpSanitize(intval($_SESSION["clientID"])).">0 && visibility='registered') OR added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."') && added_clientid='".rpSanitize(intval($_POST["rpSearchClient"]))."' ORDER ".$order." LIMIT 100";

	} else if ($_POST["rpSearchCounterNotices"]!="") {
	
		$notices_query = "SELECT id, title, latitude, longitude, added_clientid, added_datetime, publish_end, published, state, products, city".$distance_sql_pre." FROM ".$rpSettings->getValue("noticesTable")." ".$distance_sql_post." WHERE".$published." && products LIKE '%rpProduct|".rpSanitize($_POST["rpSearchCounterNotices"])."|%' && (visibility='all' OR (".rpSanitize(intval($_SESSION["clientID"])).">0 && visibility='registered') OR added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."') ORDER ".$order." LIMIT 5";
		
	} else {

		$notices_query = "SELECT id, title, latitude, longitude, added_clientid, added_datetime, publish_end, published, state, products, city".$distance_sql_pre." FROM ".$rpSettings->getValue("noticesTable")." ".$distance_sql_post." WHERE".$published.$options." ".$cities." && (visibility='all' OR (".rpSanitize(intval($_SESSION["clientID"])).">0 && visibility='registered') OR added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."')".$additionals." ORDER ".$order." LIMIT 100";

	}

	$notices_result = $rpConnection->query($notices_query);
		
	if (mysql_num_rows($notices_result)>0) {
	
		for ($i = 0; $i < mysql_num_rows($notices_result); $i += 1) {
		
			if (rpDate(mysql_result($notices_result, $i, "publish_end")) == "" || strtotime(date("Y-m-d"))<strtotime(mysql_result($notices_result, $i, "publish_end"))) {

				if ($_POST["rpSearchClient"]>0 || $_POST["rpSearchType"]=="all" || $_POST["rpSearchCounterNotices"]!="" || rpIsSearchResultValid(mysql_result($notices_result, $i, "products"), $_POST["rpSearchType"], $options)) {
		
					$tooutput .= "<div id=\"searchResultDiv_".mysql_result($notices_result, $i, "id")."\" name=\"searchResultDiv_".mysql_result($notices_result, $i, "id")."\" class=\"searchResultDiv\"><h2><a onmouseover=\"showInfo('Näytä ilmoituksen tiedot');\" onmouseout=\"hideInfo();\" href=\"javascript:showSubNotice(".mysql_result($notices_result, $i, "id").");\">".rpUTF8Encode(mysql_result($notices_result, $i, "title"));
					
					if (rpIsAdmin($_SESSION["clientID"]) && mysql_result($notices_result, $i, "published")==0) {$tooutput .= " <span style=\"color:#FF0000;\">(julkaisematon)</span>";}
					
					$tooutput .= "</a>";
										
					$tooutput .= "<a href=\"javascript:toggleNotice(".mysql_result($notices_result, $i, "id").");\" class=\"smallFormButton right\">Näytä kartalla</a></h2>".rpGetNoticeProducts("&bull; [rp(type_title)]: [rp(title)]<br />", mysql_result($notices_result, $i, "id"), false)."<h3><a onmouseover=\"showInfo('Näytä käyttäjän profiili');\" onmouseout=\"hideInfo();\" href=\"javascript:showProfile(".mysql_result($notices_result, $i, "added_clientid").");\">".rpGetOtherClient(mysql_result($notices_result, $i, "added_clientid"), "name")."</a> (".rpDate(mysql_result($notices_result, $i, "added_datetime")).")</h3>";
										
					if (rpUTF8Encode(mysql_result($notices_result, $i, "state"))!="" || rpUTF8Encode(mysql_result($notices_result, $i, "city"))!="") {
						
						if (rpUTF8Encode(mysql_result($notices_result, $i, "city"))!="") {
							
							$tooutput .= rpUTF8Encode(mysql_result($notices_result, $i, "state")).", ".rpUTF8Encode(mysql_result($notices_result, $i, "city"));
						
						} else {
							
							$tooutput .= rpUTF8Encode(mysql_result($notices_result, $i, "state"));
							
						}
						
					}
					
					$tooutput .= "</div>";
						
				}
			
			}
			
		}
	
	}

	if ($tooutput!="") {
		
		if ($_POST["rpSearchCounterNotices"]!="") {$tooutput = str_replace("class=\"searchResultDiv\"","class=\"searchResultDiv bright\"",$tooutput);}
		
		echo $tooutput;
		
	} else {echo "Ilmoituksia ei löytynyt.";}

}

if ($_POST["rpSearchType"]!="" && $_POST["rpAction"]=="rpGetSearchOptions") {

	if ($_POST["rpSearchType"]!="all") {
		
		$products_result = $rpConnection->query("SELECT id, prefix, title, options FROM ".$rpSettings->getValue("productsTable")." WHERE published='1' && types LIKE '%[".rpSanitize($_POST["rpSearchType"])."]%' ORDER by priority ASC");

		echo "<div class=\"searchOptionsDiv\">";

	}

	for ($i = 0; $i < mysql_num_rows($products_result); $i += 1) {

		if (strstr(mysql_result($products_result, $i, "options"), "|0|end|")) {

			echo "<div class=\"clear height5\"></div><h2>".rpUTF8Encode(mysql_result($products_result, $i, "title"))."<a href=\"javascript:clearOptions('optionGroup_".mysql_result($products_result, $i, "id")."');triggerSearch();\" class=\"smallFormButton right\">X</a></h2>";
	
			rpGetProductOptions("<div class=\"searchOptionDiv\"><input name=\"rpProductHiddenOption_[rp(type)]\" id=\"rpProductHiddenOption_[rp(type)]\" type=\"hidden\" /><input class=\"css-checkbox optionGroup_".mysql_result($products_result, $i, "id")."\" name=\"rpProductOption_[rp(type)]\" onchange=\"triggerSearch();\" id=\"rpProductOption_[rp(type)]\" type=\"checkbox\" CHECKED /><label class=\"css-label\" for=\"rpProductOption_[rp(type)]\">[rp(title)]</label></div>", "", "", mysql_result($products_result, $i, "id"));
	
			echo "<div class=\"clear\"></div>";

		} else {
			
			echo "<div class=\"clear height5\"></div><h2>".rpUTF8Encode(mysql_result($products_result, $i, "title"))."</h2>
	
			<div class=\"searchOptionDiv\"><input name=\"rpProductHidden_".rpUTF8Encode(mysql_result($products_result, $i, "prefix"))."\" id=\"rpProductHidden_".rpUTF8Encode(mysql_result($products_result, $i, "prefix"))."\" type=\"hidden\" /><input class=\"css-checkbox\" name=\"rpProduct_".rpUTF8Encode(mysql_result($products_result, $i, "prefix"))."\" onchange=\"triggerSearch();\" id=\"rpProduct_".rpUTF8Encode(mysql_result($products_result, $i, "prefix"))."\" type=\"checkbox\" CHECKED /><label class=\"css-label\" for=\"rpProduct_".rpUTF8Encode(mysql_result($products_result, $i, "prefix"))."\">".rpUTF8Encode(mysql_result($products_result, $i, "title"))."</label></div>
	
			<div class=\"clear\"></div>";
			
		}

	}

	if ($_POST["rpSearchType"]!="all") {
		
		echo "</div>";
		
	}

}

include_once("engine/rp.end.php"); ?>