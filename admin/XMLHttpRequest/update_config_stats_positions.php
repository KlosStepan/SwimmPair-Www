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
//DEPRECATE SOON
/*if($positionsManager->UpdateStatsPositions($json_processed))
{
	echo "succ<br/>\r\n";
}
else
{
	echo "err<br/>\r\n";
}*/
$mysqli->begin_transaction();
try
{
	if(!($positionsManager->DeleteOldStatsPositions()))
	{
		echo "Delete old stats-positions. - failed";
		throw new RuntimeException("Delete old stats-positions - failed");
	}
	foreach ($json_processed as $record)
	{
		if (!isset($record["idpoz"]))
		{
			throw  new RuntimeException();
		}
		elseif (!ctype_digit($record["idpoz"]))
		{
			throw new RuntimeException();
		}
		if(!($positionsManager->InsertNewStatPosition($record["idpoz"])))
		{
			echo "New stat insert {$record["idpoz"]}- failed";
			throw new RuntimeException("New stat insert {$record["idpoz"]} - failed");
		}
	}
	$mysqli->commit();
	echo "succ<br/>\r\n";
}
catch (RuntimeException $e)
{
	$mysqli->rollback();
	echo "err<br/>\r\n";
	echo $e->getMessage();
}