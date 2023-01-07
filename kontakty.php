<?php
require 'start.php';

$page = $pagesManager->GetPageByID(1);
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
    <?php
    echo "<!-- ".($page->Serialize())." -->";
    echo "\r\n";
    ?>
    <section class="content">
        <div id="title"><h1><?php echo($page->title); ?></h1></div>
        <div id="content"><?php echo($page->content); ?></div>
    </section>
    <!--PAGE SPECIALIZATION GOES HERE FIN-->
    <?php include("UNIFIED_footer.php"); ?>
</div>
</body>
</html>