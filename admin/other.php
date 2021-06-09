<?php
require __DIR__ . '/../start.php';

$posts = $postsManager->FindAllPostsOrderByIDDesc();

session_start();
Auth::requireRole(UserRights::SuperUser);

require_once('tmpl_header.php');
?>

<h1>Other things maybe</h1>
<ul id="nabidkavadminu">
    <li><a href="#">SMAZAT AKTUALITU</a></li>
    <li><a href="#">EDITOVAT KLUBY</a></li>
    <li><a href="#">EDITOVAT UŽIVATELE</a></li>
    <li><a href="#">ZMĚNIT HESLO</a></li>
    <li><a href="#">ODHLÁŠENÍ ROZHODČÍ</a></li>
</ul>
<?php
require_once('tmpl_footer.php');
?>