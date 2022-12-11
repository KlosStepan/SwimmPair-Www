<?php
$host = "database";
$user = "root";
$pass = "moje_tajne_heslo";
$db = "plavani";
$mysqli = new mysqli($host, $user, $pass, $db) or die($mysqli->error);
$mysqli->set_charset('utf8');
?>