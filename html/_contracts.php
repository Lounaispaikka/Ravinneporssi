<?php include_once("engine/rp.start.php");


if ($_GET["id"]=="editcontract" && $_SESSION["clientID"]) {
	
	if (rpIsAlive($_GET["contractid"], "contracts")) {
	
		if (rpGetContract($_GET["contractid"], "editable")==1 || rpGetContract($_GET["contractid"], "added_clientid")==$_SESSION["clientID"]) {
		
			echo "<form name=\"editContractForm\" id=\"editContractForm\" action=\"http://".$rpSettings->getValue("domain")."/map\" method=\"POST\">
			
			<input type=\"hidden\" name=\"rpCheck\" value=\"".md5("CONTRACT".$rpSettings->getValue("secret").$_SESSION["clientID"])."\" />
			<input type=\"hidden\" name=\"rpAction\" value=\"rpEditContract\" />
			<input type=\"hidden\" name=\"rpContractID\" value=\"".intval($_GET["contractid"])."\" />
			
			<h2>Sopimuksen muut osapuolet<input onmouseover=\"showInfo('Kirjoita käyttäjän nimi');\" onmouseout=\"hideInfo();\" type=\"text\" id=\"searchText\" name=\"searchText\" value=\"Lisää osapuoli\" onkeypress=\"triggerPartyList();\" onfocus=\"$('#searchText').val('');\" /><div id=\"rpClientSearchResultDiv\" name=\"rpClientSearchResultDiv\"></div></h2>
			
			<div id=\"rpParticipantsDiv\" name=\"rpParticipantsDiv\">";
			
			rpGetContractParticipants("<div id='rpToClientDiv_[rp(id)]' name='rpToClientDiv_[rp(id)]'><input class='rpToClientInput' type='hidden' id='rpToClientID_[rp(id)]' name='rpToClientID_[rp(id)]' value='[rp(id)]' /><h3>Osapuoli: [rp(name)] (<a href='javascript:removeContractParticipant([rp(id)]);'>poista</a>)</h3></div>", $_GET["contractid"]);
			
			echo "</div>
			
			<hr />
			
			<script>
			
			var partyList = new Array();
			var partyListTrigger = false;
			
			function addParticipant(id) {
				
				if ($(\"#rpToClientID_\"+id).val()!=id) {
				
					$(\"#rpParticipantsDiv\").append(\"<div id='rpToClientDiv_\"+id+\"' name='rpToClientDiv_\"+id+\"'><input class='rpToClientInput' type='hidden' id='rpToClientID_\"+id+\"' name='rpToClientID_\"+id+\"' value='\"+id+\"' /><h3>Osapuoli: \"+$(\"#rpClientName_\"+id).val()+\" (<a href='javascript:removeContractParticipant(\"+id+\");'>poista</a>)</h3></div>\");
				
				}
				
			}
			
			function triggerPartyList() {
						
				window.setTimeout(function() {populatePartyList();}, 100);	
				
			}
			
			function populatePartyList() {
				
				if (partyList.length<1 && !partyListTrigger) {
					
					partyListTrigger = true;
					
					$.ajax({
						type:\"POST\",
						data: {
							rpAction:\"rpGetClients\"
						},
					    url:\"_search.php\",
					    success: function(data) {
						
							if (data!=\"\") {
										
								var clients = data.split(\"|end|\");
													
								for (var i = 0; i < clients.length; i++) {
							
									var client = clients[i].split(\"|\");
							
									if (client[0]>0) {
							
										partyList.push(new Array(client[0],client[1]));
							
									}
							
								}
								
								populatePartyList();
																
							}
						
						}
					
					});
					
				}
				
				$(\"#rpClientSearchResultDiv\").html(\"\");
				
				var result_runner = 0;
				
				if ($(\"#searchText\").val()!=\"\") {
					
					for (var i = 0; i < partyList.length; i++) {
		
						var client = partyList[i];
									
						var searchReg = new RegExp($(\"#searchText\").val(),\"gi\");
									
						if (client[1].search(searchReg)>-1 && $(\"#rpToClientID_\"+client[0]).val()!=client[0]) {
									
							if (result_runner<3) {
								$(\"#rpClientSearchResultDiv\").append(\"<input type='hidden' id='rpClientName_\"+client[0]+\"' name='rpClientName_\"+client[0]+\"' value='\"+client[1]+\"'>&nbsp;&nbsp;&nbsp;<a href='javascript:addParticipant(\"+client[0]+\");' onmouseover='showInfo();' onmouseout='hideInfo();'>\"+client[1]+\"</a>\");
							}
					
							result_runner += 1;
					
						}
					
					}
					
				}
				
			}
			
			function removeContractParticipant(id) {
		
				$(\"#rpToClientID_\"+id).val(0);
				$(\"#rpToClientDiv_\"+id).fadeOut(300);
		
			}
			
			</script>
			
			<h2>Lannan luovuttaja</h2>
			
			<div class=\"col_50 left\">
				
				<label for=\"rpContractOutputName\">Nimi:<input value=\"".rpGetContract($_GET["contractid"], "output_name")."\" name=\"rpContractOutputName\" id=\"rpContractOutputName\" type=\"text\" /></label>	
			
			</div>
			
			<div class=\"col_50 left\">
		
				<label for=\"rpContractOutputAddress\">Osoite:<input value=\"".rpGetContract($_GET["contractid"], "output_address")."\" name=\"rpContractOutputAddress\" id=\"rpContractOutputAddress\" class=\"full\" type=\"text\" /></label>	
		
			</div>
			
			<div class=\"clear\"></div>
			
			<div class=\"col_50 left\">
				
				<label for=\"rpContractOutputPhonenumber\">Puhelinnumero:<input value=\"".rpGetContract($_GET["contractid"], "output_phonenumber")."\" name=\"rpContractOutputPhonenumber\" id=\"rpContractOutputPhonenumber\" type=\"text\" /></label>	
			
			</div>
			
			<div class=\"col_50 left\">
		
				<label for=\"rpContractOutputEmail\">Sähköpostiosoite:<input value=\"".rpGetContract($_GET["contractid"], "output_email")."\" name=\"rpContractOutputEmail\" id=\"rpContractOutputEmail\" class=\"full\" type=\"text\" /></label>	
		
			</div>
			
			<div class=\"clear\"></div>
			
			<h2>Tilan tiedot</h2>
			
			<div class=\"col_50 left\">
				
				<label for=\"rpContractOutputCompanyCode\">Tilatunnus:<input value=\"".rpGetContract($_GET["contractid"], "output_companycode")."\" name=\"rpContractOutputCompanyCode\" id=\"rpContractOutputCompanyCode\" type=\"text\" /></label>	
			
			</div>
			
			<div class=\"col_50 left\">
		
				<label for=\"rpContractOutputAnimalAmount\">Eläinyksikkömäärä (ey):<input value=\"".rpGetContract($_GET["contractid"], "output_animal_amount")."\" name=\"rpContractOutputAnimalAmount\" id=\"rpContractOutputAnimalAmount\" class=\"full\" type=\"text\" /></label>	
		
			</div>
			
			<div class=\"clear\"></div>
			
			<div class=\"col_50 left\">
				
				<label for=\"rpContractOutputFieldArea\">Hallinnassa oleva peltoala (ha):<input value=\"".rpGetContract($_GET["contractid"], "output_field_area")."\" name=\"rpContractOutputFieldArea\" id=\"rpContractOutputFieldArea\" type=\"text\" /></label>	
			
			</div>
			
			<div class=\"col_50 left\">
		
				<label for=\"rpContractOutputOtherContracts\">Muut lannanluovutussopimukset (ha):<input value=\"".rpGetContract($_GET["contractid"], "output_other_contracts")."\" name=\"rpContractOutputOtherContracts\" id=\"rpContractOutputOtherContracts\" class=\"full\" type=\"text\" /></label>	
		
			</div>
			
			<div class=\"clear\"></div>
			
			<div class=\"col_50 left\">
				
				<label for=\"rpContractOutputTotalAnimalAmount\">Eläinyksikkömäärä yhteensä (ey/ha):<input value=\"".rpGetContract($_GET["contractid"], "output_total_animal_amount")."\" name=\"rpContractOutputTotalAnimalAmount\" id=\"rpContractOutputTotalAnimalAmount\" type=\"text\" /></label>	
			
			</div>
				
			<div class=\"clear\"></div>
			
			<h2>Eläinlajit</h2>
			
			<script>
			
			var aIndex = 100;
			
			function addAnimal() {
				
				$(\"#animalContainer\").append(\"<div id='animal_\"+aIndex+\"' class='animalDiv'><a href='javascript:removeAnimal(\"+aIndex+\");' class='smallFormButton right'>Poista</a><label for='rpAnimalTitle_\"+aIndex+\"'>Eläinlajin nimi: *<br /><input name='rpAnimalTitle_\"+aIndex+\"' id='rpAnimalTitle_\"+aIndex+\"' type='text' /></label></div>\");
				
				aIndex += 1;
				
			}
			
			function removeAnimal(id) {
				
				$(\"#animal_\"+id).remove();
				
			}
			
			</script>
				
			<div id=\"animalContainer\">";
			
				rpGetContractAnimals("<div id='animal_[rp(id)]' class='animalDiv'><a href='javascript:removeAnimal([rp(id)]);' class='smallFormButton right'>Poista</a><label for='rpAnimalTitle_[rp(id)]'>Eläinlajin nimi: *<br /><input value=\"[rp(title)]\" name='rpAnimalTitle_[rp(id)]' id='rpAnimalTitle_[rp(id)]' type='text' /></label></div>", $_GET["contractid"]);
			
			echo "</div>
		
			<div class=\"clear height5\"></div>
			
			<a href=\"javascript:addAnimal();\" class=\"formButton left\">Lisää uusi eläinlaji</a>
			
			<div class=\"clear height15\"></div>
			
			<h2>Lannan vastaanottaja</h2>
			
			<div class=\"col_50 left\">
				
				<label for=\"rpContractInputName\">Nimi:<input value=\"".rpGetContract($_GET["contractid"], "input_name")."\" name=\"rpContractInputName\" id=\"rpContractInputName\" type=\"text\" /></label>	
			
			</div>
			
			<div class=\"col_50 left\">
		
				<label for=\"rpContractInputAddress\">Osoite:<input value=\"".rpGetContract($_GET["contractid"], "input_address")."\" name=\"rpContractInputAddress\" id=\"rpContractInputAddress\" class=\"full\" type=\"text\" /></label>	
		
			</div>
			
			<div class=\"clear\"></div>
			
			<div class=\"col_50 left\">
				
				<label for=\"rpContractInputBIC\">Tila-/Y-tunnus:<input value=\"".rpGetContract($_GET["contractid"], "input_bic")."\" name=\"rpContractInputBIC\" id=\"rpContractInputBIC\" type=\"text\" /></label>	
			
			</div>
			
			<div class=\"col_50 left\">
		
				<label for=\"rpContractInputPhonenumber\">Puhelinnumero:<input value=\"".rpGetContract($_GET["contractid"], "input_phonenumber")."\" name=\"rpContractInputPhonenumber\" id=\"rpContractInputPhonenumber\" class=\"full\" type=\"text\" /></label>	
		
			</div>
			
			<div class=\"clear\"></div>
			
			<div class=\"col_50 left\">
		
				<label for=\"rpContractInputEmail\">Sähköpostiosoite:<input value=\"".rpGetContract($_GET["contractid"], "input_email")."\" name=\"rpContractInputEmail\" id=\"rpContractInputEmail\" type=\"text\" /></label>	
		
			</div>
			
			<div class=\"col_50 left\">
		
				<label for=\"rpContractInputDungArea\">Lannan levitysala vastaanottajalle (ha):<input value=\"".rpGetContract($_GET["contractid"], "input_dung_area")."\" name=\"rpContractInputDungArea\" id=\"rpContractInputDungArea\" class=\"full\" type=\"text\" /></label>	
		
			</div>
			
			<div class=\"clear\"></div>
				
			<div class=\"col_50 left\">
		
				<label for=\"rpContractInputRefinement\">Lanta vastaanotetaan jatkojalostettavaksi:<select id=\"rpContractInputRefinement\" name=\"rpContractInputRefinement\"><option value=\"1\" "; if (rpGetContract($_GET["contractid"], "input_refinement")==1) {echo "SELECTED";} echo ">Kyllä</option><option value=\"0\" "; if (rpGetContract($_GET["contractid"], "input_refinement")==0) {echo "SELECTED";} echo ">Ei</option></select></label>	
		
			</div>
			
			<div class=\"col_50 left\">
		
				<label for=\"rpContractInputContractTime\">Sopimusaika:<input value=\"".rpGetContract($_GET["contractid"], "input_contract_time")."\" name=\"rpContractInputContractTime\" id=\"rpContractInputContractTime\" class=\"full\" type=\"text\" /></label>	
		
			</div>
			
			<div class=\"clear\"></div>
			
			<h2>Luovutettavat lantalajit ja määrät</h2>
			
			<script>
			
			var dIndex = 100;
			
			function addContractProduct() {
				
				$(\"#contractProductContainer\").append(\"<div id='product_\"+dIndex+\"' class='contractProductDiv'><select class='tradeSelector' name='rpContractProduct_\"+dIndex+\"' id='rpContractProduct_\"+dIndex+\"'><option value=''>Valitse lantalaji...</option>";
				
					rpPrintTypes("<option value='[rp(type)]'>[rp(title)]</option>", "dungTypes");
				
				echo "</select><a href='javascript:removeContractProduct(\"+dIndex+\");' class='smallFormButton right'>Poista</a><div class='col_50 left'><label for='rpContractProductAmount_\"+dIndex+\"'>Lannan määrä (m<sup>3</sup> / vuosi):<input name='rpContractProductAmount_\"+dIndex+\"' id='rpContractProductAmount_\"+dIndex+\"' type='text' style='width: 95%;' /></label></div><div class='col_50 left'><label for='rpContractProductTransportationDistance_\"+dIndex+\"'>Lannan kuljetusmatka (km):<input name='rpContractProductTransportationDistance_\"+dIndex+\"' id='rpContractProductTransportationDistance_\"+dIndex+\"' type='text' /></label></div><div class='clear'></div></div>\");
				
				dIndex += 1;
				
			}
			
			function removeContractProduct(id) {
				
				$(\"#product_\"+id).remove();
				
			}
			
			</script>
				
			<div id=\"contractProductContainer\">";
			
				rpGetContractProducts("<div id='product_[rp(id)]' class='contractProductDiv'><select class='tradeSelector' name='rpContractProduct_[rp(id)]' id='rpContractProduct_[rp(id)]'><option value=''>Valitse lantalaji...</option>[rp(options)]</select><a href='javascript:removeContractProduct([rp(id)]);' class='smallFormButton right'>Poista</a><div class='col_50 left'><label for='rpContractProductAmount_[rp(id)]'>Lannan määrä (m<sup>3</sup> / vuosi):<input name='rpContractProductAmount_[rp(id)]' id='rpContractProductAmount_[rp(id)]' value=\"[rp(amount)]\" type='text' style='width: 95%;' /></label></div><div class='col_50 left'><label for='rpContractProductTransportationDistance_[rp(id)]'>Lannan kuljetusmatka (km):<input name='rpContractProductTransportationDistance_[rp(id)]' id='rpContractProductTransportationDistance_[rp(id)]' value=\"[rp(distance)]\" type='text' /></label></div><div class='clear'></div></div>", $_GET["contractid"]);
			
			echo "</div>
		
			<div class=\"clear height5\"></div>
			
			<a href=\"javascript:addContractProduct();\" class=\"formButton left\">Lisää uusi luovutettava lantalaji</a>
			
			<div class=\"clear height15\"></div>	
			
			<div class=\"col_50 left\">
		
				<label for=\"rpContractTransporter\">Lannan kuljettaa:<input value=\"".rpGetContract($_GET["contractid"], "transporter")."\" name=\"rpContractTransporter\" id=\"rpContractTransporter\" type=\"text\" /></label>	
		
			</div>
			
			<div class=\"col_50 left\">
		
				<label for=\"rpContractDistributor\">Lannan levittää:<input value=\"".rpGetContract($_GET["contractid"], "distributor")."\" name=\"rpContractDistributor\" id=\"rpContractDistributor\" class=\"full\" type=\"text\" /></label>	
		
			</div>
			
			<div class=\"clear\"></div>
			
			<div class=\"col_50 left\">
		
				<label for=\"rpContractTransportationPayer\">Kuljetuskulut maksaa:<input value=\"".rpGetContract($_GET["contractid"], "transportation_payer")."\" name=\"rpContractTransportationPayer\" id=\"rpContractTransportationPayer\" type=\"text\" /></label>	
		
			</div>
			
			<div class=\"col_50 left\">
		
				<label for=\"rpContractDistributionPayer\">Levityskulut maksaa:<input value=\"".rpGetContract($_GET["contractid"], "distribution_payer")."\" name=\"rpContractDistributionPayer\" id=\"rpContractDistributionPayer\" class=\"full\" type=\"text\" /></label>	
		
			</div>
			
			<div class=\"clear\"></div>
			
			
			<label for=\"rpContractRemarks\">Muut sopimusehdot:<textarea name=\"rpContractRemarks\" id=\"rpContractRemarks\" class=\"bigText\">".rpGetContract($_GET["contractid"], "remarks")."</textarea></label>
			
			<div class=\"col_50 left\">
		
				<label for=\"rpContractPlaceAndTime\">Paikka ja aika:<input value=\"".rpGetContract($_GET["contractid"], "place_and_time")."\" name=\"rpContractPlaceAndTime\" id=\"rpContractPlaceAndTime\" type=\"text\" /></label>	
		
			</div>";
			
			if ($_SESSION["clientID"]==rpGetContract($_GET["contractid"], "added_clientid")) {
			
				echo "<div class=\"col_50 left\">
			
					<label for=\"rpContractEditable\">Sopimuksen muokkausoikeudet:<select class=\"full\" id=\"rpContractEditable\" name=\"rpContractEditable\"><option value=\"0\" "; if (rpGetContract($_GET["contractid"], "editable")==0) {echo "SELECTED";} echo ">Vain minulla</option><option value=\"1\" "; if (rpGetContract($_GET["contractid"], "editable")==1) {echo "SELECTED";} echo ">Kaikilla sopimuksen osapuolilla</option></select></label>	
			
				</div>";
			
			}
			
			echo "<div class=\"clear\"></div>
			
			<div class=\"clear height5\"></div>
			
			<a href=\"javascript:saveContractSettings();\" class=\"formButton left\">Tallenna sopimus</a>
				
			<a href=\"javascript:changeTab('contracts');\" class=\"formButton left\">Palaa takaisin</a>
			
			</form>";
		
			if (rpGetContract($_GET["contractid"], "modified_clientid")>0) {
		
				echo "<div class=\"clear height15\"></div>
				Sopimusta viimeksi muokattu: ".rpFullTime(rpGetContract($_GET["contractid"], "modified_datetime"))." (".rpGetOtherClient(rpGetContract($_GET["contractid"], "modified_clientid"),"name").")";
		
			}
		
		} else {echo "Sinulla ei ole oikeutta muokata tätä sopimusta.";}
	
	} else {echo "Sopimusta ei löytynyt.";}
	
}



if ($_GET["id"]=="showcontract" && $_SESSION["clientID"]) {
	
	if (strstr(rpGetContract($_GET["contractid"],"to_clientid"),"[".$_SESSION["clientID"]."]") || rpGetContract($_GET["contractid"],"added_clientid")==$_SESSION["clientID"]) {
		
		if (rpGetContract($_GET["contractid"], "to_clientid") != "") {
		
			echo "<h2>Sopimuksen osapuolet</h2>";
			
			echo rpGetOtherClient(rpGetContract($_GET["contractid"],"added_clientid"),"name")."<br />";
			
			rpGetContractParticipants("[rp(name)]<br />", $_GET["contractid"]);
			
			echo "<div class=\"clear height10\"></div>";
		
		}
		
		if (rpGetContract($_GET["contractid"], "to_noticeid") != "") {
		
			echo "<h2>Sopimusta koskevat ilmoitukset</h2>";
			
			rpGetContractNotices("", $_GET["contractid"]);
			
			echo "<div class=\"clear height5\"></div>";
		
		}
		
		echo "<h2>Lannan luovuttaja</h2>
		
		<div class=\"noticeInformationDiv\">
			
			<div class=\"col_50 left\">
			
				<h3>Luovuttajan nimi:</h3>".rpGetContract($_GET["contractid"], "output_name");
			
				if (rpGetContract($_GET["contractid"], "output_name")=="") {echo "-";} echo "
			
			</div>
			
			<div class=\"col_50 left\">
			
				<h3>Luovuttajan osoite:</h3>".rpGetContract($_GET["contractid"], "output_address");
			
				if (rpGetContract($_GET["contractid"], "output_address")=="") {echo "-";} echo "
			
			</div>
			
			<div class=\"clear height5\"></div>
			
			<div class=\"col_50 left\">
			
				<h3>Luovuttajan puhelinnumero:</h3>".rpGetContract($_GET["contractid"], "output_phonenumber");
			
				if (rpGetContract($_GET["contractid"], "output_phonenumber")=="") {echo "-";} echo "
			
			</div>
			
			<div class=\"col_50 left\">
			
				<h3>Luovuttajan sähköpostiosoite:</h3>".rpGetContract($_GET["contractid"], "output_email");
			
				if (rpGetContract($_GET["contractid"], "output_email")=="") {echo "-";} echo "
			
			</div>
			
			<div class=\"clear height5\"></div>
			
		</div>
		
		<div class=\"clear height5\"></div>
		
		<h2>Tilan tiedot</h2>
		
		<div class=\"noticeInformationDiv\">
			
			<div class=\"col_50 left\">
			
				<h3>Tilatunnus:</h3>".rpGetContract($_GET["contractid"], "output_companycode");
			
				if (rpGetContract($_GET["contractid"], "output_companycode")=="") {echo "-";} echo "
			
			</div>
			
			<div class=\"col_50 left\">
			
				<h3>Eläinyksikkömäärä (ey):</h3>".rpGetContract($_GET["contractid"], "output_animal_amount");
			
				if (rpGetContract($_GET["contractid"], "output_animal_amount")=="") {echo "-";} echo "
			
			</div>
			
			<div class=\"clear height5\"></div>
			
			<div class=\"col_50 left\">
			
				<h3>Hallinnassa oleva peltoala (ha):</h3>".rpGetContract($_GET["contractid"], "output_field_area");
			
				if (rpGetContract($_GET["contractid"], "output_field_area")=="") {echo "-";} echo "
			
			</div>
			
			<div class=\"col_50 left\">
			
				<h3>Muut lannanluovutussopimukset (ha):</h3>".rpGetContract($_GET["contractid"], "output_other_contracts");
			
				if (rpGetContract($_GET["contractid"], "output_other_contracts")=="") {echo "-";} echo "
			
			</div>
			
			<div class=\"clear height5\"></div>
			
			<div class=\"col_50 left\">
			
				<h3>Eläinyksikkömäärä yhteensä (ey/ha):</h3>".rpGetContract($_GET["contractid"], "output_total_animal_amount");
			
				if (rpGetContract($_GET["contractid"], "output_total_animal_amount")=="") {echo "-";} echo "
			
			</div>
			
			<div class=\"clear height5\"></div>
			
		</div>
		
		<div class=\"clear height5\"></div>";
		
		if (rpGetContract($_GET["contractid"], "output_animals") != "") {
		
			echo "<h2>Eläinlajit</h2>";
			
			rpGetContractAnimals("&bull; [rp(title)]<br />", $_GET["contractid"]);
			
			echo "<div class=\"clear height10\"></div>";
		
		}
		
		echo "<h2>Lannan vastaanottaja</h2>
		
		<div class=\"noticeInformationDiv\">
			
			<div class=\"col_50 left\">
			
				<h3>Nimi:</h3>".rpGetContract($_GET["contractid"], "input_name");
			
				if (rpGetContract($_GET["contractid"], "input_name")=="") {echo "-";} echo "
			
			</div>
			
			<div class=\"col_50 left\">
			
				<h3>Osoite:</h3>".rpGetContract($_GET["contractid"], "input_address");
			
				if (rpGetContract($_GET["contractid"], "input_address")=="") {echo "-";} echo "
			
			</div>
			
			<div class=\"clear height5\"></div>
			
			<div class=\"col_50 left\">
			
				<h3>Tila-/Y-tunnus:</h3>".rpGetContract($_GET["contractid"], "input_bic");
			
				if (rpGetContract($_GET["contractid"], "input_bic")=="") {echo "-";} echo "
			
			</div>
			
			<div class=\"col_50 left\">
			
				<h3>Puhelinnumero:</h3>".rpGetContract($_GET["contractid"], "input_phonenumber");
			
				if (rpGetContract($_GET["contractid"], "input_phonenumber")=="") {echo "-";} echo "
			
			</div>
			
			<div class=\"clear height5\"></div>
			
			<div class=\"col_50 left\">
			
				<h3>Sähköpostiosoite:</h3>".rpGetContract($_GET["contractid"], "input_email");
			
				if (rpGetContract($_GET["contractid"], "input_email")=="") {echo "-";} echo "
			
			</div>
			
			<div class=\"clear height5\"></div>
			
		</div>";
		
		if (rpGetContract($_GET["contractid"], "input_products")!="") {
		
			echo "<div class=\"clear height5\"></div>
			
			<h2>Luovutettavat lantalajit ja määrät</h2>";
			
			rpGetContractProducts("<div class=\"noticeInformationDiv\">
			
				<h3 style='font-size: 17px; color: #FFFFFF;'>[rp(title)]</h3>
			
				<div class=\"col_50 left\"><h3>Lannan määrä (m<sup>3</sup> / vuosi):</h3>[rp(amount)]</div>
							
				<div class=\"col_50 left\"><h3>Lannan kuljetusmatka (km):</h3>[rp(distance)]</div>
				
				<div class=\"clear height5\"></div>
				
				</div>", $_GET["contractid"]);
		
		}
		
		echo "<div class=\"clear height5\"></div>
		
		<h2>Sopimuksen tiedot</h2>
		
		<div class=\"noticeInformationDiv\">
		
			<div class=\"col_50 left\">
			
				<h3>Lannan levitysala vastaanottajalle (ha):</h3>".rpGetContract($_GET["contractid"], "input_dung_area");
			
				if (rpGetContract($_GET["contractid"], "input_dung_area")=="") {echo "-";} echo "
			
			</div>
						
			<div class=\"col_50 left\">
			
				<h3>Lanta vastaanotetaan jatkojalostettavaksi:</h3>".rpBooleanConvert(rpGetContract($_GET["contractid"], "input_refinement"))."
						
			</div>
			
			<div class=\"clear height5\"></div>
			
			<div class=\"col_50 left\">
			
				<h3>Sopimusaika:</h3>".rpGetContract($_GET["contractid"], "input_contract_time");
			
				if (rpGetContract($_GET["contractid"], "input_contract_time")=="") {echo "-";} echo "
			
			</div>
						
			<div class=\"col_50 left\">
			
				<h3>Lannan kuljettaa:</h3>".rpGetContract($_GET["contractid"], "transporter");
			
				if (rpGetContract($_GET["contractid"], "transporter")=="") {echo "-";} echo "
			
			</div>
			
			<div class=\"clear height5\"></div>
			
			<div class=\"col_50 left\">
			
				<h3>Lannan levittää:</h3>".rpGetContract($_GET["contractid"], "distributor");
			
				if (rpGetContract($_GET["contractid"], "distributor")=="") {echo "-";} echo "
			
			</div>
						
			<div class=\"col_50 left\">
			
				<h3>Kuljetuskulut maksaa:</h3>".rpGetContract($_GET["contractid"], "transportation_payer");
			
				if (rpGetContract($_GET["contractid"], "transportation_payer")=="") {echo "-";} echo "
			
			</div>
			
			<div class=\"clear height5\"></div>
			
			<div class=\"col_50 left\">
			
				<h3>Levityskulut maksaa:</h3>".rpGetContract($_GET["contractid"], "distribution_payer");
			
				if (rpGetContract($_GET["contractid"], "distribution_payer")=="") {echo "-";} echo "
			
			</div>
			
			<div class=\"clear height5\"></div>
			
		</div>
		
		<div class=\"clear height5\"></div>
		
		<h2>Muut sopimusehdot</h2>".rpGetContract($_GET["contractid"], "remarks");
			
		if (rpGetContract($_GET["contractid"], "remarks")=="") {echo "-";} echo "
		
		<div class=\"clear height5\"></div>
		
		<a href=\"_print.php?contractid=".intval($_GET["contractid"])."\" class=\"formButton left\" target=\"_blank\">Tulosta sopimus</a>
		
		<a href=\"javascript:changeTab('contracts');\" class=\"formButton left\">Palaa takaisin</a>";
		
	} else {echo "Sopimusta ei löytynyt.";}
	
}

if ($_GET["id"]=="addcontract" && $_SESSION["clientID"]) {
	
	echo "<form name=\"addContractForm\" id=\"addContractForm\" action=\"http://".$rpSettings->getValue("domain")."/map\" method=\"POST\">
	
	<input type=\"hidden\" name=\"rpCheck\" value=\"".md5("CONTRACT".$rpSettings->getValue("secret").$_SESSION["clientID"])."\" />
	<input type=\"hidden\" name=\"rpAction\" value=\"rpAddContract\" />
	
	<h2>Sopimuksen muut osapuolet<input onmouseover=\"showInfo('Kirjoita käyttäjän nimi');\" onmouseout=\"hideInfo();\" type=\"text\" id=\"searchText\" name=\"searchText\" value=\"Lisää osapuoli\" onkeypress=\"triggerPartyList();\" onfocus=\"$('#searchText').val('');\" /><div id=\"rpClientSearchResultDiv\" name=\"rpClientSearchResultDiv\"></div></h2>
	
	<div id=\"rpParticipantsDiv\" name=\"rpParticipantsDiv\"></div>
	
	<hr />
	
	<script>
	
	var partyList = new Array();
	var partyListTrigger = false;
	
	function addParticipant(id) {
		
		if ($(\"#rpToClientID_\"+id).val()!=id) {
		
			$(\"#rpParticipantsDiv\").append(\"<div id='rpToClientDiv_\"+id+\"' name='rpToClientDiv_\"+id+\"'><input type='hidden' id='rpToClientName_\"+id+\"' name='rpToClientName_\"+id+\"' value='\"+$(\"#rpClientName_\"+id).val()+\"' /><input class='rpToClientInput' type='hidden' id='rpToClientID_\"+id+\"' name='rpToClientID_\"+id+\"' value='\"+id+\"' /><h3>Osapuoli: \"+$(\"#rpClientName_\"+id).val()+\" (<a href='javascript:removeContractParticipant(\"+id+\");'>poista</a>)</h3></div>\");
		
			updatePreDefinedSelectors();
		
		}
		
	}
	
	function triggerPartyList() {
				
		window.setTimeout(function() {populatePartyList();}, 100);	
		
	}
	
	function populatePartyList() {
		
		if (partyList.length<1 && !partyListTrigger) {
			
			partyListTrigger = true;
			
			$.ajax({
				type:\"POST\",
				data: {
					rpAction:\"rpGetClients\"
				},
			    url:\"_search.php\",
			    success: function(data) {
				
					if (data!=\"\") {
								
						var clients = data.split(\"|end|\");
											
						for (var i = 0; i < clients.length; i++) {
					
							var client = clients[i].split(\"|\");
					
							if (client[0]>0) {
					
								partyList.push(new Array(client[0],client[1]));
					
							}
					
						}
						
						populatePartyList();
														
					}
				
				}
			
			});
			
		}
		
		$(\"#rpClientSearchResultDiv\").html(\"\");
		
		var result_runner = 0;
		
		if ($(\"#searchText\").val()!=\"\") {
			
			for (var i = 0; i < partyList.length; i++) {

				var client = partyList[i];
							
				var searchReg = new RegExp($(\"#searchText\").val(),\"gi\");
							
				if (client[1].search(searchReg)>-1 && $(\"#rpToClientID_\"+client[0]).val()!=client[0]) {
							
					if (result_runner<3) {
						$(\"#rpClientSearchResultDiv\").append(\"<input type='hidden' id='rpClientName_\"+client[0]+\"' name='rpClientName_\"+client[0]+\"' value='\"+client[1]+\"'>&nbsp;&nbsp;&nbsp;<a href='javascript:addParticipant(\"+client[0]+\");' onmouseover='showInfo();' onmouseout='hideInfo();'>\"+client[1]+\"</a>\");
					}
			
					result_runner += 1;
			
				}
			
			}
			
		}
				
	}
	
	function pastePreDefined(type, id) {

		if (type==\"output\") {
		
			if (id>0) {

				$.get(\"_client.php?rpAction=rpGetClient&type=name&id=\"+id, function(result) {
					$(\"#rpContractOutputName\").val(result);
				});
				
				$.get(\"_client.php?rpAction=rpGetClient&type=address_1&id=\"+id, function(result) {
					$(\"#rpContractOutputAddress\").val(result);
				});
				
				$.get(\"_client.php?rpAction=rpGetClient&type=phonenumber&id=\"+id, function(result) {
					$(\"#rpContractOutputPhonenumber\").val(result);
				});
				
				$.get(\"_client.php?rpAction=rpGetClient&type=email&id=\"+id, function(result) {
					$(\"#rpContractOutputEmail\").val(result);
				});
				
				$.get(\"_client.php?rpAction=rpGetClient&type=bic&id=\"+id, function(result) {
					$(\"#rpContractOutputCompanyCode\").val(result);
				});
				
			} else if (id==0) {
			
				$(\"#rpContractOutputName\").val(\"\");
				$(\"#rpContractOutputAddress\").val(\"\");
				$(\"#rpContractOutputPhonenumber\").val(\"\");
				$(\"#rpContractOutputEmail\").val(\"\");
				$(\"#rpContractOutputCompanyCode\").val(\"\");
				
			}
			
		} else if (type==\"input\") {
			
			if (id>0) {

				$.get(\"_client.php?rpAction=rpGetClient&type=name&id=\"+id, function(result) {
					$(\"#rpContractInputName\").val(result);
				});
				
				$.get(\"_client.php?rpAction=rpGetClient&type=address_1&id=\"+id, function(result) {
					$(\"#rpContractInputAddress\").val(result);
				});
				
				$.get(\"_client.php?rpAction=rpGetClient&type=phonenumber&id=\"+id, function(result) {
					$(\"#rpContractInputPhonenumber\").val(result);
				});
				
				$.get(\"_client.php?rpAction=rpGetClient&type=email&id=\"+id, function(result) {
					$(\"#rpContractInputEmail\").val(result);
				});
				
				$.get(\"_client.php?rpAction=rpGetClient&type=bic&id=\"+id, function(result) {
					$(\"#rpContractInputBIC\").val(result);
				});
				
			} else if (id==0) {
			
				$(\"#rpContractInputName\").val(\"\");
				$(\"#rpContractInputAddress\").val(\"\");
				$(\"#rpContractInputPhonenumber\").val(\"\");
				$(\"#rpContractInputEmail\").val(\"\");
				$(\"#rpContractInputBIC\").val(\"\");
				
			}
			
		}	
		
	}
	
	function removeContractParticipant(id) {

		$(\"#rpToClientID_\"+id).val(0);
		$(\"#rpToClientDiv_\"+id).fadeOut(300);
		$(\"#rpToClientID_\"+id).remove();

	}
	
	function updatePreDefinedSelectors() {

		var client_options = \"\";
		
		$(\".rpToClientInput\").each(function() {
			
			if ($(this).val()>0 && $(this).val()!=undefined) {
			
				client_options += \"<option value='\"+$(this).val()+\"'>\"+$(\"#rpToClientName_\"+$(this).val()).val()+\"</option>\";
			
			}
			
		});
		
		$(\".pasteSelector\").html(\"<option value=''>Liitä tiedot profiilista...</option><option value='".intval($_SESSION["clientID"])."'>Omat tiedot</option>\"+client_options+\"<option value='0'>Tyhjennä tiedot</option>\");	
		
	}
	
	$(document).ready(function() {
		
		updatePreDefinedSelectors();
		
	});
	
	</script>
	
	<h2>Lannan luovuttaja<select class=\"pasteSelector\" onchange=\"pastePreDefined('output',this.value);\" id=\"pasteSelector_output\" name=\"pasteSelector_output\"></select></h2>
	
	<div class=\"clear\"></div>
	
	<div class=\"col_50 left\">
		
		<label for=\"rpContractOutputName\">Nimi:<input name=\"rpContractOutputName\" id=\"rpContractOutputName\" type=\"text\" /></label>	
	
	</div>
	
	<div class=\"col_50 left\">

		<label for=\"rpContractOutputAddress\">Osoite:<input name=\"rpContractOutputAddress\" id=\"rpContractOutputAddress\" class=\"full\" type=\"text\" /></label>	

	</div>
	
	<div class=\"clear\"></div>
	
	<div class=\"col_50 left\">
		
		<label for=\"rpContractOutputPhonenumber\">Puhelinnumero:<input name=\"rpContractOutputPhonenumber\" id=\"rpContractOutputPhonenumber\" type=\"text\" /></label>	
	
	</div>
	
	<div class=\"col_50 left\">

		<label for=\"rpContractOutputEmail\">Sähköpostiosoite:<input name=\"rpContractOutputEmail\" id=\"rpContractOutputEmail\" class=\"full\" type=\"text\" /></label>	

	</div>
	
	<div class=\"clear\"></div>
	
	<h2>Tilan tiedot</h2>
	
	<div class=\"col_50 left\">
		
		<label for=\"rpContractOutputCompanyCode\">Tilatunnus:<input name=\"rpContractOutputCompanyCode\" id=\"rpContractOutputCompanyCode\" type=\"text\" /></label>	
	
	</div>
	
	<div class=\"col_50 left\">

		<label for=\"rpContractOutputAnimalAmount\">Eläinyksikkömäärä (ey):<input name=\"rpContractOutputAnimalAmount\" id=\"rpContractOutputAnimalAmount\" class=\"full\" type=\"text\" /></label>	

	</div>
	
	<div class=\"clear\"></div>
	
	<div class=\"col_50 left\">
		
		<label for=\"rpContractOutputFieldArea\">Hallinnassa oleva peltoala (ha):<input name=\"rpContractOutputFieldArea\" id=\"rpContractOutputFieldArea\" type=\"text\" /></label>	
	
	</div>
	
	<div class=\"col_50 left\">

		<label for=\"rpContractOutputOtherContracts\">Muut lannanluovutussopimukset (ha):<input name=\"rpContractOutputOtherContracts\" id=\"rpContractOutputOtherContracts\" class=\"full\" type=\"text\" /></label>	

	</div>
	
	<div class=\"clear\"></div>
	
	<div class=\"col_50 left\">
		
		<label for=\"rpContractOutputTotalAnimalAmount\">Eläinyksikkömäärä yhteensä (ey/ha):<input name=\"rpContractOutputTotalAnimalAmount\" id=\"rpContractOutputTotalAnimalAmount\" type=\"text\" /></label>	
	
	</div>
		
	<div class=\"clear\"></div>
	
	<h2>Eläinlajit</h2>
	
	<script>
	
	var aIndex = 1;
	
	function addAnimal() {
		
		$(\"#animalContainer\").append(\"<div id='animal_\"+aIndex+\"' class='animalDiv'><a href='javascript:removeAnimal(\"+aIndex+\");' class='smallFormButton right'>Poista</a><label for='rpAnimalTitle_\"+aIndex+\"'>Eläinlajin nimi: *<br /><input name='rpAnimalTitle_\"+aIndex+\"' id='rpAnimalTitle_\"+aIndex+\"' type='text' /></label></div>\");
		
		aIndex += 1;
		
	}
	
	function removeAnimal(id) {
		
		$(\"#animal_\"+id).remove();
		
	}
	
	</script>
		
	<div id=\"animalContainer\"></div>

	<div class=\"clear height5\"></div>
	
	<a href=\"javascript:addAnimal();\" class=\"formButton left\">Lisää uusi eläinlaji</a>
	
	<div class=\"clear height15\"></div>
	
	<h2>Lannan vastaanottaja<select class=\"pasteSelector\" onchange=\"pastePreDefined('input',this.value);\" id=\"pasteSelector_input\" name=\"pasteSelector_input\"></select></h2>
	
	<div class=\"clear\"></div>
	
	<div class=\"col_50 left\">
		
		<label for=\"rpContractInputName\">Nimi:<input name=\"rpContractInputName\" id=\"rpContractInputName\" type=\"text\" /></label>	
	
	</div>
	
	<div class=\"col_50 left\">

		<label for=\"rpContractInputAddress\">Osoite:<input name=\"rpContractInputAddress\" id=\"rpContractInputAddress\" class=\"full\" type=\"text\" /></label>	

	</div>
	
	<div class=\"clear\"></div>
	
	<div class=\"col_50 left\">
		
		<label for=\"rpContractInputBIC\">Tila-/Y-tunnus:<input name=\"rpContractInputBIC\" id=\"rpContractInputBIC\" type=\"text\" /></label>	
	
	</div>
	
	<div class=\"col_50 left\">

		<label for=\"rpContractInputPhonenumber\">Puhelinnumero:<input name=\"rpContractInputPhonenumber\" id=\"rpContractInputPhonenumber\" class=\"full\" type=\"text\" /></label>	

	</div>
	
	<div class=\"clear\"></div>
	
	<div class=\"col_50 left\">

		<label for=\"rpContractInputEmail\">Sähköpostiosoite:<input name=\"rpContractInputEmail\" id=\"rpContractInputEmail\" type=\"text\" /></label>	

	</div>
	
	<div class=\"col_50 left\">

		<label for=\"rpContractInputDungArea\">Lannan levitysala vastaanottajalle (ha):<input name=\"rpContractInputDungArea\" id=\"rpContractInputDungArea\" class=\"full\" type=\"text\" /></label>	

	</div>
	
	<div class=\"clear\"></div>
		
	<div class=\"col_50 left\">

		<label for=\"rpContractInputRefinement\">Lanta vastaanotetaan jatkojalostettavaksi:<select id=\"rpContractInputRefinement\" name=\"rpContractInputRefinement\"><option value=\"1\">Kyllä</option><option value=\"0\">Ei</option></select></label>	

	</div>
	
	<div class=\"col_50 left\">

		<label for=\"rpContractInputContractTime\">Sopimusaika:<input name=\"rpContractInputContractTime\" id=\"rpContractInputContractTime\" class=\"full\" type=\"text\" /></label>	

	</div>
	
	<div class=\"clear\"></div>
	
	<h2>Luovutettavat lantalajit ja määrät</h2>
	
	<script>
	
	var dIndex = 1;
	
	function addContractProduct() {
		
		$(\"#contractProductContainer\").append(\"<div id='product_\"+dIndex+\"' class='contractProductDiv'><select class='tradeSelector' name='rpContractProduct_\"+dIndex+\"' id='rpContractProduct_\"+dIndex+\"'><option value=''>Valitse lantalaji...</option>";
		
			rpPrintTypes("<option value='[rp(type)]'>[rp(title)]</option>", "dungTypes");
		
		echo "</select><a href='javascript:removeContractProduct(\"+dIndex+\");' class='smallFormButton right'>Poista</a><div class='col_50 left'><label for='rpContractProductAmount_\"+dIndex+\"'>Lannan määrä (m<sup>3</sup> / vuosi):<input name='rpContractProductAmount_\"+dIndex+\"' id='rpContractProductAmount_\"+dIndex+\"' type='text' style='width: 95%;' /></label></div><div class='col_50 left'><label for='rpContractProductTransportationDistance_\"+dIndex+\"'>Lannan kuljetusmatka (km):<input name='rpContractProductTransportationDistance_\"+dIndex+\"' id='rpContractProductTransportationDistance_\"+dIndex+\"' type='text' /></label></div><div class='clear'></div></div>\");
		
		dIndex += 1;
		
	}
	
	function removeContractProduct(id) {
		
		$(\"#product_\"+id).remove();
		
	}
	
	</script>
		
	<div id=\"contractProductContainer\"></div>

	<div class=\"clear height5\"></div>
	
	<a href=\"javascript:addContractProduct();\" class=\"formButton left\">Lisää uusi luovutettava lantalaji</a>
	
	<div class=\"clear height15\"></div>	
	
	<div class=\"col_50 left\">

		<label for=\"rpContractTransporter\">Lannan kuljettaa:<input name=\"rpContractTransporter\" id=\"rpContractTransporter\" type=\"text\" /></label>	

	</div>
	
	<div class=\"col_50 left\">

		<label for=\"rpContractDistributor\">Lannan levittää:<input name=\"rpContractDistributor\" id=\"rpContractDistributor\" class=\"full\" type=\"text\" /></label>	

	</div>
	
	<div class=\"clear\"></div>
	
	<div class=\"col_50 left\">

		<label for=\"rpContractTransportationPayer\">Kuljetuskulut maksaa:<input name=\"rpContractTransportationPayer\" id=\"rpContractTransportationPayer\" type=\"text\" /></label>	

	</div>
	
	<div class=\"col_50 left\">

		<label for=\"rpContractDistributionPayer\">Levityskulut maksaa:<input name=\"rpContractDistributionPayer\" id=\"rpContractDistributionPayer\" class=\"full\" type=\"text\" /></label>	

	</div>
	
	<div class=\"clear\"></div>
	
	
	<label for=\"rpContractRemarks\">Muut sopimusehdot:<textarea name=\"rpContractRemarks\" id=\"rpContractRemarks\" class=\"bigText\"></textarea></label>
	
	<div class=\"col_50 left\">

		<label for=\"rpContractPlaceAndTime\">Paikka ja aika:<input name=\"rpContractPlaceAndTime\" id=\"rpContractPlaceAndTime\" type=\"text\" /></label>	

	</div>
	
	<div class=\"col_50 left\">

		<label for=\"rpContractEditable\">Sopimuksen muokkausoikeudet:<select class=\"full\" id=\"rpContractEditable\" name=\"rpContractEditable\"><option value=\"0\">Vain minulla</option><option value=\"1\">Kaikilla sopimuksen osapuolilla</option></select></label>	

	</div>
	
	<div class=\"clear\"></div>
	
	<div class=\"clear height5\"></div>
	
	<a href=\"javascript:saveNewContract();\" class=\"formButton left\">Tallenna sopimus</a>
		
	<a href=\"javascript:changeTab('contracts');\" class=\"formButton left\">Palaa takaisin</a>
	
	</form>";
		
}

include_once("engine/rp.end.php"); ?>