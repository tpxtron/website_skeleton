<?php

// Index
$app->get("/", function() use($app,$viewData) {
	$viewData['page']['head']['meta_description'] = "";
	$viewData['page']['head']['meta_keywords'] = "";
	$viewData['page']['area'] = "home";
	
	$app->render("homepage/index.html.twig",$viewData);
});

// Imprint/Disclaimer
$app->get("/impressum", function() use($app,$viewData) {
	$viewData['page']['head']['title'] = "Impressum/Disclaimer";
	$app->render("homepage/impressum.html.twig",$viewData);
});

// Privacy
$app->get("/datenschutz", function() use($app,$viewData) {
	$viewData['page']['head']['title'] = "Datenschutz";
	$app->render("homepage/datenschutz.html.twig",$viewData);
});

