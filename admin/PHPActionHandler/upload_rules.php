<?php
require __DIR__ . '/../../start.php';

session_start();

$targetfolder = "/../../pravidla/";

$targetfolder = $targetfolder . basename( $_FILES['file']['name']) ;
echo $targetfolder."\r\n";
if(move_uploaded_file($_FILES['file']['tmp_name'], $targetfolder))

{

	echo "The file ". basename( $_FILES['file']['name']). " is uploaded";

}

else {

	echo "Problem uploading file";

}
