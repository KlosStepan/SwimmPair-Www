<?php
require '../start.php';

$clubID = Sanitizer::getGetInt('id');
$year = Sanitizer::getGetInt('year');

$stats = $usersManager->CountClubSeasonalStatistics($clubID, $year);

$_reply = "";
$_reply = "[";
$max = sizeof($stats);
for($i = 0; $i<$max;$i++)
{
	if($i!=0)
		$_reply.=",";

	$_reply .= $stats[$i]->Serialize();
}
$_reply .= "]";

echo $_reply;