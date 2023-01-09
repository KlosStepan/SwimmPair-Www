<?php
require __DIR__ . '/../../start.php';

session_start();
Auth::requireRole(UserRights::VedouciKlubu);

$id = Sanitizer::getPostInt('id');
$json = Sanitizer::getPostString('json');
$hash = Sanitizer::getPostString('hash');

$json_processed = json_decode($json, true);
if (json_last_error()!==JSON_ERROR_NONE)
{
	echo "RuntimeException(), no commit";
    throw new RuntimeException("Decode json error - failed");
}

$mysqli->begin_transaction();
try {
	if($hash==$cupsManager->GetPairingHashForThisCup($id))
	{
		//$statement = $mysqli->prepare('DELETE FROM `sp_user_position_pairing` WHERE cup_id=?');
		//$statement->bind_param('i', $id);
		//$statement->execute();
		if(!($cupsManager->DeleteOldPairing($id)))
		{
			echo "Delete old avail. - failed";
			throw new RuntimeException("Delete old avail. - failed");
		}
		foreach ($json_processed as $record)
		{
			if (!isset($record["idpoz"], $record["iduser"]))
			{
				throw  new RuntimeException("Params not set - failed");
			}
			elseif (!ctype_digit($record["idpoz"]) || !ctype_digit($record["iduser"]))
			{
				throw new RuntimeException("Params not digits - failed");
			}
			//$statement = $mysqli->prepare('INSERT INTO `sp_user_position_pairing` (`id`, `cup_id`, `position_id`, `user_id`) VALUES (NULL, ? , ? , ?)');
			//$statement->bind_param('iii', $id, $record["idpoz"], $record["iduser"]);
			//$statement->execute();
			if(!($cupsManager->InsertNewPairing($id, $record["idpoz"], $record["iduser"])))
			{
				echo "New avail. insert {$id}, {$record["idpoz"]}, {$record["iduser"]} - failed";
				throw new RuntimeException("New avail. insert {$id}, {$record["idpoz"]}, {$record["iduser"]} - failed");
			}
		}
		$mysqli->commit();
		echo "succ<br/>\r\n";
	}
	else
	{
		throw new RuntimException("Hash consistency mismatch");
	}
}
catch (RuntimeException $e)
{
	$mysqli->rollback();
	echo "err<br/>\r\n";
	echo $e->getMessage();
}