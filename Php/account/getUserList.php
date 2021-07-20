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
	$role = $_POST["role"];
    $sql = "";
    if($type != '3'){
	    $sql = "select F_UserCode,F_UserID,F_UserName,F_UserType,F_Email,F_Mobile,F_LockIP,isnull(F_RoleCode,0) as F_RoleCode,case when (F_EndDate IS NULL OR GETDATE() BETWEEN F_StartDate AND F_EndDate) then 1 else 0 end as F_IsValid,cast(F_StartDate as varchar) as F_StartDate,cast(F_EndDate as varchar) as F_EndDate from dbo.tb_A_LoginUser where F_UserType = '" . $type . "'";
	}else{
        $sql = "select F_UserCode,F_UserID,F_UserName,F_UserType,F_Email,F_Mobile,F_LockIP,isnull(F_RoleCode,0) as F_RoleCode,case when (F_EndDate IS NULL OR GETDATE() BETWEEN F_StartDate AND F_EndDate) then 1 else 0 end as F_IsValid,cast(F_StartDate as varchar) as F_StartDate,cast(F_EndDate as varchar) as F_EndDate from dbo.tb_A_LoginUser where F_UserType = '3'" . ($role == '' ? '' : ' and F_RoleCode=' . $role);
    }
    $result = $db -> query($sql);
	echo json_encode($result);
?>
