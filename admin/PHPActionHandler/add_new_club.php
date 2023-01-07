<?php
require __DIR__ . '/../../start.php';
//Session and Authentication
session_start();
Auth::requireRole(UserRights::SuperUser);
//Club data prep - HTTP POST
$name = Sanitizer::getPostString('name');
$abbreviation = Sanitizer::getPostString('abbreviation');
$club_id = Sanitizer::getPostInt('club_id');
$img = Sanitizer::getPostString('img');
$affiliation_region_id = Sanitizer::getPostInt('affiliation_region_id');
//Redirect address
$admin = "http://".$_SERVER['SERVER_NAME']."/admin";
$redDestURL = "Location: $admin/profile.php";

//Insert and redirect or throw
if($clubsManager->InsertNewClub($name, $abbreviation, $club_id, $img, $affiliation_region_id))
{
	echo "succ<br/>\r\n";
	header($redDestURL);
}
else
{
	throw new Exception('Insert failer - ClubsManader::InsertNewClub');
	echo "err<br/>\r\n";
	echo "Insert failer - ClubsManader::InsertNewClub<br/>\r\n";
}