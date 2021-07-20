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
	$plugin = $_POST["plugin"];
	$tree = $_POST["tree"];
	$node = $_POST["node"];
	$fun = $_POST["fun"];
    
    if($type == "1"){
	    $sql = "insert into tb_A_PluginToEntity values(" . $fun . "," . $plugin . "," . $tree . "," . $node . ")";
	}else if($type == "0"){
	    $sql = "delete from tb_A_PluginToEntity where F_PluginCode=" . $plugin . " and F_EntityTreeNo=" . $tree . " and F_EntityID=" . $node . " and F_FunctionCode=" . F_FunctionCode;
    }
    echo json_encode($db -> execute($sql));
?>
