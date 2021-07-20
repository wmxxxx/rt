<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/plugin/share.php");

	$base = base();
	$check_num = 0;
	$task_num = 0;
	$task_id = uniqid("t");
	$curl_info = "";
	$sql = "insert into [Things+].[dbo].[TB_Share_Task] values('".$task_id."','".$_POST["name"]."','0',3,'".date("Y-m-d H:i:s")."',".$_POST["app"].");";
	$fail = $db -> query("select * from [Things+].[dbo].[TB_Share_Log] a left join [Things+].[dbo].[TB_Share_Back] b on a.F_id = b.F_bid where a.F_task_id = '".$_POST["id"]."'");
	if(count($fail)){
		$param =  array(
			"taskName" => $_POST["name"],
			"callbackUrl" => $base -> ip."/Php/lib/plugin/share/strategyBack.php",
			"subTasks" => array()
		);
		foreach($fail as $f){
			if(!in_array($f -> F_code,array("0","100","101","102","103"))){
				$sub_id = uniqid("s");
				$sql .= "insert into [Things+].[dbo].[TB_Share_Log] values('".$sub_id."','".$task_id."','".$f -> F_node_id."','".$f -> F_command_name."',3,'".$f -> F_content."','".date("Y-m-d H:i:s")."',".$_POST["app"].");";
				$de_content = json_decode($f -> F_content);
				array_push($param["subTasks"],array(
					"taskName" => $sub_id,
					"type" => isset($de_content -> cmdName) ? 'SendDeviceCustomCmd' : 'WriteDeviceVariantData',
					"args" => $de_content
				));
				$check_num++;
				if($check_num == $base -> num){
					$task_num++;
					$param["taskName"] = $task_id."_".$task_num;
					$curl_info = curl($base -> ip."/API/IoT/ExecBatchTask/","post",array("param" => json_encode($param)));
					$param["subTasks"] = array();
					$check_num = 0;
				}
			}
		}
		if(count($param["subTasks"])){
			$task_num++;
			$param["taskName"] = $task_id."_".$task_num;
			$curl_info = curl($base -> ip."/API/IoT/ExecBatchTask/","post",array("param" => json_encode($param)));
		}
		$db -> multi_query($sql);
	}
	ob_clean();
	echo json_encode($curl_info);
?>