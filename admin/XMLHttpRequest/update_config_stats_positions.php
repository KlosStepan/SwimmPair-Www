<?php
require __DIR__ . '/../../start.php';

session_start();
Auth::requireRole(UserRights::SuperUser);
$json = Sanitizer::getPostString('json');

$json_processed = json_decode($json, true);
if (json_last_error()!==JSON_ERROR_NONE){
	echo "hahaha";
	echo $json;
	echo "RuntimeException(), no commit";
	throw new RuntimeException();
}

$result = $positionsManager->UpdateStatsPositions($json_processed);
if($result==true)
{
	echo "Success, commited";
}
else
{
	echo "RuntimeException(), rollback";
}
//DELETE FROM statsconfig