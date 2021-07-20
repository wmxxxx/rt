<?php
    header("Content:text/html;charset=utf-8");
	/*
	 * Created on 2018-09-16
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	header("content-type:text/html; charset=utf-8"); //设置编码
    
    session_start();
    if(!isset($_SESSION['user'])){
	    echo '请先登录系统认证！';
        exit;
    }
    $code = $_GET["code"];
    $fun = $_GET["fun"];
    $app = $_GET["app"];
    $env = $_GET["env"];
    $data = array_key_exists('data',$_GET) ? $_GET["data"] : '';
    $user = $_GET["user"];
    echo('
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <title></title>
            <link type="text/css" rel="stylesheet" href="/Resources/css/base.css" />
        </head>
        <body>
            <script type="text/javascript">
                var code = ' . $code . ',fun = ' . $fun . ',app = ' . $app . ',user = ' . $user . ',env = unescape(' . $env . '),datainfo = unescape(' . $data . ')
            </script>
            <div id="mainPanel"></div>
            <script type="text/javascript" src="/Lib/jquery.min.js"></script>
            <script type="text/javascript" src="/Lib/jquery.mousewheel.js"></script>
            <script type="text/javascript" src="/Lib/base.js"></script>
        </body>
    </html>');
?>
