<?php
require __DIR__ . '/../../start.php';

session_start();
Auth::requireRole(UserRights::SuperUser);

$redDestURL = "Location: http://www.google.com/";
/*Register a user from administration by Lukas*/

//sanitize POST information
$first_name = Sanitizer::getPostString('first_name');
$last_name = Sanitizer::getPostString('last_name');
$email = Sanitizer::getPostString('email');
$affiliation_club_id = Sanitizer::getPostInt('affiliation_club_id');
$referee_rank_id = Sanitizer::getPostInt('referee_rank_id'); // &insert later on
$rights = Sanitizer::getPostInt('rights');
$password = Sanitizer::getPostString('password'); //send them this

//Creating here
if ($usersManager->IsEmailPresentAlready($email)) {
	throw new RuntimeException('User with this email exists');
} else {
	if ($usersManager->RegisterUser($first_name, $last_name, $email, $password, $rights, $referee_rank_id, $affiliation_club_id)) {
		if ($usersManager->EmailNewPersonRegistered($email, $password)) {
			header($redDestURL);
		} else {
			echo "Registered, mail not sent";
			header($redDestURL);
		}
	} else {
		throw new RuntimeException('Registration error');
	}
}