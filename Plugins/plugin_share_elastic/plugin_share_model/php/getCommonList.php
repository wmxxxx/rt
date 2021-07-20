<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/Php/lib/plugin/share.php");
	
	// 获取命令列表
	$result = $db -> query("select distinct f.F_id as F_code,f.F_name,f.F_remark from [Things+].[dbo].[TB_command_impl_mx] mx,[Things+].[dbo].[TB_command_impl] m,[Things+].[dbo].[TB_command_interface] f where m.F_id = mx.F_info_id and f.F_id = mx.F_face_id and m.F_template_id in ('".implode("','",$_POST["device"])."') order by f.F_remark desc");
	// 添加传入参数
	foreach($result as $res){
		$sub = $db -> query("select * from [Things+].[dbo].[TB_command_interface_arg] where F_info_id = '".$res -> F_code."' order by F_code");
		$res -> sub = $sub;
	}
	ob_clean();
	echo json_encode($result);
?>