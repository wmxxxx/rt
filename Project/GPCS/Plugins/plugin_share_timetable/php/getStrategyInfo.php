<?php
    header("Content:text/html;charset=utf-8");
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/plugin/share.php");

    $sql = "select distinct F_name,F_code,F_keyid from [Things+].dbo.TB_Common_StrategyInfo where F_project_code='".$_POST["app"]."'";
    $result = $db -> query($sql);

	$obj = new StdClass;
    $obj->rows = $result;
ob_clean();
    echo json_encode($result);
?>