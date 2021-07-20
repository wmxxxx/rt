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
        $config_str = file_get_contents('../../data.json');
        if (preg_match('/^\xEF\xBB\xBF/',$config_str)){
	        $config_str = substr($config_str,3);
        }
        $config = json_decode($config_str,false);
        $config -> db -> dbType = $db_json -> dbType; 
        $config -> db -> dbHost = $db_json -> dbHost; 
        $config -> db -> dbName = $db_json -> dbName; 
        $config -> db -> dbUser = $db_json -> dbUser; 
        $config -> db -> dbPwd = $db_json -> dbPwd; 
        file_put_contents('../../data.json',json_encode($config));
        echo '{"status":true }';
    }else{
        $error = sqlsrv_errors();
        echo '{"status":false,"msg":"测试连接失败！\n' . $error[0]['message'] . '"}';
    }
?>
