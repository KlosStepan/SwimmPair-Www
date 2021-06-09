<?php
require __DIR__ . '/../start.php';

session_start();
Auth::requireRole(UserRights::SuperUser);

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

<!--<h1>Nová aktualita</h1>-->
<div id="hcontainer">
    <div id="hnavi" ><a href="javascript:history.go(-1)"><img src="img/icons/arrow-121-xxl.png"  width="36" heigt="36"></a></div>
    <div id="hinfoi">
        <h1>Přidat aktualitu</h1>
    </div>
</div>
<form action="PHPActionHandler/add_new_post.php" onsubmit="return validateForm()" method="post">
    <input type="hidden" id="author" name="author" value="<?= $_SESSION['id']; ?>">
    <h2>Titulek</h2><input type="text" id="title" name="title"></br>
    <h2>Obsah</h2><textarea id="mytextarea" name="mytextarea" placeholder="Text aktuality..."></textarea>
    </br>
    <!-- https://stackoverflow.com/questions/4554758/how-to-read-if-a-checkbox-is-checked-in-php -->
    <div class="field-wrap-in-fifty">
        <h2>Zobrazovat</h2>
        </br>
        <input type="checkbox" id="display_flag" name="display_flag" value="checked"><br>
    </div>
    <div class="field-wrap-in-fifty">
        <h2>Veřejně podepsáno</h2>
        </br>
        <input type="checkbox" id="signature_flag" name="signature_flag" value="checked"><br>
    </div>
    <div class="button-wrapper" style="width:100%;text-align:center;">
        <button type="submit">Zveřejnit</button>
    </div>
</form>

<?php
require_once('tmpl_footer.php');
?>