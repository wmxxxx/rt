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
    echo('
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <title>业务模型</title>
            <link type="text/css" rel="stylesheet" href="/Lib/extjs/css/ext-all.css"/>
            <link type="text/css" rel="stylesheet" href="/Lib/extjs/css/ytheme-gray.css"/>
            <link type="text/css" rel="stylesheet" href="/Lib/extjs/css/comm.css"/>
            <link type="text/css" rel="stylesheet" href="/Lib/easyui/easyui.css"/>
            <link type="text/css" rel="stylesheet" href="/Lib/easyui/icon.css"/>
            <link type="text/css" rel="stylesheet" href="/Resources/css/model.css" />
        </head>
        <body>
            <div class="model">
                <div id="centerPanel" class="deskPanel"></div>
            </div>
            <script type="text/javascript" src="/Lib/jquery.min.js"></script>
            <script type="text/javascript" src="/Lib/extjs/ext-base.js"></script>
            <script type="text/javascript" src="/Lib/extjs/ext-all.js"></script>
            <script type="text/javascript" src="/Lib/extjs/ext-lang-zh_CN.js"></script>
            <script type="text/javascript" src="/Lib/extjs/monthPicker.js"></script>
            <script type="text/javascript" src="/Lib/easyui/jquery.easyui.min.js"></script>
            <script type="text/javascript" src="main.js"></script>
        </body>
    </html>');
?>
