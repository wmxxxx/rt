<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/Php/lib/plugin/share.php");

	$id = $_POST["id"];
	$name = $_POST["name"];
	$remark = $_POST["remark"];
	$obj = new StdClass;
	$sql = "";
	if($id){
		$sql .= "update [Things+].dbo.TB_command_interface set F_name='".$name."',F_remark='".$remark."' where F_id='".$id."'";
	}else{
		$keyid = md5(uniqid(microtime(true),true));
		$sql .= "insert into [Things+].dbo.TB_command_interface values('".$keyid."','".$name."','".$remark."');";
	}
	$result = $db -> multi_query($sql);
	ob_clean();
	echo json_encode($obj);
?>