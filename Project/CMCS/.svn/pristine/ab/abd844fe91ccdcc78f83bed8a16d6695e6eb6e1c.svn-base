<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/Php/lib/plugin/share.php");

	$app = $_POST["app"];
	$gtree = $_POST["gtree"];
	$gid = $_POST["gid"];
	$dtree = $_POST["dtree"];
	$nodes = $_POST["nodes"];
	$sql = '';
	$db -> query("delete [Things+].[dbo].[TB_Share_AccessRel] where F_App = '".$app."' and F_GroupNo = '".$gtree."' and F_GroupID = '".$gid."' and F_DeviceNo = '".$dtree."'");
	foreach($nodes as $node){
		$sql .= "insert into [Things+].[dbo].[TB_Share_AccessRel] values('".$app."','".$gtree."','".$gid."','".$dtree."','".$node."');";
	}
	$result = $db -> query($sql);
	ob_clean();
	echo json_encode($result);
?>
