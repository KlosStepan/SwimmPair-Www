<?php
require __DIR__ . '/../../start.php';

session_start();
Auth::requireRole(UserRights::SuperUser);

$id = Sanitizer::getPostInt('id');
$name = Sanitizer::getPostString('name');
$abbreviation = Sanitizer::getPostString('abbreviation');

$actionError = "Location: action_error.php";

if($regionsManager->UpdateRegion($id, $name, $abbreviation))
{
	//succ
	echo "Region updated";
	echo "<script>window.history.back();</script>";
}
else
{
	//error
	echo 'Action Error calling RegionsManager::UpdateRegion(args[])';
	echo "\r\n";
	echo "Kraj neaktualizovan/Region not updated";
	//header($actionError);
}