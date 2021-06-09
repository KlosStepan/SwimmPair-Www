<?php
require __DIR__ . '/../../start.php';

$cupId = Sanitizer::getPostInt('cupid');
$userId = Sanitizer::getPostInt('userid');

$mysqli->begin_transaction();
try {
	//$statement = $mysqli->prepare('INSERT INTO `dostupnost` (`id`, `zavodid`, `userid`) VALUES (NULL, ?, ?)');
	//$statement->bind_param('ii', $cupId, $userId);
	//$statement->execute();

	$mysqli->commit();
	echo "Success, commited";
} catch (RuntimeException $e) {
	echo $e->getMessage();
	$mysqli->rollback();
	echo "RuntimeException(), rollback";
}