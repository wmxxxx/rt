<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/Php/lib/plugin/share.php");

	$infoid = $_POST["infoid"];
	$ids = $_POST["faceids"];
	$s1 = explode(',',$ids);
	$obj = new StdClass;
	$sql = "";
	foreach($s1 as $gp){
		if($gp!=''){
			$keyid = md5(uniqid(microtime(true),true));
			$sql .= "insert into [Things+].dbo.TB_command_impl_mx values('".$keyid."','".$infoid."','".$gp."');";
		}
	}
	$result = $db -> multi_query($sql);
	ob_clean();
	echo json_encode($obj);
?>