<?php
require 'start.php';

//$users = [];
$users = $usersManager->FindAllActiveUsersOrderByLastNameAsc();

$refereeRanks = $usersManager->FindAllRefereeRanks();
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

    <div class="scrollable">
    <!--PAGE SPECIALIZATION GOES HERE-->
    <section class="content lide">
        <h1>Registrovaní rozhodčí</h1>
	    <?php
	    //UPCOMING TESTING INFO
	    echo "\r\n";
	    echo '<!-- auxiliary $users rozhodci type and count for debug purposes -->';
	    echo "\r\n";
	    echo "<!-- type: ".gettype($users)." -->";
	    echo "\r\n";
	    echo "<!-- cnt: ".count($users)." -->";
	    echo "\r\n";
	    ?>
        <?php
            if(count($users)!=0) {
	            ?>
                <div id="kraje-box" align="center">
                    <!--<span>KRAJE</span>-->
                    <div id="kraje" class="lide-filter-box">
                        <!-- loop generate -->
                        <span id="raid-all" onclick="RegionPickerChanged(this);"
                              class="lide-filter-button selected">VŠE</span>
                        <span id="raid-1" onclick="RegionPickerChanged(this);" class="lide-filter-button">OLK</span>
                        <span id="raid-2" onclick="RegionPickerChanged(this);" class="lide-filter-button">ZLK</span>
                    </div>
                </div>

                <div class="tridy-box" align="center">
                    <!--<span>TRIDY</span>-->
                    <div id="tridy" class="lide-filter-box">
                        <span id="rrid-all" onclick="RefereeRankPickerChanged(this);"
                              class="lide-filter-button selected">VŠE</span>
                        <!--<span onclick="tridaTapped(this);" class="lide-filter-button">default</span>-->
			            <?php foreach ($refereeRanks as $refereeRank): ?>
                            <span id="rrid-<?= h($refereeRank->id) ?>" onclick="RefereeRankPickerChanged(this);"
                                  class="lide-filter-button"><?= h($refereeRank->rank_name) ?></span>
			            <?php endforeach; ?>
                    </div>
                </div>
                <div align="center">
                    <!--<input id="inputText" onkeyup="refreshPPL();" type="text" name="search" placeholder="Hledat...">-->
                    <input id="inputText" onkeyup="SearchBarChanged();" type="text" name="search"
                           placeholder="Hledat...">
                </div>
                <div></div>
                <div id="lide">
		            <?php foreach ($users as $user): ?>
                        <article class="rozhodci" onclick="location.href='clovek.php?id=<?= $user->id ?>'">
                            <h1 class="last_name"><?= $user->last_name ?></h1>
                            <h1 class="first_name"><?= $user->first_name ?></h1>
                            <h2 class="rrid-<?= $user->referee_rank_id ?>"><?= $usersManager->GetRefereeRank($user->referee_rank_id) ?></h2>
                            <span class="rrid" style="display: none;"><?= $user->referee_rank_id ?></span>
                            <h2 class="raid-<?= $clubsManager->GetClubAffiliationToRegion($user->affiliation_club_id) ?>"><?= $regionsManager->GetRegionNameOfClub($clubsManager->GetClubAffiliationToRegion($user->affiliation_club_id)) ?></h2>
                            <span class="raid"
                                  style="display: none;"><?= $clubsManager->GetClubAffiliationToRegion($user->affiliation_club_id) ?></span>
                        </article>
		            <?php endforeach; ?>
                </div>
	            <?php
            }
            else
            {
	            echo "<p style=\"text-align:center;\">–<span style=\"border: 1px solid black;\">&nbsp;0 ROZHODČÍCH V SYSTÉMU&nbsp;</span>–</p>";
            }
        ?>
    <div id="nopplfound" style="display:none;">
        <p style="text-align:center;">–<span style="border: 1px solid black;">&nbsp;0 ROZHODČÍCH ODPOVÍDÁ HLEDÁNÍ&nbsp;</span>–</p>
    </div>
    <!-- KONEC -->
    </section>
    <!--PAGE SPECIALIZATION GOES HERE FIN -->
    </div>

    <?php include("UNIFIED_footer.php"); ?>
</div>
</body>
</html>