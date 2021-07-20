<?php
    header("Content:text/html;charset=utf-8");
	/*
	 * Created on 2018-09-16
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
    session_start();
    if(array_key_exists('user',$_GET) && array_key_exists('pass',$_GET)){
	    include_once($_SERVER['DOCUMENT_ROOT'] . "/Php/lib/base.php");
        $sql = "exec proc_A_LoginUserAuth '" . $_GET["user"] . "','" . $_GET["pass"] . "','" . $_SERVER["REMOTE_ADDR"] . "',''";
	    $result = $db -> query($sql);
	    if(count($result) > 0){
		    $obj = reset($result);
		    $resObj = array(
		        "status" => 1,
		        "code" => $obj -> F_UserCode,
		        "id" => $obj -> F_UserID,
		        "name" => urlencode($obj -> F_UserName),
		        "type" => $obj -> F_UserType,
		        "type_name" => $obj -> F_TypeName,
		        "email" => base64_encode($obj -> F_Email),
		        "mobile" => base64_encode($obj -> F_Mobile),
                "logged" => false,
                "ui" => $obj -> F_UI != null ? $obj -> F_UI : 'd',
		        "kanban" => $obj -> F_MyKanban,
		        "project" => $obj -> F_MyProject
	        );
            $redis = new Redis();
            $redis -> connect('127.0.0.1', 6379);
            $s_keys = $redis -> keys('PHPREDIS_SESSION:' . "*");
            for($i = 0;$i < count($s_keys);$i++){
                $user_obj = $redis -> get($s_keys[$i]);
                if($user_obj && $user_obj != ''){
                    $user_codes = explode(";",$user_obj); 
                    $user_code= explode(":",$user_codes[3]); 
                    if(json_decode($user_code[2]) == $obj -> F_UserCode){
                        $resObj["logged"] = true;
                    }
                }
            }
            $_SESSION['user'] = $resObj;
	    }
    }
    echo('
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
	        <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <title></title>
            <link type="text/css" rel="stylesheet" href="/Lib/zTree/zTreeStyle.css"/>
            <link type="text/css" rel="stylesheet" href="/Lib/kindeditor/themes/default/default.css" />
            <link type="text/css" rel="stylesheet" href="/Resources/css/comm.css" />
            <link type="text/css" rel="stylesheet" href="/Resources/css/init.css" />
            <link type="text/css" rel="stylesheet" href="/Resources/css/login.css" />
            <link type="text/css" rel="stylesheet" href="/Resources/css/main.css" />
            <link type="text/css" rel="stylesheet" href="/Resources/css/user.css" />
            <link type="text/css" rel="stylesheet" href="/Resources/css/project.css" />
            <link type="text/css" rel="stylesheet" href="/Resources/css/relation.css" />
            <link type="text/css" rel="stylesheet" href="/Resources/css/model.css" />
            <link type="text/css" rel="stylesheet" href="/Resources/css/plugin.css" />
            <link type="text/css" rel="stylesheet" href="/Resources/css/build.css" />
            <link type="text/css" rel="stylesheet" href="/Resources/css/role.css" />
            <link type="text/css" rel="stylesheet" href="/Resources/css/agent.css" />
            <link type="text/css" rel="stylesheet" href="/Resources/css/log.css" />
            <link type="text/css" rel="stylesheet" href="/Resources/css/theme.css" />
            <link type="text/css" rel="stylesheet" href="/Resources/css/event.css" />
            <link type="text/css" rel="stylesheet" href="/Resources/css/account.css" />
            <link type="text/css" rel="stylesheet" href="/Resources/css/file.css" />
            <link type="text/css" rel="stylesheet" href="/Resources/css/calendar.css" />
            <link type="text/css" rel="stylesheet" href="/Resources/css/service.css" />
            <link type="text/css" rel="stylesheet" href="/Resources/css/wechat.css" />
            <link type="text/css" rel="stylesheet" href="/Resources/css/plan.css" />
            <link type="text/css" rel="stylesheet" href="/Resources/css/dispatch.css" />
            <link type="text/css" rel="stylesheet" href="/Resources/css/mail.css" />
            <link type="text/css" rel="stylesheet" href="/Resources/css/alarm.css" />
            <link type="text/css" rel="stylesheet" href="/Resources/css/storage.css" />
            <link type="text/css" rel="stylesheet" href="/Resources/css/backup.css" />
            <link type="text/css" rel="stylesheet" href="/Resources/css/setup.css" />
            <script type="text/javascript" src="/Lib/jquery.min.js"></script>
            <script type="text/javascript" src="/Lib/jquery.cookie.js"></script>
            <script type="text/javascript" src="/Lib/jquery.timer.js"></script>
            <script type="text/javascript" src="/Lib/jquery.mousewheel.js"></script>
            <script type="text/javascript" src="/Lib/modernizr.2.5.3.min.js"></script>
            <script type="text/javascript" src="/Lib/jwplayer/jwplayer.js"></script>
            <script type="text/javascript" src="/Lib/component/datepicker/WdatePicker.js"></script>
            <script type="text/javascript" src="/Lib/zTree/jquery.ztree.min.js"></script>
            <script type="text/javascript" src="/Lib/zTree/jquery.ztree.excheck-3.5.min.js"></script>
            <script type="text/javascript" src="/Lib/kindeditor/kindeditor-min.js"></script>
            <script type="text/javascript" src="/Lib/kindeditor/zh_CN.js"></script>
            <script type="text/javascript" src="/Lib/socket/socket.io.js"></script>
            <script type="text/javascript" src="/Lib/base.js"></script>
        </head>
        <body>
            <div id="deskContainer">
		        <div id="loginFrame" class="deskPanel"></div>
                <div id="deskFrame" class="deskPanel"></div>
                <div id="userFrame" class="userPanel"></div>
                <div id="mainFrame" class="deskPanel"></div>
                <div id="coverFrame" class="deskPanel"></div>
                <div id="kanbanFrame" class="deskPanel"><div class="kanbanPanel"></div><div class="kanbanHide"></div></div>
	        </div>
            <div id="loadingCover">
                <div id="loadingLogo"><img alt="" src="/Resources/images/custom/logo.gif"/></div>
		        <div id="loadingText">正在启动...</div>
		        <div id="loadingBar"></div>
		        <div class="loadingVer"></div>
	        </div>
            <div id="warningCover">
		        <div id="warningLogo"></div>
		        <div id="warningText">您的浏览器版本太低，无法正常使用&nbsp;Things Smart Platform<br/>请安装<a href="http://www.google.cn/intl/zh-CN/chrome/"><font size="3" color="#ffff99">下载</font></a>最新的Chrome浏览器，然后重试。</div>
		        <div class="loadingVer"></div>
	        </div>

            <script type="text/javascript" src="/Scripts/portal.js"></script>
            <script type="text/javascript" src="/Scripts/init.js"></script>
            <script type="text/javascript" src="/Scripts/login.js"></script>
            <script type="text/javascript" src="/Scripts/main.js"></script>
            <script type="text/javascript" src="/Scripts/user.js"></script>
            <script type="text/javascript" src="/Scripts/project.js"></script>
            <script type="text/javascript" src="/Scripts/relation.js"></script>
            <script type="text/javascript" src="/Scripts/model.js"></script>
            <script type="text/javascript" src="/Scripts/plugin.js"></script>
            <script type="text/javascript" src="/Scripts/build.js"></script>
            <script type="text/javascript" src="/Scripts/role.js"></script>
            <script type="text/javascript" src="/Scripts/agent.js"></script>
            <script type="text/javascript" src="/Scripts/log.js"></script>
            <script type="text/javascript" src="/Scripts/theme.js"></script>
            <script type="text/javascript" src="/Scripts/event.js"></script>
            <script type="text/javascript" src="/Scripts/account.js"></script>
            <script type="text/javascript" src="/Scripts/file.js"></script>
            <script type="text/javascript" src="/Scripts/calendar.js"></script>
            <script type="text/javascript" src="/Scripts/service.js"></script>
            <script type="text/javascript" src="/Scripts/wechat.js"></script>
            <script type="text/javascript" src="/Scripts/plan.js"></script>
            <script type="text/javascript" src="/Scripts/dispatch.js"></script>
            <script type="text/javascript" src="/Scripts/mail.js"></script>
            <script type="text/javascript" src="/Scripts/alarm.js"></script>
            <script type="text/javascript" src="/Scripts/storage.js"></script>
            <script type="text/javascript" src="/Scripts/backup.js"></script>
            <script type="text/javascript" src="/Scripts/setup.js"></script>
        </body>
    </html>');
?>
