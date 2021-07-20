<?php
	/*
	 * Created on 2018-10-21
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/base.php");
    date_default_timezone_set("PRC");
    
    try
    {
        $pending = array();
        $sql = "select F_TaskCode,F_TaskName,F_TaskTag,F_ProjectNo,F_FunctionCode,F_TaskType,F_YearTime,F_MonthTime,F_DayTime,F_WeekDay,F_WeekTime,F_CycleTime,convert(varchar,F_LatelyDate,120) as F_LatelyDate from dbo.tb_A_PlanTask";
        $result = $db -> query($sql);
        
        for($i = 0;$i < count($result);$i++){
            if($result[$i] -> F_TaskType == 'cyc'){
                if($result[$i] -> F_LatelyDate == null || time() - strtotime($result[$i] -> F_LatelyDate) > $result[$i] -> F_CycleTime){
                    array_push($pending,$result[$i]);
                    if(file_exists("../plan/task/" . $result[$i] -> F_TaskTag . ".php")){
                        include_once("../plan/task/" . $result[$i] -> F_TaskTag . ".php");
                    }
                    echo '系统服务加载计划任务“' . $result[$i] -> F_TaskName . '”到执行队列！<br>';
                }
            }else if($result[$i] -> F_TaskType == 'day'){
                if(strtotime(date('Y-m-d H:i')) - strtotime(date('Y-m-d') . ' ' . $result[$i] -> F_DayTime) >= 0 && strtotime(date('Y-m-d H:i')) - strtotime(date('Y-m-d') . ' ' . $result[$i] -> F_DayTime) < 120){
                    array_push($pending,$result[$i]);
                    if(file_exists("../plan/task/" . $result[$i] -> F_TaskTag . ".php")){
                        include_once("../plan/task/" . $result[$i] -> F_TaskTag . ".php");
                    }
                    echo '系统服务加载计划任务“' . $result[$i] -> F_TaskName . '”到执行队列！<br>';
                }
            }else if($result[$i] -> F_TaskType == 'week'){
                if(date('w') == $result[$i] -> F_WeekDay && strtotime(date('Y-m-d H:i')) - strtotime(date('Y-m-d') . ' ' . $result[$i] -> F_WeekTime) >= 0 && strtotime(date('Y-m-d H:i')) - strtotime(date('Y-m-d') . ' ' . $result[$i] -> F_WeekTime) < 120){
                    array_push($pending,$result[$i]);
                    if(file_exists("../plan/task/" . $result[$i] -> F_TaskTag . ".php")){
                        include_once("../plan/task/" . $result[$i] -> F_TaskTag . ".php");
                    }
                    echo '系统服务加载计划任务“' . $result[$i] -> F_TaskName . '”到执行队列！<br>';
                }
            }else if($result[$i] -> F_TaskType == 'month'){
                if(strtotime(date('Y-m-d H:i')) - strtotime(date('Y-m') . '-' . $result[$i] -> F_MonthTime) >= 0 && strtotime(date('Y-m-d H:i')) - strtotime(date('Y-m') . '-' . $result[$i] -> F_MonthTime) < 120){
                    array_push($pending,$result[$i]);
                    if(file_exists("../plan/task/" . $result[$i] -> F_TaskTag . ".php")){
                        include_once("../plan/task/" . $result[$i] -> F_TaskTag . ".php");
                    }
                    echo '系统服务加载计划任务“' . $result[$i] -> F_TaskName . '”到执行队列！<br>';
                }
            }else if($result[$i] -> F_TaskType == 'year'){
                if(strtotime(date('Y-m-d H:i')) - strtotime(date('Y') . '-' . $result[$i] -> F_YearTime) >= 0 && strtotime(date('Y-m-d H:i')) - strtotime(date('Y') . '-' . $result[$i] -> F_YearTime) < 120){
                    array_push($pending,$result[$i]);
                    if(file_exists("../plan/task/" . $result[$i] -> F_TaskTag . ".php")){
                        include_once("../plan/task/" . $result[$i] -> F_TaskTag . ".php");
                    }
                    echo '系统服务加载计划任务“' . $result[$i] -> F_TaskName . '”到执行队列！<br>';
                }
            }
        };
        
        for($i = 0;$i < count($pending);$i++){
            if(file_exists("../plan/task/" . $pending[$i] -> F_TaskTag . ".php")){
                $db -> execute("update tb_A_PlanTask set F_LatelyDate = '" . date('Y-m-d H:i:s') . "' where F_TaskCode=" . $pending[$i] -> F_TaskCode)
                $msg = '';
                eval("\$msg=" . $pending[$i] -> F_TaskTag . "('" . ($pending[$i] -> F_ProjectNo == null ? '' : $pending[$i] -> F_ProjectNo) . "','" . ($pending[$i] -> F_FunctionCode == null ? '' : $pending[$i] -> F_FunctionCode) . "');");
                $sql = "exec proc_A_WriteEventLog 13,null,null," . "'“" . $pending[$i] -> F_TaskName . "”任务后台执行完成！返回结果：" . $msg . "'";
                echo "“" . $pending[$i] -> F_TaskName . "”任务后台执行完成！返回结果：" . $msg . "<br>";
                if($db -> execute($sql)){
                    echo '系统服务写入本次执行计划任务日志到数据库成功！<br>';
                }
            }
        }
    }
    catch(Exception $e)
    {
        echo '系统服务执行计划任务发生异常！异常消息：' . $e -> getMessage() . '。<br>';
    }
?>