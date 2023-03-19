<?php
require __DIR__ . '/../../start.php';
//Redirect address
session_start();
Auth::requireRole(UserRights::SuperUser);
//Region data prep - HTTP POST
$name = Sanitizer::getPostString('name');
$abbreviation = Sanitizer::getPostString('abbreviation');
//Redirect address
$admin = "http://" . $_SERVER['SERVER_NAME'] . "/admin";
$redDestURL = "Location: $admin/profile.php";

//Insert and redirect or throw
if ($regionsManager->InsertNewRegion($name, $abbreviation)) {
	echo "succ<br/>\r\n";
	header($redDestURL);
} else {
	echo "err<br/>\r\n";
	echo "Insert failed - RegionsManager::InsertNewRegion<br/>\r\n";
	throw new Exception('Insert failed - RegionsManager::InsertNewRegion');
}