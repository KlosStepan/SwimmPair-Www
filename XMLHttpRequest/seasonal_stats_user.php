<?php
require '../start.php';

$userID = Sanitizer::getGetInt('id');
$year = Sanitizer::getGetInt('year');

$personCupsCount = $usersManager->CountCupsAttendanceOfUserGivenYear($userID, $year);
$stats = $usersManager->CountOverallStatisticsOfUserGivenYear($userID, $year);

$_reply = "";

$_reply .= $personCupsCount;
$_reply .= ";";
$_reply .= "[";
$max = sizeof($stats);
for($i = 0; $i<$max;$i++)
{
	if($i!=0)
		$_reply.=",";

	$_reply .= $stats[$i]->Serialize();
}
$_reply .= "]";

echo $_reply;