<?php
require __DIR__ . '/../start.php';

session_start();
Auth::requireRole(UserRights::SuperUser);

$users = $usersManager->FindAllUsers();

require_once('tmpl_header.php');
?>

<div id="hcontainer">
    <div id="hnavi" ><a href="javascript:history.go(-1)"><img src="img/icons/arrow-121-xxl.png"  width="36" heigt="36"></a></div>
    <div id="hinfoi">
        <h1>Editovat profily</h1>
    </div>
</div>
<ul id="nabidkavadminu">
<?php foreach ($users as $user): ?>
	<li style="text-align:left" onclick="location.href='editovat_profil.php?id=<?= h(urlencode($user->id)); ?>';" >
        <div>
            #<?= h($user->id); ?>
            -
            <b>
            <?= h($user->first_name); ?>
			<?= h($user->last_name); ?>
            </b>
            |
			<?= $usersManager->GetClubNameByAffiliationID($user->affiliation_club_id); ?>
        </div>
    </li>
<?php endforeach; ?>
</ul>
<?php
require_once('tmpl_footer.php');
?>