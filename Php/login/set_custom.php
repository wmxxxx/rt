<?php
	/*
	 * Created on 2014-4-14
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
    header("content-type:text/html; charset=utf-8"); //设置编码 
    
    $p_cname = $_POST["p_cname"];
    $p_ename = $_POST["p_ename"];
    $c_cname = $_POST["c_cname"];
    $c_ename = $_POST["c_ename"];
    
	$config_str = file_get_contents('../../data.json');
    if (preg_match('/^\xEF\xBB\xBF/',$config_str)){
	    $config_str = substr($config_str,3);
    }
    $config = json_decode($config_str,false);
    $config -> custom -> p_cname = $p_cname; 
    $config -> custom -> p_ename = $p_ename; 
    $config -> custom -> c_cname = $c_cname; 
    $config -> custom -> c_ename = $c_ename; 
    file_put_contents('../../data.json',json_encode($config));
    echo json_encode(array('status' => true));
?>
