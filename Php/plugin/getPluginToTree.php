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
	
	$fun = $_POST["fun"];
    $sql = "select cast(a.F_EntityTreeNo as varchar) + '-' + a.F_EntityTreeType as id,F_EntityTreeName as name, 0 as pId,case when b.F_EntityTreeNo is null then 0 else 1 end as checked from tb_B_EntityTreeType a left outer join dbo.tb_A_PluginToTree b on a.F_EntityTreeNo = b.F_EntityTreeNo and b.F_FunctionCode = '" . $fun . "' where a.F_EntityTreeType = '1' or a.F_EntityTreeType = '2' order by a.F_EntityTreeNo";
	$result = $db -> query($sql);
	echo json_encode($result);
?>
