<?php
require __DIR__ . '/../../start.php';
//Session and Authentication
session_start();
Auth::requireRole(UserRights::SuperUser);
//Post data prep - HTTP POST
$id = Sanitizer::getPostInt('postID'); //postID
$title = Sanitizer::getPostString('title'); //title
$content = Sanitizer::getPostString('mytextarea'); //mytextarea
//Parameters read/prep
if (empty($_POST["display_flag"])) {
	$display_flag = 0;
} else {
	$display_flag = 1;
}
if (empty($_POST["signature_flag"])) {
	$signature_flag = 0;
} else {
	$signature_flag = 1;
}
//Redirect address
$admin = "http://" . $_SERVER['SERVER_NAME'] . "/admin";
$redDestURL = "Location: $admin/editovat_aktuality.php";
//Debug info
echo "<p>Updating post from the frontend</p>\r\n";
echo "<table>";
echo "<tr><td>\$id: </td><td>" . $id . "</td>\r\n";
echo "<tr><td>\$title: </td><td>" . $title . "</td>\r\n";
echo "<tr><td>\$content: </td><td>" . h($content) . "</td>\r\n";
echo "<tr><td>&nbsp;</td><td>" . $content . "</td>\r\n";
echo "<tr><td>\$display_flag: </td><td>" . $display_flag . "</td>\r\n";
echo "<tr><td>\$signature_flag: </td><td>" . $signature_flag . "</td>\r\n";
echo "</table>";
echo "<p>About to call \$postsManager->UpdatePost(\$id, \$title, \$content, \$display_flag, \$signature_flag) function.</p>";

//Update and redirect or throw
if ($postsManager->UpdatePost($id, $title, $content, $display_flag, $signature_flag)) {
	echo "succ<br/>\r\n";
	header($redDestURL);
} else {
	echo "err<br/>\r\n";
	echo ("Insert failed - PostsManager::UpdatePost<br/>\r\n");
	throw new Exception('Update failed - PostsManager::UpdatePost');
}