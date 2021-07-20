<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/plugin/share.php");

    $type = $_POST["type"];
    if($type == "save"){
        $result = $db -> execute("delete [Things+].[dbo].[TB_Common_TimeTable_Config];insert into [Things+].[dbo].[TB_Common_TimeTable_Config] values('".json_encode(json_decode($_POST["param"]))."');");
        ob_clean();
        echo $result;
    }else if($type == "get"){
    	$result = $db -> query("select * from [Things+].[dbo].[TB_Common_TimeTable_Config]");
        ob_clean();
        echo count($result) == 0 ? 0 :$result[0] -> F_Config;
    }
?>
