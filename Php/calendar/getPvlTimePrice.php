<?php
	/*
	 * Created on 2015-12-20
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/comm.php");
	include_once("../lib/base.php");
    
	$energy = $_POST["energy"];
    $sql = "exec proc_D_GetPvlTimePrice " . $energy;
    echo json_encode($db -> query($sql));
?>
