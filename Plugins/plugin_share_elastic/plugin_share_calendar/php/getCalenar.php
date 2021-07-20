<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/Php/lib/plugin/share.php");

	$prep = prepare();
	$force = $db -> query("select * from [Things+].[dbo].[TB_Share_GroupToNode] where F_node_id = ".$_POST["id"]." and F_group_id like '%force%'");
	$result = array(
		"work" => $prep["work"],
		"data" => array()
	);
	if(!count($force)){
		$result["data"] = $db -> query("select distinct c.*,b.F_action_id,b.F_open from [Things+].[dbo].[TB_Share_GroupToNode] a,[Things+].[dbo].[TB_Share_Relation] b,[Things+].[dbo].[TB_Share_Model] c where a.F_node_id = ".$_POST["id"]." and  b.F_group_id like '%'+a.F_group_id+'%' and b.F_cycle_id = c.F_id and c.F_id not like '%force%' and c.F_start < '".$_POST["end"]."' and c.F_end >= '".$_POST["start"]."'");
		foreach($result["data"] as $res){
			$res -> sub = array();
			$sub = $db -> query("select a.*,b.F_name from [Things+].[dbo].[TB_Share_Action] a,[Things+].[dbo].[TB_command_interface] b where a.F_command_id = b.F_id and a.F_action_id = '".$res -> F_action_id."'");
			foreach($sub as $s){
				if($s -> F_poll_time && $s -> F_poll_num){
					$time = date("Y-m-d ".$s -> F_time.":s");
					for($i = 0;$i <= $s -> F_poll_num;$i++){
						$add = new stdClass();
						$add -> F_action_id = $s -> F_action_id;
						$add -> F_time = date("H:i",strtotime("+".($s -> F_poll_time * $i)." minute",strtotime($time)));
						$add -> F_command_id = $s -> F_command_id;
						$add -> F_value = $s -> F_value;
						$add -> F_name = $s -> F_name;
						array_push($res -> sub,$add);
					}
				}else{
					array_push($res -> sub,$s);
				}
			}
		}
	}
	ob_clean();
	echo json_encode($result);
?>