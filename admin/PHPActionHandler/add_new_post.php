<?php
require __DIR__ . '/../../start.php';
//Session and Authentication
session_start();
Auth::requireRole(UserRights::SuperUser);
//Post data prep - HTTP POST
$title = Sanitizer::getPostString('title');
$content = Sanitizer::getPostString('mytextarea');
$display_flag = 0;
$author = Sanitizer::getPostInt('author');
$signature_flag = 0;
//Redirect address
$admin = "http://" . $_SERVER['SERVER_NAME'] . "/admin";
$redDestURL = "Location: $admin/editovat_aktuality.php";
//Debug info
echo "<p>Inserting post from the frontend</p>\r\n";
echo "<table>";
echo "<tr><td>\$title: </td><td>" . $title . "</td>\r\n";
echo "<tr><td>\$content: </td><td>" . h($content) . "</td>\r\n";
echo "<tr><td>&nbsp;</td><td>" . $content . "</td>\r\n";
echo "<tr><td>\$author: </td><td>" . $author . "</td>\r\n";
//Parameters read/prep
if (isset($_POST['display_flag'])) {
	if ($_POST['display_flag'] == "checked") {
		$display_flag = 1;
		echo "<tr><td>\$display_flag: </td><td>displayed-</td>\r\n";
	}
} else {
	echo "<tr><td>\$display_flag: </td><td>-not displayed-</td>\r\n";
}
if (isset($_POST['signature_flag'])) {
	if ($_POST['signature_flag'] == "checked") {
		$signature_flag = 1;
		echo "<tr><td>\$signature_flag: </td><td>-signed-</td>\r\n";
	}
} else {
	echo "<tr><td>\$signature_flag: </td><td>-not signed-</td>\r\n";
}
echo "</table>";
echo "<p>About to call \$postsManager->InsertNewPost(\$title, \$content, \$display_flag, \$author, \$signature_flag) function.</p>";

//Insert and redirect or throw
if ($postsManager->InsertNewPost($title, $content, $display_flag, $author, $signature_flag)) {
	echo "succ<br/>\r\n";
	header($redDestURL);
} else {
	echo "err<br/>\r\n";
	echo ("Insert failed - PostsManager::InsertNewPost<br/>\r\n");
	throw new Exception('Insert failed - PostsManager::InsertNewPost');
}