<?php
require __DIR__ . '/../start.php';


session_start();
Auth::requireRole(UserRights::SuperUser);

require_once('tmpl_header.php');
?>
	<!--<h1>Nová pravidla</h1>-->
    <div id="hcontainer">
        <div id="hnavi" ><a href="javascript:history.go(-1)"><img src="img/icons/arrow-121-xxl.png"  width="36" heigt="36"></a></div>
        <div id="hinfoi">
            <h1>Nová pravidla</h1>
        </div>
    </div>
    <form action="PHPActionHandler/upload_rules.php" method="post" enctype="multipart/form-data">
        <input type="file" name="file" size="50" />
        <br />
        <input type="submit" value="Upload" />
    </form>
<?php
require_once('tmpl_footer.php');
?>