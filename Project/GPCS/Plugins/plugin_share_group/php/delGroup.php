<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/plugin/share.php");

	session_start();
	$id = $_POST["id"];
	$log = $db -> query("select * from [Things+].[dbo].[TB_Share_Group] where F_id = '".$id."'");
	// 删除分组和分组关联的设备
	$sql = "delete [Things+].[dbo].[TB_Share_Group] where F_id = '".$id."';delete [Things+].[dbo].[TB_Share_GroupToNode] where F_group_id = '".$id."';";
	// 获取包含该分组的策略
	$str = $db -> query("select * from [Things+].[dbo].[TB_Share_Relation] where F_group_id like '%".$id."%'");
	foreach($str as $s){
		if($s -> F_group_id == $id){// 只有该分组的策略删除
			$sql .= "delete [Things+].[dbo].[TB_Share_Relation] where F_id = '".$s -> F_id."'";
		}else{// 包含多个分组的策略去除该分组
			$arr = explode(",",$s -> F_group_id);
			array_splice($arr,array_search($id,$arr),1);
			$sql .= "update [Things+].[dbo].[TB_Share_Relation] set F_group_id = '".implode(",",$arr)."' where F_id = '".$s -> F_id."'";
		}
	}
	// 获取包含该分组的违规模型
	$ill = $db -> query("select * from [Things+].[dbo].[TB_Share_Model] where F_name like '%".$id."%'");
	foreach($ill as $i){
		$arr = explode(",",$i -> F_name);
		array_splice($arr,array_search($id,$arr),1);
		$sql .= "update [Things+].[dbo].[TB_Share_Model] set F_name = '".implode(",",$arr)."' where F_id = '".$i -> F_id."'";
	}
	$sql .= "insert into [Things].[dbo].[tb_A_Log] values(default,19,'".date("Y-m-d h:i:s")."','".$_SESSION['user']["id"]."','".$_SERVER["REMOTE_ADDR"]."','管理分组删除：".json_encode($log)."')";
	$result = $db -> execute($sql);
	ob_clean();
	echo json_encode($result);
?>