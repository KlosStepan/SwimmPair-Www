<?php
require __DIR__ . '/../../start.php';

session_start();
Auth::requireRole(UserRights::SuperUser);

if (!isset($_GET['id']))
{
	throw new RuntimeException();
}
$id = Sanitizer::getGetInt('id');

//Approved flag to 1
if($usersManager->SetApprovedForUser($id))
{
	echo "succ<br/>\r\n";
}
else
{
	echo "err<br/>\r\n";
}