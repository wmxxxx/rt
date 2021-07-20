<?php
	/*
	 * Created on 2015-12-20
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
    
	$year = $_POST["year"];
    $sql = "select year,month,work_start_time,work_end_time,tran_start_time1,tran_end_time1,tran_start_time2,tran_end_time2 from dbo.fun_GetMonthWorkTime(" . $year . ",1) union select year,month,work_start_time,work_end_time,tran_start_time1,tran_end_time1,tran_start_time2,tran_end_time2 from dbo.fun_GetMonthWorkTime(" . $year . ",2) union select year,month,work_start_time,work_end_time,tran_start_time1,tran_end_time1,tran_start_time2,tran_end_time2 from dbo.fun_GetMonthWorkTime(" . $year . ",3) union select year,month,work_start_time,work_end_time,tran_start_time1,tran_end_time1,tran_start_time2,tran_end_time2 from dbo.fun_GetMonthWorkTime(" . $year . ",4) union select year,month,work_start_time,work_end_time,tran_start_time1,tran_end_time1,tran_start_time2,tran_end_time2 from dbo.fun_GetMonthWorkTime(" . $year . ",5) union select year,month,work_start_time,work_end_time,tran_start_time1,tran_end_time1,tran_start_time2,tran_end_time2 from dbo.fun_GetMonthWorkTime(" . $year . ",6) union select year,month,work_start_time,work_end_time,tran_start_time1,tran_end_time1,tran_start_time2,tran_end_time2 from dbo.fun_GetMonthWorkTime(" . $year . ",7) union select year,month,work_start_time,work_end_time,tran_start_time1,tran_end_time1,tran_start_time2,tran_end_time2 from dbo.fun_GetMonthWorkTime(" . $year . ",8) union select year,month,work_start_time,work_end_time,tran_start_time1,tran_end_time1,tran_start_time2,tran_end_time2 from dbo.fun_GetMonthWorkTime(" . $year . ",9) union select year,month,work_start_time,work_end_time,tran_start_time1,tran_end_time1,tran_start_time2,tran_end_time2 from dbo.fun_GetMonthWorkTime(" . $year . ",10) union select year,month,work_start_time,work_end_time,tran_start_time1,tran_end_time1,tran_start_time2,tran_end_time2 from dbo.fun_GetMonthWorkTime(" . $year . ",11) union select year,month,work_start_time,work_end_time,tran_start_time1,tran_end_time1,tran_start_time2,tran_end_time2 from dbo.fun_GetMonthWorkTime(" . $year . ",12)";
    echo json_encode($db -> query($sql));
?>
