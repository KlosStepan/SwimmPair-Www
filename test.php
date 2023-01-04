<?php
require 'start.php';

$start = microtime(true);
$y = $usersManager->FindAllActiveUsersOrderByLastNameAsc();
//print_r($y)
$total = microtime(true) - $start;
echo $total;
?>