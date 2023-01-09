<?php
require 'start.php';

$posts = $postsManager->FindLastNPosts(3);
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
        <h1>Aktuality</h1>
	    <?php
	        //AKTUALITY TESTING INFO
            echo "\r\n";
            echo "<!-- DEBUG -->";
	        echo "\r\n";
	        echo '<!-- auxiliary $posts type and count for debug purposes -->';
	        echo "\r\n";
	        echo "<!-- type: ".gettype($posts)." -->";
	        echo "\r\n";
	        echo "<!-- cnt: ".count($posts)." -->";
	        echo "\r\n";
	        echo "<!-- /DEBUG -->";
	        echo "\r\n";
	        echo "";
	        echo "\r\n";
	    ?>
        <section id="posts">
            <?php if ($posts): ?>
                <?php foreach ($posts as $post): ?>
                    <article class="post" id="<?= h($post->id) ?>">
                        <h1><span><?= h($post->title) ?></span>
                            <?php
                            if($post->signature_flag==1)
                            {
	                            echo "<span class=\"author\">";
	                            if($post->author_user_id==null)
                                {
                                    echo "<img src=\"img/info_24x24.png\" alt=\"info\" height=\"12\" width=\"12\"> OZNÁMENÍ";
                                }
                                else
                                {
	                                echo "<span class=\"frame\">aut. </span><span>".$usersManager->GetUserFullNameByID($post->author_user_id).", </span><span class=\"frame\">naps. ".date("d-m-Y", strtotime($post->timestamp))."</span>";
                                }
	                            echo "</span>";
                            }
                            ?>
                        </h1>
                        <p class="paragraph"><?= $post->content ?></p>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                0 aktualit
            <?php endif; ?>
        </section>
        <p id="err" text-align="center"></p>
        <?php
            if (count($posts)==3) {
	            ?>
                <p id="btn" align="center">
                    <button class="dalsiAktualita" onclick="GetPostAppendPost(PushLastID())">Starší aktualita</button>
                </p>
	            <?php
            }
            else
            {

            }
        ?>
    </section>
    <!--PAGE SPECIALIZATION FIN-->
    <?php include("UNIFIED_footer.php"); ?>
</div>
</body>
</html>