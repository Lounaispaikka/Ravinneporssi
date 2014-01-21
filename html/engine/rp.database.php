<?php

// define the database connection class

class rpConnection {

	public $database;

	// open connection to database

	public function connect($host, $username, $password, $database) {

	$this->database = mysql_connect($host, $username, $password) or die("Cannot connect.");
	mysql_select_db($database) or die("Cannot select database.");

	}

	// close connection to database

	public function close() {
		
	if ($this->database) {
		
		mysql_close($this->database);
		
		}
		
	}

	// process query

	public function query($sql) {
		
		return mysql_query($sql, $this->database);
		
	}

}

?>