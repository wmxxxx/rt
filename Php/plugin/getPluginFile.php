<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
	$sql = "select F_PluginTag from tb_A_Plugins where F_GuideMode='js'";
	$result = $db -> query($sql);
    $resArray = array();
    foreach ($result as $obj){
        $o = new stdClass();
        if(file_exists("../../Plugins/" . $obj -> F_PluginTag)
            &&file_exists("../../Plugins/" . $obj -> F_PluginTag . "/index.js")
            &&file_exists("../../Plugins/" . $obj -> F_PluginTag . "/index.css")){
            $o -> F_PluginTag = $obj -> F_PluginTag;
            array_push($resArray,$o);
        }
    }
	echo json_encode($resArray);
?>
