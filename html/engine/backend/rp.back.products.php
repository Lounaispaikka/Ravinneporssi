<?php

function rpGetProductPrefix($id, $option=0) {
	
	global $rpConnection;
	global $rpSettings;
	
	$product_result = $rpConnection->query("SELECT prefix, options FROM ".$rpSettings->getValue("productsTable")." WHERE published='1' && id='".rpSanitize(intval($id))."' LIMIT 1");

	if (mysql_num_rows($product_result)>0) {
	
		if ($option>0) {
			
			$options_array = explode("|end|", rpUTF8Encode(mysql_result($product_result, 0, "options")));
									
			if ($options_array[$option-1]) {
			
				$option_array = explode("|", $options_array[$option-1]);
				
				return $option_array[0];
				
			}
			
			} else {
			
				return rpUTF8Encode(mysql_result($product_result, 0, "prefix"));
			
		}
	
	}
	
}

function rpGetProductIdByPrefix($product, $option="") {
	
	global $rpConnection;
	global $rpSettings;
	
	$product_result = $rpConnection->query("SELECT id, options FROM ".$rpSettings->getValue("productsTable")." WHERE published='1' && prefix='".rpSanitize($product)."' LIMIT 1");
	
	if (mysql_num_rows($product_result)>0) {
		
		if ($option!="") {
		
			$runner = 0; $outputrunner = 0;
			
			$products_array = explode("|end|", rpUTF8Encode(mysql_result($product_result, 0, "options")));
			
			foreach ($products_array as $value) {
				
				$product_array = explode("|", $value);
				
				if ($product_array[0]==$option) {$outputrunner = $runner;}
				
				$runner += 1;
				
			}
			
			return $outputrunner+1;
		
		} else {
			
			return mysql_result($product_result, 0, "id");
			
		}
		
	}
	
}

function rpGetNoticeProducts($html, $id, $print=true) {
	
	global $rpConnection;
	global $rpSettings;
	
	$notice_result = $rpConnection->query("SELECT products FROM ".$rpSettings->getValue("noticesTable")." WHERE id='".rpSanitize(intval($id))."' &&  (added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."' OR visibility='all' OR (".rpSanitize(intval($_SESSION["clientID"])).">0 && visibility='registered')) LIMIT 1");
	
	$tooutput = "";
	
	if (mysql_num_rows($notice_result)>0) {
				
		$products_array = explode("|end|", rpUTF8Encode(mysql_result($notice_result, 0, "products")));
		
		foreach ($products_array as $value) {
			
			if ($value != "") {
				
				$product_array = explode("|", $value);
				
				if ($product_array[0]=="rpProduct") {
				
					$output = $html;

					$output = str_replace("[rp(id)]",$product_array[2],$output);
					
					$output = str_replace("[rp(prefix)]",$product_array[3],$output);

					$output = str_replace("[rp(type)]",$product_array[1],$output);
					
					$output = str_replace("[rp(type_title)]",rpGetTypeTitle($product_array[1], "baseTypes"),$output);
				
					if (strstr($output, "[rp(title)]")) {
						
						$product_id = rpGetProductIdByPrefix($product_array[3]);
						
						if ($product_id>0) {
						
						$output = str_replace("[rp(title)]", rpGetProduct("title", $product_id), $output);
						
						$output = str_replace("[rp(options)]", rpGetNoticeProductOptions("[rp(title)][rp(value)]<br />", $product_array[2], $products_array), $output);
						
						$tooutput .= $output;
						
						}
						
					} else {
						
						$tooutput .= $output;
						
					}					
				
				}
				
			}
			
		}
		
	}
	
	if ($print) {echo $tooutput;} else {return $tooutput;}
	
}

function rpGetProductOptionTitle($pid, $id) {
	
	global $rpConnection;
	global $rpSettings;
	
	$product_result = $rpConnection->query("SELECT options FROM ".$rpSettings->getValue("productsTable")." WHERE published='1' && id='".rpSanitize(intval($pid))."' LIMIT 1");
	
	if (mysql_num_rows($product_result)>0) {
		
		$options_array = explode("|end|", rpUTF8Encode(mysql_result($product_result, 0, "options")));
				
		if ($options_array[$id-1]) {
			
			$option_array = explode("|", $options_array[$id-1]);
			
			return $option_array[1];
							
		}
			
	}
	
}

function rpGetNoticeProductOptions($html, $id, $products_array) {
	
	$tooutput = "";
	
	foreach ($products_array as $value) {
			
		if ($value != "") {
			
			$product_array = explode("|", $value);
			
			if ($product_array[0]=="rpProductOption" || $product_array[0]=="rpProductDescription") {

				if ($product_array[2] == $id) {
				
					$output = $html;
					
					if ($product_array[0]=="rpProductOption") {
						
						$output = "&bull; ".$output;
						
					}
					
					$output = str_replace("[rp(title)]", rpGetProductOptionTitle(rpGetProductIdByPrefix($product_array[3]), rpGetProductIdByPrefix($product_array[3], $product_array[4])),$output);
					
					if ($product_array[5]!="") {
						
						$output = str_replace("[rp(value)]", ": ".$product_array[5],$output);
						
					} else {
						
						$output = str_replace("[rp(value)]", "",$output);
						
					}
					
					$tooutput .= $output;
				
				}
				
			}
			
		}
			
	}
	
	return $tooutput;
	
}

function rpGetNoticeProductPrefix($nid, $pid) {
	
	global $rpConnection;
	global $rpSettings;
	
	$notice_result = $rpConnection->query("SELECT products FROM ".$rpSettings->getValue("noticesTable")." WHERE id='".rpSanitize(intval($nid))."' && (added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."' OR visibility='all' OR (".rpSanitize(intval($_SESSION["clientID"])).">0 && visibility='registered')) LIMIT 1");
	
	if (mysql_num_rows($notice_result)>0) {
				
		$products_array = explode("|end|", rpUTF8Encode(mysql_result($notice_result, 0, "products")));
		
		foreach ($products_array as $value) {
			
			if ($value != "") {
				
				$product_array = explode("|", $value);
				
				if ($product_array[0] == "rpProduct" && $product_array[2] == $pid) {return $product_array[3];}
								
			}
			
		}
		
	}
	
}

function rpGetProduct($row, $id) {
	
	global $rpConnection;
	global $rpSettings;
	
	$product_result = $rpConnection->query("SELECT ".rpSanitize($row)." FROM ".$rpSettings->getValue("productsTable")." WHERE published='1' && id='".rpSanitize(intval($id))."' LIMIT 1");
	
	return rpUTF8Encode(mysql_result($product_result, 0, $row));
			
}

function rpGetProducts($html, $id="", $nid=0, $pid=0, $selected="") {
	
	global $rpConnection;
	global $rpSettings;
	
	if ($id!="") {
	
		$products_result = $rpConnection->query("SELECT id, prefix, title FROM ".$rpSettings->getValue("productsTable")." WHERE published='1' && types LIKE '%[".rpSanitize($id)."]%' ORDER by priority ASC");
	
	} else {
		
		$products_result = $rpConnection->query("SELECT id, prefix, title FROM ".$rpSettings->getValue("productsTable")." WHERE published='1' ORDER by priority ASC");
		
	}
	
	if ($nid>0) {
		
		$prefix = rpGetNoticeProductPrefix($nid, $pid);
		
	}	
		
	for ($i = 0; $i < mysql_num_rows($products_result); $i += 1) {
	
		$output = $html;
		
		$output = str_replace("[rp(id)]",mysql_result($products_result, $i, "id"),$output);
		
		$output = str_replace("[rp(prefix)]",mysql_result($products_result, $i, "prefix"),$output);
		
		$output = str_replace("[rp(title)]",rpUTF8Encode(mysql_result($products_result, $i, "title")),$output);
		
		if (mysql_result($products_result, $i, "prefix")!=$prefix) {$output = str_replace("SELECTED","",$output);}
		
		if (!strstr($selected,mysql_result($products_result, $i, "prefix"))) {$output = str_replace("CHECKED","",$output);}
		
		echo $output;

	}
		
}

function rpExistsNoticeOption($nid, $pid, $prefix) {
	
	global $rpConnection;
	global $rpSettings;

	$notice_result = $rpConnection->query("SELECT products FROM ".$rpSettings->getValue("noticesTable")." WHERE id='".rpSanitize(intval($nid))."' LIMIT 1");
	
	$whattoreturn = false;
	
	if (mysql_num_rows($notice_result)>0) {

		$options_array = explode("|end|", rpUTF8Encode(mysql_result($notice_result, 0, "products")));
	
		foreach ($options_array as $value) {
			
			$option_array = explode("|", $value);
			
			if ($option_array[2]==$pid && $option_array[4]==$prefix) {
				
				$whattoreturn = true;				
				
			}			
			
		}
	
	}
	
	return $whattoreturn;
	
}

function rpGetNoticeOptionValue($nid, $pid, $prefix) {
	
	global $rpConnection;
	global $rpSettings;

	$notice_result = $rpConnection->query("SELECT products FROM ".$rpSettings->getValue("noticesTable")." WHERE id='".rpSanitize(intval($nid))."' LIMIT 1");

	$runner = 1;
	
	if (mysql_num_rows($notice_result)>0) {
			
		$options_array = explode("|end|", rpUTF8Encode(mysql_result($notice_result, 0, "products")));
	
		foreach ($options_array as $value) {

			$option_array = explode("|", $value);

			if ($option_array[2]==$pid && $option_array[4]==$prefix) {
								
				return $option_array[5];
				
			}			
			
		}
	
	}
	
}

function rpGetProductOptions($html, $html2, $html3, $id, $nid=0, $pid=0) {
	
	global $rpConnection;
	global $rpSettings;
	
	$product_result = $rpConnection->query("SELECT options FROM ".$rpSettings->getValue("productsTable")." WHERE published='1' && id='".rpSanitize(intval($id))."' LIMIT 1");

	$runner = 1;
	
	if (mysql_num_rows($product_result)>0) {
			
		$options_array = explode("|end|", rpUTF8Encode(mysql_result($product_result, 0, "options")));
		
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
				
				$output = str_replace("[rp(id)]", $runner, $output);
				
				if ($html2=="" && $html3=="") {
					
					$output = str_replace("[rp(value)]", "", $output);
					
				} else if ($nid>0) {
				
					$output = str_replace("[rp(value)]", rpGetNoticeOptionValue($nid, $pid, $option_array[0]), $output);
					if (!rpExistsNoticeOption($nid, $pid, $option_array[0])) {$output = str_replace("CHECKED", "", $output);}
					
				} else {
					
					$output = str_replace("CHECKED", "", $output);
					$output = str_replace("[rp(value)]", "", $output);
					
				}
				
				$output = str_replace("[rp(type)]", $option_array[0], $output);				
				$output = str_replace("[rp(title)]", $option_array[1], $output);
				
				echo $output;
				
				$runner += 1;
				
			}
			
		}
				
	}
	
}

?>