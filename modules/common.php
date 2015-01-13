<?php

require_once(dirname(__FILE__)."/../models/ExampleModel.php");
require_once(dirname(__FILE__)."/../models/MailModel.php");
require_once(dirname(__FILE__)."/../models/SessionModel.php");
require_once(dirname(__FILE__)."/../models/UserModel.php");

$exampleModel = new ExampleModel();
$mailModel = new MailModel();
$sessionModel = new SessionModel();
$userModel = new UserModel();

$viewData['session']['isLoggedin'] = $sessionModel->isLoggedIn();

// 404
$app->notFound(function() use($app,$viewData) {
	$app->render("404.html.twig",$viewData);
});
