<?php

if(!$sessionModel->isLoggedIn() && preg_match("/^\/dashboard/i",$app->request->getResourceUri())) {
	header("location:/");
	die();
}

$app->get("/dashboard", function() use($app,$viewData,$sessionModel,$userModel) {
	$viewData['page']['area'] = "dashboard";
	$app->render("dashboard.html.twig",$viewData);
});

