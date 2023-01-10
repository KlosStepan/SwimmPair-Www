<?php
require __DIR__ . '/../../start.php';
//Session and Authentication
session_start();
Auth::requireRole(UserRights::SuperUser);
//User refRank prep - HTTP POST
$uid = Sanitizer::getPostInt('uid');
$referee_rank_id = Sanitizer::getPostInt('referee_rank_id');
//Redirect address - TODO red. to USER(?)
$admin = "http://".$_SERVER['SERVER_NAME']."/admin";
$redDestURL = "Location: $admin/profile.php";

//Update/Set and redirect or throw
if ($usersManager->IsUserWithIDPresentAlready($uid))
{
	//Set user-id -> refRank
	if($usersManager->SetRefereeRankForUser($uid, $referee_rank_id))
	{
		echo "succ<br/>\r\n";
		header($redDestURL);
	}
	else
	{
		echo "err<br/>\r\n";
		echo "SetRefereeRank failed - UsersManager::SetRefereeRankForUser";
		throw new Exception('SetRefereeRank failed - UsersManager::SetRefereeRankForUser');
	}
}
else
{
	throw new RuntimeException('No such user exists');
}