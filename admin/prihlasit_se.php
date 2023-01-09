<?php
require __DIR__ . '/../start.php';

session_start();
Auth::requireRole(UserRights::Rozhodci);

$cupID = Sanitizer::getGetInt('id'); //cup id this one via GET ?id=
$userID = $_SESSION['id'];

require_once('tmpl_header.php');
?>

<div id="hcontainer">
    <div id="hnavi" ><a href="javascript:history.go(-1)"><img src="img/icons/arrow-121-xxl.png" width="36" heigt="36"></a></div>
    <div id="hinfoi">
        <h1>Rozhodcování <?= $cupsManager->GetCupByID($cupID)->name; ?></h1>
    </div>
</div>
<p><?= $cupsManager->GetCupByID($cupID)->time_start; ?></p>
<?php
    if($cupsManager->IsUserAvailableForTheCup($userID, $cupID))
    {
	    //echo "<h1>MÁTE REGISTRACI NA ZÁVOD</h1>";
	    //check here again
    if($usersManager->IsComing($cupID, $userID))
    {
        echo "<h1>Máte registraci</h1>";
	    echo "<input type=\"button\" onclick=\"makeMeNotGoing(".$cupID.",".$userID.");\" value=\"NejspíšE nedorazím\">";
    }
    else
    {
        echo "<h1>Nemáte registraci</h1>";
	    echo "<input type=\"button\" onclick=\"makeMeGoing(".$cupID.",".$userID.");\" value=\"Zaregistr\">";
    }
}
else
{
	echo "<h1>Nemáte registraci</h1>";
	echo "<input type=\"button\" onclick=\"registerMeForTheCup(".$cupID.",".$userID.");\" value=\"Registrovat\">";
}
?>
<?php
require_once('tmpl_footer.php');
?>
