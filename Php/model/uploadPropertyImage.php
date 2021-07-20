<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
    $tag = $_GET["tag"];
    $filename = $_FILES[$tag]["tmp_name"];
    if(move_uploaded_file ($filename, "../../Resources/images/property/" . $tag . ".png"))  
    {
        echo "{ status: '1'}";
    }else{
	    echo "{ status: '0'}";
    }
?>
