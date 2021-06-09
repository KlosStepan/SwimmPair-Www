<?php
require __DIR__ . '/../start.php';

session_start();
Auth::requireRole(UserRights::SuperUser);

$clubID = Sanitizer::getGetInt('id');
$club = $clubsManager->GetClubByID($clubID);
$regions = $regionsManager->FindAllRegions();
require_once('tmpl_header.php');
?>
<div id="hcontainer">
    <div id="hnavi" ><a href="javascript:history.go(-1)"><img src="img/icons/arrow-121-xxl.png"  width="36" heigt="36"></a></div>
    <div id="hinfoi">
        <h1>Editovat klub</h1>
    </div>
</div>
<?php
    echo '<!-- auxiliary $club->SerializeFull() of this club for control purposes-->';
    echo "\r\n";
    echo "<!-- ".($club->SerializeFull())." -->\r\n";
?>
<form action="PHPActionHandler/update_club.php" method="post" autocomplete="off">
    <!--hidden id-->
    <input name="id" type="hidden" value="<?= h($club->id); ?>">
    <div class="field-wrap-in-hundred"><div class="field-wrap-in-twenty-five"><h2>Jméno</h2></div><div class="field-wrap-in-seventy"><input class="fiftyPercent" name="name" type="text" value="<?= h($club->name); ?>"></div></div>
    <div class="field-wrap-in-hundred"><div class="field-wrap-in-twenty-five"><h2>Zkratka</h2></div><div class="field-wrap-in-seventy"><input class="fiftyPercent" name="abbreviation" type="text" value="<?= h($club->abbreviation); ?>"></div></div>
    <div class="field-wrap-in-hundred"><div class="field-wrap-in-twenty-five"><h2>ID klubu</h2></div><div class="field-wrap-in-seventy"><input class="fiftyPercent" name="code" type="text" placeholder="number only" value="<?= h($club->code); ?>"></div></div>
    <div class="field-wrap-in-hundred"><div class="field-wrap-in-twenty-five"><h2>Obrázek</h2></div><div class="field-wrap-in-seventy"><input class="fiftyPercent" name="img" type="text" value="<?= h($club->img); ?>" readonly></div></div>
    <div class="field-wrap-in-hundred"><div class="field-wrap-in-twenty-five"><h2>Kraj</h2></div>
        <div class="field-wrap-in-seventy">
            <select name="kraj" class="select-wrap-in-hundred" style="height: 37px; margin-top: 0px;">
		        <?php foreach ($regions as $region): ?>
                    <!-- check $club->krajaffil == region-> id for and put selected-->
                    <option value="<?= h($region->id)?>" <?php if($club->affiliation_region_id==$region->id){echo "selected";} ?>><?= h($region->name)?></option>
			    <?php endforeach; ?>
            </select>
         </div>
    </div>
    <div class="center-fifty-wrap">
        <div class="field-wrap-in-center">
            <button type="submit" class="button-style">Aktualizovat</button>
        </div>
    </div>
</form>
<?php
require_once('tmpl_footer.php');
?>