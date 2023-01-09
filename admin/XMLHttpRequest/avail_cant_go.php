<?php
require __DIR__ . '/../../start.php';

$cupId = Sanitizer::getPostInt('cupid');
$userId = Sanitizer::getPostInt('userid');

//PROC SetAvailabilityCantGo
//UPDATE `sp_user_cup_availability`
//SET `attendance_flag`=0
//WHERE `cup_id`=FArg_cupID 	// <- 7
//  AND `user_id`=FArg_userID 	// <- 21
if($usersManager->SetAvailabilityCantGo($userId, $cupId))
{
	echo "succ<br/>\r\n";
}
else
{
	echo "err<br/>\r\n";
}