<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/plugin/share.php");

	$start = ($_POST["page"] - 1) * $_POST["limit"] + 1;
	$end = $_POST["page"] * $_POST["limit"];
	$result = array(
		"code" => 0,
		"msg" => "",
		"count" => count($db -> query("select F_id from [Things+].[dbo].[TB_Share_Task] where F_time like '%".$_POST["time"]."%' and F_app = ".$_POST["app"])),
		"data" => $db -> query("select * from (select ROW_NUMBER() over(order by F_time desc) as num,* from [Things+].[dbo].[TB_Share_Task] where F_time like '%".$_POST["time"]."%' and F_app = ".$_POST["app"].") k where k.num between ".$start." and ".$end." order by num")
	);
	$sub = $db -> query("select a.F_task_id,b.F_code from [Things+].[dbo].[TB_Share_Log] a left join [Things+].[dbo].[TB_Share_Back] b on a.F_id = b.F_bid where a.F_send like '%".$_POST["time"]."%' and a.F_app = ".$_POST["app"]);
	foreach($result["data"] as $res){
		$res -> all = 0;
		$res -> success = 0;
		$res -> fail = 0;
		$res -> abnormal = 0;
		$res -> ignore = 0;
		foreach($sub as $s){
			if($res -> F_id == $s -> F_task_id){
				$res -> all++;
				if($s -> F_code == "" || $s -> F_code == NULL){
					$res -> abnormal++;
				}else if($s -> F_code == "0"){
					$res -> success++;
				}else if(in_array($s -> F_code,array("100","101","102","103"))){
					$res -> ignore++;
				}else{
					$res -> fail++;
				}
			}
		}
	}
	ob_clean();
	echo json_encode($result);
?>