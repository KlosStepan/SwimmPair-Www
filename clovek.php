<?php
require 'start.php';

$userID = Sanitizer::getGetInt('id');
$minCupYear = $cupsManager->GetEarliestCupYear();
$maxCupYear = $cupsManager->GetMaximumCupYear();

$user = $usersManager->GetUserByID($userID);
$relevantPositions = $positionsManager->DisplayedLiveStatsConfiguredPositions();
$initialCupCount = $usersManager->CountCupsAttendanceOfUserGivenYear($userID, $maxCupYear);
$initialStats = $usersManager->CountOverallStatisticsOfUserGivenYear($userID, $maxCupYear);
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
        <h1 style=\"text-align:center\"><?= h($user->last_name)?> <?= h($user->first_name) ?><!--, refrank: <?= h($user->referee_rank_id)?> --></h1>
        <h1 style=\"text-align:center\"><?= h($usersManager->GetClubNameByAffiliationID($user->affiliation_club_id)) ?></h1>
        <div id="roky-box" align="center">
            <div id="roky" class="season-box">
                <?php for($i=$minCupYear;$i<$maxCupYear;$i++): ?>
                    <span onclick="ProcessPersonForTheSeason(<?php echo $userID; ?>, this);" class="season-button"><?php echo $i; ?></span>
                <?php endfor; ?>
                <span onclick="ProcessPersonForTheSeason(<?php echo $userID; ?>, this);" class="season-button selected"><?php echo $maxCupYear; ?></span>

            </div>
        </div>
        <h1 id="curr-rok" style="visibility:hidden; height: 1px; margin-top: 0.25px; margin-bottom: 0.25px;"><?php echo date("Y"); ?></h1>
        <h1 class="contracted" style=\"text-align:center\">Počet účastí na závodech je <span id="pocet-ucasti"><?php echo $initialCupCount; ?></span> v roce <span id="rok-ucasti"><?php echo $maxCupYear; ?></span></h1>
        <?php
            //print_r($initialStats);
        ?>
        <table class="statistikysezona" align="center">
            <tbody>
                <tr class="statistikyhlavicka">
                    <th>Pozice</th>
                    <th>Zastávaná</th>
                </tr>
                <?php foreach ($relevantPositions as $relevantPosition): ?>
                    <tr>
                        <td><?= h($relevantPosition->name) ?></td>
                        <td style="text-align: center;"><span id="<?= h($relevantPosition->id) ?>"><?php echo($initialStats[$relevantPosition->id-1]->cnt)?></span>x</td>
                        <?php
                            $initialStats[$relevantPosition->id-1]->cnt=0;
                        ?>
                    </tr>
                <?php endforeach; ?>
                <?php
                $cnt=0;
                foreach ($initialStats as $stat)
                    {
                        $cnt+=$stat->cnt;
                    }
                    ?>
                    <tr>
                        <td>Zbývající</td>
                        <td style="text-align: center;"><span id="zbyvajici"><?= h($cnt)?></span>x</td>
                    </tr>

                <!-- Ostatní bullthis count->
                <!--
                <tr>
                    <td>Vrchní rozhodčí</td>
                    <td>2x</td>
                </tr>
                <tr>
                    <td>Vrchní časomíra</td>
                    <td>0x</td>
                </tr>
                <tr>
                    <td>Startér</td>
                    <td>3x</td>
                </tr>
                <tr>
                    <td>Hlasatel</td>
                    <td>1x</td>
                </tr>
                <tr>
                    <td>Obsluha PC</td>
                    <td>2x</td>
                </tr>
                <tr>
                    <td>Ostatní</td>
                    <td>7x</td>
                </tr>
                -->
            </tbody>
        </table>
    </section>
    <!--PAGE SPECIALIZATION GOES HERE FIN -->

    <?php include("UNIFIED_footer.php"); ?>
</div>
</body>
</html>