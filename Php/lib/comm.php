<?php
	/*
	 * Created on 2019-7-31
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
    header("content-type:text/html; charset=utf-8"); //设置编码 
    session_start();
    if(!(isset($_SESSION['user']) && $_SESSION['user']['type'] != '4')){
	    die('非法的访问方式！');
    }
?>
