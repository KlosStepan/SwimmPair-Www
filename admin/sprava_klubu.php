<?php
require __DIR__ . '/../start.php';

session_start();
Auth::requireRole(UserRights::SuperUser);

$clubs = $clubsManager->FindAllClubs();

require_once('tmpl_header.php');
?>
<div id="hcontainer">
    <div id="hnavi" ><a href="javascript:history.go(-1)"><img src="img/icons/arrow-121-xxl.png"  width="36" heigt="36"></a></div>
    <div id="hinfoi">
        <h1>Správa klubů</h1>
    </div>
</div>
<!--<p><a href="novy_klub.php"><h2>+PŘIDAT KLUB</h2></a></p>-->
<ul id="nabidkavadminu">
<?php foreach ($clubs as $club): ?>
    <li style="text-align:left" onclick="location.href = 'editovat_klub.php?id=<?= h(urlencode($club->id)); ?>';" >
        <div>
            <b><?= h($club->abbreviation); ?></b>
            &ndash;
		    <?= h($club->name); ?>
        </div>
    </li>
<?php endforeach; ?>
</ul>
<?php
require_once('tmpl_footer.php');
?>
