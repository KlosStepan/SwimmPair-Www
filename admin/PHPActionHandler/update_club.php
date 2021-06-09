<?php
require __DIR__ . '/../../start.php';

session_start();
Auth::requireRole(UserRights::SuperUser);

$id = Sanitizer::getPostInt('id');
$name = Sanitizer::getPostString('name');
$abbreviation = Sanitizer::getPostString('abbreviation');
$code = Sanitizer::getPostInt('code');
$img = Sanitizer::getPostString('img');

$affiliation_region_id = Sanitizer::getPostInt('affiliation_region_id');

$actionError = "Location: action_error.php";

if($clubsManager->UpdateClub($id, $name, $abbreviation, $code, $img, $affiliation_region_id))
{
	//succ
	echo "Club updated";
	echo "<script>window.history.back();</script>";
}
else
{
	//error
	echo 'Action Error calling ClubsManager::UpdateClub(args[])';
	echo "\r\n";
	echo "Klub neaktualizovan/Club not updated";
	//header($actionError);
}