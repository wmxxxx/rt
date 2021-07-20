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
            <title>功能管理</title>
            <link type="text/css" rel="stylesheet" href="/Lib/mapbox/map.css" />
            <link type="text/css" rel="stylesheet" href="/Lib/zTree/zTreeStyle.css"/>
            <link type="text/css" rel="stylesheet" href="/Resources/css/plugin.css" />
        </head>
        <body>
            <div id="mainPanel" class="deskPanel"></div>
            <script type="text/javascript" src="/Lib/jquery.min.js"></script>
            <script type="text/javascript" src="/Lib/jquery.mousewheel.js"></script>
            <script type="text/javascript" src="/Lib/mapbox/mapbox.js"></script>
            <script type="text/javascript" src="/Lib/zTree/jquery.ztree.min.js"></script>
            <script type="text/javascript" src="/Lib/zTree/jquery.ztree.excheck-3.5.min.js"></script>
            <script type="text/javascript" src="/Lib/datepicker/WdatePicker.js"></script>
            <script type="text/javascript" src="/Lib/highcharts-5.0.6/code/highcharts.js"></script>
            <script type="text/javascript" src="/Lib/echarts/echarts.js"></script>
            <script type="text/javascript" src="/Lib/base.js"></script>
            <script type="text/javascript" src="/Scripts/plugin/main.js"></script>
        </body>
    </html>');
?>
