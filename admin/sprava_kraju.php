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
		<h1>Správa krajů</h1>
	</div>
</div>
<ul id="nabidkavadminu">
<!-- loop here -->
<?php foreach ($regions as $region): ?>
	<li style="text-align:left" onclick="location.href = 'editovat_kraj.php?id=<?= h(urlencode($region->id)); ?>';" >
		<div>
			<b><?= h($region->abbreviation); ?></b>
			&ndash;
			<?= h($region->name); ?>
		</div>
	</li>
<?php endforeach; ?>
</ul>
<?php
require_once('tmpl_footer.php');
?>