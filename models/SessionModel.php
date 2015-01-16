<?php

require_once(dirname(__FILE__)."/../backend/db.php");
require_once(dirname(__FILE__) . "/UserModel.php");

session_start();

class sessionModel {

	private $_user;
	private $_userModel;
	private $_language;

	public function __construct() {
		$this->_userModel = new userModel();

		if(!isset($_SESSION['csrftoken']) || $_SESSION['csrftimeout'] < time()) {
			$_SESSION['csrftoken'] = uniqid();
			$_SESSION['csrftimeout'] = time() + 300;
		}
		if(!isset($_SESSION['language'])) {
			// TODO: Change the default fallback language, if you'd like to :-)
//			$this->setLanguage("en_US.utf8");
			$this->setLanguage("de_DE");
		} else {
			$this->setLanguage($_SESSION['language']);
		}

	}

	public function getCSRFToken() {
		return $_SESSION['csrftoken'];
	}

	public function getLanguage() {
		return $this->_language;
	}

	public function setLanguage($language) {
		$this->_language = $language;
		$_SESSION['language'] = $language;

		setlocale(LC_ALL,$this->_language);
		bindtextdomain("messages",dirname(__FILE__).'/../locale/');
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
