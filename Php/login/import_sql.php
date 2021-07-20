<?php
	/*
	 * Created on 2014-4-14
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
    header("content-type:text/html; charset=utf-8"); //设置编码 
    
    $db_json = json_decode($_POST["db"],false);
    
	$conn_str = array("UID"=>$db_json->dbUser, "PWD"=>$db_json->dbPwd, "Database"=>$db_json->dbName, "CharacterSet" => "UTF-8");
    $conn = sqlsrv_connect($db_json->dbHost, $conn_str);
	if($conn){
        $msg = '';
        $things_db = array();
		$result = sqlsrv_query($conn,"SELECT Name FROM master..sysdatabases WHERE name='Things'");
        while( $obj = sqlsrv_fetch_object( $result)){
			array_push($things_db,$obj);
		}
        if(count($things_db) == 0){
            $msg = '请创建Things数据库！\n';
        }
        $thingsp_db = array();
		$result = sqlsrv_query($conn,"SELECT Name FROM master..sysdatabases WHERE name='Things+'");
        while( $obj = sqlsrv_fetch_object( $result)){
			array_push($thingsp_db,$obj);
		}
        if(count($thingsp_db) == 0){
            $msg = $msg . '请创建Things+数据库！\n';
        }
        if($msg != ''){
            echo '{"status":false,"msg":"' . $msg . '"}';
            exit;
        }        
        
        $setup_sql = file_get_contents($_SERVER['DOCUMENT_ROOT'] . 'setup.sql');
        if (preg_match('/^\xEF\xBB\xBF/',$setup_sql)){
	        $setup_sql = substr($setup_sql,3);
        }
		$result = sqlsrv_query($conn,$setup_sql);
		if( $result === false){
            $error = sqlsrv_errors();
      		$msg = '平台主架构脚本执行失败！（' . $error[0]['message'] . '）\n';
		}else{
            $msg = '平台主架构脚本执行成功！\n';
        }
        $data_sql = file_get_contents($_SERVER['DOCUMENT_ROOT'] . 'data.sql');
        if (preg_match('/^\xEF\xBB\xBF/',$data_sql)){
	        $setup_sql = substr($data_sql,3);
        }
		$result = sqlsrv_query( $conn,$data_sql);
		if( $result === false){
      		$error = sqlsrv_errors();
      		$msg = $msg . '平台主数据脚本执行失败！（' . $error[0]['message'] . '）\n';
		}else{
            $msg = $msg . '平台主数据脚本执行成功！\n';
        }
        
        $plugins = scandir($_SERVER['DOCUMENT_ROOT'] . 'Plugins');
        foreach($plugins as $plugin){
            if($plugin != '.' && $plugin != '..' && file_exists($_SERVER['DOCUMENT_ROOT'] . 'Plugins' . '/' . $plugin . '/setup.sql')){
                $plugin_sql = file_get_contents($_SERVER['DOCUMENT_ROOT'] . 'Plugins' . '/' . $plugin . '/setup.sql');
		        $result = sqlsrv_query($conn,iconv("utf-8", "gbk//IGNORE",$plugin_sql));
		        if( $result === false){
                    $error = sqlsrv_errors();
      		        $msg = $msg . '插件[' . $plugin . ']初始化脚本执行失败！（' . $error[0]['message'] . '）\n';
		        }else{
                    $msg = $msg . '插件[' . $plugin . ']初始化脚本执行成功！';
                }
            }
        }
	    //sqlsrv_free_stmt($result);
        echo '{"status":' . ($msg == '' ? 'true' : 'false') . ',"msg":"' . $msg . '"}';

    }else{
        $error = sqlsrv_errors();
        echo '{"status":false,"msg":"测试连接失败！\n' . $error[0]['message'] . '"}';
    }
?>
