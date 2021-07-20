<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/Php/lib/plugin/share.php");

	$app = $_POST["app"];
	$gtree = $_POST["gtree"];
	$gid = $_POST["gid"];
	$dtree = $_POST["dtree"];
	$sql = '';
	if($gid == 0 && $dtree == 0){
		$sql = "select *,F_EntityName as name from [Things].[dbo].[tb_B_EntityTreeModel] where F_EntityTreeNo =".$gtree;
	}else if($gid == 0 && $dtree != 0){
		$sql = "select *,F_EntityName as name from [Things].[dbo].[tb_B_EntityTreeModel] where F_EntityTreeNo =".$dtree;
	}else{
		$sql = "select a.*,a.F_EntityName as name,(case when a.F_EntityID = b.F_DeviceID then 1 else 0 end) as checked from [Things].[dbo].[tb_B_EntityTreeModel] a left join [Things+].[dbo].[TB_Share_AccessRel] b 
		on a.F_EntityTreeNo = b.F_DeviceNo and a.F_EntityID = b.F_DeviceID and b.F_App = '".$app."' and b.F_GroupNo = '".$gtree."' and b.F_GroupID = '".$gid."'
		where a.F_EntityTreeNo = '".$dtree."'";
	}
	$result =  $db -> query($sql);
	ob_clean();
	echo json_encode($result);
?>
