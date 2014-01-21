<?php

function rpGetFirstType($type) {
	
	global $rpSettings;
	
	$types_array = $rpSettings->getValue($type);
	
	return $types_array[0][0];
	
}

function rpFormatDate($string) {
	
	if ($string == "") {$string = "1.1.1970";}
	
	$fulldate = explode(".", $string);
	
	return date("Y-m-d H:i:s", mktime(0, 0, 0, $fulldate[1], $fulldate[0], $fulldate[2]));
	
}

function rpGetFirst($types) {
	
	$types_array = explode("]", $types);	
	
	return rpCleanBraces($types_array[0]);
	
}

function rpGetTypeTitle($selected, $type) {

	global $rpSettings;

	$types_array = $rpSettings->getValue($type);

	foreach ($types_array as $type) {
		
		if ($selected == $type[0]) {return $type[1];}		
		
	}

}

function rpPrintFileTypes($html, $removefromend=0, $print=true) {
	
	global $rpSettings;
	
	$types_array = explode("][", $rpSettings->getValue("allowFileTypes"));
	$whattooutput = "";
	
	foreach ($types_array as $type) {
	
		if (rpCleanBraces($type)!="") {
			
			$output = $html;
			
			$output = str_replace("[rp(type)]", rpCleanBraces($type), $output);
						
			$whattooutput .= $output;
			
		}
	
	}
	
	$whattooutput = substr($whattooutput, 0, -$removefromend);
	
	if ($print) {
		echo $whattooutput;
	} else {
		return $whattooutput;	
	}
	
}

function rpPrintTypes($html, $type, $selected="", $print=true) {

	global $rpSettings;

	$types_array = $rpSettings->getValue($type);
	$whattooutput = "";

	foreach ($types_array as $type) {
		
		$output = $html;
		
		$output = str_replace("[rp(type)]", $type[0], $output);
		$output = str_replace("[rp(title)]", $type[1], $output);
						
		if ($selected != $type[0]) {$output = str_replace("SELECTED", "", $output);}
		if (!strstr($selected, $type[0])) {$output = str_replace("CHECKED", "", $output);}
		
		$whattooutput .= $output;
		
	}

	if ($print) {echo $whattooutput;} else {return $whattooutput;}

}

function rpBoolean($string) {
	
	if ($string == "on") {return 1;} else {return 0;}	
	
}

function rpBooleanConvert($string) {
	
	if ($string == 1) {return "Kyllä";} else {return "Ei";}	
	
}

function rpGeoDistance($lat1, $lng1, $lat2, $lng2) {
	
	$pi80 = M_PI / 180;
	$lat1 *= $pi80;
	$lng1 *= $pi80;
	$lat2 *= $pi80;
	$lng2 *= $pi80;
	
	$r = 6372.797;
	$dlat = $lat2 - $lat1;
	$dlng = $lng2 - $lng1;
	$a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
	$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
	$km = $r * $c;
	
	return $km;
	
}

function rpDistance($distance) {
	
	$km = floor($distance);	
	$meters = round(($distance-$km)*1000);
	
	if ($km>0) {
		return $km." km ".$meters." m";
	} else {	
		return $meters." m";
	}
	
}

function rpArea($area) {
	
	$hectare = $area/10000;	
	$meters = round($area);
	
	return $meters." m<sup>2</sup> (".number_format($hectare,2,",","")." ha)";
	
}

function rpExtension($filename) { 
	
	$filename = strtolower($filename) ; 
	$exts = explode(".", $filename) ; 
	$n = count($exts)-1; 
	$exts = $exts[$n];
	if ($n==0) {return "";} else {return $exts;}
	
}

function rpCleanBraces($content) {
	
	 $content = str_replace("(", "", $content);
	 $content = str_replace(")", "", $content);
	 $content = str_replace("[", "", $content);
	 $content = str_replace("]", "", $content);
	
	 return $content;
	
}

function rpEncodeFilename($string) {

	$string = str_replace("_auml_","ä",$string);
	$string = str_replace("_ouml_","ö",$string);
	$string = str_replace("_euml_","å",$string);
	$string = str_replace("_Auml_","Ä",$string);
	$string = str_replace("_Ouml_","Ö",$string);
	$string = str_replace("_Euml_","Å",$string);
	$string = str_replace("_"," ",$string);
	
	return $string;
	
}

function rpDecodeFilename($string) {

	$string = str_replace(" ","_",$string);
	$string = str_replace("ä","_auml_",$string);
	$string = str_replace("ö","_ouml_",$string);
	$string = str_replace("å","_euml_",$string);
	$string = str_replace("Ä","_Auml_",$string);
	$string = str_replace("Ö","_Ouml_",$string);
	$string = str_replace("Å","_Euml_",$string);
	
	return $string;
	
}

function rpGetBetween($content, $start, $end) {
    $r = explode($start, $content);
    if (isset($r[1])){
        $r = explode($end, $r[1]);
        return $r[0];
    }
    return "";
}

function rpGetStates($html, $selected) {
	
	if ($handle = opendir("pcf")) {
		
		$states = array();
		
		while (false !== ($entry = readdir($handle))) {
			
			if ($entry != "." && $entry != ".." && substr($entry,0,1)!=".") {
			
				array_push($states, $entry);
			
			}
			
		}
		
		sort($states);
		
		foreach($states as $state) {
			
			$output = $html;
				
			$output = str_replace("[rp(title)]",rpEncodeFilename($state),$output);
				
			if ($selected != rpEncodeFilename($state)) {$output = str_replace("SELECTED","",$output);}
				
			echo $output;
			
		}
		
		closedir($handle);
		
	}
	
}

function rpGetNextID($table) {
	
	global $rpConnection;
	global $rpSettings;
			
	$result = $rpConnection->query("SELECT MAX(id) FROM ".$rpSettings->getValue($table."Table"));
	$row = mysql_fetch_array($result);
	return $row['MAX(id)']+1;
	
}

function rpIsAlive($id, $table) {
		
	global $rpConnection;
	global $rpSettings;
		
	$total_result = $rpConnection->query("SELECT id FROM ".$rpSettings->getValue($table."Table")." WHERE id='".rpSanitize(intval($id))."' LIMIT 1");
	
	if (mysql_num_rows($total_result)>0) {return true;} else {return false;}	
	
}

function rpSendMail($email, $title, $message) {
		
	global $rpSettings;
	
	$header = "MIME-Version: 1.0\r\nContent-type: text/html; charset=UTF-8\r\n";
	$header .= "From: ".$rpSettings->getValue("contactEmail")." \r\n";
	$header .= "Reply-To: ".$rpSettings->getValue("contactEmail")." \r\n";

	if (mail($email, "=?UTF-8?B?".base64_encode($title)."?=", $message, $header)) {return true;} else {return false;}
	
}

function rpGenerateSalt($length) {
	
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$salt = "";
	
	for ($i=0;$i<$length;$i++) {
		$salt .= $chars[mt_rand(0, strlen($chars)-1)];
	}
	
	return "$2a$07$".$salt."$";
    
}

function rpGetIP() {
	
	if (!empty($_SERVER["HTTP_CLIENT_IP"]))	{
		$ip=$_SERVER["HTTP_CLIENT_IP"];
	}
	else if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
		$ip=$_SERVER["HTTP_X_FORWARDED_FOR"];
	}
	else {
		$ip=$_SERVER["REMOTE_ADDR"];
	}
	
	return $ip;
	
}

function rpFullTime($string) {
	
	if (date("j.n.Y", strtotime($string)) == "1.1.1970" || date("j.n.Y", strtotime($string)) == "30.11.-0001" || date("j.n.Y", strtotime($string)) == "00.00.0000") {return "";} else {return date("j.n.Y H.i", strtotime($string));}
	
}

function rpDate($string) {
	
	if (date("j.n.Y", strtotime($string)) == "1.1.1970" || date("j.n.Y", strtotime($string)) == "30.11.-0001" || date("j.n.Y", strtotime($string)) == "00.00.0000") {return "";} else {return date("j.n.Y", strtotime($string));}
	
}

function rpUTF8Decode($string) {
	
	global $rpSettings;
	
	if ($rpSettings->getValue("processUTF8")) {
	return utf8_decode($string);	
	} else {
	return $string;
	}
	
}

function rpUTF8Encode($string) {
	
	global $rpSettings;
	
	if ($rpSettings->getValue("processUTF8")) {
	return utf8_encode($string);	
	} else {
	return $string;
	}
	
}

function rpSanitize($string) {
	
	return rpUTF8Decode(mysql_real_escape_string(strip_tags($string, "<br>")));
	
}

function rpAdminSanitize($string) {
	
	return rpUTF8Decode(mysql_real_escape_string(strip_tags($string, "<br><a><p><h2><h3>")));
	
}

?>