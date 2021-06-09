<?php
require __DIR__ . '/../../start.php';

session_start();
$cupId = Sanitizer::getGetInt('cupid');
$userId = Sanitizer::getGetInt('userid');

$_ret = $usersManager->RetStringComingFlag($cupId, $userId);

echo $_ret;