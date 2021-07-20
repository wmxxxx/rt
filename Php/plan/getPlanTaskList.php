<?php
    header("Content:text/html;charset=utf-8");
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
	
	$sql = "select a.F_TaskCode,a.F_TaskName,a.F_TaskTag,a.F_TaskType,a.F_YearTime,a.F_MonthTime,a.F_DayTime,a.F_CycleTime,a.F_WeekDay,case a.F_WeekDay when 1 then '周一' when 2 then '周二' when 3 then '周三' when 4 then '周四' when 5 then '周五' when 6 then '周六' when 0 then '周日' end as F_WeekDayName,a.F_WeekTime,a.F_ProjectNo,b.F_ProjectName,convert(varchar,a.F_LatelyDate,120) as F_LatelyDate,c.F_FunctionCode ,isnull(c.F_FunctionName,'') as F_FunctionName,d.F_FunctionTypeNo from dbo.tb_A_PlanTask a left outer join dbo.tb_A_Project b on a.F_ProjectNo = b.F_ProjectNo left outer join dbo.tb_A_Function c on a.F_FunctionCode = c.F_FunctionCode left outer join dbo.tb_A_FunctionType d on c.F_FunctionTypeNo = d.F_FunctionTypeNo";
	$result = $db -> query($sql);
    foreach ($result as $obj){
        if(file_exists("task/" . $obj -> F_TaskTag . ".php")){
            $obj -> F_FileExists = 1;
        }else{
            $obj -> F_FileExists = 0;
        }
    }
	echo json_encode($result);
?>
