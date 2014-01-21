<?php include_once("engine/rp.start.php");

$_POST["state"] = str_replace(".","",$_POST["state"]);
$_POST["state"] = str_replace("/","",$_POST["state"]);

$_POST["city"] = str_replace(".","",$_POST["city"]);
$_POST["city"] = str_replace("/","",$_POST["city"]);

if ($_POST["city"]!="" && $_POST["state"]!="") {

	echo file_get_contents("pcf/".rpDecodeFilename($_POST["state"])."/".rpDecodeFilename($_POST["city"]).".dat");

} else if ($_POST["state"]!="") {

	$output = "";

	if ($handle = opendir("pcf/".rpDecodeFilename($_POST["state"]))) {
				
		while (false !== ($entry = readdir($handle))) {
			
			if ($entry != "." && $entry != ".." && substr($entry,0,1)!=".") {
			
				$output .= rpEncodeFilename(str_replace(".dat","",$entry)).";";
			
			}
			
		}
		
		closedir($handle);
		
	}

	echo $output;

}



include_once("engine/rp.end.php"); ?>