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
    $start = $_POST["start"] . ' 00:00:00';
    $end = $_POST["end"] . ' 23:59:59';
    $page = $_POST["page"];
    $page_num = $_POST["page_num"];
    if($type == ''){
	    $sql = "select ROW_NUMBER()OVER(ORDER BY F_Status,F_SendTime DESC) AS F_RowNum,F_EmailCode,F_Recipient,F_Copier,F_Theme,F_Content,F_Attachment,F_AttachCode,convert(varchar,F_SendTime,120) as F_SendTime,F_Status from dbo.tb_A_EmailInfo where F_MakeTime between '" . $start . "' and '" . $end . "'";
	}else{
        $sql = "select ROW_NUMBER()OVER(ORDER BY F_Status,F_SendTime DESC) AS F_RowNum,F_EmailCode,F_Recipient,F_Copier,F_Theme,F_Content,F_Attachment,F_AttachCode,convert(varchar,F_SendTime,120) as F_SendTime,F_Status from dbo.tb_A_EmailInfo where F_MakeTime between '" . $start . "' and '" . $end . "' and F_Status in (" . $type . ")";
    }
    $resSet = $db -> query($sql);
    $result = new stdClass;
    $result -> count = count($resSet);
    $result -> total = ceil(count($resSet) / $page_num);
    $result -> data = array();
    for($i = $page_num * ($page - 1);$i < count($resSet) && $i < $page_num * $page;$i++){
        array_push($result -> data,$resSet[$i]);
    }
	echo json_encode($result);
?>
