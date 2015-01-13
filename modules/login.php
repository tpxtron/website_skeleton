<?php

if($sessionModel->isLoggedIn() && preg_match("/^\/login/i",$app->request->getResourceUri())) {
	header("location:/");
	die();
}

// Signup
$app->get("/anmelden", function() use($app,$viewData) {
	$viewData['page']['head']['meta_description'] = "";
	$viewData['page']['head']['meta_keywords'] = "";
	$viewData['page']['area'] = "";
	
	$app->render("login/signup.html.twig",$viewData);
});
$app->post("/anmelden", function() use($app,$viewData,$userModel,$mailModel) {
	$viewData['page']['head']['meta_description'] = "";
	$viewData['page']['head']['meta_keywords'] = "";
	$viewData['page']['area'] = "";
	
	$error = array();
	if($userModel->isEmailTaken($_POST['signup_email'])) {
		$error["emailtaken"] = true;
	}
	if(!filter_var($_POST['signup_email'], FILTER_VALIDATE_EMAIL)) {
		$error["invalidemail"] = true;
	}
	$viewData['email'] = htmlentities($_POST['signup_email']);
	if(count($error) > 0) {
		$viewData['error'] = $error;
		$app->render("login/signup.html.twig",$viewData);
	} else {
		$key = $userModel->createPendingUser($_POST['signup_email']);
		// TODO: Set 'from' mail address
		$mailModel->sendMail('user_activation','TODO FROM',$_POST['signup_email'],array("key" => $key));
		$app->render("login/signup_step2.html.twig",$viewData);
	}
});
$app->get("/anmelden/:key", function($key) use($app,$viewData,$userModel) {
	$user = $userModel->getUserByActivationKey($key);
	if($user === false) {
		$app->render("login/signup_step2_error.html.twig",$viewData);
	} else {
		$viewData['key'] = $key;
		$app->render("login/signup_step3.html.twig",$viewData);
	}
});
$app->post("/anmelden/:key", function($key) use($app,$viewData,$userModel,$sessionModel) {
	$user = $userModel->getUserByActivationKey($key);
	if($user === false) {
		$app->render("login/signup_step2_error.html.twig",$viewData);
	} else {
		$error = array();
		if($userModel->isUsernameTaken($_POST['signup_username'])) {
			$error["usernametaken"] = true;
		}
		if(strlen(trim($_POST['signup_username'])) < 3) {
			$error["usernametooshort"] = true;
		}
		if($_POST['signup_password'] != $_POST['signup_passwordrepeat']) {
			$error["passwordsdontmatch"] = true;
		}
		if(strlen($_POST['signup_password']) < 8) {
			$error["passwordtooshort"] = true;
		}
	
		$viewData['username'] = htmlentities($_POST['signup_username']);
		$viewData['key'] = $key;
		if(count($error) > 0) {
			$viewData['error'] = $error;
			$app->render("login/signup_step3.html.twig",$viewData);
		} else {
			$userModel->activateUser($user['id'],$_POST['signup_username'],$_POST['signup_password']);
			$sessionModel->loginUser($_POST['signup_username'],$_POST['signup_password']);
			$app->render("login/signup_step4.html.twig",$viewData);
		}
	}
});

// Login
$app->get("/login", function() use($app,$viewData,$sessionModel) {
	$viewData['page']['head']['title'] = "Login";
	$app->render("login/login.html.twig",$viewData);
});

// Login POST
$app->post("/login", function() use($app,$viewData,$sessionModel,$userModel) {
	$viewData['page']['head']['title'] = "Login";
	if(!isset($_POST['username']) || !isset($_POST['password']) || !$sessionModel->loginUser($_POST['username'],$_POST['password'])) {
		$viewData['error'] = true;
		$viewData['username'] = $_POST['username'];
		$app->render("login/login.html.twig",$viewData);
	} else {
		$app->redirect("/dashboard");
	}
});

// Forgot password
$app->get("/login/vergessen", function() use($app,$viewData,$sessionModel) {
	$viewData['page']['head']['title'] = "Passwort vergessen";
	$app->render("login/forgotpwd.html.twig",$viewData);
});

// Passwort vergessen: Token
$app->post("/login/vergessen", function() use($app,$viewData,$sessionModel,$userModel,$mailModel) {
	$viewData['page']['head']['title'] = "Passwort vergessen";
	if(isset($_POST['email']) && $userModel->getUserByEmail($_POST['email']) != false) {
		$token = $userModel->setPasswordResetToken($_POST['email']);
		$mailModel->sendResetPasswordMail($_POST['email'],$token);
	}
	$app->render("login/forgotpwd_success.html.twig",$viewData);
});

$app->get("/login/vergessen/:token", function($token) use($app,$viewData,$sessionModel,$userModel) {
	$viewData['page']['head']['title'] = "Passwort vergessen";
	$user = $userModel->getUserByPasswordResetToken($token);
	if($user != false) {
		$app->render("login/forgotpwd_reset_form.html.twig",$viewData);
	} else {
		$app->render("login/forgotpwd_error.html.twig",$viewData);
	}
});
$app->post("/login/vergessen/:token", function($token) use($app,$viewData,$sessionModel,$userModel) {
	$viewData['page']['head']['title'] = "Passwort vergessen";
	$user = $userModel->getUserByPasswordResetToken($token);
	if($user != false) {
		$error = array();
		if(strlen(trim($_POST['password'])) < 6) { $error['tooshort'] = true; }
		if($_POST['password'] != $_POST['passwordrepeat']) { $error['dontmatch'] = true; }

		if(count($error) > 0) {
			$viewData['error'] = $error;
			$app->render("login/forgotpwd_reset_form.html.twig",$viewData);
		} else {
			$loggedinUser = $userModel->setNewPassword($user['user'],$_POST['password']);
			$sessionModel->loginUser($loggedinUser['email'],$_POST['password']);
			$app->render("login/forgotpwd_reset_success.html.twig",$viewData);
		}
	} else {
		$app->render("login/reset_password_error.html.twig",$viewData);
	}
});

// Logout
$app->get("/logout", function() use($app,$viewData,$sessionModel) {
	$sessionModel->logoutUser();
	$app->redirect("/");
});
