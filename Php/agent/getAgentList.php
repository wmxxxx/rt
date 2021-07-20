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
    
	$sql = "select * from tb_A_Agent order by F_ProjectType,F_AgentCode";
	$result = $db -> query($sql);
    $resArray = array();
    foreach ($result as $obj){
        $o = new stdClass();
        $o -> F_AgentCode = $obj -> F_AgentCode;
        $o -> F_AgentName = $obj -> F_AgentName;
        $o -> F_AgentAbbr = $obj -> F_AgentAbbr;
        $o -> F_ProjectTag = $obj -> F_ProjectTag;
        $o -> F_ProjectType = $obj -> F_ProjectType;
        $o -> F_ProjectColor = $obj -> F_ProjectColor;
        $o -> F_ProjectDes = $obj -> F_ProjectDes;
        $o -> F_AgentURL = $obj -> F_AgentURL;
        $o -> F_PublicKey = $obj -> F_PublicKey;
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
        array_push($resArray,$o);
    }
	echo json_encode($resArray);
?>
