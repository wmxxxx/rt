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
	$sql = "select b.F_FunctionCode,c.F_PluginCode,c.F_PluginTag,c.F_GuideMode,dbo.fun_GetFunctionEnvVar(b.F_FunctionCode) as F_EnvVar from tb_A_Project a,dbo.tb_A_Function b,tb_A_Plugins c where a.F_ProjectNo = '" . $project . "' and a.F_SystemFunction = b.F_FunctionCode and b.F_PluginCode = c.F_PluginCode";
	$result = $db -> query($sql);
    $resArray = array();
    foreach ($result as $obj){
        $o = new stdClass();
        if($obj -> F_GuideMode == 'js'){
            if(file_exists("../../Plugins/" . $obj -> F_PluginTag)
                &&file_exists("../../Plugins/" . $obj -> F_PluginTag . "/index.js")
                &&file_exists("../../Plugins/" . $obj -> F_PluginTag . "/index.css")){
                array_push($resArray,$obj);
            }
        }else if($obj -> F_GuideMode == 'html'){
            array_push($resArray,$obj);
        }
    }
	echo json_encode($resArray);
?>
