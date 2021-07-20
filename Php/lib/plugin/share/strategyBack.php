<?php
	header("content-type:text/html; charset=utf-8");
	include_once(dirname(dirname(dirname(__FILE__)))."/utils.php");
	include_once(dirname(dirname(__FILE__))."/share.php");
	$base = base();
	// 获取回调结果
	$back = file_get_contents('php://input');
	$back = json_decode($back);
	$task = explode("_",$back -> taskName);
	$sql = "";
	// 插入新记录
	foreach($back -> subTasks as $sub){
		// 判断命令返回状态
		$code = $sub -> result -> errCode;
		if(isset($sub -> result -> cmdReturnData -> Code))$code = $sub -> result -> cmdReturnData -> Code;
		if(isset($sub -> result -> writeResult)){
			foreach($sub -> result -> writeResult as $key => $write){
				if($write != 0 && $key != "StartStopCmdTemp")$code = $write;
			}
		}
		$sql .= "insert into [Things+].[dbo].[TB_Share_Back] values('".$sub -> taskName."','".json_encode($sub -> result)."','".date('Y-m-d H:i:s',$sub -> endTime)."','".$code."');";
	}
	// 是否需要消息推送 && 是否全部执行完成 && 有失败的子任务
	$str = $db -> query("select a.F_id as taskid,b.* from [Things+].[dbo].[TB_Share_Task] a,[Things+].[dbo].[TB_Share_Relation] b where a.F_id = '".$task[0]."' and a.F_rel_id = b.F_id");
	if(count($str)){
		$str = $str[0];
		$str -> num = 0;
		$str -> fail = 0;
		$sub = $db -> query("select * from [Things+].[dbo].[TB_Share_Log] a left join [Things+].[dbo].[TB_Share_Back] b on a.F_id = b.F_bid where a.F_task_id = '".$str -> taskid."'");
		foreach($sub as $s){
			if($s -> F_code == ""){
				$str -> num++;
			}else if(!in_array($s -> F_code,array("","0","100","103"))){
				$str -> fail++;
			}
		}
		if($str -> F_push && !$str -> num && $str -> fail){
			foreach(explode(",",$str -> F_user_id) as $user){
				T_Utils::sendTplMessage($user,$str -> F_name.'执行完成','策略任务',$str -> fail.'台设备执行命令失败',date("Y-m-d H:i:s"),'',$base -> ip."/Plugins/plugin_share_relation/wx.html?id=".$task[0]."&openid=ovBQMwh3GDxnZODDNqodLETd-m1Q");
			}
		}
	}
	$db -> multi_query($sql);
	echo "taskName:".$back -> taskName;
?>