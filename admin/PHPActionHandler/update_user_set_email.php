<?php
require __DIR__ . '/../../start.php';
//Session and Authentication
session_start();
Auth::requireRole(UserRights::SuperUser);
//User email prep - HTTP POST
$uid = Sanitizer::getPostString('uid');
$email = Sanitizer::getPostString('email');
//Redirect address - TODO red. to USER(?)
$admin = "http://".$_SERVER['SERVER_NAME']."/admin";
$redDestURL = "Location: $admin/profile.php";

//Update/Set and redirect or throw
if ($usersManager->IsUserWithIDPresentAlready($uid)) {
	//If changing my own username
	$me = false;
	if($_SESSION['email']==$usersManager->GetUserEmailByID($uid))
	{
		$me = true;
	}
	else
	{
		$me = false;
	}
	//Set user-id -> email
	if($usersManager->SetLoginEmailForUser($uid, $email))
	{
		echo "succ<br/>\r\n";
		//Update SESSION if me
		if($me==true)
		{
			$_SESSION['email']=$email;
		}
		header($redDestURL);
	}
	else
	{
		echo "err<br/>\r\n";
		echo "SetLoginEmail failed - UsersManager::SetLoginEmailForUser";
		throw new Exception('SetLoginEmail failed - UsersManager::SetLoginEmailForUser');
	}
}
else
{
	throw new RuntimeException('No such user exists');
}