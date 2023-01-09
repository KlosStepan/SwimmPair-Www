<?php
/* User login process, checks if user exists and password is correct */

// Escape email to protect against SQL injections
$email = $mysqli->escape_string($_POST['email']);
//$result = $mysqli->query("SELECT * FROM sp_users WHERE email='$email'");
$user = $usersManager->LoginCandidateToBeAuthorized($email);
echo $user;
if ($user==null)
{
	$_SESSION['message'] = "User with that email doesn't exist!";
	header("location: error.php");
}
else
{
	//echo "logged in ".$email;
	if (password_verify($_POST['password'], $user['password'])) {
		$_SESSION['id'] = $user['id'];
		$_SESSION['first_name'] = $user['first_name'];
		$_SESSION['last_name'] = $user['last_name'];
		$_SESSION['email'] = $user['email'];
		$_SESSION['active_flag'] = $user['active_flag'];
		$_SESSION['approved_flag'] = $user['approved_flag'];
		$_SESSION['rights'] = $user['rights'];
		$_SESSION['affiliation_club_id'] = $user['affiliation_club_id'];

		// This is how we'll know the user is logged in
		$_SESSION['logged_in'] = true;

		header("location: profile.php");
	}
	else
	{
		$_SESSION['message'] = "You have entered wrong password, try again!";
		header("location: error.php");
	}
}
/*if ($result->num_rows == 0) { // User doesn't exist
    $_SESSION['message'] = "User with that email doesn't exist!";
    header("location: error.php");
} else { // User exists
    $user = $result->fetch_assoc();

    if (password_verify($_POST['password'], $user['password'])) {
        $_SESSION['id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        $_SESSION['active'] = $user['active'];

        //Pridam Lukasovo schvaleni a Access rights
        $_SESSION['approved'] = $user['approved'];
        $_SESSION['rights'] = $user['rights'];
        $_SESSION['klubaffil'] = $user['klubaffil'];

        // This is how we'll know the user is logged in
        $_SESSION['logged_in'] = true;

        header("location: profile.php");
    } else {
        $_SESSION['message'] = "You have entered wrong password, try again!";
        header("location: error.php");
    }
}*/

