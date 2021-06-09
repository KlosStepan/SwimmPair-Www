<?php
require __DIR__ . '/../start.php';

session_start();
Auth::requireRole(UserRights::SuperUser);

$clubs = $clubsManager->FindAllClubs();
$refPositions = $usersManager->FindAllRefereeRanks();

require_once('tmpl_header.php');
?>
<form action="PHPActionHandler/add_new_user.php" method="post" autocomplete="off">
    <!--<h1>Zaregistrovat uživatele</h1>-->
    <div id="hcontainer">
        <div id="hnavi" ><a href="javascript:history.go(-1)"><img src="img/icons/arrow-121-xxl.png"  width="36" heigt="36"></a></div>
        <div id="hinfoi">
            <h1>Zaregistrovat uživatele</h1>
        </div>
    </div>
    <div class="field-wrap-in-fifty"><p>Jméno *</p><input class="fiftyPercent" type="text" required autocomplete="off" name="first_name"></div>
    <div class="field-wrap-in-fifty"><p>Příjmení *</p><input class="fiftyPercent" type="text" required autocomplete="off" name="last_name"></div>
    <div class="field-wrap-in-hundred"><p style="margin-top:0px;">Email *</p><input type="email" required autocomplete="off" name="email"></div>
    <div class="field-wrap-in-hundred"><h3>Klub a role *</h3></div>
    <div class="field-wrap-in-hundred">
        <select name="affiliation_club_id" class="select-wrap-in-hundred" style="height: 37px; margin-top: 0px;">
	        <?php foreach ($clubs as $club): ?>
                <option value="<?= h($club->id)?>"><?= h($club->name)?></option>
	        <?php endforeach; ?>
        </select>
    </div>
    <div class="field-wrap-in-hundred"><h3>Rozhodcovská třída *</h3></div>
    <div class="field-wrap-in-hundred">
        <select name="referee_rank_id" class="select-wrap-in-hundred" style="height: 37px; margin-top: 0px;">
		    <?php foreach ($refPositions as $refPosition): ?>
                <option value="<?= h($refPosition->id)?>"><?= h($refPosition->rank_name)?></option>
		    <?php endforeach; ?>
        </select>
    </div>
    <div class="field-wrap-in-hundred"><h3>Pravomoce *</h3></div>
    <div class="field-wrap-in-hundred">
        <select name="rights" class="select-wrap-in-hundred" style="height: 37px; margin-top: 0px;">
            <option value="0">Plavecký rozhodčí /0</option>
            <option value="1">Vedoucí klubu /1</option>
        </select>
    </div>
    <div class="field-wrap-in-hundred" style="margin-top: 0px;"><p>Heslo *</p>
        <div class="seventy_five_split">
            <input  required autocomplete="off" id="passElement" name="password">
        </div>
        <div class="twenty_five_split">
            <input type="button" onclick="FillDummyPasswdIN('passElement',DummyPasswd(3));" value="DUMMY">
        </div>
    </div>
    <p style="margin-top:10px;">Pole označená * jsou povinná</p>
    <div class="button-wrapper">
        <button type="submit" class="button-submit-in" name="register">Registrovat</button>
    </div>
</form>
<?php
require_once('tmpl_footer.php');
?>