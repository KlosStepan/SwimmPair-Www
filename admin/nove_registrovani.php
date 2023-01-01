<?php
require __DIR__ . '/../start.php';

session_start();
Auth::requireRole(UserRights::SuperUser);

$users = $usersManager->FindAllInactiveUsersOrderByLastNameAsc();

require_once('tmpl_header.php');
?>
<div id="hcontainer">
    <div id="hnavi" ><a href="javascript:history.go(-1)"><img src="img/icons/arrow-121-xxl.png"  width="36" heigt="36"></a></div>
    <div id="hinfoi">
        <h1>Nově registrovaní</h1>
    </div>
</div>
<table class="registeredrozhodci" text-align="center">
    <tbody>
        <tr class="registeredhlavicka">
            <th>Jméno</th>
            <th>Příjmení</th>
            <th>Práva</th>
            <th>Klub</th>
            <th>Status</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= h($user->first_name)?></td>
                <td><?= h($user->last_name)?></td>
                <td><?= UserRights::getRightsById(h($user->rights))?></td>
                <td><?= $usersManager->GetClubAbbreviationByAffiliationID(h($user->affiliation_club_id)) ?></td>
                <td class="status" id="<?= h($user->id)?>" text-align="center" ><img src="img/icons/circle-x-2x.png" style="margin-top: 4px;cursor: pointer;" onclick="ApproveUser(<?=h($user->id)?>)"></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php
require_once('tmpl_footer.php');
?>