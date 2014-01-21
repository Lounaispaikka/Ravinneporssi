<?php include_once("engine/rp.start.php");

if ($_GET["id"]=="editnotice" && $_SESSION["clientID"] && rpIsAlive($_GET["noticeid"], "notices")) {
	
	if (rpGetNotice($_GET["noticeid"], "added_clientid") == $_SESSION["clientID"]) {

		echo "<form name=\"updateNoticeForm\" id=\"updateNoticeForm\" action=\"http://".$rpSettings->getValue("domain")."/map\" method=\"POST\" enctype=\"multipart/form-data\">
	
		<script>
		
		$(function() {
			
			$(\"#rpNoticePublishEnd\").datepicker({
				monthNames: [\"Tammikuu\", \"Helmikuu\", \"Maaliskuu\", \"Huhtikuu\", \"Toukokuu\", \"Kesäkuu\", \"Heinäkuu\", \"Elokuu\", \"Syyskuu\", \"Lokakuu\", \"Marraskuu\", \"Joulukuu\"],
				dayNamesMin: [\"Su\",\"Ma\",\"Ti\",\"Ke\",\"To\",\"Pe\",\"La\"],
				dateFormat: \"d.m.yy\",
				firstDay:1
				});
		
		});
		
		var userState = \"".rpGetNotice($_GET["noticeid"], "state")."\";
		var userCity = \"".rpGetNotice($_GET["noticeid"], "city")."\";
		var userPostalcode = \"".rpGetClient("postalcode")."\";
		
		if (userCity!=\"\") {listCities(userState);}
		
		</script>
		
		<input type=\"hidden\" name=\"rpCheck\" value=\"".md5("NOTICE".intval($_GET["noticeid"]).$rpSettings->getValue("secret").$_SESSION["clientID"])."\" />
		<input type=\"hidden\" name=\"rpAction\" value=\"rpUpdateNotice\" />
		<input type=\"hidden\" name=\"rpNoticeID\" value=\"".intval($_GET["noticeid"])."\" />
				
		<label for=\"rpNoticeTitle\">Ilmoituksen otsikko: *<input name=\"rpNoticeTitle\" id=\"rpNoticeTitle\" type=\"text\" class=\"full\" value=\"".rpGetNotice($_GET["noticeid"], "title")."\" /></label>	
		
		<label for=\"rpNoticeDescription\">Ilmoituksen tiedot: *<textarea name=\"rpNoticeDescription\" id=\"rpNoticeDescription\" class=\"bigText\">".rpGetNotice($_GET["noticeid"], "description")."</textarea></label>
		
		<div class=\"clear\"></div>
		
		<div class=\"col_50 left\">
	
		<label for=\"rpNoticeState\">Maakunta: *<select onchange=\"listCities(this.value);\" id=\"rpNoticeState\" name=\"rpNoticeState\">";
		
			rpGetStates("<option value=\"[rp(title)]\" SELECTED>[rp(title)]</option>", rpGetNotice($_GET["noticeid"], "state"));
		
		echo "</select></label>	
	
		</div>
		
		<div class=\"col_50 left\">
		
			<label for=\"rpNoticeCity\">Paikkakunta: *<select id=\"rpNoticeCity\" name=\"rpNoticeCity\" class=\"full\"></select></label>	
		
		</div>
		
		<div class=\"clear\"></div>
		
		<div class=\"col_50 left\">
		
			<label for=\"rpNoticeVisibility\">Ilmoituksen näkyvyys:<select id=\"rpNoticeVisibility\" name=\"rpNoticeVisibility\">";
			
				rpPrintTypes("<option value=\"[rp(type)]\" SELECTED>[rp(title)]</option>", "visibilityTypes", rpGetNotice($_GET["noticeid"], "visibility"));
			
			echo "</select></label>	
		
		</div>
		
		<div class=\"col_50 left\">
		
			<label for=\"rpNoticePublishEnd\">Ilmoituksen päättyminen (esim. 1.1.2014):<input name=\"rpNoticePublishEnd\" id=\"rpNoticePublishEnd\" type=\"text\" class=\"full\" value=\"".rpDate(rpGetNotice($_GET["noticeid"], "publish_end"))."\" /></label>	
		
		</div>
		
		<div class=\"clear\"></div>
	
		<div class=\"col_50 left\">
		
			<label for=\"rpNoticeValue\">Lannoitemäärä kuutiometreissä (m<sup>3</sup>):<input name=\"rpNoticeValue\" id=\"rpNoticeValue\" type=\"text\" value=\"".str_replace(".",",",rpGetNotice($_GET["noticeid"], "value"))."\" /></label>	
		
		</div>
		
		<div class=\"col_50 left\">
	
		<label for=\"rpNoticeAddress\">Ilmoituksen katuosoite: *<input name=\"rpNoticeAddress\" id=\"rpNoticeAddress\" type=\"text\" class=\"full\" value=\"".rpGetNotice($_GET["noticeid"], "address")."\" /></label>	
	
		</div>
		
		<div class=\"clear height5\"></div>
		
		<h2>Ilmoituksen sisältö</h2>
		
		<script>
		
		var rIndex = 1;
		var fIndex = 1;
		
		function removeProduct(id) {
			
			$(\"#product_\"+id).remove();
			
		}
		
		function changeProductOption(id, type, pid) {
						
			$(\"#productOptions_\"+id).load(\"_products.php?nid=".$_GET["noticeid"]."&pid=\"+pid+\"&rid=\"+id+\"&type=options&id=\"+$(\"#rpProduct_\"+id).val());
					
		}
		
		function changeProductType(id, type, pid) {

			if (type!=undefined) {

					$(\"#rpProductType_\"+id).val(type);

				}

			$(\"#productOptions_\"+id).html(\"\");

			$(\"#productTypes_\"+id).load(\"_products.php?nid=".$_GET["noticeid"]."&pid=\"+pid+\"&rid=\"+id+\"&type=base&id=\"+$(\"#rpProductType_\"+id).val(), function() {

				if (type!=undefined) {

					changeProductOption(id, type, pid);
					
				}
				
			});
			
		}
		
		function addProduct(type, pid) {
				
			$(\"#productsContainer\").append(\"<div id='product_\"+rIndex+\"' class='productDiv'><select onchange='changeProductType(\"+rIndex+\")' class='productTypeSelector' name='rpProductType_\"+rIndex+\"' id='rpProductType_\"+rIndex+\"'><option value=''>Valitse tyyppi...</option>";
			
			rpPrintTypes("<option value='[rp(type)]'>[rp(title)]</option>", "baseTypes");
					
			echo "</select><a href='javascript:removeProduct(\"+rIndex+\");' class='smallFormButton right'>Poista</a><div id='productTypes_\"+rIndex+\"'></div><div id='productOptions_\"+rIndex+\"'></div></div>\");

			if (type!=undefined) {changeProductType(rIndex, type, pid);}
			
			rIndex+=1;
			
		}
		
		function removeNoticeFile(id,checksum) {
			
			$.ajax({
				type:\"POST\",
				data: {
					rpAction:\"rpRemoveNoticeFile\",
					rpNotice:".$_GET["noticeid"].",
					rpFileID:checksum
				},
			    url:\"_notice.php\",
			    success: function(data) {
			    	
			    	if (data==\"SUCCESS\") {
	    				    	
			    		$(\"#rpNoticeFileDiv_\"+id).fadeOut(300);
			    	
			    	} else {notify(data);} 
			    					    	
			    }
			});		
			
		}
		
		function addNoticeFile() {
		
			$(\"#noticeFileUploadContainer\").append(\"<div id='rpNoticeFileUploadDiv_\"+fIndex+\"' name='rpNoticeFileUploadDiv_\"+fIndex+\"'><a href='javascript:removeNoticeFileUpload(\"+fIndex+\");' style='margin-top: 0px;' class='smallFormButton right'>Poista</a><input type='file' class='rpNoticeFileUpload' id='rpNoticeFileUpload_\"+fIndex+\"' name='rpNoticeFileUpload_\"+fIndex+\"' /><div class='clear height5'></div></div>\");
							
			fIndex += 1;
		
		}
		
		function removeNoticeFileUpload(id) {
					
			$(\"#rpNoticeFileUploadDiv_\"+id).fadeOut(300);	
			$(\"#rpNoticeFileUpload_\"+id).val(\"\");
			$(\"#rpNoticeFileUpload_\"+id).remove();
			
		}
		
		";
		
		rpGetNoticeProducts("addProduct(\"[rp(type)]\",[rp(id)]);", $_GET["noticeid"]);
		
		echo "</script>
		
		<div id=\"productsContainer\"></div>
		
		<div class=\"clear height5\"></div>
		
		<a href=\"javascript:addProduct();\" class=\"formButton left\">Lisää uusi sisältö</a>
		
		<div class=\"clear height15\"></div>
		
		<h2>Ilmoituksen liitetiedostot</h2>";
		
		rpGetNoticeFiles("<div id=\"rpNoticeFileDiv_[rp(id)]\" name=\"rpNoticeFileDiv_[rp(id)]\"><a href=\"javascript:removeNoticeFile([rp(id)],'[rp(checksum)]');\" style=\"margin-top: 0px;\" class=\"smallFormButton right\">Poista</a><a href=\"[rp(link)]\" target=\"_blank\">[rp(filename)]</a><div class=\"clear height5\"></div></div>", $_GET["noticeid"]);
		
		echo "<div id=\"noticeFileUploadContainer\"></div>
	
		<div class=\"clear height5\"></div>
		
		<a href=\"javascript:addNoticeFile();\" class=\"formButton left\">Lisää uusi tiedosto</a><div style=\"margin-top: 5px;\"> Sallitut tiedostomuodot: ".rpPrintFileTypes("[rp(type)], ", 2, false)."<br />(maks. ".intval($rpSettings->getValue("maximumFileSize")/1000000)." megatavua)</div>
		
		<div class=\"clear height15\"></div>
		
		<h2>Sallitut yhteydenottotavat</h2>";
		
		rpPrintTypes("<div class=\"col_33 left mb5\"><input class=\"css-checkbox\" name=\"rpNoticeContactVia_[rp(type)]\" id=\"rpNoticeContactVia_[rp(type)]\" type=\"checkbox\" CHECKED /><label class=\"css-label\" for=\"rpNoticeContactVia_[rp(type)]\">[rp(title)]</label></div>", "contactTypes", rpGetNotice($_GET["noticeid"], "contact_via"));
		
		echo "<div class=\"clear height5\"></div>
		
		<a href=\"javascript:updateNotice();\" class=\"formButton left\">Tallenna ilmoitus</a>
		
		<a href=\"javascript:positionNotice(".intval($_GET["noticeid"]).");\" class=\"formButton left\">Muuta ilmoituksen paikkaa</a>
		
		<a href=\"javascript:changeTab('notices');\" class=\"formButton left\">Palaa takaisin</a>
		
		</form>";

	} else {echo "Ilmoitusta ei löytynyt.";}
	
}

if ($_GET["id"]=="shownotice" && (rpGetNotice($_GET["noticeid"], "visibility") == "all" || $_SESSION["clientID"]>0)) {

	if (rpIsAlive($_GET["noticeid"], "notices")) {

		echo "<h1>Ilmoituksen tiedot";
		
		if ($_GET["return"]=="list") {
			echo "<a href=\"javascript:closeSubPage();\" class=\"formButton right last\">Takaisin</a>";
		} else {
			echo "<a href=\"javascript:closePage();\" class=\"formButton right last\">Sulje</a>";
		}
		
		echo "</h1>";
	
		echo "<h2>".rpGetNotice($_GET["noticeid"], "title")."</h2>".rpGetNotice($_GET["noticeid"], "description")."
		
		<div class=\"clear height15\"></div>
		
		<div class=\"noticeInformationDiv\">
		
		<div class=\"col_50 left\">
		
			<h3>Maakunta:</h3>".rpGetNotice($_GET["noticeid"], "state"); if (rpGetNotice($_GET["noticeid"], "state")=="") {echo "Ei määritelty";}
		
		echo "</div>
		
		<div class=\"col_50 left\">
		
			<h3>Paikkakunta:</h3>".rpGetNotice($_GET["noticeid"], "city"); if (rpGetNotice($_GET["noticeid"], "city")=="") {echo "Ei määritelty";}
		
		echo "</div>
		
		<div class=\"clear height5\"></div>
		
		<div class=\"col_50 left\">
		
			<h3>Ilmoituksen näkyvyys:</h3>".rpGetTypeTitle(rpGetNotice($_GET["noticeid"], "visibility"), "visibilityTypes")."
		
		</div>
		
		<div class=\"col_50 left\">
		
			<h3>Ilmoituksen päättymisaika:</h3>".rpDate(rpGetNotice($_GET["noticeid"], "publish_end"));
		
			if (rpDate(rpGetNotice($_GET["noticeid"], "publish_end"))=="") {echo "Toistaiseksi voimassa";}
		
		echo "</div>
		
		<div class=\"clear height5\"></div>
		
		<div class=\"col_50 left\">
		
			<h3>Ilmoituksen katuosoite:</h3>".rpGetNotice($_GET["noticeid"], "address"); if (rpGetNotice($_GET["noticeid"], "address")=="") {echo "Ei määritelty";}
		
		echo "</div>
		
		<div class=\"col_50 left\">
		
			<h3>Ilmoittaja:</h3><a class=\"whitelink\" onmouseover=\"showInfo('Näytä käyttäjän profiili');\" onmouseout=\"hideInfo();\" href=\"javascript:showProfile(".rpGetNotice($_GET["noticeid"], "added_clientid").");\">".rpGetOtherClient(rpGetNotice($_GET["noticeid"], "added_clientid"), "name")."</a>
		
		</div>
		
		<div class=\"clear height5\"></div>";
		
		if (rpGetNotice($_GET["noticeid"], "value")>0) {
			
		echo "<div class=\"col_50 left\">
		
			<h3>Lannoitteen määrä:</h3>".str_replace(".",",",rpGetNotice($_GET["noticeid"], "value"))." m<sup>3</sup>
		
		</div><div class=\"clear height5\"></div>";
			
		}
		
		echo "</div>
		
		<div class=\"clear height10\"></div>
		
		<h2>Ilmoituksen sisältö</h2>";
	
		rpGetNoticeProducts("<div class=\"productDiv\"><h3>[rp(type_title)]: [rp(title)]</h3>[rp(options)]<div class=\"clear height5\"></div></div>", $_GET["noticeid"]);
		
		if (rpGetNotice($_GET["noticeid"], "files")!="") {
			
			echo "<div class=\"clear height10\"></div><h2>Ilmoituksen liitetiedostot</h2>";	
			
			rpGetNoticeFiles("<a href=\"[rp(link)]\" target=\"_blank\">[rp(filename)]</a><br />", $_GET["noticeid"]);
			
		}
		
		echo "<div class=\"clear height5\"></div>";
		
		if (rpGetNotice($_GET["noticeid"], "added_clientid") != $_SESSION["clientID"] && strstr(rpGetOtherClient(rpGetNotice($_GET["noticeid"], "added_clientid"), "contact_via"),"[email]") && strstr(rpGetOtherClient(rpGetNotice($_GET["noticeid"], "added_clientid"), "email"),"@")) {
			
			echo "<a href=\"mailto:".rpGetOtherClient(rpGetNotice($_GET["noticeid"], "added_clientid"), "email")."\" class=\"formButton left\" target=\"_blank\">Lähetä sähköpostia</a>";
			
		}
		
		if (rpGetNotice($_GET["noticeid"], "added_clientid") != $_SESSION["clientID"] && strstr(rpGetOtherClient(rpGetNotice($_GET["noticeid"], "added_clientid"), "contact_via"),"[rpmail]") && $_SESSION["clientID"]) {
			
			echo "<a href=\"javascript:openPage('messages', ".intval(rpGetNotice($_GET["noticeid"], "added_clientid")).");\" class=\"formButton left\">Lähetä yksityisviesti</a>";
			
		}
		
		echo "<div class=\"clear\"></div>";

		if (rpGetCounterNoticeType(rpGetNotice($_GET["noticeid"], "products"))!="") {
			
			echo "<div class=\"clear height5\"></div><hr />
		
			<h2>".rpGetCounterNoticeType(rpGetNotice($_GET["noticeid"], "products"), true)."</h2>
		
			<script>
			
				$(document).ready(function() {
									
					$.ajax({
						type:\"POST\",
						data: {
							rpAction:\"rpSearchNotices\",
							rpSearchCounterNotices:\"".rpGetCounterNoticeType(rpGetNotice($_GET["noticeid"], "products"))."\",
							rpSearchLatitude:".rpGetNotice($_GET["noticeid"], "latitude").",
							rpSearchLongitude:".rpGetNotice($_GET["noticeid"], "longitude")."
						},
					    url:\"_search.php\",
					    success: function(data) {
					    	
					    	$(\"#counterNoticesDiv\").html(data);
					    					    	
					    }
					});	
					
				});
			
			</script>
			
			<div id=\"counterNoticesDiv\" name=\"counterNoticesDiv\"></div>
			
			<div class=\"clear height10\"></div>";

			if (rpIsAdmin($_SESSION["clientID"])) {
				
				echo "<div class=\"clear height10\"></div><h2>Pääkäyttäjän toiminnot</h2>
				<div class=\"noticeInformationDiv\">				
				
				<div class=\"clear height5\"></div>";			
				
				if (rpGetNotice($_GET["noticeid"], "published")==1) {echo "<a href=\"javascript:rpAdminUnpublishNotice(".intval($_GET["noticeid"]).");\" class=\"formButton left\">Piilota ilmoitus näkyvistä</a>";}
				
				else if (rpGetNotice($_GET["noticeid"], "published")==0) {echo "<a href=\"javascript:rpAdminPublishNotice(".intval($_GET["noticeid"]).");\" class=\"formButton left\">Palauta ilmoitus näkyviin</a>";}
				
				echo "<div class=\"clear height10\"></div></div>";
				
			}
			
		}

	} else {echo "Ilmoitusta ei löytynyt.";}

}

if ($_GET["id"]=="addnotice" && $_SESSION["clientID"]) {
	
	echo "<form name=\"saveNoticeForm\" id=\"saveNoticeForm\" action=\"http://".$rpSettings->getValue("domain")."/map\" method=\"POST\" enctype=\"multipart/form-data\">
	
	<script>
	
	$(function() {
		
		$(\"#rpNoticePublishEnd\").datepicker({
			monthNames: [\"Tammikuu\", \"Helmikuu\", \"Maaliskuu\", \"Huhtikuu\", \"Toukokuu\", \"Kesäkuu\", \"Heinäkuu\", \"Elokuu\", \"Syyskuu\", \"Lokakuu\", \"Marraskuu\", \"Joulukuu\"],
			dayNamesMin: [\"Su\",\"Ma\",\"Ti\",\"Ke\",\"To\",\"Pe\",\"La\"],
			dateFormat: \"d.m.yy\",
			firstDay:1
			});
	
	});
		
	var userState = \"".rpGetClient("state")."\";
	var userCity = \"".rpGetClient("city")."\";
	var userPostalcode = \"".rpGetClient("postalcode")."\";
	
	if (userCity!=\"\") {listCities(userState);}
		
	</script>
	
	<input type=\"hidden\" name=\"rpCheck\" value=\"".md5("NOTICE".$rpSettings->getValue("secret").$_SESSION["clientID"])."\" />
	<input type=\"hidden\" name=\"rpAction\" value=\"rpSaveNotice\" />
	
	<input type=\"hidden\" id=\"rpNoticeLatitude\" name=\"rpNoticeLatitude\" value=\"0\" />
	<input type=\"hidden\" id=\"rpNoticeLongitude\" name=\"rpNoticeLongitude\" value=\"0\" />
	<input type=\"hidden\" id=\"rpNoticePosX\" name=\"rpNoticePosX\" value=\"0\" />
	<input type=\"hidden\" id=\"rpNoticePosY\" name=\"rpNoticePosY\" value=\"0\" />
			
	<label for=\"rpNoticeTitle\">Ilmoituksen otsikko: *<input name=\"rpNoticeTitle\" id=\"rpNoticeTitle\" type=\"text\" class=\"full\" /></label>	
	
	<label for=\"rpNoticeDescription\">Ilmoituksen tiedot: *<textarea name=\"rpNoticeDescription\" id=\"rpNoticeDescription\" class=\"bigText\"></textarea></label>
	
	<div class=\"clear\"></div>
	
	<div class=\"col_50 left\">
	
		<label for=\"rpNoticeState\">Maakunta: *<select onchange=\"listCities(this.value);\" id=\"rpNoticeState\" name=\"rpNoticeState\">";
		
			rpGetStates("<option value=\"[rp(title)]\" SELECTED>[rp(title)]</option>", rpGetClient("state"));
		
		echo "</select></label>	
	
	</div>
	
	<div class=\"col_50 left\">
	
		<label for=\"rpNoticeCity\">Paikkakunta: *<select id=\"rpNoticeCity\" name=\"rpNoticeCity\" class=\"full\"></select></label>	
	
	</div>
	
	<div class=\"clear\"></div>
	
	<div class=\"col_50 left\">
	
		<label for=\"rpNoticeVisibility\">Ilmoituksen näkyvyys:<select id=\"rpNoticeVisibility\" name=\"rpNoticeVisibility\">";
		
			rpPrintTypes("<option value=\"[rp(type)]\" SELECTED>[rp(title)]</option>", "visibilityTypes", $rpSettings->getValue("noticeDefaultVisibility"));
		
		echo "</select></label>	
	
	</div>
	
	<div class=\"col_50 left\">
	
		<label for=\"rpNoticePublishEnd\">Ilmoituksen päättyminen (esim. 1.1.2014):<input name=\"rpNoticePublishEnd\" id=\"rpNoticePublishEnd\" type=\"text\" class=\"full\" /></label>	
	
	</div>
	
	<div class=\"clear\"></div>
	
	<div class=\"col_50 left\">
	
		<label for=\"rpNoticeValue\">Lannoitemäärä kuutiometreissä (m<sup>3</sup>):<input name=\"rpNoticeValue\" id=\"rpNoticeValue\" type=\"text\" /></label>	
	
	</div>
	
	<div class=\"col_50 left\">
	
		<label for=\"rpNoticeAddress\">Ilmoituksen katuosoite: *<input name=\"rpNoticeAddress\" id=\"rpNoticeAddress\" type=\"text\" class=\"full\" value=\"".strip_tags($_GET["address"])."\" /></label>	
	
	</div>
	
	<div class=\"clear height5\"></div>
	
	<h2>Ilmoituksen sisältö</h2>
	
	<script>
	
	var rIndex = 1;
	var fIndex = 1;
	
	function removeProduct(id) {
		
		$(\"#product_\"+id).remove();
		
	}
	
	function changeProductOption(id) {
		
		$(\"#productOptions_\"+id).load(\"_products.php?rid=\"+id+\"&type=options&id=\"+$(\"#rpProduct_\"+id).val());
		
	}
	
	function changeProductType(id) {
		
		$(\"#productOptions_\"+id).html(\"\");
		
		$(\"#productTypes_\"+id).load(\"_products.php?rid=\"+id+\"&type=base&id=\"+$(\"#rpProductType_\"+id).val());
		
	}
	
	function addProduct(id) {
	
		$(\"#productsContainer\").append(\"<div id='product_\"+rIndex+\"' class='productDiv'><select onchange='changeProductType(\"+rIndex+\")' class='productTypeSelector' name='rpProductType_\"+rIndex+\"' id='rpProductType_\"+rIndex+\"'><option value=''>Valitse tyyppi...</option>";
		
		rpPrintTypes("<option value='[rp(type)]'>[rp(title)]</option>", "baseTypes");
				
		echo "</select><a href='javascript:removeProduct(\"+rIndex+\");' class='smallFormButton right'>Poista</a><div id='productTypes_\"+rIndex+\"'></div><div id='productOptions_\"+rIndex+\"'></div></div>\");
						
		rIndex+=1;
		
	}
	
	function addNoticeFile() {
		
		$(\"#noticeFileUploadContainer\").append(\"<div id='rpNoticeFileUploadDiv_\"+fIndex+\"' name='rpNoticeFileUploadDiv_\"+fIndex+\"'><a href='javascript:removeNoticeFileUpload(\"+fIndex+\");' style='margin-top: 0px;' class='smallFormButton right'>Poista</a><input type='file' class='rpNoticeFileUpload' id='rpNoticeFileUpload_\"+fIndex+\"' name='rpNoticeFileUpload_\"+fIndex+\"' /><div class='clear height5'></div></div>\");
						
		fIndex += 1;
		
	}
	
	function removeNoticeFileUpload(id) {
				
		$(\"#rpNoticeFileUploadDiv_\"+id).fadeOut(300);	
		$(\"#rpNoticeFileUpload_\"+id).val(\"\");
		$(\"#rpNoticeFileUpload_\"+id).remove();
		
	}
	
	</script>
	
	<div id=\"productsContainer\"></div>
	
	<div class=\"clear height5\"></div>
	
	<a href=\"javascript:addProduct();\" class=\"formButton left\">Lisää uusi sisältö</a>
	
	<div class=\"clear height15\"></div>
	
	<h2>Ilmoituksen liitetiedostot</h2>
		
	<div id=\"noticeFileUploadContainer\"></div>

	<div class=\"clear height5\"></div>
	
	<a href=\"javascript:addNoticeFile();\" class=\"formButton left\">Lisää uusi tiedosto</a><div style=\"margin-top: 5px;\"> Sallitut tiedostomuodot: ".rpPrintFileTypes("[rp(type)], ", 2, false)."<br />(maks. ".intval($rpSettings->getValue("maximumFileSize")/1000000)." megatavua)</div>
	
	<div class=\"clear height15\"></div>
	
	<h2>Sallitut yhteydenottotavat</h2>";
	
	rpPrintTypes("<div class=\"col_33 left mb5\"><input class=\"css-checkbox\" name=\"rpNoticeContactVia_[rp(type)]\" id=\"rpNoticeContactVia_[rp(type)]\" type=\"checkbox\" CHECKED /><label class=\"css-label\" for=\"rpNoticeContactVia_[rp(type)]\">[rp(title)]</label></div>", "contactTypes", rpPrintTypes("[[rp(type)]]", "contactTypes", "", false));
	
	echo "<div class=\"clear height5\"></div>
	
	<a href=\"javascript:saveNotice();\" class=\"formButton left\">Tallenna ilmoitus</a>
	
	<a href=\"javascript:changeTab('notices');\" class=\"formButton left\">Palaa takaisin</a>
	
	</form>";
	
}

include_once("engine/rp.end.php"); ?>