<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/plugin/share.php");

	$result = $db -> query("select distinct b.F_ValueName,b.F_ValueLabel,b.F_KV from [Things].[dbo].[tb_A_Template] a,[Things].[dbo].[tb_A_Value] b where a.F_TemplateLabel in ('".implode("','",$_POST["tpl"])."') and a.F_TemplateCode = b.F_TemplateCode and b.F_IsDisplay = 1 and b.F_ValueType = 2");
	ob_clean();
	echo json_encode($result);
?>
