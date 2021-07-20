<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
    header("Content-type: image/png");
    $protag = $_GET["protag"];
    $tag = $_GET["tag"];
    $file = "../../Project/" . $protag . "/Resources/images/nav/" . $tag . ".png";
    echo (fread(fopen($file,'r'),filesize($file)));
?>
