<?php

require_once(dirname(__FILE__)."/../backend/db.php");

class UserModel {

	private $_db;

	public function __construct() {
		$this->_db = new db();
	}

	public function isEmailTaken($email) {
		$this->_cleanupUsers();
		
		$st = $this->_db->prepare("SELECT * FROM `user` WHERE `email`=? LIMIT 1");
		$st->execute(array($email));

		$res = $st->fetch();
		if($res !== false) return true;

		return false;
	}

	public function getUserById($id) {
		$st = $this->_db->prepare("SELECT * FROM `user` WHERE `id`=? LIMIT 1");
		$st->execute(array($id));

		return $st->fetch();
	}

	public function getUserByEmail($email) {
		$st = $this->_db->prepare("SELECT * FROM `user` WHERE `email`=? LIMIT 1");
		$st->execute(array($email));

		return $st->fetch();
	}

	public function getUserByUsername($username) {
		$st = $this->_db->prepare("SELECT * FROM `user` WHERE `username`=? LIMIT 1");
		$st->execute(array($username));

		return $st->fetch();
	}

	public function isUsernameTaken($username) {
		if($this->getUserByUsername($username) === false) return false;

		return true;
	}

	public function createPendingUser($email,$password = null) {
		$key = uniqid();
		if(isset($password)) {
			$pwdhash = crypt($password);
			$st = $this->_db->prepare("INSERT INTO `user` SET `email`=?, `passwordhash`=?, `username`=?, `active`=0, `activationkey`=?");
			$st->execute(array($email,$pwdhash,$key,$key));
		} else {
			$st = $this->_db->prepare("INSERT INTO `user` SET `email`=?, `active`=0, `username`=?, `activationkey`=?");
			$st->execute(array($email,$key,$key));
		}
		return $key;
	}

	public function activateUser($id,$username = null,$password = null) {
		if(isset($password) && isset($username)) {
			$pwdhash = crypt($password);
			$st = $this->_db->prepare("UPDATE `user` SET `active`=1, `activationkey`='', `username`=?, `passwordhash`=? WHERE `id`=? LIMIT 1");
			$st->execute(array($username,$pwdhash,$id));
		} else {
			$st = $this->_db->prepare("UPDATE `user` SET `active`=1, `activationkey`='' WHERE `id`=? LIMIT 1");
			$st->execute(array($id));
		}
	}
	
	public function getUserByActivationKey($key) {
		$this->_cleanupUsers();
		
		$st = $this->_db->prepare("SELECT * FROM `user` WHERE `active`=0 AND `activationkey`=? LIMIT 1");
		$st->execute(array($key));
		
		return $st->fetch();
	}

	public function getUserByApiKey($key) {
		$this->_cleanupUsers();
		
		$st = $this->_db->prepare("SELECT * FROM `user` WHERE `active`=1 AND `apikey`=? LIMIT 1");
		$st->execute(array($key));
		
		return $st->fetch();
	}	

	private function _cleanupUsers() {
		$st = $this->_db->prepare("DELETE FROM `user` WHERE `active`=0 AND `timestamp_created` < NOW() - INTERVAL 24 HOUR");
		$st->execute();
	}

}
