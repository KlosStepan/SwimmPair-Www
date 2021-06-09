<?php
require __DIR__ . '/../../start.php';

session_start();
Auth::requireRole(UserRights::SuperUser);

$uid = Sanitizer::getPostString('uid');
$email = Sanitizer::getPostString('email');

$backToMenu = "Location: ../editovat_profily.php";
$actionError = "Location: action_error.php";


if ($usersManager->IsUserWithIDPresentAlready($uid)) {
	//menim svuj username?
	$me = false;
	if($_SESSION['email']==$usersManager->GetUserEmailByID($uid))
	{
		$me = true;
	}
	else
	{
		$me = false;
	}
	//actual change
	if($usersManager->SetLoginEmailForUser($uid, $email))
	{
		//succ
		echo "Login Email changed";
		//update session info after change
		if($me==true)
		{
			$_SESSION['email']=$email;
		}
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