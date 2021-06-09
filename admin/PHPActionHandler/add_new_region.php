<?php
require __DIR__ . '/../../start.php';

session_start();
Auth::requireRole(UserRights::SuperUser);

$name = Sanitizer::getPostString('name');
$abbreviation = Sanitizer::getPostString('abbreviation');

$actionError = "Location: action_error.php";
//try insert
if($regionsManager->InsertNewRegion($name, $abbreviation))
{
	//succ
	echo "New Region created";
	echo "<script>window.history.back();</script>";
}
else
{
	//error
	echo "Action Error";
	header($actionError);
}