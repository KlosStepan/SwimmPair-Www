<?php
require __DIR__ . '/../../start.php';

$id = Sanitizer::getPostInt('id');
$json = Sanitizer::getPostString('json');

$json_processed = json_decode($json, true);
if (json_last_error()!==JSON_ERROR_NONE){
	echo "RuntimeException(), no commit";
	throw new RuntimeException();
}

$mysqli->begin_transaction();
try {
	//drop all availabilities
	$statement = $mysqli->prepare('DELETE FROM `dostupnost` WHERE zavodid=?');
	$statement->bind_param('i', $id);
	$statement->execute();

	foreach ($json_processed as $record){
		if(!isset($record["idcup"], $record["iduser"], $record["coming"])) {
			throw new RuntimeException();
		} elseif (!ctype_digit($record["idcup"]) || !ctype_digit($record["iduser"])|| !ctype_digit($record["coming"])) {
			throw new RuntimeException();
		}
		$statement = $mysqli->prepare('INSERT INTO `dostupnost` (`id`, `zavodid`, `userid`, `coming`) VALUES (NULL, ?, ?, ?)');
		$statement->bind_param('iii', $record['idcup'], $record['iduser'], $record['coming']);
		$statement->execute();
	}
	$mysqli->commit();
	echo "Success, commited";
}
catch (RuntimeException $e){
	echo $e->getMessage();
	$mysqli->rollback();
	echo "RuntimeException(), rollback";
}