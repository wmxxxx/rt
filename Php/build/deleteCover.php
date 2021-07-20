<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");    
	$tag = $_POST["tag"];
	$ui = $_POST["ui"];
    if(unlink("../../Project/" . $tag . "/logo_" . $ui . ".png")) {   
        echo "{ status: '1'}";
    }else{
        echo "{ status: '0'}";
    }
?>
