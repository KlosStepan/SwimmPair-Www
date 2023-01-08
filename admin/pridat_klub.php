<?php
require __DIR__ . '/../start.php';

session_start();
Auth::requireRole(UserRights::SuperUser);

$regions = $regionsManager->FindAllRegions();
require_once('tmpl_header.php');
?>
<div id="hcontainer">
    <div id="hnavi" ><a href="javascript:history.go(-1)"><img src="img/icons/arrow-121-xxl.png"  width="36" heigt="36"></a></div>
    <div id="hinfoi">
        <h1>Nový klub</h1>
    </div>
</div>
<form action="PHPActionHandler/add_new_club.php" method="post" autocomplete="off">
    <div class="field-wrap-in-hundred"><div class="field-wrap-in-twenty-five"><h2>Jméno</h2></div><div class="field-wrap-in-seventy"><input name="name" type="text"></div></div>
    <div class="field-wrap-in-hundred"><div class="field-wrap-in-twenty-five"><h2>Zkratka</h2></div><div class="field-wrap-in-seventy"><input name="abbreviation" type="text"></div></div>
    <div class="field-wrap-in-hundred"><div class="field-wrap-in-twenty-five"><h2>ID klubu</h2></div><div class="field-wrap-in-seventy"><input name="club_id" type="text" placeholder="number only"></div></div>
    <div class="field-wrap-in-hundred"><div class="field-wrap-in-twenty-five"><h2>Obrázek</h2></div><div class="field-wrap-in-seventy"><input name="img" type="text" value="null.jpg" readonly></div></div>
    <div class="field-wrap-in-hundred"><div class="field-wrap-in-twenty-five"><h2>Kraj</h2></div>
    <div class="field-wrap-in-seventy">
        <select name="affiliation_region_id" class="select-wrap-in-hundred" style="height: 37px; margin-top: 0px;">
            <?php foreach ($regions as $region): ?>
                <option value="<?= h($region->id)?>"><?= h($region->name)?></option>
            <?php endforeach; ?>
        </select>
    </div>
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