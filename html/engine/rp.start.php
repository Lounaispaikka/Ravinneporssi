<?php session_start();

ini_set("display_errors", 0);

// generate htaccess if missing

if (!file_exists(".htaccess")) {

	$htaccess_file = fopen(".htaccess", "w");
	fwrite($htaccess_file, "Options -Indexes\nRewriteEngine on\nRewriteRule ^map$ ?rpPage=map [L]\nRewriteRule ^logout$ ?logout=1 [L]");
	fclose($htaccess_file);
	
}

// require base essentials

require_once("rp.functions.php");
require_once("rp.settings.php");
require_once("rp.database.php");
require_once("rp.help.php");

require_once("backend/rp.back.trades.php");
require_once("backend/rp.back.clients.php");
require_once("backend/rp.back.messages.php");
require_once("backend/rp.back.routes.php");
require_once("backend/rp.back.fields.php");
require_once("backend/rp.back.notices.php");
require_once("backend/rp.back.products.php");
require_once("backend/rp.back.content.php");
require_once("backend/rp.back.contracts.php");

// create new objects

$rpSettings = new rpSettings();
$rpConnection = new rpConnection();

// connect to the database

$rpConnection->connect(
	$rpSettings->getValue("dburl"),
	$rpSettings->getValue("username"),
	$rpSettings->getValue("password"),
	$rpSettings->getValue("database"));

require_once("rp.login.php");

require_once("rp.logout.php");

require_once("rp.process.message.php");
require_once("rp.process.settings.php");
require_once("rp.process.notice.php");

?>