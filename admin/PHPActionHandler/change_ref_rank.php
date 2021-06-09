<?php
require __DIR__ . '/../../start.php';

session_start();
Auth::requireRole(UserRights::SuperUser);

$uid = Sanitizer::getPostInt('uid');
$referee_rank_id = Sanitizer::getPostInt('refRank');

$backToMenu = "Location: ../editovat_profily.php";
$actionError = "Location: action_error.php";

if ($usersManager->IsUserWithIDPresentAlready($uid))
{
	//change here for uid, refRank
	if($usersManager->SetRefereeRankForUser($uid, $referee_rank_id))
	{
		//succ
		echo "Referee Rank changed";
		echo ">>uid: ".$uid.", refrank: ".$referee_rank_id." ";
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