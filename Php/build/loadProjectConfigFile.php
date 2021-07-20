<?php
    header("Content:text/html;charset=utf-8");
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	$s_tag = $_POST["s_tag"];
    if(file_exists('../../Project/' . $s_tag . '/Resources/files/' . $s_tag . '_config.md')){
	    $config_str = file_get_contents('../../Project/' . $s_tag . '/Resources/files/' . $s_tag . '_config.md');
        echo $config_str;
    }else{
        echo '';
    }
?>
