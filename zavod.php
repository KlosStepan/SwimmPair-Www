<?php
require 'start.php';

$cupID = Sanitizer::getGetInt('id');
$cup = $cupsManager->GetCupByID($cupID);
$pairs = $cupsManager->FindPairingsForThisCup($cupID);
$registeredAll = $usersManager->FindAllRegisteredUsersForTheCup($cupID);
?>
<!DOCTYPE html>
<html>
<head>
	<?php include("UNIFIED_head_content.php"); ?>
</head>

<body>
<div class="bodywrapper">
    <?php include("UNIFIED_header.php"); ?>
    <?php include("UNIFIED_menu.php"); ?>

    <!--PAGE SPECIALIZATION GOES HERE-->
    <section class="content">
        <section>
            <h1 style="text-align:center"><?= h($cup->name)?> </h1>
            <h1 class="contracted"><?=  date('j. m. Y', strtotime($cup->time_start)) ?> – <?=  date('j. m. Y', strtotime($cup->time_end)) ?></h1>
            <h1 class="contracted"> Info: <?=h($cup->description)?></h1>
            <p>&nbsp;</p>
            <h1 class="contracted">Registrovaní rozhodčí</h1>
            <!--<p style="text-align:center;line-height: 165%;">-->
                <!-- DOSTUPNOST frame -->
                <?php
                    //DOSTUPNOST TESTING INFO
                    echo "\r\n";
                    echo '<!-- auxiliary $registeredAll referees type and count for debug purposes -->';
                    echo "\r\n";
                    echo "<!-- type: ".gettype($registeredAll)." -->";
                    echo "\r\n";
                    echo "<!-- cnt: ".count($registeredAll)." -->";
                    echo "\r\n";
                ?>
                <?php
                    if(count($registeredAll)!=0) {
                        echo "<p style=\"text-align:center;line-height: 165%;\">";
	                    foreach ($registeredAll as $registeredOne) {
		                    //if available
		                    if ($usersManager->IsComing($cupID, $registeredOne->id) == true) {
			                    //available or not
			                    echo "<span id='" . h($registeredOne->id) . "' class='" . h($usersManager->RetComingCSSClass($cupID, $registeredOne->id)) . "'>" . h($registeredOne->first_name) . " " . h($registeredOne->last_name) . "</span>\t";
		                    }
	                    }
	                    echo "</p>";
                    }
                    else
                    {
                        echo "<p style=\"text-align:center;\">–<span style=\"border: 1px solid black;\">&nbsp;ZATÍM 0 REGISTROVANÝCH NA TENTO ZÁVOD&nbsp;</span>–</p>";
                    }
                    ?>
                <!--</p>-->
                <table class="rozhodcinazavody" text-align="center">
                <tr class="rozhodcihlavicka"><th>Pozice</th><th>Rozhodčí</th></tr>
                <!-- IF NOT isComing($cupId, $registeredOne->id) STRIKE -->
	            <?php
	                //DOSTUPNOST TESTING INFO
	                echo "\r\n";
	                echo '<!-- auxiliary $pairs referee pairs type and count for debug purposes -->';
	                echo "\r\n";
	                echo "<!-- type: ".gettype($pairs)." -->";
	                echo "\r\n";
	                echo "<!-- cnt: ".count($pairs)." -->";
	                echo "\r\n";
	            ?>
                <?php
                    if(count($pairs)!=0) {
	                    foreach ($pairs as $pair) {
		                    if ($usersManager->IsComing($cupID, $pair->user_id)) {
			                    echo "<tr><td>" . h($positionsManager->GetPositionNameById($pair->position_id)) . "</td><td>";
			                    if (!$usersManager->IsComing($cupID, $pair->user_id)) {
				                    echo "<strike>";
			                    }
			                    echo h($usersManager->GetUserFullNameByID($pair->user_id));
			                    if (!$usersManager->IsComing($cupID, $pair->user_id)) {
				                    echo "</strike>";
			                    }
			                    echo "</td></tr>";
		                    }
	                    }
	                    echo '</table>';
                    }
                    else
                    {
	                    echo '</table>';
                        echo "<p style=\"text-align:center;\">–<span style=\"border: 1px solid black;\">&nbsp;ZATÍM ŽÁDNÉ ROZDĚLENÍ ROZHODČÍCH NA POZICE&nbsp;</span>–</p>";
                    }
                    ?>
                    <p style="margin-top:0px;margin-bottom:0px;">&nbsp;</p>
                <!--</table>-->
                </section>

    </section>
    <!--PAGE SPECIALIZATION GOES HERE FIN -->

    <?php include("UNIFIED_footer.php"); ?>
</div>
</body>
</html>