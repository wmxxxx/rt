<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(__FILE__))) . "/Php/lib/base.php");
	include_once(dirname(dirname(dirname(__FILE__))) . "/Php/lib/utils.php");
	date_default_timezone_set("PRC");
	function task_standbyPowerModel($sys,$fun){
		$db = new DB();
    
    $sql = "select distinct a.* from [Things+].dbo.tb_EM_PowerModel a,[Things+].dbo.tb_EM_ElectricToModel b where a.F_ModelID=b.F_ModelID";
	  $result = $db -> query($sql);
  
    $nowTime = date("Y-m-d H:i:s");
    $preDate = date("Y-m-d",strtotime("-1 day"));
    $nowDate = date("Y-m-d");
    $hour = date("H");
    $devCount = 0;
    $unSafetyCount = 0;
    $recoverCount = 0;
    for($i=0;$i<count($result);$i++){
      // 该模型下有几个设备
      $sql1 = "select a.*,b.F_EntityName from [Things+].dbo.tb_EM_ElectricToModel a,Things.dbo.tb_B_EntityTreeModel b where a.F_EntityID=b.F_EntityID and a.F_ModelID=" . $result[$i] -> F_ModelID;
      $result1 = $db -> query($sql1);
      
      // 该模型关联哪些人员编号
      $sql11 = "with t as (select a.* from 
                [Things+].dbo.tb_EM_ModelToUser a
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
        $devCount++;
        $temp = explode("-",$result[$i]->F_PowerHourSection);
        $st = substr($temp[0],0,2);//开始小时
        $et = substr($temp[1],0,2);//结束小时
      
        $temp1 = explode("-",$result[$i]->F_StandbyPeriod);
        $value = floatval($temp1[1]);
      
        $errorList = array();
        
        //用量参数
        $sql02 = "exec proc_API_GetNodePointData ".$result1[$j]->F_EntityID.",'". $result[$i]->F_PowerParam ."','". $preDate ."','". $nowDate ."','','>',".$value;
        echo json_encode("**sql02 = ".$sql02 );
        $result02 = $db -> query($sql02);
        
        $flag = 0;//正常异常标志，0：正常，1：异常
        $stNum = 0;//开始数
        $etNum = 0;//结束数
        
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
            $date = $preDate;
            if($a>23 && intval($et)<intval($st)){
              $date = $nowDate;
            }
            $obj = new stdClass;
            $obj -> date = $date;
            $obj -> data = array();
            for($b=0;$b<count($data);$b++){
              if($data[$b]->F_Day==$date && $data[$b]->F_Hour==$temp){
                array_push($obj->data,$b ."时，待机功耗为". $data[$b]->F_EnergyData. "；");
                echo json_encode("ERROR = ". $result1[$j]->F_EntityID . "** F_Hour = ".$data[$b]->F_Hour);
                $flag = 1;
              }
            }
            if(count($obj -> data)>0){
              array_push($errorList,$obj);
            }
          }
           echo json_encode("***** errorList = ". count($errorList));
        }
        $sql2 = "select top 1 * from [Things+].dbo.tb_EM_AlarmInfo where F_EntityID=".$result1[$j]->F_EntityID." and F_ModelID=". $result[$i] -> F_ModelID ." order by F_AlarmTime desc";
        $result2 = $db -> query($sql2);
        $nowState = 0;// 设备当前状态 0没有数据，1有报警没恢复，2有报警，已恢复
        if(count($result2)==1){
          if($result2[0]->F_RecoverTime!=null){
            $nowState = 2;
          }else{
            $nowState = 1;
          }
        }
        if($flag==1){
          $unSafetyCount++;
          $errorContent = "";
          for($a1=0;$a1<count($errorList);$a1++){
            $errorContent .= $errorList[$a1] -> date."，";
            for($b1=0;$b1<count($errorList[$a1]->data);$b1++){
              $errorContent .= $errorList[$a1]->data[$b1];
            }
          }
          $errorContent.= "超过最大范围值".$value."。";
          //插入报警表
          $sql3 = "insert into [Things+].dbo.tb_EM_AlarmInfo values(". $result1[$j]->F_EntityID .",". $result[$i] -> F_ModelID .",'". $nowTime ."',null,'". $errorContent ."',0,null,null,null,1) ";
          $db -> query($sql3);
          
          // 同步
          T_Utils::writeExtendAlarmEvent(4,'',$result1[$j]->F_EntityID,'',$sys,$result[$i] -> F_ModelID,$errorContent,$result11[0].Users);
          
        }
        if($flag==0){//没有异常也插入
          if($nowState==1){
            $recoverCount++;
          }
          $sql3 = "insert into [Things+].dbo.tb_EM_AlarmInfo values(". $result1[$j]->F_EntityID .",". $result1[$j] -> F_ModelID .",'". $nowTime ."','". $nowTime ."',null,0,null,null,null,1) ";
          $db -> query($sql3);
        }
      }
    }
    
    return "共检测电表". $devCount . "个，其中".$unSafetyCount."个电表待机功耗异常，".$recoverCount."个电表恢复报警，请在电系统中查看报警详情！";
	}
?>