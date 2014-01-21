<?php include_once("engine/rp.start.php"); ?>

<!DOCTYPE html>
<html lang="fi" xml:lang="fi"<?php if ($_GET["rpPage"]!="map") {echo " style=\"overflow: auto !important;\"";}?>>
<head>
<?php

if (!$_SESSION["clientID"]) {

	echo "<meta http-equiv=\"cache-control\" content=\"max-age=0\" />
<meta http-equiv=\"cache-control\" content=\"no-cache\" />
<meta http-equiv=\"expires\" content=\"0\" />
<meta http-equiv=\"expires\" content=\"Tue, 01 Jan 1980 1:00:00 GMT\" />
<meta http-equiv=\"pragma\" content=\"no-cache\" />";

}

?>

<meta charset="utf-8">

<title><?php echo $rpSettings->getValue("domainTitle"); ?></title>   
<?php include("_scripts.php"); ?>

</head>
<body<?php if ($_GET["rpPage"]!="map") {echo " style=\"overflow: auto !important;\"";}?>>

<?php

if ($_GET["rpPage"]=="map" || intval($_GET["noticeID"])>0) {require_once("engine/frontend/rp.front.mapview.php");}
else {require_once("engine/frontend/rp.front.start.php");}

?>

</body>
</html>

<?php include_once("engine/rp.end.php"); ?>
