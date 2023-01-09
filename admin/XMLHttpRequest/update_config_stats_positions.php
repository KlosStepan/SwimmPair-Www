<?php
require __DIR__ . '/../../start.php';

session_start();
Auth::requireRole(UserRights::SuperUser);
$json = Sanitizer::getPostString('json');

$json_processed = json_decode($json, true);
if (json_last_error()!==JSON_ERROR_NONE)
{
	echo "RuntimeException(), no commit";
	throw new RuntimeException("Decode json error - failed");
}

if($positionsManager->UpdateStatsPositions($json_processed))
{
	echo "succ<br/>\r\n";
}
else
{
	echo "err<br/>\r\n";
}