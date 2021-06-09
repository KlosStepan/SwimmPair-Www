<?php
require __DIR__ . '/../../start.php';

session_start();
Auth::requireRole(UserRights::SuperUser);

$name = Sanitizer::getPostString('name');
$abbreviation = Sanitizer::getPostString('abbreviation');
$club_id = Sanitizer::getPostInt('club_id');
$img = Sanitizer::getPostString('img');
$affiliation_region_id = Sanitizer::getPostInt('affiliation_region_id');

$action_error = "Location: action_error.php";

if($clubsManager->InsertNewClub($name, $abbreviation, $club_id, $img, $affiliation_region_id))
{
	//succ
	echo "New Club created";
	echo "<script>window.history.back();</script>";
}
else
{
	//error
	echo "Action Error";
	header($action_error);
}