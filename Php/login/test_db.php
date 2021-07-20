<?php
	/*
	 * Created on 2014-4-14
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
    header("content-type:text/html; charset=utf-8"); //设置编码 
    
    $db_json = json_decode($_POST["db"],false);
    
	$conn = array("UID"=>$db_json->dbUser, "PWD"=>$db_json->dbPwd, "Database"=>$db_json->dbName, "CharacterSet" => "UTF-8");
	if(sqlsrv_connect($db_json->dbHost, $conn)){
        echo '{"status":true,"msg":"测试连接成功！"}';
    }else{
        $error = sqlsrv_errors();
        echo '{"status":false,"msg":"测试连接失败！\n' . $error[0]['message'] . '"}';
    }
?>
