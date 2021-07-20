<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/Php/lib/plugin/share.php");

	$InfoId = $_GET["id"];
	$obj = new StdClass;
	$sql = "select * from [Things+].dbo.TB_command_impl_mx where F_face_id='".$InfoId."';";
	$result = $db -> query($sql);
	if(count($result)>0){
		$obj->rs = false;
	}else{
		$obj->rs = true;
		$sql = "delete from [Things+].dbo.TB_command_interface where F_id='".$InfoId."';";
		$result = $db -> query($sql);
	}
	ob_clean();
	echo json_encode($obj);
?>