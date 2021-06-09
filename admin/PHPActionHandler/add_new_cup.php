<?php
require __DIR__ . '/../../start.php';

session_start();
Auth::requireRole(UserRights::VedouciKlubu);

$redDestURL = "Location: profile.php";

//sanitize POST information
$name = Sanitizer::getPostString('name');
$time_start = Sanitizer::getPostString('time_start');
$time_end = Sanitizer::getPostString('time_end');
$description = Sanitizer::getPostString('descr_zavodu');
$organizer_club_id = Sanitizer::getPostInt('organizer_club_id');

//person ID
echo "person_id: ".$_SESSION['id'];
$clubID = $_SESSION['affiliation_club_id'];
echo "club_id: ".$clubID;
//$usersManager = getClubNameByAffiliationId($clubID)
//$usersManager = getClubAbbreviationByAffiliationId($clubID)
$clubAbbrev = $usersManager->GetClubAbbreviationByAffiliationID($clubID);

//TODO if returned number is not null

if ($cupsManager->InsertNewCup($name, $time_start, $time_end, $organizer_club_id, $description)) {
	echo 'succ ';
	//create PSA
	$cupID = $cupsManager->GetNewCupIDByInfo($name, $time_start, $time_end);
	echo $cupID;
	if($cupID!=null)
	{
		$postsManager->InsertNewCupPSAPost($name, $cupID, $time_start, $time_end, $authorID, $clubAbbrev);
	}
	header($redDestURL);
} else {
	throw new Exception('Insert failed');
}
//echo $title . "</br>";
//echo $_POST['datum_zavodu'] . "</br>";
//echo $owner ."</br>";
//echo $description . "</br>";
