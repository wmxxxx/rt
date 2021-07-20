<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/Php/lib/plugin/share.php");

	$InfoId = $_GET["infoid"];
	$sql = "select mx.*,f.F_id as face_id,f.F_name as face_name,f.F_remark as face_remark from [Things+].[dbo].[TB_command_impl_mx] mx ,[Things+].[dbo].[TB_command_interface] f where f.F_id=mx.F_face_id and F_info_id='".$InfoId."' order by face_remark";
	$result = $db -> query($sql);
	$obj = new StdClass;
	$obj->data = $result;
	$obj->count = count($result);
	$obj->code = "0";
	ob_clean();
	echo json_encode($obj);
?>