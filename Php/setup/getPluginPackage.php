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
	
	$sys = $_POST["sys"];
	$menu = $_POST["menu"];
	$app = $_POST["app"];
    
    $sql = "exec proc_A_GetPluginPackage '" . $sys . "','" . $menu . "','" . $app . "'";
	$result = $db -> query($sql);
    $resArray = array();
    foreach ($result as $obj){
        $o = new stdClass();
        $o -> F_PluginCode = $obj -> F_PluginCode;
        $o -> F_PluginTag = $obj -> F_PluginTag;
        $o -> F_PluginName = $obj -> F_PluginName;
        if(file_exists("../../Plugins/" . $obj -> F_PluginTag . "/setup.sql")){
            $o -> F_SetupFile = "1";
        }else{
            $o -> F_SetupFile = "0";
        }
        array_push($resArray,$o);
    }
	echo json_encode($resArray);
?>
