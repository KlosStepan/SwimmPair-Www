<?php
require __DIR__ . '/../start.php';

session_start();
Auth::requireRole(UserRights::SuperUser);

$positions = $positionsManager->FindAllPositions();
$positionsRelevant = $positionsManager->DisplayedLiveStatsConfiguredPositions();
require_once('tmpl_header.php');
?>
<div id="hcontainer">
    <div id="hnavi" ><a href="javascript:history.go(-1)"><img src="img/icons/arrow-121-xxl.png"  width="36" heigt="36"></a></div>
    <div id="hinfoi">
        <h1>Konfigurace statistik</h1>
    </div>
</div>
<h2>Všechny pozice</h2>
<div id="div0" ondrop="" ondragover="allowDrop(event)" style="margin-top:10px;">
	<?php foreach($positions as $position): ?>
		<div id="<?= h($position->id)?>" class="pozice" draggable="true" ondragstart="drag(event)" ondblclick=""><?= h($position->name)?><br></div>
	<?php endforeach; ?>
</div>
<hr>
<h2>Zobrazovat</h2>
<div id="pairing">
	<div id="div1" ondrop="drop(event,0)" ondragover="allowDrop(event)" style="margin-top:10px;">
		<?php foreach($positionsRelevant as $position): ?>
            <div id="0,<?= h($position->id)?>" class="pozice" draggable="true" ondragstart="drag(event)" ondblclick="destroyElement(this.id);"><?= h($position->name)?><br></div>
		<?php endforeach; ?>
	</div>
</div>
<p></p>
<input type="button" onclick="UpdatePreferedStats(ParseSerializeStatsDOM());" value="Uložit">
<?php
//echo print_r($positions);
?>
<?php
require_once('tmpl_footer.php');
?>
