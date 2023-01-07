<?php
require __DIR__ . '/../../start.php';
//Session and Authentication
session_start();
Auth::requireRole(UserRights::SuperUser);
//Region prep - HTTP POST
$id = Sanitizer::getPostInt('id');
$name = Sanitizer::getPostString('name');
$abbreviation = Sanitizer::getPostString('abbreviation');
//Redirect address
$admin = "http://".$_SERVER['SERVER_NAME']."/admin";
$redDestURL = "Location: $admin/profile.php";

//Update and redirect or throw
if($regionsManager->UpdateRegion($id, $name, $abbreviation))
{
	echo "succ<br/>\r\n";
	header($redDestURL);
}
else
{
	echo "err<br/>\r\n";
	echo "Update failed - RegionsManager::UpdateRegion";
	throw new Exception('Update failed - RegionsManager::UpdateRegion');
}