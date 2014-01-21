<?php include_once("engine/rp.start.php");

if ($_GET["id"]=="messages" && $_SESSION["clientID"]) {

	// messages

	require_once("engine/frontend/rp.front.messages.php");

} else if ($_GET["id"]=="profile" && $_SESSION["clientID"]) {
	
	// profile
	
	require_once("engine/frontend/rp.front.settings.php");
	
} else if ($_GET["id"]=="actions" && $_SESSION["clientID"]) {
	
	// actions
	
	require_once("engine/frontend/rp.front.actions.php");
	
} else if ($_GET["id"]=="listview") {
	
	// list view
	
	require_once("engine/frontend/rp.front.listview.php");
	
} else if ($_GET["id"]=="clients") {
	
	// clients
	
	require_once("engine/frontend/rp.front.clients.php");
	
} else if ($_GET["id"]=="help") {
	
	// help
	
	require_once("engine/frontend/rp.front.help.php");
	
} else if ($_GET["id"]=="links") {
	
	// links
	
	require_once("engine/frontend/rp.front.links.php");
	
} else if ($_GET["id"]=="feedback") {
	
	// feedback
	
	require_once("engine/frontend/rp.front.feedback.php");
	
} else if ($_GET["id"]=="content" && rpIsAdmin($_SESSION["clientID"])) {
	
	// content
	
	require_once("engine/frontend/rp.front.content.php");
	
}

include_once("engine/rp.end.php"); ?>