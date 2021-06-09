<?php
require __DIR__ . '/../../start.php';

session_start();
Auth::requireRole(UserRights::SuperUser);

$id = Sanitizer::getPostInt('id');
$title = Sanitizer::getPostString('title');
$content = Sanitizer::getPostString('mytextarea');

if($pagesManager->UpdatePage($id, $title, $content))
{
	//succ
	echo "Page updated";
	echo "<script>window.history.back();</script>";
}
else
{
	//error
	echo 'Action Error calling PagesManager::UpdatePage(args[])';
	echo "\r\n";
	echo "Page neaktualizovan/Page not updated";
	//header($actionError);
}