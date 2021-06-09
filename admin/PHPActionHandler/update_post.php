<?php
require __DIR__ . '/../../start.php';

session_start();
Auth::requireRole(UserRights::SuperUser);

$id = Sanitizer::getPostInt('postID'); //postID
$title = Sanitizer::getPostString('title'); //title
$content = Sanitizer::getPostString('mytextarea'); //mytextarea
if(empty($_POST["display_flag"])) {
	$display_flag = 0;
} else {
	$display_flag = 1;
}
if(empty($_POST["signature_flag"])) {
	$signature_flag = 0;
} else {
	$signature_flag = 1;
}

echo "<p>Updating post from the frontend</p>\r\n";
echo "<table>";
echo "<tr><td>\$id : </td><td>".$id."</td>\r\n";
echo "<tr><td>\$title : </td><td>".$title."</td>\r\n";
echo "<tr><td>\$content : </td><td>".h($content)."</td>\r\n";
echo "<tr><td>&nbsp;</td><td>".$content."</td>\r\n";
echo "<tr><td>\$display_flag : </td><td>".$display_flag."</td>\r\n";
echo "<tr><td>\$signature_flag : </td><td>".$signature_flag."</td>\r\n";
echo "</table>";
echo "<p>About to call \$postsManager->UpdatePost(\$id, \$title, \$content, \$display_flag, \$signature_flag) function.</p>";

if($postsManager->UpdatePost($id, $title, $content, $display_flag, $signature_flag))
{
	//succ
	echo "Post updated";
	echo "<script>window.history.back();</script>";
}
else
{
	//error
	echo 'Action Error calling PostsManager::UpdatePost(args[])';
	echo "\r\n";
	echo "Post neaktualizovan/Post not updated";
}