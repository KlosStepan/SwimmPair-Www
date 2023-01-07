<?php
require __DIR__ . '/../../start.php';
//Session and Authentication
session_start();
Auth::requireRole(UserRights::SuperUser);
//Page data prep - HTTP POST
$id = Sanitizer::getPostInt('id');
$title = Sanitizer::getPostString('title');
$content = Sanitizer::getPostString('mytextarea');
//Redirect address
$admin = "http://".$_SERVER['SERVER_NAME']."/admin";
$redDestURL = "Location: $admin/profile.php";

//Update and redirect or throw
if($pagesManager->UpdatePage($id, $title, $content))
{
	echo "succ<br/>\r\n";
	header($redDestURL);
}
else
{
	echo "err<br/>\r\n";
	echo "Update failed - PagesManager::UpdatePage";
	throw new Exception('Update failed - PagesManager::UpdatePage');
}