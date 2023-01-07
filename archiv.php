<?php
require 'start.php';

$cups = $cupsManager->FindAllPastCupsMostRecentFirst();
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
        <h1>Archiv minulých závodů</h1>
	    <?php
	        //UPCOMING TESTING INFO
	        echo "\r\n";
	        echo '<!-- auxiliary $cups archive type and count for debug purposes -->';
	        echo "\r\n";
	        echo "<!-- type: ".gettype($cups)." -->";
	        echo "\r\n";
	        echo "<!-- cnt: ".count($cups)." -->";
	    echo "\r\n";
	    ?>
        <?php
            if(count($cups)!=0) {
	            ?>
	            <?php foreach ($cups as $cup): ?>
                    <article class="zavod" onclick="location.href='zavod.php?id=<?= $cup->id ?>';">
                        <h1><?= h($cup->name) ?></h1>
                        <h2 style="text-align:center"><?= date('j. m. Y', strtotime($cup->time_start)) ?>
                            – <?= date('j. m. Y', strtotime($cup->time_end)) ?></h2>
                        <p><?= h($cup->description) ?></p>
                    </article>
	            <?php endforeach; ?>
	            <?php
            }
            else
            {
	            echo "<p style=\"text-align:center;\">–<span style=\"border: 1px solid black;\">&nbsp;0 ZÁVODŮ V ARCHIVU&nbsp;</span>–</p>";
            }
        ?>
    </section>
    <!--PAGE SPECIALIZATION FIN-->
    <?php include("UNIFIED_footer.php"); ?>
</div>
</body>
</html>