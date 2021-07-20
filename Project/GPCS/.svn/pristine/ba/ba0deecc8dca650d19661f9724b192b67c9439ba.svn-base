<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/plugin/share.php");

	$start = ($_POST["page"] - 1) * $_POST["limit"] + 1;
	$end = $_POST["page"] * $_POST["limit"];
	$sql = "from [Things+].[dbo].[TB_Share_Log] a left join [Things+].[dbo].[TB_Share_Back] b on a.F_id = b.F_bid left join [Things].[dbo].[tb_B_EntityTreeModel] c on a.F_node_id = c.F_EntityID where a.F_send like '%".$_POST["time"]."%' and a.F_app = ".$_POST["app"];
	if(isset($_POST["res"])){
		$res = $_POST["res"];
		if($res == 1)$sql .= " and b.F_code = '0'";
		if($res == 2)$sql .= " and b.F_code not in ('','0','100','101','102','103')";
		if($res == 3)$sql .= " and b.F_code = ''";
		if($res == 4)$sql .= " and b.F_code in ('100','101','102','103')";
	}
	if($_POST["name"] != "")$sql .= " and c.F_EntityName like '%".$_POST["name"]."%'";
	$result = array(
		"code" => 0,
		"msg" => "",
		"count" => count($db -> query("select F_id ".$sql)),
		"data" => $db -> query("select * from (select ROW_NUMBER() over(order by F_send desc) as num,a.*,b.*,c.F_EntityName ".$sql.") k where k.num between ".$start." and ".$end)
	);
	ob_clean();
	echo json_encode($result);
?>