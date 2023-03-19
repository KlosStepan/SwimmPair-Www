<?php
require __DIR__ . '/../../start.php';
//Session and Authentication
session_start();
Auth::requireRole(UserRights::VedouciKlubu);
//Cup data prep - HTTP POST
$name = Sanitizer::getPostString('name');
$time_start = Sanitizer::getPostString('time_start');
$time_end = Sanitizer::getPostString('time_end');
$organizer_club_id = Sanitizer::getPostInt('organizer_club_id');
$description = Sanitizer::getPostString('descr_zavodu');
//Redirect address
$admin = "http://" . $_SERVER['SERVER_NAME'] . "/admin";
$redDestURL = "Location: $admin/profile.php";
//User id - SESSION
echo "person_id: " . $_SESSION['id'];
//Club id - SESSION and pull abbrevation
$clubID = $_SESSION['affiliation_club_id'];
echo "club_id: " . $clubID;
$clubAbbrev = $usersManager->GetClubAbbreviationByAffiliationID($clubID);
//Debug info
echo ("<p>" . $name . "/" . $time_start . "/" . $time_end . "/" . $organizer_club_id . "/" . $description . "</p><br/>\r\n");

//Insert and redirect or throw
if ($cupsManager->InsertNewCup($name, $time_start, $time_end, $organizer_club_id, $description)) {
	echo "succ<br/>\r\n";
	//Create PSA - w/ flag
	$cupID = $cupsManager->GetNewCupIDByInfo($name, $time_start, $time_end);
	echo $cupID;
	if ($cupID != null) {
		$postsManager->InsertNewCupPSAPost($name, $cupID, $time_start, $time_end, $authorID, $clubAbbrev);
	}
	echo "succ<br/>\r\n";
	header($redDestURL);
} else {
	echo "err<br/>\r\n";
	echo "Insert failed - CupsManager::InsertNewCup<br/>\r\n";
	throw new Exception('Insert failed - CupsManager::InsertNewCup');
}
//Some old DEBUG
//echo $title . "</br>";
//echo $_POST['datum_zavodu'] . "</br>";
//echo $owner ."</br>";
//echo $description . "</br>";
