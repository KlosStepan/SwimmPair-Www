<?php
require 'start.php';

//$clubs = [];
$clubs = $clubsManager->FindAllClubs();
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
        <h1>Kluby v našich krajích</h1>
	    <?php
	    //KLUBY TESTING INFO
	    echo "\r\n";
	    echo '<!-- auxiliary $clubs registrovane kluby type and count for debug purposes -->';
	    echo "\r\n";
	    echo "<!-- type: ".gettype($clubs)." -->";
	    echo "\r\n";
	    echo "<!-- cnt: ".count($clubs)." -->";
	    echo "\r\n";
	    ?>
        <div id="kluby">
        <?php
            if(count($clubs)!=0) {
	            ?>
	            <?php foreach ($clubs as $club): ?>
                    <article class="rozhodci" onclick="location.href='klub.php?id=<?= $club->id ?>';">
                        <h1><?= $club->name ?></h1>
                        <h2><?= $club->abbreviation ?> </h2>
                        <h2>No. <?= $club->code ?></h2>
                    </article>
	            <?php endforeach; ?>
	            <?php
            }
            else
            {
	            echo "<p style=\"text-align:center;\">–<span style=\"border: 1px solid black;\">&nbsp;0 KLUBŮ V SYSTÉMU&nbsp;</span>–</p>";
            }
        ?>
        </div>
    </section>
    <!--PAGE SPECIALIZATION GOES HERE FIN -->

    <?php include("UNIFIED_footer.php"); ?>
</div>
</body>
</html>