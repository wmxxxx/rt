<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
    $protag = $_GET["protag"];
    $tag = $_GET["tag"];
    $frame = $_GET["frame"];
    $filename = $_FILES["b_menu_logo"]["tmp_name"];
    if(move_uploaded_file ($filename, "../../Project/" . $protag . "/Resources/images/nav/" . $frame . "/" . $tag . ".png"))  
    {
        echo "{ status: '1'}";
    }else{
	    echo "{ status: '0'}";
    }
?>
