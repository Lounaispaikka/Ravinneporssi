<?php

// define settings

class rpSettings {
	
	public $secret, $domain, $domainTitle, $dburl, $database, $username, $password, $publicUsername, $publicPassword;
	
	public $baseTypes, $subTypes, $visibilityTypes, $contactTypes, $noticeOrderTypes, $clientOrderTypes, $noticeValuePercentages, $fieldSizePercentages, $counterNotices, $startUpLocationTypes, $ratingTypes, $dungTypes;
	
	public $clientsTable, $tradesTable, $messagesTable, $routesTable, $noticesTable, $fieldsTable, $productsTable, $contentTable, $feedbackTable, $ratingsTable, $contractsTable;
	public $clientsTableStructure, $tradesTableStructure, $messagesTableStructure, $routesTableStructure, $noticesTableStructure, $fieldsTableStructure, $productsTableStructure, $contentTableStructure, $feedbackTableStructure, $ratingsTableStructure, $contractsTableStructure;
	
	public $clientDefaultVisibility, $fieldDefaultVisibility, $noticeDefaultVisibility;
	
	public $blockInterval, $loginInterval, $contactEmail, $allowFileTypes, $allowImageTypes, $maximumFileSize, $sessionTimeOut, $defaultJPEGQuality, $maxImageSide, $thumbnailWidth, $thumbnailHeight;
	
	public $processUTF8;
	
	public function __construct () {
		
		// basic settings
		
		$this->secret = "ravinneporssi1.0";			// sala-avain
		$this->domain = "";							// esim. www.ravinneporssi.fi
		$this->domainTitle = "Ravinnepörssi";
		$this->dburl = "";							// tietokantapalvelimen osoite
		$this->database = "";						// tietokanta
		$this->username = "";						// käyttäjätunnus
		$this->password = "";						// salasana
		$this->publicUsername = "";					// käyttäjätunnus
		$this->publicPassword = "";					// käyttäjätunnus
		
		// types construct
		
		$this->baseTypes = array(
			array("output","Lannoitteen luovuttaja"),
			array("input","Lannoitteen vastaanottaja"),
			array("contractor","Urakoitsija")
		);
		
		$this->subTypes = array(
			array("organic","Luomu")
		);
		
		$this->visibilityTypes = array(
			array("all", "Kaikille"),
			array("registered", "Vain rekisteröityneille"),
			array("administrators", "Vain ylläpidolle")
		);
		
		$this->contactTypes = array(
			array("txtmsg", "Tekstiviesti"),
			array("rpmail", "Ravinnepörssin postilaatikko"),
			array("phone", "Puhelin"),
			array("email", "Sähköposti")
		);
		
		$this->startUpLocationTypes = array(
			array("gps", "Olet tässä -paikkaan"),
			array("previous", "Viimeksi selailtuun paikkaan"),
			array("home", "Kotipaikkaan")
		);
		
		$this->noticeOrderTypes = array(
			array("closest", "Lähin ensin"),
			array("furthest", "Kauimmaisin ensin"),
			array("newest", "Uusin ensin"),
			array("oldest", "Vanhin ensin"),
			array("title_ascending", "Otsikon mukaan nouseva"),
			array("title_descending", "Otsikon mukaan laskeva"),
			array("value_ascending", "Määrän mukaan nouseva"),
			array("value_descending", "Määrän mukaan laskeva"),		
		);
		
		$this->clientOrderTypes = array(
			array("closest", "Lähin ensin"),
			array("furthest", "Kauimmaisin ensin"),
			array("newest", "Uusin ensin"),
			array("oldest", "Vanhin ensin"),
			array("name_ascending", "Nimen mukaan nouseva"),
			array("name_descending", "Nimen mukaan laskeva"),	
		);
		
		$this->noticeValuePercentages = array(
			array(0,50),
			array(500,60),
			array(1000,70),
			array(2000,80),
			array(5000,90),
			array(10000,100)	
		);
		
		$this->fieldSizePercentages = array(
			array(0,50),
			array(100000,60),
			array(500000,70),
			array(1000000,80),
			array(2000000,90),
			array(5000000,100)	
		);
		
		$this->ratingTypes = array(
			array(-100, "Negatiivinen"),
			array(0, "Neutraali"),
			array(100, "Positiivinen")
		);
		
		$this->dungTypes = array(
			array("dry_dung","Kuiva"),
			array("sludge_dung","Liete"),
			array("composted_dung","Kompostoitu"),
			array("other_dung","Muu käsittely"),
		);
		
		$this->counterNotices = array(
			array("input,output", "contractor", "Lähialueen urakointipalvelut")
		);
		
		$this->clientDefaultVisibility = "all";
		$this->fieldDefaultVisibility = "administrators";
		$this->noticeDefaultVisibility = "registered";
		
		// database construct
		
		$this->clientsTable = "rp_clients";
		$this->tradesTable = "rp_trades";
		$this->messagesTable = "rp_messages";
		$this->routesTable = "rp_routes";
		$this->noticesTable = "rp_notices";
		$this->fieldsTable = "rp_fields";
		$this->productsTable = "rp_products";
		$this->contentTable = "rp_content";
		$this->feedbackTable = "rp_feedback";
		$this->ratingsTable = "rp_ratings";
		$this->contractsTable = "rp_contracts";
				
		$this->clientsTableStructure = "id,parent,admin,language,types,types2,current_latitude,current_longitude,current_zoom,current_layers,current_annotations,base_latitude,base_longitude,email,password,salt,company,bic,name,address_1,address_2,postalcode,city,municipality,state,country,phonenumber,fax,gsm,description,trades,arsenal,favourites,contact_via,images,notifier,notifier_contact,notifier_threshold,notifier_types,notifier_products,visibility,blacklist,first_login,startup_location,added_datetime,added_ip,added_userid,modified_datetime,modified_ip,modified_userid,logged_datetime,logged_ip,attempt_datetime,attempt_ip,authenticated_datetime,authenticated_type,priority,confirmed,published,deactivated";
		
		$this->tradesTableStructure = "id,parent,type,prefix,title,description,options,added_datetime,added_ip,added_userid,modified_datetime,modified_ip,modified_userid,priority,published";
		
		$this->messagesTableStructure = "id,parent,type,title,message,files,added_datetime,added_ip,added_clientid,to_clientid,seen_clientid,modified_datetime,modified_ip,modified_clientid,hide_clientid,priority";
		
		$this->routesTableStructure = "id,parent,type,title,route,distance,added_datetime,added_ip,added_clientid,modified_datetime,modified_ip,modified_clientid,priority";
		
		$this->noticesTableStructure = "id,parent,types,types2,latitude,longitude,pos_x,pos_y,value,title,description,trades,state,city,address,products,files,contact_via,visibility,publish_start,publish_end,added_datetime,added_ip,added_clientid,modified_datetime,modified_ip,modified_userid,priority,published";
		
		$this->fieldsTableStructure = "id,parent,type,latitude,longitude,pos_x,pos_y,size,polygon,title,description,visibility,added_datetime,added_ip,added_clientid,modified_datetime,modified_ip,modified_userid,priority,published";
		
		$this->productsTableStructure = "id,parent,types,prefix,title,description,options,added_datetime,added_ip,added_userid,modified_datetime,modified_ip,modified_userid,priority,published";
		
		$this->contentTableStructure = "id,parent,type,name,title,content,added_datetime,added_ip,added_userid,modified_datetime,modified_ip,modified_userid,priority,removable,published";
		
		$this->feedbackTableStructure = "id,parent,type,title,message,added_datetime,added_ip,added_clientid,modified_datetime,modified_ip,modified_clientid,priority";
		
		$this->ratingsTableStructure = "id,parent,rating,title,content,added_datetime,added_ip,added_clientid,to_clientid,modified_datetime,modified_ip,modified_userid,priority,published";
		
		$this->contractsTableStructure = "id,parent,output_name,output_address,output_phonenumber,output_email,output_companycode,output_animals,output_animal_amount,output_field_area,output_other_contracts,output_total_animal_amount,input_name,input_address,input_bic,input_phonenumber,input_email,input_dung_area,input_refinement,input_contract_time,input_products,transporter,distributor,transportation_payer,distribution_payer,remarks,place_and_time,output_signature,input_signature,extra,added_datetime,added_ip,added_clientid,to_noticeid,to_clientid,modified_datetime,modified_ip,modified_clientid,editable,priority,published";
		
		// variables
		
		$this->blockInterval = 60;
		$this->loginInterval = 10;
		$this->contactEmail = "info@ravinneporssi.fi";
		$this->allowFileTypes = "[gif][jpg][jpeg][png][pdf][bmp][doc][docx][txt]";
		$this->allowImageTypes = "[jpg][jpeg]";
		$this->maximumFileSize = "10000000";
		
		$this->sessionTimeOut = 3600;
		
		$this->defaultJPEGQuality = 90;
		
		$this->maxImageSide = 1024;
		$this->thumbnailWidth = 130;
		$this->thumbnailHeight = 100;
		
		// boolean
		
		$this->processUTF8 = true;
		
	}
		
	public function getValue ($variable) {
		
		return $this->{$variable};
		
	}
	
}

?>