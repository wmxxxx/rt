<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/plugin/share.php");

	$start = ($_POST["page"] - 1) * $_POST["limit"] + 1;
	$end = $_POST["page"] * $_POST["limit"];
	$sql = "from [Things+].[dbo].[TB_Share_Illegal] a,[Things].[dbo].[tb_B_EntityTreeModel] b where a.F_node_id = b.F_EntityID and a.F_app = ".$_POST["app"];
	$time = explode(" - ",$_POST["time"]);
	$sql .= " and a.F_time >= '".$time[0]."' and a.F_time <= '".$time[1]."'";
	if(isset($_POST["res"]))$sql .= " and a.F_type = ".$_POST["res"];
	if($_POST["name"] != "")$sql .= " and b.F_EntityName like '%".$_POST["name"]."%'";
	if(isset($_POST["rep"]) && $_POST["rep"])$sql .= " and a.F_id in (select max(F_id) from [Things+].[dbo].[TB_Share_Illegal] where F_app = ".$_POST["app"]." and F_time >= '".$time[0]."' and F_time <= '".$time[1]."' group by F_node_id)";
	$result = array(
		"code" => 0,
		"msg" => "",
		"count" => count($db -> query("select F_id ".$sql)),
		"data" => $db -> query("select * from (select ROW_NUMBER() over(order by F_time desc) as num,a.*,b.F_EntityName ".$sql.") k where k.num between ".$start." and ".$end)
	);
	ob_clean();
	echo json_encode($result);
?>