<?php
    header("Content:text/html;charset=utf-8");
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
	$type = $_POST["type"];
	$fun = $_POST["fun"];
	$plugin = $_POST["plugin"];
	$tree = $_POST["tree"];
    
    if($type == "1"){
	    $sql = "insert into tb_A_PluginToTree values(" . $fun . "," . $plugin . "," . $tree . ")";
	}else if($type == "0"){
	    $sql = "delete from tb_A_PluginToTree where F_FunctionCode=" . $fun . " and F_EntityTreeNo=" . $tree . " and F_PluginCode=" . $plugin;
    }
    echo json_encode($db -> execute($sql));
?>
