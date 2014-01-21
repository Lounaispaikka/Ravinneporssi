<?php

function rpGetContractProducts($html, $id, $print=true) {
	
	global $rpConnection;
	global $rpSettings;
	
	$to_output = "";
	
	$contract_result = $rpConnection->query("SELECT input_products FROM ".$rpSettings->getValue("contractsTable")." WHERE id='".rpSanitize(intval($id))."' && (added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."' OR to_clientid LIKE '%[".rpSanitize(intval($_SESSION["clientID"]))."]%') LIMIT 1");
	
	if (mysql_num_rows($contract_result)>0) {
		
		$products_array = explode("|end|", mysql_result($contract_result, 0, "input_products"));
		
		$runner = 1;
	
		foreach($products_array as $value) {
		
			if ($value!="") {
	
				$product_array = explode("|",$value);
	
				$output = $html;
				
				$output = str_replace("[rp(id)]",$runner,$output);
				$output = str_replace("[rp(title)]",rpGetTypeTitle($product_array[0], "dungTypes"),$output);
				$output = str_replace("[rp(amount)]",$product_array[1],$output);
				$output = str_replace("[rp(distance)]",$product_array[2],$output);
				
				$output = str_replace("[rp(options)]",rpPrintTypes("<option value=\"[rp(type)]\" SELECTED>[rp(title)]</option>", "dungTypes", $product_array[0], false),$output);
				
				$to_output .= $output;	
	
				$runner += 1;
	
			}
	
		}
	
	}
	
	if ($print) {echo $to_output;} else {return $to_output;}
	
}

function rpGetContractParticipants($html, $id) {
	
	global $rpConnection;
	global $rpSettings;
	
	$contract_result = $rpConnection->query("SELECT to_clientid FROM ".$rpSettings->getValue("contractsTable")." WHERE id='".rpSanitize(intval($id))."' && (added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."' OR to_clientid LIKE '%[".rpSanitize(intval($_SESSION["clientID"]))."]%') LIMIT 1");
	
	if (mysql_num_rows($contract_result)>0) {
		
		$participants_array = explode("][", mysql_result($contract_result, 0, "to_clientid"));
		
		foreach($participants_array as $value) {
			
			if (intval(rpCleanBraces($value))>0) {
				
				$output = $html;
				
				$output = str_replace("[rp(id)]",intval(rpCleanBraces($value)),$output);
				
				$output = str_replace("[rp(name)]",rpGetOtherClient(intval(rpCleanBraces($value)),"name"),$output);
				
				echo $output;	
				
			}	
			
		}
		
	}
	
}

function rpGetContractNotices($html, $id) {
	
	global $rpConnection;
	global $rpSettings;
	
	$contract_result = $rpConnection->query("SELECT to_noticeid FROM ".$rpSettings->getValue("contractsTable")." WHERE id='".rpSanitize(intval($id))."' && (added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."' OR to_clientid LIKE '%[".rpSanitize(intval($_SESSION["clientID"]))."]%') LIMIT 1");
	
	if (mysql_num_rows($contract_result)>0) {
		
		$notices_array = explode("][", mysql_result($contract_result, 0, "to_noticeid"));
		
		foreach($notices_array as $value) {
			
			if (intval(rpCleanBraces($value))>0) {
				
				$output = $html;
				
				$output = str_replace("[rp(id)]",intval(rpCleanBraces($value)),$output);
				
				$output = str_replace("[rp(title)]",rpGetNotice(intval(rpCleanBraces($value)),"title"),$output);
				
				echo $output;	
				
			}	
			
		}
		
	}
	
}

function rpGetContractAnimals($html, $id, $print=true) {
	
	global $rpConnection;
	global $rpSettings;
	
	$to_output = "";
	
	$contract_result = $rpConnection->query("SELECT output_animals FROM ".$rpSettings->getValue("contractsTable")." WHERE id='".rpSanitize(intval($id))."' && (added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."' OR to_clientid LIKE '%[".rpSanitize(intval($_SESSION["clientID"]))."]%') LIMIT 1");
	
	if (mysql_num_rows($contract_result)>0) {

		$animals_array = explode("|end|", rpUTF8Encode(mysql_result($contract_result, 0, "output_animals")));
		
		$runner = 1;
				
		foreach($animals_array as $value) {
			
			if ($value!="") {
				
				$output = $html;
				
				$output = str_replace("[rp(id)]",$runner,$output);
				
				$output = str_replace("[rp(title)]",$value,$output);
				
				$to_output .= $output;	
				
				$runner += 1;
				
			}	
			
		}
		
	}
	
	if ($print) {echo $to_output;} else {return $to_output;}
	
}

function rpGetContract($id, $row) {
	
	global $rpConnection;
	global $rpSettings;
	
	$contract_result = $rpConnection->query("SELECT ".rpSanitize($row)." FROM ".$rpSettings->getValue("contractsTable")." WHERE id='".rpSanitize(intval($id))."' && (added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."' OR to_clientid LIKE '%[".rpSanitize(intval($_SESSION["clientID"]))."]%') LIMIT 1");
	
	if (mysql_num_rows($contract_result)>0) {
		
		return rpUTF8Encode(mysql_result($contract_result, 0, $row));
		
	}
	
}

function rpGetContracts($html, $order="by added_datetime ASC", $nohtml="") {
	
	global $rpConnection;
	global $rpSettings;
	
	$contracts_result = $rpConnection->query("SELECT id, added_datetime, added_clientid, to_clientid, editable FROM ".$rpSettings->getValue("contractsTable")." WHERE added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."' OR to_clientid LIKE '%[".rpSanitize(intval($_SESSION["clientID"]))."]%' ORDER ".rpSanitize($order));

	if (mysql_num_rows($contracts_result) > 0) {

		for ($i = 0; $i < mysql_num_rows($contracts_result); $i += 1) {
		
			$output = $html;
	
			$output = str_replace("[rp(id)]", mysql_result($contracts_result, $i, "id"), $output);
			
			$output = str_replace("[rp(added_datetime)]", rpDate(mysql_result($contracts_result, $i, "added_datetime")), $output);
	
			$output = str_replace("[rp(added_id)]", mysql_result($contracts_result, $i, "added_clientid"), $output);
	
			$output = str_replace("[rp(added_name)]", rpGetOtherClient(mysql_result($contracts_result, $i, "added_clientid"),"name"), $output);
			
			if ($_SESSION["clientID"]!=mysql_result($contracts_result, $i, "added_clientid")) {
				
				$output = str_replace("[removable/]".rpGetBetween($output, "[removable/]", "[/removable]")."[/removable]","",$output);
				
			} else {
				
				$output = str_replace("[removable/]","",$output);
				$output = str_replace("[/removable]","",$output);
				
			}
			
			if ($_SESSION["clientID"]==mysql_result($contracts_result, $i, "added_clientid") || mysql_result($contracts_result, $i, "editable")==1) {
				
				$output = str_replace("[editable/]","",$output);
				$output = str_replace("[/editable]","",$output);
				
			} else {
				
				$output = str_replace("[editable/]".rpGetBetween($output, "[editable/]", "[/editable]")."[/editable]","",$output);
				
			}
			
			if (mysql_result($contracts_result, $i, "to_clientid")=="") {

				$output = str_replace("Muut osapuolet: [rp(participants)]", "", $output);
				
			} else {
			
				$participants_array = explode("][", mysql_result($contracts_result, $i, "to_clientid"));
				
				$participants = "";
				
				foreach($participants_array as $value) {
					
					if (intval(rpCleanBraces($value))>0) {
					
						$participants .= "<a href=\"javascript:showProfile(".intval(rpCleanBraces($value)).");\">".rpGetOtherClient(intval(rpCleanBraces($value)), "name")."</a>, ";
					
					}
					
				}
				
				$participants = substr($participants, 0, -2);
				
				$output = str_replace("[rp(participants)]", $participants, $output);
	
			}
	
			echo $output;
	
		}
		
	} else {echo $nohtml;}
	
}

?>