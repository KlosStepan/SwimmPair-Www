<?php
header("Content-type: application/pdf");
header("Content-Disposition: inline; filename=file.pdf");
@readfile('file.pdf');
?>