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
	
	$sql = "select a.F_ProjectNo,a.F_ProjectName,a.F_ProjectAbbr,a.F_ProjectTag,a.F_ProjectType,a.F_ProjectFrame,a.F_ProjectColor,a.F_ProjectDes,b.F_FunctionTypeNo,a.F_SystemFunction,a.F_GuideFunction,dbo.fun_GetProjectTreeNum(a.F_ProjectNo) as F_TreeNum,dbo.fun_GetProjectFunNum(a.F_ProjectNo,'1') as F_OneFunNum,dbo.fun_GetProjectFunNum(a.F_ProjectNo,'2') as F_TwoFunNum from dbo.tb_A_Project a left outer join tb_A_Function b on a.F_SystemFunction = b.F_FunctionCode order by a.F_ProjectNo";
	$result = $db -> query($sql);
    $resArray = array();
    foreach ($result as $obj){
        $o = new stdClass();
        $o -> F_ProjectNo = $obj -> F_ProjectNo;
        $o -> F_ProjectName = $obj -> F_ProjectName;
        $o -> F_ProjectAbbr = $obj -> F_ProjectAbbr;
        $o -> F_ProjectTag = $obj -> F_ProjectTag;
        $o -> F_ProjectFrame = $obj -> F_ProjectFrame;
        $o -> F_ProjectType = $obj -> F_ProjectType;
        $o -> F_ProjectColor = $obj -> F_ProjectColor;
        $o -> F_ProjectDes = $obj -> F_ProjectDes;
        $o -> F_FunctionTypeNo = $obj -> F_FunctionTypeNo;
        $o -> F_SystemFunction = $obj -> F_SystemFunction;
        $o -> F_GuideFunction = $obj -> F_GuideFunction;
        $o -> F_TreeNum = $obj -> F_TreeNum;
        $o -> F_OneFunNum = $obj -> F_OneFunNum;
        $o -> F_TwoFunNum = $obj -> F_TwoFunNum;
        if(file_exists("../../Project/" . $obj -> F_ProjectTag . "/logo_d.png")){
            $o -> F_ProjectLogo_d = "1";
        }else{
            $o -> F_ProjectLogo_d = "0";
        }
        if(file_exists("../../Project/" . $obj -> F_ProjectTag . "/logo_m.png")){
            $o -> F_ProjectLogo_m = "1";
        }else{
            $o -> F_ProjectLogo_m = "0";
        }
        if(file_exists("../../Project/" . $obj -> F_ProjectTag . "/logo_i.png")){
            $o -> F_ProjectLogo_i = "1";
        }else{
            $o -> F_ProjectLogo_i = "0";
        }
        if(file_exists("../../Project/" . $obj -> F_ProjectTag . "/logo_k.png")){
            $o -> F_ProjectLogo_k = "1";
        }else{
            $o -> F_ProjectLogo_k = "0";
        }
        if(file_exists("../../Project/" . $obj -> F_ProjectTag . "/logo_app.png")){
            $o -> F_ProjectLogo_app = "1";
        }else{
            $o -> F_ProjectLogo_app = "0";
        }
        array_push($resArray,$o);
    }
	echo json_encode($resArray);
?>
