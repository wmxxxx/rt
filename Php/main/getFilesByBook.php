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
	
	$book = $_POST["book"];
	
	$sql = "select a.*,b.F_BookName from dbo.tb_A_FileInfo a,dbo.tb_A_BookInfo b where a.F_BookCode = b.F_BookCode and a.F_BookCode ='" . $book . "' order by a.F_UploadDate";
	$result = $db -> query($sql);
	echo json_encode($result);
?>
