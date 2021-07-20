<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/Php/lib/plugin/share.php");

	$InfoId = $_GET["infoid"];
	$sql = "select a.* from [Things+].[dbo].[TB_command_interface] a where not exists(select 1 from [Things+].[dbo].[TB_command_impl_mx] where a.F_id=F_face_id and F_info_id='".$InfoId."') order by F_remark";
	$result = $db -> query($sql);
	$obj = new StdClass;
	$obj->data = $result;
	$obj->count = count($result);
	$obj->code = "0";
	ob_clean();
	echo json_encode($result);
?>