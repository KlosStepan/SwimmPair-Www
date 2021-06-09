<?php
require __DIR__ . '/../../start.php';
//TODO cup under my jurisdiction
session_start();
Auth::requireRole(UserRights::VedouciKlubu);

$id = Sanitizer::getPostInt('id');
$json = Sanitizer::getPostString('json');
$hash = Sanitizer::getPostString('hash');

$json_processed = json_decode($json, true);
if (json_last_error()!==JSON_ERROR_NONE){
	echo "RuntimeException(), no commit";
    throw new RuntimeException();
}
/*TODO rewrite both $statement bla bla stuff to single purpose functions*/
$mysqli->begin_transaction();
try {
	//hash for cup same as when page loaded
	if($hash==$cupsManager->GetPairingHashForThisCup($id))
	{
		/*deleting of old pairing*/
		$statement = $mysqli->prepare('DELETE FROM `pozicerozhodci` WHERE idzav=?');
		$statement->bind_param('i', $id);
		$statement->execute();

		/*loop new pairing*/
		foreach ($json_processed as $record) {
			if (!isset($record["idpoz"], $record["iduser"]))
			{
				throw  new RuntimeException();
			}
			elseif (!ctype_digit($record["idpoz"]) || !ctype_digit($record["iduser"]))
			{
				throw new RuntimeException();
			}
			/*insert the new pair*/
			$statement = $mysqli->prepare('INSERT INTO `pozicerozhodci` (`id`, `idzav`, `idpoz`, `iduser`) VALUES (NULL, ? , ? , ?)');
			$statement->bind_param('iii', $id, $record["idpoz"], $record["iduser"]);
			$statement->execute();
		}
		/*succ, commit transaction*/
		$mysqli->commit();
		echo "Success, commited";
	}
	else
	{
		throw new RuntimException("Consistency mismatch");
	}
}
catch (RuntimeException $e)
{
	echo $e->getMessage();
	$mysqli->rollback();
	echo "RuntimeException(), rollback";
}