<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(__FILE__))) . "/Php/lib/base.php");
	include_once(dirname(dirname(dirname(__FILE__))) . "/Php/lib/utils.php");
	date_default_timezone_set("PRC");
	function task_leakageMonitor($sys,$fun){
		$db = new DB();
		//获取模型相关的数据
	  $sql = "select distinct a.* from [Things+].dbo.tb_WM_LeakModel a,[Things+].dbo.tb_WM_WaterToModel b where a.F_ModelID=b.F_ModelID";
	  $result = $db -> query($sql);
    $nowTime = date("Y-m-d H:i:s");
    $nowDate = date("Y-m-d");
    $preDate = date("Y-m-d",strtotime("-1 day"));
    $hour = date("H");
    
    //return "测试漏水模型1";
    $devCount = 0;
    $leakCount = 0;
    $recoverCount = 0;
    for($i=0;$i<count($result);$i++){
      // 该模型下有几个设备
      $sql1 = "select a.*,b.F_EntityName from [Things+].dbo.tb_WM_WaterToModel a,Things.dbo.tb_B_EntityTreeModel b where a.F_EntityID=b.F_EntityID and a.F_ModelID=" . $result[$i] -> F_ModelID;
      $result1 = $db -> query($sql1);
      // 该模型关联哪些人员编号
      $sql11 = "with t as (select a.* from 
                [Things+].dbo.tb_WM_ModelToUser a
                where a.F_ModelID=".$result[$i] -> F_ModelID.") 
                SELECT F_ModelID,F_ModelName, 
                STUFF(
                     ( 
                      SELECT ','+ convert(varchar,F_UserID) FROM t a FOR XML PATH('')
                     ),1 ,1, '') Users 
                FROM t 
                GROUP BY F_ModelID,F_ModelName;";
      $result11 = $db -> query($sql11);
      
      for($j=0;$j<count($result1);$j++){
        $temp = explode("-",$result[$i]->F_RationPeriod);
        $st = $temp[0];//开始小时
        $et = $temp[1];//结束小时
        $stNum = 0;//开始数
        $etNum = 0;//结束数
        $devCount++;
        //标志位
        $flag = 0;//整体标志，0：正常，1：异常
        $flag1 = 0;//瞬时参数标志，0：正常，1：异常
        $flag2 = 0;//用量参数标志，0：正常，1：异常
        if($result[$i]->F_MonitorParam_I&&$result[$i]->F_MonitorParam_I!=""){
          //瞬时参数接口
          $sql01 = "exec proc_API_GetNodePointData ".$result1[$j]->F_EntityID.",'". $result[$i]->F_MonitorParam_I ."','". $preDate ."','". $preDate ."','','=',0";
          $result01 = $db -> query($sql01);
          if(count($result01)==0){
            $flag1=1;
          }
        }
        if($result[$i]->F_MonitorParam_I&&$result[$i]->F_MonitorParam_U!=""){
          //用量参数
          $sql02 = "exec proc_API_GetNodePointData ".$result1[$j]->F_EntityID.",'". $result[$i]->F_MonitorParam_U ."','". $preDate ."','". $preDate ."','',null,null";
          $result02 = $db -> query($sql02);
          $valueList = array();
          if(count($result02)>0){
            $data = $result02;
            if(intval($et)<intval($st)){
              $stNum = intval($st);
              $etNum = intval($et)+23;
            }else{
              $stNum = intval($st);
              $etNum = intval($et);
            }
            for($a=$stNum;$a<=$etNum;$a++){
              $temp = ($a>23?$a-23:$a);
              for($b=0;$b<count($data);$b++){
                if($data[$b]->F_Day==$preDate && $data[$b]->F_Hour==$temp && floatval($data[$b]->F_EnergyData)>0){
                  array_push($valueList,$data[$b]->F_EnergyData);
                  $flag2 = 1;//只判断了一次
                }
              }
            }
          }
        }
        $sql2 = "select top 1 * from [Things+].dbo.tb_WM_AlarmInfo where F_EntityID=".$result1[$j]->F_EntityID." and F_ModelID=". $result[$i] -> F_ModelID ." order by F_AlarmTime desc";
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
        $errorContent = "";
        if($flag1==1 && $flag2==1){
          $errorFlag = true;
          $leakCount++;
        }
        if($errorFlag==true){//有异常都插入
          $total = 0;
          $count = 0;
          for($c=0;$c<count($valueList);$c++){
            $count++;
            $total = ($total+$valueList[$c]);
          }
          $hl = 0;
          if($count>0){
            $hl = round($total/$count,2);
          }
          
          $errorContent = $result1[$j]->F_EntityName."水表，在最近".$result[$i]->F_MonitorCycle."小时疑似有漏水，每小时".$hl."吨。";
          //插入报警表
          $sql3 = "insert into [Things+].dbo.tb_WM_AlarmInfo values(". $result1[$j]->F_EntityID .",". $result1[$j] -> F_ModelID .",'". $nowTime ."',null,". $hl .",'". $errorContent ."',0,null,null,null,1) ";
          $result3 = $db -> query($sql3);
          // 同步$type_no,$item_no,$device_code,$value_label,$sys_code,$model_code,$msg,$touser
          T_Utils::writeExtendAlarmEvent(4,'',$result1[$j]->F_EntityID,'',$sys,$result[$i] -> F_ModelID,$errorContent,count($result11)>0?$result11[0]->Users:"");
        }
        if($errorFlag==false && $nowState == 1 ){
          $recoverCount++;
          //报警恢复
          //$sql3 = "update [Things+].dbo.tb_WM_AlarmInfo set F_RecoverTime='".$nowTime."' where F_EntityID=".$result2[0]->F_EntityID." and F_ModelID=".$result2[0]->F_ModelID." and F_AlarmTime='".$result2[0]->F_AlarmTime."'";
          $sql3 = "insert into [Things+].dbo.tb_WM_AlarmInfo values(". $result1[$j]->F_EntityID .",". $result1[$j] -> F_ModelID .",'". $nowTime ."','". $nowTime ."',null,'报警恢复',0,null,null,null,1) ";
          $result3 = $db -> query($sql3);
        }
      }
    }
    return "共检测水表". $devCount . "个，其中".$leakCount."个水表疑似漏水，".$recoverCount."个水表恢复报警，请在水系统中查看报警详情！";
	}
?>