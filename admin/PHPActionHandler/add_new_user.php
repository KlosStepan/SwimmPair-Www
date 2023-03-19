<?php
require __DIR__ . '/../../start.php';
//Session and Authentication
session_start();
Auth::requireRole(UserRights::SuperUser);
//User data prep - HTTP POST
$first_name = Sanitizer::getPostString('first_name');
$last_name = Sanitizer::getPostString('last_name');
$email = Sanitizer::getPostString('email');
$affiliation_club_id = Sanitizer::getPostInt('affiliation_club_id');
$referee_rank_id = Sanitizer::getPostInt('referee_rank_id'); // &insert later on
$rights = Sanitizer::getPostInt('rights');
$password = Sanitizer::getPostString('password'); //send them this
//Redirect address
$admin = "http://" . $_SERVER['SERVER_NAME'] . "/admin";
$redDestURL = "Location: $admin/editovat_profily.php";

//Create User or throw 
if ($usersManager->IsEmailPresentAlready($email)) {
	throw new RuntimeException('Email exists - UsersManager::IsEmailPresentAlready');
} else {
	if ($usersManager->RegisterUser($first_name, $last_name, $email, $password, $rights, $referee_rank_id, $affiliation_club_id)) { //No database operation in this call
		if ($usersManager->EmailNewPersonRegistered($email, $password)) {
			echo "succ<br/>\r\n";
			header($redDestURL);
		} else {
			echo "succ w/o mail<br/>\r\n";
			header($redDestURL);
		}
	} else {
		echo "err<br/>\r\n";
		throw new RuntimeException('Registration error - UsersManager::RegisterUser');
	}
}