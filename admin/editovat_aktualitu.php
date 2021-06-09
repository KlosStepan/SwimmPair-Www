<?php
require __DIR__ . '/../start.php';

session_start();
Auth::requireRole(UserRights::SuperUser);

$postID = Sanitizer::getGetInt('id');
$post = $postsManager->GetPostByID($postID);

require_once('tmpl_header.php');
?>

<script src='js/tinymce/tinymce.min.js'></script>
<script>
    tinymce.init({
        selector: '#mytextarea',
        menubar: false,
        statusbar: false,
        force_br_newlines : false,
        force_p_newlines : false,
        forced_root_block : '',
        plugins: "save",
        toolbar: "false",
    });
</script>

<!--<h1>Editovat aktualitu</h1>-->
<div id="hcontainer">
    <div id="hnavi" ><a href="javascript:history.go(-1)"><img src="img/icons/arrow-121-xxl.png"  width="36" heigt="36"></a></div>
    <div id="hinfoi">
        <h1>Editovat aktualitu</h1>
    </div>
</div>
<form action="PHPActionHandler/update_post.php" onsubmit="return validateForm()" method="post">
    <input id="postID" type="hidden" name="postID" value="<?= h($post->id); ?>">
    <h2>Titulek</h2><input id="title" type="text" name="title" value="<?= h($post->title); ?>"><br>
    <h2>Obsah</h2><textarea id="mytextarea" name="mytextarea"><?= h($post->content); ?></textarea>
    </br>
    <!-- https://stackoverflow.com/questions/4554758/how-to-read-if-a-checkbox-is-checked-in-php -->
    <div class="field-wrap-in-fifty">
        <h2>Zobrazovat</h2>
        <?php
            echo "<input type=\"checkbox\" name=\"display_flag\" value=\"checked\"";
            if($post->display_flag==1)
            {
                echo "checked";
            }
            echo "><br>";
        ?>
    </div>
    <div class="field-wrap-in-fifty">
        <h2>Veřejně podepsáno</h2>
        <?php
            echo "<input type=\"checkbox\" name=\"signature_flag\" value=\"checked\"";
            if($post->signature_flag==1)
            {
                echo "checked";
            }
            echo "><br>";
        ?>
    </div>
    <div class="button-wrapper" style="width:100%;text-align:center;">
        <button type="submit">Aktualizovat</button>
    </div>
</form>

<?php
require_once('tmpl_footer.php');