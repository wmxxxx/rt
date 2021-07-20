<?php
    header("Content:text/html;charset=utf-8");
	/*
	 * Created on 2018-09-16
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once($_SERVER['DOCUMENT_ROOT'] . "/Php/lib/base.php");
    
    $sys_type = '2';
    $sys_no = '';
    $active_tag = '';
    $param_data = '';
    session_start();
    if(!isset($_SESSION['user'])){
	    $headers = getallheaders();
        if(array_key_exists('Authorization',$headers)){
            $str_authorization = base64_decode(substr($headers['Authorization'],6));
            $array_authorization = explode(':',$str_authorization);
            $userID = $array_authorization[0];
	        $passHash = sha1($array_authorization[0] . '-' . $array_authorization[1]);
            $sys_no = $_GET["appid"];
            $sql = "exec proc_A_LoginUserAuth_Ext '" . $userID . "','" . $passHash . "','" . $_SERVER["REMOTE_ADDR"] . "','" . $sys_no . "'";
	        $result = $db -> query($sql);
	        if(count($result) > 0){
		        $record = reset($result);
                if($record -> F_HasAuth == 1){
		            $resObj = array(
		                "status" => 1,
		                "code" => $record -> F_UserCode,
		                "id" => $record -> F_UserID,
                        "name" => urlencode($record -> F_UserName),
		                "type" => $record -> F_UserType,
		                "type_name" => $record -> F_TypeName,
                        "email" => base64_encode($record -> F_Email),
		                "mobile" => base64_encode($record -> F_Mobile),
                        "ui" => $record -> F_UI != null ? $record -> F_UI : 'd',
		                "kanban" => $record -> F_MyKanban,
		                "project" => $record -> F_MyProject
	                );
                    $_SESSION['user'] = $resObj;
                }else if($record -> F_HasAuth == 0){
                    echo '您没有该系统的访问权限！';
                    exit;
                }else if($record -> F_HasAuth == -1){
                    echo '您访问的系统不存在！';
                    exit;
                }
	        }else{
                header("HTTP/1.0 401");
                header('WWW-Authenticate:Basic realm=""'); 
                echo 'Things Http Server Unauthorized!';
                exit;
            }
        }else if(array_key_exists('user',$_GET) && array_key_exists('pass',$_GET) && array_key_exists('appid',$_GET)){
            $sys_no = $_GET["appid"];
            $sql = "exec proc_A_LoginUserAuth_Ext '" . $_GET["user"] . "','" . $_GET["pass"] . "','" . $_SERVER["REMOTE_ADDR"] . "','" . $sys_no . "'";
	        $result = $db -> query($sql);
	        if(count($result) > 0){
		        $record = reset($result);
                if($record -> F_HasAuth == 1){
		            $resObj = array(
		                "status" => 1,
		                "code" => $record -> F_UserCode,
		                "id" => $record -> F_UserID,
                        "name" => urlencode($record -> F_UserName),
		                "type" => $record -> F_UserType,
		                "type_name" => $record -> F_TypeName,
                        "email" => base64_encode($record -> F_Email),
		                "mobile" => base64_encode($record -> F_Mobile),
                        "ui" => $record -> F_UI != null ? $record -> F_UI : 'd',
		                "kanban" => $record -> F_MyKanban,
		                "project" => $record -> F_MyProject
	                );
                    $_SESSION['user'] = $resObj;
                }else if($record -> F_HasAuth == 0){
                    echo '您没有该系统的访问权限！';
                    exit;
                }else if($record -> F_HasAuth == -1){
                    echo '您访问的系统不存在！';
                    exit;
                }
	        }else{
                echo '账户无效或登录身份验证失败！';
                exit;
            }
        }else if(array_key_exists('appid',$_GET)){
            header("HTTP/1.0 401");
            header('WWW-Authenticate:Basic realm=""'); 
            echo 'Things Http Server Unauthorized!';
            exit;
        }else{
            echo '无效的访问路径！';
            exit;
        }
    }else if(array_key_exists('appid',$_GET)){
        $sys_no = $_GET["appid"];
        $sys_type = array_key_exists('type',$_GET) ? $_GET["type"] : '2';
        $active_tag = array_key_exists('tag',$_GET) ? $_GET["tag"] : '';
        $param_data = array_key_exists('data',$_GET) ? $_GET["data"] : '';
        $sql = "select dbo.fun_GetUserProjectAuth( " . $sys_no . ",'" . $_SESSION['user']['code'] . "') as has_auth";
        $result = reset($db -> query($sql));
	    if($result -> has_auth == 0){
            echo '您没有该系统的访问权限！';
            exit;
        }else if($result -> has_auth == -1){
            echo '您访问的系统不存在！';
            exit;
        }
    }else{
        echo '请提交系统工程号（参数名:appid）！';
        exit;
    }
echo('
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title></title>
        <link type="text/css" rel="stylesheet" href="/Lib/mapbox/map.css" />
        <link type="text/css" rel="stylesheet" href="/Lib/zTree/zTreeStyle.css"/>
        <link type="text/css" rel="stylesheet" href="/Lib/layui/css/layui.css" />
        <link type="text/css" rel="stylesheet" href="/Resources/css/component/green.css" />
        <link type="text/css" rel="stylesheet" href="/Resources/css/base.css" />
    </head>
    <body>
        <script type="text/javascript">
            var sys_type = ' . $sys_type . ',sys_no = ' . $sys_no . ',active_tag = "' . $active_tag . '",param_data = \'' . $param_data . '\'
        </script>
        <div id="mainPanel"></div>
        <script type="text/javascript" src="/Lib/jquery.min.js"></script>
        <script type="text/javascript" src="/Lib/jquery.mousewheel.js"></script>
        <script type="text/javascript" src="/Lib/jquery.timer.js"></script>
        <script type="text/javascript" src="/Lib/layui/layui.js"></script>
        <script type="text/javascript" src="/Lib/mapbox/mapbox.js"></script>
        <script type="text/javascript" src="/Lib/zTree/jquery.ztree.min.js"></script>
        <script type="text/javascript" src="/Lib/zTree/jquery.ztree.excheck-3.5.min.js"></script>
        <script type="text/javascript" src="/Lib/highcharts/code/highcharts.js"></script>
        <script type="text/javascript" src="/Lib/echarts/echarts.js"></script>
        <script type="text/javascript" src="/Lib/svgjs/svgmap.js"></script>
        <script type="text/javascript" src="/Lib/base.js"></script>
        <script type="text/javascript" src="Scripts/main.js"></script>
    </body>
</html>');
?>
