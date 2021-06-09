<?php
//require '../db.php';
//$call = new functions($mysqli);
require __DIR__ . '/../../start.php';
//require __DIR__ . '/../../model/PostsManager.php';
//$postsManager = new PostsManager($mysqli);

if (!isset($_GET['id'], $_GET['title'], $_GET["article"])) {
    throw new RuntimeException();
}

//sanitize shit
$id = Sanitizer::getGetInt('id');
$title = Sanitizer::getGetString('title');
$article = Sanitizer::getGetString("article");

$result = $postsManager->UpdatePost($id, $title, $article);
if($result==true)
{
	echo "Success, commited";
}
else
{
	echo "RuntimeException(), rollback";
}
//TODO delete old XHR
/*
$mysqli->begin_transaction();
try {
	$statement = $mysqli->prepare("UPDATE `posts` SET `title` = ?, `content` = ?  WHERE `posts`.`id` = ?");
	$statement->bind_param('ssi', $title, $article, $id);
	$statement->execute();

	$mysqli->commit();
	echo "Success, commited";
}
catch(RuntimeException $e){
	echo $e->getMessage();
	$mysqli->rollback();
	echo "RuntimeException(), rollback";
}
*/