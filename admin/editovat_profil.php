<?php
require __DIR__ . '/../start.php';

session_start();
Auth::requireRole(UserRights::SuperUser);

$profileID = Sanitizer::getGetInt('id');
$profile = $usersManager->GetUserByID($profileID);
$refPositions = $usersManager->FindAllRefereeRanks();

require_once('tmpl_header.php');
?>
<!--<h1>Editovat profil</h1>-->
<div id="hcontainer">
    <div id="hnavi" ><a href="javascript:history.go(-1)"><img src="img/icons/arrow-121-xxl.png"  width="36" heigt="36"></a></div>
    <div id="hinfoi">
        <h1>Editovat profil</h1>
    </div>
</div>
<!--<h1><?= h($profileID); ?></h1>-->
<div style="border: 1px solid white;">
    <h2><?= h($profile->first_name); ?> <?= h($profile->last_name); ?></h2>
    <h2><?= $usersManager->GetClubNameByAffiliationID($profile->affiliation_club_id); ?></h2>
    <h2><?= h($profile->email); ?></h2>
</div>
<p>&nbsp;</p>
<h2>Změnit rozhodčovskou trídu</h2>
<form action="PHPActionHandler/change_ref_rank.php" method="post" autocomplete="off">
    <input type="hidden" name="uid" value="<?= h($profileID); ?>">
    <div class="center-fifty-wrap">
        <div class="field-wrap-in-center picker-padding">
            <select name="referee_rank_id" style="height: 37px; margin-top: 0px;">
            <?php foreach ($refPositions as $refPosition): ?>
                <option value="<?= h($refPosition->id)?>" <?php if($profile->referee_rank_id==$refPosition->id){echo "selected";} ?>><?= h($refPosition->rank_name)?></option>
            <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="center-fifty-wrap">
        <div class="field-wrap-in-center">
            <button type="submit" class="button-style">Změnit třídu</button>
        </div>
    </div>
</form>
<p>&nbsp;</p>
<h2>Nový přihlašovací email</h2>
<form action="PHPActionHandler/change_login_email_for_user.php" method="post" autocomplete="off">
    <input type="hidden" name="uid" value="<?= h($profileID); ?>">
    <div class="center-fifty-wrap">
        <div class="field-wrap-in-center">
            <input class="fiftyPercent" name="email">
        </div>
    </div>
    <!-- <button type="submit" class="button-submit-in">Změnit</button> -->
    <div class="center-fifty-wrap">
        <div class="field-wrap-in-center">
            <button type="submit" class="button-style">Změnit login</button>
        </div>
    </div>
</form>
<p>&nbsp;</p>
<h2>Nové heslo</h2>
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
<!-- <p><?= h($profile->SerializeFull()); ?></p> -->
<?php
require_once('tmpl_footer.php');
?>