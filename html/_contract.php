<?php include_once("engine/rp.start.php");

if ($_POST["rpAction"]=="rpEditContract" && $_SESSION["clientID"]) {

	if (rpIsAlive($_POST["rpContractID"], "contracts")) {

		if (rpGetContract($_POST["rpContractID"], "editable")==1 || rpGetContract($_POST["rpContractID"], "added_clientid")==$_SESSION["clientID"]) {

			// edit contract
		
			$animals = "";
			
			foreach ($_POST as $var => $value) {
				if (substr($var,0,14) == "rpAnimalTitle_") {
					if ($value!="") {
						$animals .= str_replace("|end|","",$value)."|end|";
					}
				}
			}
			
			$products = "";
			
			foreach ($_POST as $var => $value) {
				if (substr($var,0,18) == "rpContractProduct_") {
					if ($value!="") {
						
						$product_id = intval(str_replace("rpContractProduct_","",$var));
						
						$products .= str_replace("|","",$value)."|".str_replace("|","",$_POST["rpContractProductAmount_".$product_id])."|".str_replace("|","",$_POST["rpContractProductTransportationDistance_".$product_id])."|end|";
						
					}
				}
			}
			
			$notices = "";
			
			foreach ($_POST as $var => $value) {
				if (substr($var,0,11) == "rpNoticeID_") {
					if ($value=="on") {
						if (rpIsAlive(intval(str_replace("rpNoticeID_","",$var)), "notices") && rpGetNotice(intval(str_replace("rpNoticeID_","",$var)), "published")==1) {
							$notices .= "[".intval(str_replace("rpNoticeID_","",$var))."]";
						}
					}
				}
			}
			
			$participants = "";
			$participants_array = array();
			
			foreach ($_POST as $var => $value) {
				if (substr($var,0,13) == "rpToClientID_") {
					if ($value==intval(str_replace("rpToClientID_","",$var))) {
						if (rpIsAlive(intval($value), "clients") && rpGetOtherClient(intval($value), "published")==1) {
							$participants .= "[".intval($value)."]";
							array_push($participants_array, rpGetOtherClient(intval($value),"email"));
						}
					}
				}
			}
		
			if ($rpConnection->query("UPDATE ".$rpSettings->getValue("contractsTable")." SET 
				output_name='".rpSanitize($_POST["rpContractOutputName"])."',
				output_address='".rpSanitize($_POST["rpContractOutputAddress"])."',
				output_phonenumber='".rpSanitize($_POST["rpContractOutputPhonenumber"])."',
				output_email='".rpSanitize($_POST["rpContractOutputEmail"])."',
				output_companycode='".rpSanitize($_POST["rpContractOutputCompanyCode"])."',
				output_animals='".rpSanitize($animals)."',
				output_animal_amount='".rpSanitize($_POST["rpContractOutputAnimalAmount"])."',
				output_field_area='".rpSanitize($_POST["rpContractOutputFieldArea"])."',
				output_other_contracts='".rpSanitize($_POST["rpContractOutputOtherContracts"])."',
				output_total_animal_amount='".rpSanitize($_POST["rpContractOutputTotalAnimalAmount"])."',
				input_name='".rpSanitize($_POST["rpContractInputName"])."',
				input_address='".rpSanitize($_POST["rpContractInputAddress"])."',
				input_bic='".rpSanitize($_POST["rpContractInputBIC"])."',
				input_phonenumber='".rpSanitize($_POST["rpContractInputPhonenumber"])."',
				input_email='".rpSanitize($_POST["rpContractInputEmail"])."',
				input_dung_area='".rpSanitize($_POST["rpContractInputDungArea"])."',
				input_refinement='".rpSanitize($_POST["rpContractInputRefinement"])."',
				input_contract_time='".rpSanitize($_POST["rpContractInputContractTime"])."',
				input_products='".rpSanitize($products)."',
				transporter='".rpSanitize($_POST["rpContractTransporter"])."',
				distributor='".rpSanitize($_POST["rpContractDistributor"])."',
				transportation_payer='".rpSanitize($_POST["rpContractTransportationPayer"])."',
				distribution_payer='".rpSanitize($_POST["rpContractDistributionPayer"])."',
				remarks='".rpSanitize($_POST["rpContractRemarks"])."',
				place_and_time='".rpSanitize($_POST["rpContractPlaceAndTime"])."',
				to_noticeid='".rpSanitize($notices)."',
				to_clientid='".rpSanitize($participants)."',
				modified_datetime='".date("Y-m-d H:i:s")."',
				modified_ip='".rpSanitize(rpGetIP())."',
				modified_clientid='".rpSanitize(intval($_SESSION["clientID"]))."'
				 WHERE id='".rpSanitize(intval($_POST["rpContractID"]))."' LIMIT 1")) {
		
					if ($_SESSION["clientID"] == rpGetContract($_POST["rpContractID"],"added_clientid")) {
					
						$rpConnection->query("UPDATE ".$rpSettings->getValue("contractsTable")." SET 
						editable='".rpSanitize($_POST["rpContractEditable"])."' WHERE id='".rpSanitize(intval($_POST["rpContractID"]))."' LIMIT 1");
						
					}
		
				echo "SUCCESS";
	
			} else {echo "Ongelma sopimuksen tallentamisessa.";}
	
		} else {echo "Sinulla ei ole oikeutta muokata tätä sopimusta.";}
	
	} else {echo "Sopimusta ei löytynyt.";}
	
}

if ($_POST["rpContract"]>0 && $_POST["rpAction"]=="rpRemoveContract" && $_SESSION["clientID"]) {

	// remove contract

	$contract_result = $rpConnection->query("SELECT id FROM ".$rpSettings->getValue("contractsTable")." WHERE id='".rpSanitize(intval($_POST["rpContract"]))."' && added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."' LIMIT 1");

	if (mysql_num_rows($contract_result)>0) {
		
		if ($rpConnection->query("DELETE FROM ".$rpSettings->getValue("contractsTable")." WHERE id='".rpSanitize(intval($_POST["rpContract"]))."'")) {
			
			echo "SUCCESS";
			
		} else {echo "Ongelma sopimuksen poistamisessa.";}
		
	} else {echo "Sopimusta ei löytynyt.";}

}

if ($_POST["rpAction"]=="rpAddContract" && $_SESSION["clientID"]) {

	// add contract

	$nextID = rpGetNextID("contracts");

	$animals = "";
	
	foreach ($_POST as $var => $value) {
		if (substr($var,0,14) == "rpAnimalTitle_") {
			if ($value!="") {
				$animals .= str_replace("|end|","",$value)."|end|";
			}
		}
	}
	
	$products = "";
	
	foreach ($_POST as $var => $value) {
		if (substr($var,0,18) == "rpContractProduct_") {
			if ($value!="") {
				
				$product_id = intval(str_replace("rpContractProduct_","",$var));
				
				$products .= str_replace("|","",$value)."|".str_replace("|","",$_POST["rpContractProductAmount_".$product_id])."|".str_replace("|","",$_POST["rpContractProductTransportationDistance_".$product_id])."|end|";
				
			}
		}
	}
	
	$notices = "";
	
	foreach ($_POST as $var => $value) {
		if (substr($var,0,11) == "rpNoticeID_") {
			if ($value=="on") {
				if (rpIsAlive(intval(str_replace("rpNoticeID_","",$var)), "notices") && rpGetNotice(intval(str_replace("rpNoticeID_","",$var)), "published")==1) {
					$notices .= "[".intval(str_replace("rpNoticeID_","",$var))."]";
				}
			}
		}
	}
	
	$participants = "";
	$participants_array = array();
	
	foreach ($_POST as $var => $value) {
		if (substr($var,0,13) == "rpToClientID_") {
			if ($value==intval(str_replace("rpToClientID_","",$var))) {
				if (rpIsAlive(intval($value), "clients") && rpGetOtherClient(intval($value), "published")==1) {
					$participants .= "[".intval($value)."]";
					array_push($participants_array, rpGetOtherClient(intval($value),"email"));
				}
			}
		}
	}

	if ($rpConnection->query("INSERT INTO ".$rpSettings->getValue("contractsTable")." (".$rpSettings->getValue("contractsTableStructure").") VALUES (
		'".rpSanitize($nextID)."',
		'0',
		'".rpSanitize($_POST["rpContractOutputName"])."',
		'".rpSanitize($_POST["rpContractOutputAddress"])."',
		'".rpSanitize($_POST["rpContractOutputPhonenumber"])."',
		'".rpSanitize($_POST["rpContractOutputEmail"])."',
		'".rpSanitize($_POST["rpContractOutputCompanyCode"])."',
		'".rpSanitize($animals)."',
		'".rpSanitize($_POST["rpContractOutputAnimalAmount"])."',
		'".rpSanitize($_POST["rpContractOutputFieldArea"])."',
		'".rpSanitize($_POST["rpContractOutputOtherContracts"])."',
		'".rpSanitize($_POST["rpContractOutputTotalAnimalAmount"])."',
		'".rpSanitize($_POST["rpContractInputName"])."',
		'".rpSanitize($_POST["rpContractInputAddress"])."',
		'".rpSanitize($_POST["rpContractInputBIC"])."',
		'".rpSanitize($_POST["rpContractInputPhonenumber"])."',
		'".rpSanitize($_POST["rpContractInputEmail"])."',
		'".rpSanitize($_POST["rpContractInputDungArea"])."',
		'".rpSanitize($_POST["rpContractInputRefinement"])."',
		'".rpSanitize($_POST["rpContractInputContractTime"])."',
		'".rpSanitize($products)."',
		'".rpSanitize($_POST["rpContractTransporter"])."',
		'".rpSanitize($_POST["rpContractDistributor"])."',
		'".rpSanitize($_POST["rpContractTransportationPayer"])."',
		'".rpSanitize($_POST["rpContractDistributionPayer"])."',
		'".rpSanitize($_POST["rpContractRemarks"])."',
		'".rpSanitize($_POST["rpContractPlaceAndTime"])."',
		'',
		'',
		'',	
		'".date("Y-m-d H:i:s")."',
		'".rpSanitize(rpGetIP())."',
		'".rpSanitize($_SESSION["clientID"])."',
		'".rpSanitize($notices)."',
		'".rpSanitize($participants)."',
		'',
		'',
		'0',
		'".rpSanitize($_POST["rpContractEditable"])."',
		'".rpSanitize($nextID)."',
		'1')")) {

		foreach($participants_array as $email) {
			
			if (strstr($email,"@")) {

				rpSendMail($email, "Sinut on lisätty lannan luovutus- ja vastaanottosopimukseen Ravinnepörssi-palvelussa.", "<p>Tutustu sopimukseen kirjautumalla Ravinnepörssi-palveluun osoitteessa <a href=\"http://".$rpSettings->getValue("domain")."/\" target=\"_blank\">http://".$rpSettings->getValue("domain")."/</a>. Löydät sopimukset toiminnot-valikosta.</p>");
				
			}
			
		}
		
		echo "SUCCESS";
		
	} else {echo "Ongelma sopimuksen tallentamisessa.";}

}

include_once("engine/rp.end.php"); ?>