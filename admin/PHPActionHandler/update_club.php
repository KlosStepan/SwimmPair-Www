<?php
require __DIR__ . '/../../start.php';
//Session and Authentication
session_start();
Auth::requireRole(UserRights::SuperUser);
//Club prep - HTTP POST
$id = Sanitizer::getPostInt('id');
$name = Sanitizer::getPostString('name');
$abbreviation = Sanitizer::getPostString('abbreviation');
$code = Sanitizer::getPostInt('code');
$img = Sanitizer::getPostString('img');
$affiliation_region_id = Sanitizer::getPostInt('affiliation_region_id');
//Redirect address
$admin = "http://".$_SERVER['SERVER_NAME']."/admin";
$redDestURL = "Location: $admin/profile.php";

//Update and redirect or throw
if($clubsManager->UpdateClub($id, $name, $abbreviation, $code, $img, $affiliation_region_id))
{
	echo "succ<br/>\r\n";
	header($redDestURL);
}
else
{
	echo "err<br/>\r\n";
	echo "Update failed - ClubsManager::UpdateClub";
	throw new Exception('Update failed - ClubsManager::UpdateClub');
}