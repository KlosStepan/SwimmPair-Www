<?php
require __DIR__ . '/../../start.php';

$id = Sanitizer::getPostInt('id');
$json = Sanitizer::getPostString('json');
//echo ($id);
//echo ($json);
$json_processed = json_decode($json, true);
if (json_last_error()!==JSON_ERROR_NONE)
{
	echo "RuntimeException(), no commit";
	throw new RuntimeException("Decode json error - failed");
}

$mysqli->begin_transaction();
try {
	//$statement = $mysqli->prepare('DELETE FROM `sp_user_cup_availability` WHERE cup_id=?');
	//$statement->bind_param('i', $id);
	//$statement->execute();
	if(!($cupsManager->DeleteOldAvailability($id)))
	{
		echo "Delete old avail. - failed";
		throw new RuntimeException("Delete old avail. - failed");
	}
	foreach ($json_processed as $record)
	{
		if(!isset($record["idcup"], $record["iduser"], $record["coming"]))
		{
			echo "Params not set - failed";
			throw new RuntimeException("Params not set - failed");
		}
		elseif (!ctype_digit($record["idcup"]) || !ctype_digit($record["iduser"])|| !ctype_digit($record["coming"]))
		{
			echo "Params not digits - failed";
			throw new RuntimeException("Params not digits - failed");
		}
		//$statement = $mysqli->prepare('INSERT INTO `sp_user_cup_availability` (`id`, `cup_id`, `user_id`, `attendance_flag`) VALUES (NULL, ?, ?, ?)');
		//$statement->bind_param('iii', $record['idcup'], $record['iduser'], $record['coming']);
		//$statement->execute();
		if(!($cupsManager->InsertNewAvailability($record['idcup'], $record['iduser'], $record['coming'])))
		{
			echo "New avail. insert {$record['idcup']}, {$record['iduser']}, {$record['coming']} - failed";
			throw new RuntimeException("New avail. insert {$record['idcup']}, {$record['iduser']}, {$record['coming']} - failed");
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