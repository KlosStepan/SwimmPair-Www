<?php
require __DIR__ . '/../start.php';

session_start();
Auth::requireRole(UserRights::SuperUser);

$posts = $postsManager->FindAllPostsOrderByIDDesc();

require_once('tmpl_header.php');
?>

<!--<h1>Editovat aktuality</h1>-->
<div id="hcontainer">
    <div id="hnavi" ><a href="javascript:history.go(-1)"><img src="img/icons/arrow-121-xxl.png"  width="36" heigt="36"></a></div>
    <div id="hinfoi">
        <h1>Editovat aktuality</h1>
    </div>
</div>
<?php if ($posts): ?>
    <ul id="nabidkavadminu">
        <?php foreach ($posts as $post): ?>
            <li style="text-align:left;" onclick="location.href = 'editovat_aktualitu.php?id=<?= h(urlencode($post->id)); ?>';" >
                    <div>
                        <?= h($post->timestamp); ?>
                        &ndash;
                        <?= h($post->title); ?>
                    </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    0 results
<?php endif; ?>

<?php
require_once('tmpl_footer.php');
?>