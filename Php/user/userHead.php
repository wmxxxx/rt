<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
    header("Content-type: image/png");
    $userId = $_GET["userId"];
    $file = "../../Resources/images/user/" .$userId . ".png";
    if(!file_exists($file)) {
        $file = "../../Resources/images/user/new.png";
    }
    echo (fread(fopen($file,'r'),filesize($file)));
?>
