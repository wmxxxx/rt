<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/Php/lib/plugin/share.php");

	$id = $_POST["id"];
	$name = $_POST["name"];
	$code = $_POST["code"];
	$obj = new StdClass;
	$sql = "";
	if($id){
		$sql .= "update [Things+].dbo.TB_command_impl set F_template_name='".$name."',F_template_id='".$code."' where F_id='".$id."'";
	}else{
		$keyid = md5(uniqid(microtime(true),true));
		$sql .= "insert into [Things+].dbo.TB_command_impl values('".$keyid."','".$code."','".$name."');";
	}
	$result = $db -> multi_query($sql);
	ob_clean();
	echo json_encode($obj);
?>