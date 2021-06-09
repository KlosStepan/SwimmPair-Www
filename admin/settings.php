<?php
require __DIR__ . '/../start.php';

session_start();
Auth::requireRole(UserRights::SuperUser);

//Sanitizer
$profileID = $_SESSION['id'];
require_once('tmpl_header.php');
?>
<div id="hcontainer">
	<div id="hnavi" ><a href="javascript:history.go(-1)"><img src="img/icons/arrow-121-xxl.png"  width="36" heigt="36"></a></div>
	<div id="hinfoi">
		<h1>Nastavení profilu</h1>
	</div>
</div>
<!--<p>&nbsp;</p>-->
<h2>Změnit si heslo</h2>
<form action="PHPActionHandler/set_password_for_user.php" method="post" autocomplete="off">
	<input type="hidden" name="uid" value="<?= h($profileID); ?>">
	<div class="center-fifty-wrap">
		<div class="field-wrap-in-center">
			<input class="fiftyPercent" name="password">
		</div>
	</div>
	<!-- <button type="submit" class="button-submit-in">Změnit</button> -->
	<div class="center-fifty-wrap">
		<div class="field-wrap-in-center">
			<button type="submit" class="button-style">Nastavit heslo</button>
		</div>
	</div>
</form>
<?php
require_once('tmpl_footer.php');
?>