<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/Php/lib/plugin/share.php");

	$id = $_POST["infoid"];
	$json = $_POST["list"];
	$obj = new StdClass;
	$sql = "";
	$keyid = md5(uniqid(microtime(true),true));
	if($id)$sql .= "delete from [Things+].dbo.TB_command_interface_arg where F_info_id='".$id."'";
	if($json && $json!=""){
		$list =json_decode($json);
		foreach($list as $child){
			$a1 = $child -> name;
			$a2 = $child -> code;
			$a3 = $child -> type;
			$a4 = $child -> typecontent;
			$mykeyid = md5(uniqid(microtime(true),true));
			$sql .= "insert into [Things+].dbo.TB_command_interface_arg values('".$mykeyid."','".$id."','".$a1."','".$a2."','".$a3."','".$a4."');";
		}
	}
	$result = $db -> multi_query($sql);
	$obj->aaData = $result;
	ob_clean();
	echo json_encode($obj);
?>