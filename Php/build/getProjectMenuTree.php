<?php
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
    $pro = $_GET["pro"];
    
    $sql = "select a.F_MenuCode,a.F_ParentCode,a.F_MenuTag,a.F_MenuName,isnull(b.F_FunctionName,'') as F_FunctionName from dbo.tb_A_ProjectToMenu a left outer join dbo.tb_A_Function b on a.F_FunctionCode = b.F_FunctionCode where a.F_ProjectNo = '" . $pro . "' order by F_MenuPosition,F_MenuType,F_MenuIndex";

	$result = $db -> query($sql);
    
    $resArray = array();
    $nodeArray = array();
    foreach ($result as $obj){
        $o = new stdClass();
        $o -> id = $obj -> F_MenuCode;
        $o -> name = $obj -> F_MenuName;
        $o -> tag = $obj -> F_MenuTag;
        $o -> fun = $obj -> F_FunctionName;
        $o -> iconCls = $obj -> F_ParentCode == 0 ? 'icon-folder-open' : 'icon-folder';
        $o -> leaf = $obj -> F_ParentCode == 0 ? false : true;
        $o -> state = 'open';
        $o -> children = array();

        if($obj -> F_ParentCode == 0){
            array_push($resArray,$o);
            $nodeArray[$obj -> F_MenuCode] = $o;
        }else{
            array_push($nodeArray[$obj -> F_ParentCode] -> children,$o);
            $nodeArray[$obj -> F_MenuCode] = $o;
        }
    }
    echo json_encode($resArray);
?>
