<?php
require __DIR__ . '/../start.php';

session_start();
Auth::requireRole(UserRights::SuperUser);

$cups = $cupsManager->FindAllUpcomingCupsEarliestFirst();

require_once('tmpl_header.php');
?>

<div id="hcontainer">
    <div id="hnavi" ><a href="javascript:history.go(-1)"><img src="img/icons/arrow-121-xxl.png"  width="36" heigt="36"></a></div>
    <div id="hinfoi">
        <h1>Editovat rozdělení</h1>
    </div>
</div>
<ul id="nabidkavadminu">
    <?php foreach ($cups as $cup): ?>
        <li style="text-align:left;" onclick="location.href = 'editovat_pairing.php?id=<?= h($cup->id) ?>';" >
             <div>
                 <?= h($cup->time_start) ?> - <b><?= h($cup->name)?></b> <?= h($cup->description)?>
             </div>
        </li>
    <?php endforeach; ?>
</ul>
<?php
require_once('tmpl_footer.php');
?>