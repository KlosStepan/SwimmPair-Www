<?php
require __DIR__ . '/../../start.php';
//Session and Authentication
session_start();
Auth::requireRole(UserRights::SuperUser);
//User password prep - HTTP POST
$uid = Sanitizer::getPostString('uid');
$password = Sanitizer::getPostString('password');
//Redirect address - TODO red. to USER(?)
$admin = "http://".$_SERVER['SERVER_NAME']."/admin";
$redDestURL = "Location: $admin/profile.php";

//Update/Set and redirect or throw
if ($usersManager->IsUserWithIDPresentAlready($uid))
{
	//Set user-id -> password
	if($usersManager->SetPasswordForUser($uid, $password))
	{
		echo "succ<br/>\r\n";
		header($redDestURL);
	}
	else
	{
		echo "err<br/>\r\n";
		echo "SetPassword failed - UsersManager::SetPasswordForUser";
		throw new Exception('SetPassword failed - UsersManager::SetPasswordForUser');
	}
}
else
{
	throw new RuntimeException('No such user exists');
}