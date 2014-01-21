<?php include_once("engine/rp.start.php");

if (($_GET["id"]=="inbox" || $_GET["id"]=="outbox") && $_SESSION["clientID"]) {

	echo "<script>
		
	function removeMessage(id) {
		
		$.ajax({
			type:\"GET\",
			data: {
			id:id,
			rpAction:\"rpRemoveMessage\"
			},
		    url:\"_message.php\",
		    success: function(data) {
		    	if (data==\"SUCCESS\") {
		    	
		    		$(\"#message_\"+id).fadeOut(300);
		    		
		    		updateNewMessagesIndicator();
				
		    	} else {notify(data);}
		    }
		});
		
	}
	
	function toggleMessage(id) {
		
		if ($(\"#fullMessageDiv_\"+id).html()!=\"\") {
			
			$(\"#fullMessageDiv_\"+id).html(\"\");
			
		} else {
		
			$(\"#fullMessageDiv_\"+id).load(\"_message.php?id=\"+id+\"&rpAction=rpGetMessage\", function() {
				
				updateNewMessagesIndicator();
				
			});
		
		}
		
	}
	
	</script>";	
	
}

if ($_GET["id"]=="inbox" && $_SESSION["clientID"]) {
		
	rpGetMessages("<div class=\"messageDiv\" id=\"message_[rp(id)]\"><h2><a onmouseover=\"showInfo('Näytä viesti');\" onmouseout=\"hideInfo();\" href=\"javascript:toggleMessage([rp(id)]);\">[rp(title)]</a><a href=\"javascript:removeMessage([rp(id)]);\" class=\"smallFormButton right last\">Poista</a><a href=\"javascript:changeTab('newmsg', [rp(id)]);\" class='smallFormButton right'>Vastaa</a></h2><div id=\"fullMessageDiv_[rp(id)]\"></div>Lähettäjä: [rp(/from)]<a onmouseover=\"showInfo('Näytä käyttäjän profiili');\" onmouseout=\"hideInfo();\" href=\"javascript:showProfile([rp(from_id)]);\">[rp(from_name)]</a>[rp(from/)] ([rp(added_datetime)])<br />Vastaanottajat: [rp(/to)][rp(to)][rp(to/)]</div>", $_SESSION["clientID"], 0, "by added_datetime DESC", "Viestejä ei löytynyt.");
	
} else if ($_GET["id"]=="outbox" && $_SESSION["clientID"]) {
	
	rpGetMessages("<div class=\"messageDiv\" id=\"message_[rp(id)]\"><h2><a onmouseover=\"showInfo('Näytä viesti');\" onmouseout=\"hideInfo();\" href=\"javascript:toggleMessage([rp(id)]);\">[rp(title)]</a><a href=\"javascript:removeMessage([rp(id)]);\" class=\"smallFormButton right\">Poista</a></h2><div id=\"fullMessageDiv_[rp(id)]\"></div>Vastaanottajat: [rp(/to)][rp(to)][rp(to/)]([rp(added_datetime)])</div>", 0, $_SESSION["clientID"], "by added_datetime DESC", "Viestejä ei löytynyt.");
	
} else if ($_GET["id"]=="newmsg" && $_SESSION["clientID"]) {
		
	if ($_GET["clientid"]!=$_SESSION["clientID"]) {
	
		if ($_GET["messageid"]>0) {

			$message_result = $rpConnection->query("SELECT id, added_clientid, to_clientid FROM ".$rpSettings->getValue("messagesTable")." WHERE id='".rpSanitize(intval($_GET["messageid"]))."' && (to_clientid LIKE '%[".rpSanitize(intval($_SESSION["clientID"]))."]%' OR added_clientid='".rpSanitize(intval($_SESSION["clientID"]))."') LIMIT 1");
		
			if (mysql_num_rows($message_result)>0) {
				
				$replymode = true;
				
			} else {$replymode = false;}
			
		} else {$replymode = false;}
		
		if ($replymode) {
			
			echo "<h2>Lähetä vastaus viestiin";
			
		} else {
			
			echo "<h2>Lähetä uusi viesti";
			
		}
	
		echo "<input onmouseover=\"showInfo('Kirjoita käyttäjän nimi');\" onmouseout=\"hideInfo();\" type=\"text\" id=\"searchText\" name=\"searchText\" value=\"Lisää vastaanottaja\" onkeypress=\"triggerClientList();\" onfocus=\"$('#searchText').val('');\" /><div id=\"rpClientSearchResultDiv\" name=\"rpClientSearchResultDiv\"></div></h2>";
	
		echo "<script>updateNewMessagesIndicator();</script><form name=\"sendMessageForm\" id=\"sendMessageForm\" action=\"http://".$rpSettings->getValue("domain")."/map\" method=\"POST\" enctype=\"multipart/form-data\">
		
		<input type=\"hidden\" name=\"rpCheck\" value=\"".md5("MESSAGE".$rpSettings->getValue("secret").$_SESSION["clientID"])."\" />
		<input type=\"hidden\" name=\"rpAction\" value=\"rpSendMessage\" />
		<input type=\"hidden\" name=\"rpToMessageID\" value=\"".intval($_GET["messageid"])."\" /><div id=\"rpRecipientsDiv\" name=\"rpRecipientsDiv\">";
		
		if (rpIsAlive($_GET["clientid"], "clients") && rpGetOtherClient($_GET["clientid"], "published")==1) {
			
			echo "<div id=\"rpToClientDiv_".intval($_GET["clientid"])."\" name=\"rpToClientDiv_".intval($_GET["clientid"])."\"><input class=\"rpToClientInput\" type=\"hidden\" id=\"rpToClientID_".intval($_GET["clientid"])."\" name=\"rpToClientID_".intval($_GET["clientid"])."\" value=\"".intval($_GET["clientid"])."\" />";
			echo "<h3>Vastaanottaja: ".rpGetOtherClient($_GET["clientid"], "name")." (<a href=\"javascript:removeMessageRecipient(".intval($_GET["clientid"]).");\">poista</a>)</h3></div>";
			
		}
		
		if ($replymode) {
		
			$to_clients = array();
		
			if ($_SESSION["clientID"]!=mysql_result($message_result, 0, "added_clientid")) {
				
				if (rpIsAlive(mysql_result($message_result, 0, "added_clientid"), "clients")) {
				
					array_push($to_clients,mysql_result($message_result, 0, "added_clientid"));
				
				}
				
			}
		
			$to_explosion = explode("][", mysql_result($message_result, 0, "to_clientid"));

			foreach($to_explosion as $value) {

				if (intval(rpCleanBraces($value))>0) {
					
					if (rpIsAlive(intval(rpCleanBraces($value)), "clients")) {
						
						if ($_SESSION["clientID"]!=intval(rpCleanBraces($value))) {
						
							array_push($to_clients,intval(rpCleanBraces($value)));
						
						}
						
					}
					
				}
				
			}
		
			foreach($to_clients as $value) {
				
				echo "<div id=\"rpToClientDiv_".intval($value)."\" name=\"rpToClientDiv_".intval($value)."\"><input class=\"rpToClientInput\" type=\"hidden\" id=\"rpToClientID_".intval($value)."\" name=\"rpToClientID_".intval($value)."\" value=\"".intval($value)."\" />";
				echo "<h3>Vastaanottaja: ".rpGetOtherClient($value, "name")." (<a href=\"javascript:removeMessageRecipient(".intval($value).");\">poista</a>)</h3></div>";
				
			}
		
		}
		
		echo "</div>
				
		<div class=\"clear height5\"></div>
		
		<script>
		
		var rIndex = 1;
		var clientList = new Array();
		var listTrigger = false;
		
		function addRecipient(id) {
			
			if ($(\"#rpToClientID_\"+id).val()!=id) {
			
				$(\"#rpRecipientsDiv\").append(\"<div id='rpToClientDiv_\"+id+\"' name='rpToClientDiv_\"+id+\"'><input class='rpToClientInput' type='hidden' id='rpToClientID_\"+id+\"' name='rpToClientID_\"+id+\"' value='\"+id+\"' /><h3>Vastaanottaja: \"+$(\"#rpClientName_\"+id).val()+\" (<a href='javascript:removeMessageRecipient(\"+id+\");'>poista</a>)</h3></div>\");
			
			}
			
		}
		
		function triggerClientList() {
			
			window.setTimeout(function() {populateClientList();}, 100);	
			
		}
		
		function populateClientList() {
			
			if (clientList.length<1 && !listTrigger) {
				
				listTrigger = true;
				
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
						
									clientList.push(new Array(client[0],client[1]));
						
								}
						
							}
							
							populateClientList();
															
						}
					
					}
				
				});
				
			}
			
			$(\"#rpClientSearchResultDiv\").html(\"\");
			
			var result_runner = 0;
			
			if ($(\"#searchText\").val()!=\"\") {
				
				for (var i = 0; i < clientList.length; i++) {
				
					var client = clientList[i];
								
					var searchReg = new RegExp($(\"#searchText\").val(),\"gi\");
								
					if (client[1].search(searchReg)>-1 && $(\"#rpToClientID_\"+client[0]).val()!=client[0]) {
								
						if (result_runner<3) {
							$(\"#rpClientSearchResultDiv\").append(\"<input type='hidden' id='rpClientName_\"+client[0]+\"' name='rpClientName_\"+client[0]+\"' value='\"+client[1]+\"'>&nbsp;&nbsp;&nbsp;<a href='javascript:addRecipient(\"+client[0]+\");' onmouseover='showInfo();' onmouseout='hideInfo();'>\"+client[1]+\"</a>\");
						}
				
						result_runner += 1;
				
					}
				
				}
				
			}
			
		}
		
		function removeMessageRecipient(id) {

			$(\"#rpToClientID_\"+id).val(0);
			$(\"#rpToClientDiv_\"+id).fadeOut(300);
			$(\"#rpToClientID_\"+id).remove();

		}
		
		function removeMessageFile(id) {
			
			$(\"#rpMessageFileDiv_\"+id).fadeOut(300);	
			$(\"#rpMessageFile_\"+id).val(\"\");
			$(\"#rpMessageFile_\"+id).remove();
			
		}
		
		function addUpload() {
			
			$(\"#uploadDiv\").append(\"<div id='rpMessageFileDiv_\"+rIndex+\"' name='rpMessageFileDiv_\"+rIndex+\"'><a href='javascript:removeMessageFile(\"+rIndex+\");' style='margin-top: 0px;' class='smallFormButton right'>Poista</a><input type='file' id='rpMessageFile_\"+rIndex+\"' name='rpMessageFile_\"+rIndex+\"' /><div class='clear height5'></div></div>\");
						
			rIndex += 1;
			
		}
		
		function sendMessage() {
			
			$(\"#processDiv\").hide();
			
			var recipients = 0;
			
			$(\".rpToClientInput\").each(function() {

				if ($(this).val()>0) {
					recipients += 1;
				}
				
			});

			if (recipients>0) {
			
				if ($(\"#rpMessageTitle\").val()!=\"\" && $(\"#rpMessageMessage\").val()!=\"\") {
		
					$(\"#rpRecipientsDiv\").fadeOut(300);
		
					$(\"#formContent\").fadeOut(300, function() {
						
						$(\"#processDiv\").html(\"<p>Lähetetään viestiä...</p>\");
						
						$(\"#processDiv\").fadeIn(300, function() {
						
							$(\"#sendMessageForm\").submit();
						
						});
						
					});
					
				} else {notify(\"Puuttuvia kohtia lomakkeessa.\");}	
			
			} else {notify(\"Vastaanottajia ei löytynyt.\");}
			
		}
		
		</script>
		
		<div id=\"processDiv\"></div>
		
		<div id=\"formContent\">
		
		<label for=\"rpMessageTitle\">Viestin otsikko: *<input name=\"rpMessageTitle\" id=\"rpMessageTitle\" type=\"text\" class=\"full\" value=\"";
		
		if ($replymode) {rpGetMessage("VS: [rp(title)]", $_GET["messageid"]);}
		
		echo "\" /></label>	
		
		<label for=\"rpMessageMessage\">Viestin sisältö: *<textarea name=\"rpMessageMessage\" id=\"rpMessageMessage\" class=\"bigText\"></textarea></label>
		
		<div id=\"uploadDiv\"></div>
		
		<a href=\"javascript:sendMessage();\" class=\"formButton left\">Lähetä viesti</a>
		
		<a href=\"javascript:addUpload();\" class=\"formButton left\">Lisää tiedosto</a><div style=\"margin-top: 5px;\"> Sallitut tiedostomuodot: ".rpPrintFileTypes("[rp(type)], ", 2, false)."<br />(maks. ".intval($rpSettings->getValue("maximumFileSize")/1000000)." megatavua)</div>
		
		</div>
		
		</form>";
		
		if ($replymode) {
			
			echo "<div class=\"clear height10\"></div><hr />";
			
			rpGetMessage("<h2>[rp(title)]</h2>[rp(message)][rp(files)]", $_GET["messageid"]);
			
		}
	
	} else {echo "Et pysty lähettämään viestejä itsellesi.";}
	
} else if ($_GET["id"]=="search" && $_SESSION["clientID"]) {
	
	echo "<script>
	
		function searchMessages() {
			
			$.ajax({
				type:\"GET\",
				data: {
				rpAction:\"rpSearchMessages\",
				rpSearchString:$(\"#searchText\").val()
				},
			    url:\"_message.php\",
			    success: function(data) {
			    	if (data!=\"\") {
			    	
			    		$(\"#searchResultsDiv\").html(data);
					
			    	} else {notify(\"Viestejä ei löytynyt.\");}
			    }
			});
			
		}
	
	</script>
	
	<h2>Hae viestejä<input onmouseover=\"showInfo('Kirjoita hakusana ja paina Enter');\" onmouseout=\"hideInfo();\" type=\"text\" id=\"searchText\" name=\"searchText\" value=\"Kirjoita hakusana\" onkeypress=\"if (event.keyCode==13) {javascript:searchMessages();}\" onfocus=\"$('#searchText').val('');\" /></h2>
	
	<div class=\"clear\"></div>
	
	<div id=\"searchResultsDiv\"></div>";
	
}

include_once("engine/rp.end.php"); ?>