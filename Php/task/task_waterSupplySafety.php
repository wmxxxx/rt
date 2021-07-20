<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(__FILE__))) . "/Php/lib/base.php");
	include_once(dirname(dirname(dirname(__FILE__))) . "/Php/lib/utils.php");
  
	date_default_timezone_set("PRC");
	function task_waterSupplySafety($sys,$fun){
		$db = new DB();
    $sql = "select distinct a.* from [Things+].dbo.tb_WM_SafeModel a,[Things+].dbo.tb_WM_WaterToModel b where a.F_ModelID=b.F_ModelID";
	  $result0 = $db -> query($sql);
  
    $nowTime = date("Y-m-d H:i:s");
    $nowDate = date("Y-m-d");
    $hour = date("H");
    $devCount = 0;
    $unSafetyCount = 0;
    $recoverCount = 0;
    for($i=0;$i<count($result0);$i++){
      // 该模型下有几个设备
      $sql1 = "select a.*,b.F_EntityName from [Things+].dbo.tb_WM_WaterToModel a,Things.dbo.tb_B_EntityTreeModel b where a.F_EntityID=b.F_EntityID and a.F_ModelID=" . $result0[$i] -> F_ModelID;
      $result1 = $db -> query($sql1);
      // 该模型关联哪些人员编号
      $sql11 = "with t as (select a.* from 
                [Things+].dbo.tb_WM_ModelToUser a
                where a.F_ModelID=".$result0[$i] -> F_ModelID.") 
                SELECT F_ModelID,F_ModelName, 
                STUFF(
                     ( 
                      SELECT ','+ convert(varchar,F_UserID) FROM t a FOR XML PATH('')
                     ),1 ,1, '') Users 
                FROM t 
                GROUP BY F_ModelID,F_ModelName;";
      $result11 = $db -> query($sql11);
      
      for($j=0;$j<count($result1);$j++){
        $devCount++;
        //瞬时参数
        $node_code = $result1[$j]->F_EntityID;
        $filter_str = $result0[$i]->F_MonitorParam;
        
        $memcache = memcache_connect("localhost",11211);
        $node_cache = $memcache -> get("node");
        $node_param_cache = $memcache -> get("node_param");
        $tpl_param_cache = $memcache -> get("tpl_param");
        $result = new stdClass;
        $result -> status = false;
        $result -> code = $node_cache[$node_code] -> code;
        $result -> name = $node_cache[$node_code] -> name;
        $result -> time = null;
        $result -> online = null;
        $result -> variantDatas = array();
        if (array_key_exists($node_code,$node_cache)){
            $result -> status = true;
            $result -> time = $node_cache[$node_code] -> commtime;
            $result -> online = $node_cache[$node_code] -> online;
            $params = $tpl_param_cache[$node_cache[$node_code] -> tpl]; 
            for($k = 0;$k < count($params);$k++){
                if (array_key_exists($node_code . '-' . $params[$k] -> label,$node_param_cache)){
                    if(strpos($filter_str,$params[$k] -> label) !== false){
                        $param = new stdClass;
                        $param -> code = $params[$k] -> code;
                        $param -> label = $params[$k] -> label;
                        $param -> name = $params[$k] -> name;
                        $param -> unit = $params[$k] -> unit;
                        $param -> time = $node_param_cache[$node_code . '-' . $params[$k] -> label] -> commtime;
                        $param -> value = $node_param_cache[$node_code . '-' . $params[$k] -> label] -> value;
                        $param -> value_kv = $node_param_cache[$node_code . '-' . $params[$k] -> label] -> value_kv;
                        array_push($result -> variantDatas,$param);
                    }
                }
            }
        }
        
        $sql2 = "select top 1 * from [Things+].dbo.tb_WM_AlarmInfo where F_EntityID=".$result1[$j]->F_EntityID." and F_ModelID=". $result0[$i] -> F_ModelID ." order by F_AlarmTime desc";
        $result2 = $db -> query($sql2);
        $nowState = 0;// 设备当前状态 0没有数据，1有报警没恢复，2有报警，已恢复
        if(count($result2)==1){
          if($result2[0]->F_RecoverTime!=null){
            $nowState = 2;
          }else{
            $nowState = 1;
          }
        }
        $errorFlag = false;// 设备没有异常
        $errorStr = "";
        if($result!=null){
          $value = $result->variantDatas[0]->value;
          //echo json_encode("value = ".$value);
          if(floatval($value)>$result0[$i]->F_UpperLimit){
            $errorFlag = true;
            $unSafetyCount++;
            $errorStr = $result1[$j]->F_EntityName."水表". $result0[$i]->F_ParamName ."的值为".$value."，超过上限值". $result0[$i]->F_UpperLimit;
          }else if(floatval($value)<$result0[$i]->F_LowerLimit){
            $errorFlag = true;
            $unSafetyCount++;
            $errorStr = $result1[$j]->F_EntityName."水表". $result0[$i]->F_ParamName ."，值为".$value."，低于下限值". $result0[$i]->F_LowerLimit;
          }
        }
        
        //echo json_encode("errorStr = ".$errorStr);
        
        if($errorFlag==true){//有异常都插入
          //插入报警表
          $sql2 = "insert into [Things+].dbo.tb_WM_AlarmInfo values(". $result1[$j]->F_EntityID .",". $result1[$j] -> F_ModelID .",'". $nowTime ."',null,null,'". $errorStr ."',0,null,null,null,2) ";
          
          //echo json_encode("sql2 = ".$sql2);
          $result2 = $db -> query($sql2);
          // 同步
          T_Utils::writeExtendAlarmEvent(4,'',$result1[$j]->F_EntityID,'',$sys,$result0[$i] -> F_ModelID,$errorContent,$result11[0].Users);
        }
        if($errorFlag==false){//没有异常也插入
          $recoverCount++;
          //报警恢复
          $sql3 = "insert into [Things+].dbo.tb_WM_AlarmInfo values(". $result1[$j]->F_EntityID .",". $result1[$j] -> F_ModelID .",'". $nowTime ."','". $nowTime ."',null,null,0,null,null,null,2) ";
          
          //echo json_encode("sql3 = ".$sql3);
          $result3 = $db -> query($sql3);
        }
      }
    }
    return "共检测水表". $devCount . "个，其中".$unSafetyCount."个水表供水异常".$recoverCount."个水表恢复报警，请在水系统中查看报警详情！";
	}
?>