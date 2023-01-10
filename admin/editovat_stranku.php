<?php
require __DIR__ . '/../start.php';

session_start();
Auth::requireRole(UserRights::SuperUser);

$id = Sanitizer::getGetInt('id');
$page = $pagesManager->GetPageByID($id);

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
        plugins: "code",
        toolbar: "code"
    });
</script>
<div id="hcontainer">
	<div id="hnavi" ><a href="javascript:history.go(-1)"><img src="img/icons/arrow-121-xxl.png"  width="36" heigt="36"></a></div>
	<div id="hinfoi">
		<h1>Editovat stránku</h1>
	</div>
</div>
<form action="PHPActionHandler/update_page.php" method="post" autocomplete="off">
	<input id="id" type="hidden" name="id" value="<?= h($page->id); ?>">
	<h2>Titulek</h2><input id="title" type="text" name="title" value="<?= h($page->title); ?>"><br>
	<h2>Obsah</h2><textarea id="mytextarea" name="mytextarea"><?= h($page->content); ?></textarea>
	</br>
	<div class="center-fifty-wrap">
		<div class="field-wrap-in-center">
			<button type="submit" class="button-style">Uložit</button>
		</div>
	</div>
</form>
<?php
require_once('tmpl_footer.php');
?>