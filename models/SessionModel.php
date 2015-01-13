<?php

require_once(dirname(__FILE__)."/../backend/db.php");
require_once(dirname(__FILE__) . "/UserModel.php");

session_start();

class sessionModel {

	private $_user;
	private $_userModel;

	public function __construct() {
		$this->_userModel = new userModel();

		if(!isset($_SESSION['csrftoken']) || $_SESSION['csrftimeout'] < time()) {
			$_SESSION['csrftoken'] = uniqid();
			$_SESSION['csrftimeout'] = time() + 300;
		}
	}

	public function getCSRFToken() {
		return $_SESSION['csrftoken'];
	}

	public function isLoggedIn() {
		return isset($_SESSION['uid']);
	}

	public function loginUser($username,$password) {
		$user = $this->_userModel->getUserByUsername($username);
		if($user === false || crypt($password,$user['passwordhash']) != $user['passwordhash']) {
			return false;
		}

		$this->_user = $user;
		$_SESSION['uid'] = $user['id'];

		return true;
	}

	public function logoutUser() {
		$_SESSION['uid'] = null;
		unset($_SESSION['uid']);
	}

	public function getCurrentUserId() {
		return $_SESSION['uid'];
	}

}
