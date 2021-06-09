<?php
require __DIR__ . '/../../start.php';

session_start();
Auth::requireRole(UserRights::SuperUser);

//$redDestURL = "Location: http://www.google.com/";
/*Register a user from administration by Lukas*/

//sanitize POST information
$uid = Sanitizer::getPostString('uid');
$password = Sanitizer::getPostString('password');

$backToMenu = "Location: ../editovat_profily.php";
$actionError = "Location: action_error.php";


if ($usersManager->IsUserWithIDPresentAlready($uid)) {
	if($usersManager->SetPasswordForUser($uid, $password))
	{
		//succ
		echo "Password changed";
		echo "<script>window.history.back();</script>";
	}
	else
	{
		//error
		echo "Action Error";
		header($actionError);
	}
}
else
{
	throw new RuntimeException('No such user exists');
}