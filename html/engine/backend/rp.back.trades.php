<?php

function rpGetTradePrefix($id, $option=0) {
	
	global $rpConnection;
	global $rpSettings;
	
	$trade_result = $rpConnection->query("SELECT prefix, options FROM ".$rpSettings->getValue("tradesTable")." WHERE published='1' && id='".rpSanitize(intval($id))."' LIMIT 1");

	if (mysql_num_rows($trade_result)>0) {
	
		if ($option>0) {
			
			$trades_array = explode("|end|", rpUTF8Encode(mysql_result($trade_result, 0, "options")));
						
			if ($trades_array[$option-1]) {
			
				$trade_array = explode("|", $trades_array[$option-1]);
				
				return $trade_array[0];
				
			}
			
			} else {
			
				return rpUTF8Encode(mysql_result($trade_result, 0, "prefix"));
			
		}
	
	}
	
}

function rpGetTradeIdByPrefix($trade, $option="") {
	
	global $rpConnection;
	global $rpSettings;
	
	$trade_result = $rpConnection->query("SELECT id, options FROM ".$rpSettings->getValue("tradesTable")." WHERE published='1' && prefix='".rpSanitize($trade)."' LIMIT 1");
	
	if (mysql_num_rows($trade_result)>0) {
		
		if ($option!="") {
		
			$runner = 0; $outputrunner = 0;
			
			$trades_array = explode("|end|", rpUTF8Encode(mysql_result($trade_result, 0, "options")));
			
			foreach ($trades_array as $value) {
				
				$trade_array = explode("|", $value);
				
				if ($trade_array[0]==$option) {$outputrunner = $runner;}
				
				$runner += 1;
				
			}
			
			return $outputrunner+1;
		
		} else {
			
			return mysql_result($trade_result, 0, "id");
			
		}
		
	}
	
}

function rpGetClientTrades($html, $id) {
	
	global $rpConnection;
	global $rpSettings;
	
	$client_result = $rpConnection->query("SELECT trades FROM ".$rpSettings->getValue("clientsTable")." WHERE id='".rpSanitize(intval($id))."' LIMIT 1");
	
	if (mysql_num_rows($client_result)>0) {
		
		$trades = rpUTF8Encode(mysql_result($client_result, 0, "trades"));
		
		$trades_array = explode("|end|", $trades);
		
		foreach ($trades_array as $value) {
			
			if ($value != "") {
				
				$trade_array = explode("|", $value);
				
				if ($trade_array[0]=="rpClientTrade") {
				
					$output = $html;
					
					$output = str_replace("[rp(id)]",rpGetTradeIdByPrefix($trade_array[1]),$output);
				
					if (strstr($output, "[rp(trade_")) {
						
						$trade_result = $rpConnection->query("SELECT title FROM ".$rpSettings->getValue("tradesTable")." WHERE published='1' && id='".rpSanitize(intval(rpGetTradeIdByPrefix($trade_array[1])))."' LIMIT 1");
						
						if (mysql_num_rows($trade_result)>0) {
							
							$output = str_replace("[rp(trade_title)]", rpUTF8Encode(mysql_result($trade_result, 0, "title")), $output);
							
							$output = str_replace("[rp(trade_type)]", $trade_array[1], $output);
							
							$output = str_replace("[rp(trade_options)]", rpGetTradeOptions("&bull; [rp(title)] [rp(value)]<br />", "&bull; [rp(title)] [rp(value)]<br />", "&bull; [rp(title)] [rp(value)]<br />", rpGetTradeIdByPrefix($trade_array[1]), $id, false, true), $output);
							
						}
						
					}
				
					echo $output;
				
				}
				
			}
			
		}
		
	}
	
}

function rpGetTrades($html, $selected) {
	
	global $rpConnection;
	global $rpSettings;
	
	$trades_result = $rpConnection->query("SELECT id, prefix, title FROM ".$rpSettings->getValue("tradesTable")." WHERE published='1' ORDER by priority ASC");
	
	for ($i = 0; $i < mysql_num_rows($trades_result); $i += 1) {
		
		$output = $html;
		
		$output = str_replace("[rp(id)]", mysql_result($trades_result, $i, "id"), $output);
		
		$output = str_replace("[rp(prefix)]", mysql_result($trades_result, $i, "prefix"), $output);
		
		$output = str_replace("[rp(title)]", rpUTF8Encode(mysql_result($trades_result, $i, "title")), $output);
		
		if ($selected != mysql_result($trades_result, $i, "id")) {$output = str_replace("SELECTED","",$output);}
		
		echo $output;
		
	}
	
}

function rpGetClientTradeOption($id, $trade_id, $client_id, $desc=false) {
	
	global $rpConnection;
	global $rpSettings;
	
	$client_result = $rpConnection->query("SELECT trades FROM ".$rpSettings->getValue("clientsTable")." WHERE id='".rpSanitize(intval($client_id))."' LIMIT 1");
	
	if (!$desc && strstr(rpUTF8Encode(mysql_result($client_result, 0, "trades")), "rpClientTradeOption|".rpGetTradePrefix($trade_id)."|".rpGetTradePrefix($trade_id,$id)."|")) {return true;}
	else if ($desc) {
		
		if (rpGetBetween(rpUTF8Encode(mysql_result($client_result, 0, "trades")), "rpClientTradeOption|".rpGetTradePrefix($trade_id)."|".rpGetTradePrefix($trade_id,$id)."|", "|end|")!="") {
		
		return rpGetBetween(rpUTF8Encode(mysql_result($client_result, 0, "trades")), "rpClientTradeOption|".rpGetTradePrefix($trade_id)."|".rpGetTradePrefix($trade_id,$id)."|", "|end|");
		
		} else {
			
		return rpGetBetween(rpUTF8Encode(mysql_result($client_result, 0, "trades")), "rpClientTradeDescription|".rpGetTradePrefix($trade_id)."|".rpGetTradePrefix($trade_id,$id)."|", "|end|");
			
		}
		
	}
	
}

function rpGetTradeOptions($html, $html2, $html3, $id, $client_id=0, $print=true, $remove_unchecked=false) {
	
	global $rpConnection;
	global $rpSettings;
	
	$trade_result = $rpConnection->query("SELECT options FROM ".$rpSettings->getValue("tradesTable")." WHERE published='1' && id='".rpSanitize(intval($id))."' LIMIT 1");
	
	$runner = 1;
	$tooutput = "";
	
	if (mysql_num_rows($trade_result)>0) {
		
		$options = rpUTF8Encode(mysql_result($trade_result, 0, "options"));
		
		$options_array = explode("|end|", $options);
		
		foreach ($options_array as $value) {
			
			if ($value != "") {
				
				$option_array = explode("|", $value);
											
				if ($option_array[2]=="0") {
					
					$output = $html;
					
				} else if ($option_array[2]=="1") {
					
					$output = $html2;
					
				} else {
					
					$output = $html3;
					
				}
				
				$output = str_replace("[rp(id)]",$runner,$output);
								
				if (!rpGetClientTradeOption($runner, $id, $client_id)) {$output = str_replace("CHECKED","",$output);}
				$output = str_replace("[rp(value)]", rpGetClientTradeOption($runner, $id, $client_id, true),$output);
								
				$output = str_replace("[rp(title)]", $option_array[1],$output);
				
				if (!$remove_unchecked || rpGetClientTradeOption($runner, $id, $client_id) || rpGetClientTradeOption($runner, $id, $client_id, true)!="") {
				
					$tooutput .= $output;
				
				}
				
				$runner += 1;
				
			}
			
		}
				
	}
	
	if ($print) {echo $tooutput;} else {return $tooutput;}
	
}

?>