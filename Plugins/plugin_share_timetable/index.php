<?php
	header("Content:text/html;charset=utf-8");
	echo(
		'<!DOCTYPE html>
		<html lang="zh-cn">
			<head>
				<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1">
				<link type="text/css" rel="stylesheet" href="/Lib/mapbox/map.css" />
				<link type="text/css" rel="stylesheet" href="/Lib/poshytip/tip-skyblue.css" />
				<link type="text/css" rel="stylesheet" href="/Lib/poshytip/tip-skygray.css" />
				<link type="text/css" rel="stylesheet" href="/Lib/zTree/zTreeStyle.css"/>
				<link type="text/css" rel="stylesheet" href="/Resources/css/base.css" />
				<link type="text/css" rel="stylesheet" href="dist/css/zui.css">
				<link type="text/css" rel="stylesheet" href="index.css">
			</head>
			<body>
				<script type="text/javascript">
					var code='.$_GET["code"].',fun='.$_GET["fun"].',app='.$_GET["app"].',user='.$_GET["user"].',version="v1.0";
				</script>
				<div class="mainPanel"></div>
				<script type="text/javascript" src="/Lib/jquery.min.js"></script>
				<script type="text/javascript" src="/Lib/jquery.mousewheel.js"></script>
				<script type="text/javascript" src="/Lib/mapbox/mapbox.js"></script>
				<script type="text/javascript" src="/Lib/poshytip/poshytip.js"></script>
				<script type="text/javascript" src="/Lib/zTree/jquery.ztree.min.js"></script>
				<script type="text/javascript" src="/Lib/zTree/jquery.ztree.excheck-3.5.min.js"></script>
				<script type="text/javascript" src="/Lib/datepicker/WdatePicker.js"></script>
				<script type="text/javascript" src="/Lib/highcharts-5.0.6/code/highcharts.js"></script>
				<script type="text/javascript" src="/Lib/echarts/echarts.js"></script>
				<script type="text/javascript" src="/Lib/svgmap.js"></script>
				<script type="text/javascript" src="/Lib/jquery.timer.js"></script>
				<script type="text/javascript" src="/Lib/base.js"></script>
				<script type="text/javascript" src="dist/js/zui.js"></script>
				<script type="text/javascript" src="dist/lib/selectable/zui.selectable.min.js"></script>
				<script type="text/javascript" src="index.js"></script>
			</body>
		</html>');
?>
