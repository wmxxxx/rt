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
	
	$project = $_POST["project"];
	$sql = "select distinct c.F_PluginTag from tb_A_ProjectToMenu a,dbo.tb_A_Function b,tb_A_Plugins c where a.F_ProjectNo = '" . $project . "' and a.F_FunctionCode = b.F_FunctionCode and b.F_PluginCode = c.F_PluginCode and c.F_GuideMode = 'js'";
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
