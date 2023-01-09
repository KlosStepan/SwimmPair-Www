<?php
require __DIR__ . '/../../start.php';

$cupId = Sanitizer::getPostInt('cupid');
$userId = Sanitizer::getPostInt('userid');

//PROC SetAvailabilityRegister
//INSERT INTO `sp_user_cup_availability` (`id`, `cup_id`, `user_id`, `attendance_flag`)
//VALUES (NULL, FArg_cupID, FArg_userID, 1) // <- 7, 21
if($usersManager->SetAvailabilityRegister($userId, $cupId))
{
	echo "succ<br/>\r\n";
}
else
{
	echo "err<br/>\r\n";
}