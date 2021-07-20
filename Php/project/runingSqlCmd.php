<?php
	/*
	 * Created on 2014-4-14
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
    include_once("../lib/comm.php");
	
    $sql = $_POST["sql"];
    
    $connect = null;
    $redis = new Redis();
    $redis -> connect('127.0.0.1', 6379);
    if(!$redis -> get("config")){
        $config_str = file_get_contents(dirname(dirname(dirname(__FILE__))) . '\data.json');
        if (preg_match('/^\xEF\xBB\xBF/',$config_str)){
	        $config_str = substr($config_str,3);
        }
        $config = json_decode($config_str,false);
		$connect = sqlsrv_connect($config -> db -> dbHost, array("UID"=>$config -> db -> dbUser, "PWD"=>$config -> db -> dbPwd, "Database"=>$config -> db -> dbName, "CharacterSet" => "UTF-8"));
    }else{
        $config = json_decode($redis -> get("config"),true);
		$connect = sqlsrv_connect($config['db']['dbHost'], array("UID"=>$config['db']['dbUser'], "PWD"=>$config['db']['dbPwd'], "Database"=>$config['db']['dbName'], "CharacterSet" => "UTF-8"));
    }
            
    $resArray = array();
	$result = sqlsrv_query($connect,$sql);
	if($result === false){
        $error = sqlsrv_errors();
     	echo '{"status": -1, "msg": "脚本执行异常！<br>' . $error[0]['message'] . '"}';
        exit;
	}
    $rows_affected = sqlsrv_rows_affected( $result);
    
    $index = 0;
    $resMap = new stdClass;
    do{
        $index++;
        $row = '返回结果集' . $index;
	    $resMap -> $row = array();
        while( $obj = sqlsrv_fetch_object( $result)){
			array_push($resMap -> $row,$obj);
		}
    }while(sqlsrv_next_result($result));
	sqlsrv_free_stmt($result);
	sqlsrv_close($connect);

    if($index == 0){
        echo '{"status": 1, "msg": "脚本执行成功！<br>受影响的行数：' . $rows_affected . '"}';
    }else{
        echo('<html>
            <head>
                <meta charset="utf-8">
	            <meta http-equiv="X-UA-Compatible" content="IE=Edge">
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	            <title></title>
                <link type="text/css" rel="stylesheet" href="/Lib/jsonview/jquery.jsonview.css" />
	            <style type="text/css">
		            body,div,td{ font: 13px/1.4 Arial,Tahoma,微软雅黑; }
                    body{ margin: 16px 8px 8px 8px; }
		            td{ line-height:22px; }
	            </style>
                <script type="text/javascript" src="/Lib/jquery.min.js"></script>
                <script type="text/javascript" src="/Lib/jsonview/jquery.jsonview.js"></script>
                <script type="text/javascript">
                    $(function() {
                        $("#json-collapsed").JSONView(' . json_encode($resMap) . ', {collapsed: false, nl2br: true});
                    });
                </script>
	        </head>
            <body>
                <div id="json-collapsed"></div>
            </body>
        </html>');
    }
?>
