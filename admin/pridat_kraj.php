<?php
require __DIR__ . '/../start.php';

session_start();
Auth::requireRole(UserRights::SuperUser);

require_once('tmpl_header.php');
?>
<div id="hcontainer">
	<div id="hnavi" ><a href="javascript:history.go(-1)"><img src="img/icons/arrow-121-xxl.png"  width="36" heigt="36"></a></div>
	<div id="hinfoi">
		<h1>Nový kraj</h1>
	</div>
</div>
<form action="PHPActionHandler/add_new_region.php" method="post" autocomplete="off">
	<div class="field-wrap-in-hundred">
		<div class="field-wrap-in-twenty-five"><h2>Jméno</h2></div>
		<div class="field-wrap-in-seventy"><input class="fiftyPercent" name="name" type="text"></div>
	</div>
	<div class="field-wrap-in-hundred">
		<div class="field-wrap-in-twenty-five"><h2>Zkratka</h2></div>
		<div class="field-wrap-in-seventy"><input class="fiftyPercent" name="abbreviation" type="text"></div>
	</div>
	<div class="center-fifty-wrap">
		<div class="field-wrap-in-center">
			<button type="submit" class="button-style">Přidat</button>
		</div>
	</div>
</form>
<?php
require_once('tmpl_footer.php');
?>