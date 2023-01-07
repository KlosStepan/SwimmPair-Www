<?php
require 'start.php';

$clubId = Sanitizer::getGetInt('id');
$minCupYear = $cupsManager->GetEarliestCupYear();
$maxCupYear = $cupsManager->GetMaximumCupYear();

$club = $clubsManager->GetClubByID($clubId);
$myPPL = $usersManager->FindAllTeamMembers($clubId);

//statistiky na tento rok, feed tabulku raw PHP function that feeds XHR as well
?>

<!DOCTYPE html>
<html>
<head>
	<?php include("UNIFIED_head_content.php"); ?>
</head>
<body onload="PopulateClubStatsGivenYear(<?= $clubId;?>,<?=$maxCupYear;?>);">
<div class="bodywrapper">
    <?php include("UNIFIED_header.php"); ?>
    <?php include("UNIFIED_menu.php"); ?>
    <!--PAGE SPECIALIZATION GOES HERE-->
    <section class="content">
        <h1 style="text-align:center"><?= h($club->name)?><!--, krajaffil: <?= h($club->affiliation_region_id) ?>--></h1>
        <h1 style="text-align:center"><?= h($club->abbreviation)?> / No. <?= h($club->code)?></h1>
        <div id="roky-box" align="center">
            <div id="roky" class="season-box">
	            <?php for($i=$minCupYear;$i<$maxCupYear;$i++): ?>
                    <span onclick="ProcessClubForTheSeason(<?php echo $clubId; ?>, this);" class="season-button"><?php echo $i; ?></span>
	            <?php endfor; ?>
                <span onclick="ProcessClubForTheSeason(<?php echo $clubId; ?>, this);" class="season-button selected"><?php echo $maxCupYear; ?></span>
            </div>
        </div>
        <h1 style="text-align:center">Statistika klubu v roce <span id="rok-ucasti"><?php echo $maxCupYear; ?></span></h1>
	    <?php
	    //UPCOMING TESTING INFO
	    echo "\r\n";
	    echo '<!-- auxiliary $myPPL rozhodci a cnts type and count for debug purposes -->';
	    echo "\r\n";
	    echo "<!-- type: ".gettype($myPPL)." -->";
	    echo "\r\n";
	    echo "<!-- cnt: ".count($myPPL)." -->";
	    echo "\r\n";
	    ?>
        <table class="statistikysezona" align="center">
            <tbody>
            <tr class="statistikyhlavicka">
                <th>Rozhodčí</th>
                <th>Účast</th>
            </tr>
            <?php
                if(count($myPPL)!=0) {
	                ?>
	                <?php foreach ($myPPL as $person) : ?>
                        <tr>
                            <td><?= h($person->first_name) ?> <?= h($person->last_name) ?></td>
                            <td style="text-align: center;"><span id="<?= h($person->id) ?>">0</span>x</td>
                        </tr>
	                <?php endforeach; ?>
                    </tbody>
                </table>
	                <?php
                }
                else
                {
	                echo "</tbody>";
                    echo "</table>";
	                echo "<p style=\"text-align:center;\">–<span style=\"border: 1px solid black;\">&nbsp;0 ROZHODČÍCH V TOMTO KLUBU&nbsp;</span>–</p>";
                }
            ?>
            </tbody>
        </table>
    </section>
    <!--PAGE SPECIALIZATION FIN-->
    <?php include("UNIFIED_footer.php"); ?>
</div>
</body>
</html>