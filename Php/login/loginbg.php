<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
    header("Content-type: image/png");
    
    $file = "../../Resources/images/background/login.png";
    $config_str = file_get_contents('../../data.json');
    if (preg_match('/^\xEF\xBB\xBF/',$config_str)){
	    $config_str = substr($config_str,3);
    }
    $config = json_decode($config_str, true);
    if($config['custom']['bg'] != '' && file_exists("../../Resources/images/background/" . $config['custom']['bg'])){
        $file = "../../Resources/images/background/" . $config['custom']['bg'];
    }
    echo (fread(fopen($file,'r'),filesize($file)));
?>
