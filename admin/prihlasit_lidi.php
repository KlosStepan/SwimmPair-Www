<?php
require __DIR__ . '/../start.php';

session_start();
Auth::requireRole(UserRights::VedouciKlubu);

$cupID = Sanitizer::getGetInt('id');

$thisCup = $cupsManager->GetCupByID($cupID);
$teamID = $_SESSION['affiliation_club_id'];
$allTeamMates = $usersManager->FindAllTeamMembers($teamID);
$registeredTeamMates = $usersManager->FindAllRegisteredTeamMembersForTheCup($cupID, $teamID);

require_once('tmpl_header.php');
?>

<div id="hcontainer">
    <div id="hnavi" ><a href="javascript:history.go(-1)"><img src="img/icons/arrow-121-xxl.png"  width="36" heigt="36"></a></div>
    <div id="hinfoi">
        <h1><?= $thisCup->name ?></h1>
    </div>
</div>
<h2><?= $thisCup->time_start ?> </h2>
<p id="zavodID" hidden><?= $cupID ?></p>
<p style="margin-top:10px; margin-bottom:0px;">Můj klub</p>
<?php
    //echo $teamID;
    //echo $cupID;
    //var_dump($allTeamMates);
    //var_dump($registeredTeamMates);
?>
<div id="div0" ondrop="" ondragover="allowDrop(event)" style="margin-top:10px;">
	<?php foreach ($allTeamMates as $user): ?>
        <div id="<?= $user->id ?>" class="clovek" draggable="true" ondragstart="drag(event)" ondblclick=""><?= $user->first_name ?> <?= $user->last_name ?></br></div>
	<?php endforeach; ?>
</div>
<p>&nbsp;</p>
<p style="margin-top:10px; margin-bottom:0px;">Přihlášení rozhodčí</p>
<div id="pairing">
    <div id="div1" ondrop="dropAvailability(event,<?= $cupID ?>)" ondragover="allowDrop(event)" style="margin-top:10px;">
		<?php foreach ($registeredTeamMates as $user)
		{
            $comingStatus = $usersManager->RetStringComingFlag($cupID, $user->id);
            echo "<div id=\"".$cupID.",".$user->id.",".$comingStatus."\" ";
            echo "class=\"";
            if($comingStatus==1) {
                echo "clovek";
            }
            else {
                echo "clovekNG";
            }
            echo "\"";
            echo "draggable=\"false\" ondragstart=\"drag(event)\" ondblclick=\"/*destroyElement(this.id)*/\" onclick=\"availChangeHandler(this,200)\">". $user->first_name ." ".$user->last_name."</br></div>";
		}?>
    </div>
</div>
<p> </p>
<input type="button" onclick="UpdateAvailability(AvailableToJSON());" value="Aktualizovat">
<!-- <p>&nbsp;</p>
<input type="button" onclick="console.log(AvailableToJSON());" value="console.log(AvailableToJSON());"> -->

