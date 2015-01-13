<?php

/**
 * Example Model
 * --------------
 *
 * Models should be used for handling entities and communicating with database or a mailer class for just one single purpose
 */


require_once(dirname(__FILE__)."/../backend/db.php");

class ExampleModel {

	private $_db;

	public function __construct() {
		$this->_db = new db();
	}

	public function doSomething() {
		// Actually does nothing
	}

}
