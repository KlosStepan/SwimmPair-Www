<?php
require __DIR__ . '/../../start.php';

$cupId = Sanitizer::getPostInt('cupid');
$userId = Sanitizer::getPostInt('userid');

//UPDATE `dostupnost` SET `coming` = '1' WHERE `zavodid`=7 AND `userid`=21
if($usersManager->SetUserNotAttending($userId, $cupId))
{
	echo "Success, committed";
}
else
{
	echo "Error while setting the unattendance";
}