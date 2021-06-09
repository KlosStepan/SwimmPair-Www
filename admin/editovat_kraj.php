<?php
require __DIR__ . '/../start.php';

session_start();
Auth::requireRole(UserRights::SuperUser);

$regionID = Sanitizer::getGetInt('id');
$region = $regionsManager->GetRegionByID($regionID);
require_once('tmpl_header.php');
?>
<div id="hcontainer">
	<div id="hnavi" ><a href="javascript:history.go(-1)"><img src="img/icons/arrow-121-xxl.png"  width="36" heigt="36"></a></div>
	<div id="hinfoi">
		<h1>Editovat kraj</h1>
	</div>
</div>
<?php
    echo '<!-- arbitrary $region->Serialize() of this region for control purposes-->';
    echo "\r\n";
    echo "<!-- ".($region->Serialize())." -->\r\n";
?>
<form action="PHPActionHandler/update_region.php" method="post" autocomplete="off">
	<!--hidden id-->
	<input name="id" type="hidden" value="<?= h($region->id); ?>">
	<div class="field-wrap-in-hundred"><div class="field-wrap-in-twenty-five"><h2>Jméno</h2></div><div class="field-wrap-in-seventy"><input name="name" type="text" value="<?= h($region->name); ?>"></div></div>
	<div class="field-wrap-in-hundred"><div class="field-wrap-in-twenty-five"><h2>Zkratka</h2></div><div class="field-wrap-in-seventy"><input name="abbreviation" type="text" value="<?= h($region->abbreviation); ?>"></div></div>
	<div class="center-fifty-wrap">
		<div class="field-wrap-in-center">
			<button type="submit" class="button-style">Aktualizovat</button>
		</div>
	</div>
</form>
<?php
require_once('tmpl_footer.php');
?>