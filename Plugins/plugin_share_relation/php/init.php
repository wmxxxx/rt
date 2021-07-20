<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/plugin/share.php");

	$app = $_POST["app"];
	$user = $db -> query("select F_UserCode,F_UserName from [Things].[dbo].[tb_A_LoginUser] where F_UserType = 3");
	$group = $db -> query("select * from [Things+].[dbo].[TB_Share_Group] where F_id not like '%force%' and F_project_code = '".$app."' order by F_code");
	$model = $db -> query("select * from [Things+].[dbo].[TB_Share_Model] where F_id not like '%force%' and F_app = '".$app."'");
	$result = array(
		"user" => $user,
		"group" => $group,
		"cycle" => array(),
		"action" => array()
	);
	foreach($model as $m){
		if($m -> F_type == "cycle")array_push($result["cycle"],$m);
		if($m -> F_type == "action")array_push($result["action"],$m);
	}
	ob_clean();
	echo json_encode($result);
?>