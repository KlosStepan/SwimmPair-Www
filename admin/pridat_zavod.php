<?php
require __DIR__ . '/../start.php';

session_start();
Auth::requireRole(UserRights::VedouciKlubu);

require_once('tmpl_header.php');
?>
    <!--insert form-->
<form action="PHPActionHandler/add_new_cup.php" method="post" autocomplete="off">
    <!--<h1>Přidat závod</h1>-->
    <div id="hcontainer">
        <div id="hnavi" ><a href="javascript:history.go(-1)"><img src="img/icons/arrow-121-xxl.png"  width="36" heigt="36"></a></div>
        <div id="hinfoi">
            <h1>Přidat závod</h1>
        </div>
    </div>
    <div class="field-wrap-in-hundred"><p>Název závodu *</p><input class="fiftyPercent" type="text" required autocomplete="off" name="name"></div>
    <div class="field-wrap-in-fifty">
        <p>Datum konání od *</p><input type="date" name="time_start">
        <p>Datum konání do *</p><input type="date" name="time_end">
        <p style="padding-top: 10px; padding-bottom: 5px;">Pořadatel</p>
        <h2><?= $clubsManager->GetClubByID($_SESSION['affiliation_club_id'])->name ?></h2>
        <input type="hidden" name="organizer_club_id" value="<?= $_SESSION['affiliation_club_id'] ?>">
    </div>
    <div class="field-wrap-in-fifty"><p>Popis závodu *</p><textarea placeholder="Informace o závodu..." rows="7" cols="50" name="descr_zavodu"></textarea></div>
    <div class="field-wrap-in-hundred" style="margin-top: 15px;">
        <div class="button-wrapper">
            <button type="submit" class="button-submit-in" name="register">Přidat</button>
        </div>
    </div>
    <p>Pole označená * jsou povinná</p>
</form>
<p>&nbsp;</p>
<?php
require_once('tmpl_footer.php');
?>