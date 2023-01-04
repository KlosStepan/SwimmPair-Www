<?php

    $host = getenv("DATABASE_HOST");
    $user = getenv("DATABASE_USER");
    $pass = getenv("DATABASE_PASS");
    $db = getenv("DATABASE_NAME");

	$mysqli = new mysqli($host, $user, $pass, $db) or die($mysqli->error);
	$mysqli->set_charset('utf8');
	
	/* Sanitization function */
	function h($string)
	{
	    return htmlspecialchars($string);
	}
	
	/* Exception handling*/
	/*error_reporting(E_ALL);
	ini_set("display_errors", 1);
	set_exception_handler(function () {
		echo "<h3 style=\"color: red;\">INVALID REQUEST</h3>";
		exit();
	});
	
	/* Objects and Managers*/
	require __DIR__ . '/model/Sanitizer.php';
	require __DIR__ . '/model/Auth.php';
	require __DIR__ . '/model/Post.php';
	require __DIR__ . '/model/PostsManager.php';
	require __DIR__ . '/model/Page.php';
	require __DIR__ . '/model/PagesManager.php';
	require __DIR__ . '/model/StatUserCnt.php';
	require __DIR__ . '/model/StatPositionCnt.php';
	require __DIR__ . '/model/RefereeRank.php';
	require __DIR__ . '/model/Region.php';
	require __DIR__ . '/model/RegionsManager.php';
	require __DIR__ . '/model/User.php';
	require __DIR__ . '/model/UsersManager.php';
	require __DIR__ . '/model/Cup.php';
	require __DIR__ . '/model/PairPositionUser.php'; //PairPositionUser $position->userId $position->posId for CupsManager.php
	require __DIR__ . '/model/CupsManager.php';
	require __DIR__ . '/model/Position.php';
	require __DIR__ . '/model/PositionsManager.php';
	require __DIR__ . '/model/Club.php';
	require __DIR__ . '/model/ClubsManager.php';
	//require __DIR__ . '/admin/profile.php';
	
	
	/* Construction of Managers w/ reference to $mysqli */
	$postsManager = new PostsManager($mysqli);
	$pagesManager = new PagesManager($mysqli);
	$usersManager = new UsersManager($mysqli);
	$cupsManager = new CupsManager($mysqli);
	$positionsManager = new PositionsManager($mysqli);
	$clubsManager = new ClubsManager($mysqli);
	$regionsManager = new RegionsManager($mysqli);
