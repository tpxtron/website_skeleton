<?php
require_once dirname(__FILE__)."/../vendor/autoload.php";

$app = new \Slim\Slim();	
$app->config(array(
	'view' => new \Slim\Views\Twig(),
	'templates.path' => dirname(__FILE__).'/../templates/',
));

$view = $app->view();
$view->parserExtensions = array(
	new \Slim\Views\TwigExtension(),
);
$view->parserOptions = array(
	'autoescape' => true,
);
$viewData = array();

require_once("../modules/common.php");
require_once("../modules/homepage.php");
require_once("../modules/login.php");
require_once("../modules/loggedin.php");

$app->run();
