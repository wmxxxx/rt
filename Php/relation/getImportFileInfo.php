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
    
    $tree = $_POST["tree"];
	$result = $db -> query('select top 1 F_FileName,F_ImportUser,convert(varchar,F_ImportDate,120) as F_ImportDate from dbo.tb_A_ImportFile where F_EntityTreeNo = ' . $tree . ' order by F_ImportDate desc');
    echo json_encode($result);
?>
