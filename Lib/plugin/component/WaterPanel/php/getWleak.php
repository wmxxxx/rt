<?php
	header("Content:text/html;charset=utf-8");
	include_once($_SERVER['DOCUMENT_ROOT'] . "/Php/lib/base.php");

  	$sql="select top 1 * FROM [Things+].[dbo].[tb_WM_AlarmInfo] where F_AlarmType=1 and F_EntityID=". $_GET['device_code'].' order by F_AlarmTime desc';
	$resSet = $db -> query($sql);

	echo json_encode($resSet);


?>