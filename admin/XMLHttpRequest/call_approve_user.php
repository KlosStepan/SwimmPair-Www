<?php
require __DIR__ . '/../../start.php';

session_start();
Auth::requireRole(UserRights::SuperUser);

if (!isset($_GET['id'])) {
	throw new RuntimeException();
}
$id = Sanitizer::getGetInt('id');
echo $i;
$id = $_GET['id'];

if($usersManager->SetApprovedForUser($id))
{
	echo "Success, committed";
}
else
{
	echo "Error while committing";
}
/*try {
	$statement = $mysqli->prepare("UPDATE `sp_users` SET `approved` = '1' WHERE `sp_users`.`id` = ?;");
	$statement->bind_param('i', $id );
	$statement->execute();
	$mysqli->commit();
	echo "Success, commited";
}
catch(RuntimeException $e){
	echo $e->getMessage();
	$mysqli->rollback();
	echo "RuntimeException(), rollback";
}*/
//echo "update success";