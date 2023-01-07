<?php
/* Displays user information and some useful messages */
require __DIR__ . '/../start.php';

session_start();

// Check if user is logged in using the session variable
if ($_SESSION['logged_in'] != 1)
{
    $_SESSION['message'] = "You must log in before viewing your profile page!";
    header("location: error.php");
}
else
{
    //Add unverified and unconfirmed FAIL or load administration

    $id = $_SESSION['id'];
    $first_name = $_SESSION['first_name'];
    $last_name = $_SESSION['last_name'];
    $email = $_SESSION['email'];
    $active_flag = $_SESSION['active_flag'];
    $approved_flag = $_SESSION['approved_flag'];
    $rights = $_SESSION['rights'];
	$affiliation_club_id = $_SESSION['affiliation_club_id'];

    $adminBasedOnRights = "";
    if ($rights == UserRights::SuperUser)
    {
        $adminBasedOnRights = "tmplcond2_superuser.php";
    }
    if ($rights == UserRights::VedouciKlubu)
    {
        $adminBasedOnRights = "tmplcond1_vedouciklubu.php";
    }
    if ($rights == UserRights::Rozhodci)
    {
        $adminBasedOnRights = "tmplcond0_smrtelnici.php";
    }

}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome <?= $first_name . ' ' . $last_name ?></title>
    <?php include 'css/css.html'; ?>
    <?php include 'js/js.html'; ?>
</head>
<body>
<?php
    $url = $_SERVER['HTTP_HOST'];
?>
<div class="form">
    <div id="hcontainer">
    <div id="hnavi"><a href="http://<?= $url ?>/"><img src="img/icons/swimming-xxl.png" width="48" heigt="48"></a></div>
    <h1 id="hinfo">Vítejte</h1>
    <div id="hnahledzav">
        <a href="settings.php" target="">
            <img src="img/icons/services-xxl.png" width="48" heigt="48">
        </a>
    </div>
    </div>
    <?php
        // Display message about account verification link only once
        if (isset($_SESSION['message']))
        {
            echo $_SESSION['message'];
            // Don't annoy the user with more messages upon page refresh
            unset($_SESSION['message']);
        }
    ?>
    <?php
        // Keep reminding the user this account is not active, until they activate
        if (!$active_flag)
        {
            echo
            '<div class="info">
                  Account is unverified, please confirm your email by clicking
                  on the email link!
                  </div>';
        }
    ?>
    <h2><?php echo $first_name . ' ' . $last_name; ?></h2>
    <hr style="height:1px;border:none;color:#333;background-color:white;">
    <p>&nbsp;</p>
	<?php
	    if ($rights == UserRights::SuperUser)
        {
            include("tmplcond2_superuser.php");
            include("tmplcond1_vedouciklubu.php");
    		include("tmplcond0_smrtelnici.php");
    	}
    	if ($rights == UserRights::VedouciKlubu)
        {
	    	include("tmplcond1_vedouciklubu.php");
		    include("tmplcond0_smrtelnici.php");
	    }
	    if ($rights == UserRights::Rozhodci)
        {
		    include("tmplcond0_smrtelnici.php");
	    }
	?>
    <a href="logout.php">
        <button class="button button-block" name="logout"/>
            Odhlásit se
        </button>
    </a>
</div>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src="js/index.js"></script>
</body>
</html>
