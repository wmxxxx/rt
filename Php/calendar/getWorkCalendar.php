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
    $sql = "select YEAR(F_Date) as year,MONTH(F_Date) as month,DAY(F_Date) as day,F_Status as status from dbo.tb_D_WorkCalendar where YEAR(F_Date) = " . $year;
    echo json_encode($db -> query($sql));
?>
