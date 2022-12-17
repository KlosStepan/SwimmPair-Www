<?php
require __DIR__ . '/../start.php';

session_start();
Auth::requireRole(UserRights::SuperUser);

$cupID = Sanitizer::getGetInt('id');
$url = $_SERVER['HTTP_HOST'];

$users = $usersManager->FindAllRegisteredUsersForTheCup($cupID);
$positions = $positionsManager->FindAllPositions();
$thisCup = $cupsManager->GetCupByID($cupID);

require_once('tmpl_header.php');
?>
<!--<h1><?= $thisCup->name ?></h1>-->
<div id="hcontainer">
    <div id="hnavi"><a href="javascript:history.go(-1)"><img src="img/icons/arrow-121-xxl.png" width="36" heigt="36"></a></div>
    <div id="hinfo">
        <h1><?= $thisCup->name ?></h1>
    </div>
    <div id="hnahledzav">
        <a href="http://<?= $url ?>/zavod.php?id=<?= $cupID ?>" target="_blank">
            <img src="img/icons/eye-3-xxl.png" width="36" heigt="36">
        </a>
    </div>
</div>
<h2><?= $thisCup->time_start ?> </h2>
<p style="font-size:12px; padding-top:10px;text-align: justify;">Pozn.: Rozhodčí na závody přiřadíte PŘETAŽENÍM.
    Dvojklikem člověka z role odeberete. Pro chytré telefony jsou dostupné mobilní apliace na <u>Android</u> i <u>iOS</u>.
    Aplikace jsou alternativou k drag'n'drop mechanizmu.</p>
<p id="zavodID" hidden><?= $cupID ?></p>
<p id="currPairingLoadHash" hidden><?= $cupsManager->GetPairingHashForThisCup($cupID) ?></p>
<h2>Dostupní rozhodčí</h2>
<!-- DOSTUPNOST frame -->
<div id="div0" ondrop="" ondragover="allowDrop(event)" style="margin-top:10px;">
    <?php foreach ($users as $user) {
		$comingStatus = $usersManager->RetStringComingFlag($cupID, $user->id);
		echo "<div id=\"" . $user->id . "\"";

		echo "class=\"";
		if ($comingStatus == 1) {
			echo "clovek";
		} else {
			echo "clovekNG";
		}
		echo "\"";

		echo "draggable=\"";
		if ($comingStatus == 1) {
			echo "true";
		} else {
			echo "false";
		}
		echo "\"";

		echo "ondragstart=\"drag(event)\" ondblclick=\"\">" . $user->first_name . " " . $user->last_name . "</br></div>";
	} ?>
</div>
<p>&nbsp;</p>
<!-- <hr> -->
<div id="pairing">
    <?php foreach ($positions as $position) {
		echo "<p style=\"margin:0;\">" . $position->name . "</p>";
		echo "<div id=\"div" . $position->id . "\" ondrop=\"drop(event," . $position->id . ")\" ondragover=\"allowDrop(event)\">";
		$pos_users_this_iteration = [];
		$pos_users_this_iteration = $usersManager->FindPairedUsersOnCupForPosition($cupID, $position->id);
		foreach ($pos_users_this_iteration as $user) {
			$comingStatus = $usersManager->RetStringComingFlag($cupID, $user->id);
			echo "<div id=\"" . $position->id . "," . $user->id . "\"";
			echo "class=\"";
			if ($comingStatus == 1) {
				echo "clovek";
			} else {
				echo "clovekNG";
			}
			echo "\"";
			echo "draggable=\"false\" ondragstart=\"drag(event)\" ondblclick=\"destroyElement(this.id);\">" . $user->first_name . " " . $user->last_name . "</div>";
		}
		echo "</div>";
	} ?>
</div>
<div id="out"></div>
<p>&nbsp;</p>
<input type="button" onclick="UpdatePairing(ParseSerializePairingDOM());" value="Aktualizovat">
<!--<p>&nbsp;</p>
<input type="button" onclick="vypis();" value="print JSON">-->
<?php
require_once('tmpl_footer.php');
?>