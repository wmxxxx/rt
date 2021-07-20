<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/Php/lib/plugin/share.php");

	$mxid = $_POST["mxid"];
	$infoid = $_POST["infoid"];
	$json = $_POST["list"];
	$obj = new StdClass;
	$sql = "";
	$keyid = md5(uniqid(microtime(true),true));
	if($mxid)$sql .= "delete from [Things+].dbo.TB_command_impl_config where F_mx_id='".$mxid."'";
	if($json && $json!=""){
		$list =json_decode($json);
		foreach($list as $child){
			$a2 = $child -> code;
			$a3 = $child -> value;
			$a4 = $child -> type;
			$a5 = $child -> typecontent;
			$a6 = $child -> commandcode;
			$a7 = $child -> valuecode;
			$mykeyid = md5(uniqid(microtime(true),true));
			$sql .= "insert into [Things+].dbo.TB_command_impl_config values('".$mykeyid."','".$mxid."','".$infoid."','".$a2."','".$a3."','".$a4."','".$a5."','".$a6."','".$a7."');";
		}
	}
	$result = $db -> multi_query($sql);
	$obj->aaData = $result;
	ob_clean();
	echo json_encode($obj);
?>