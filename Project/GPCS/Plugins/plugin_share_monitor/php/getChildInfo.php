<?php
	header("Content:text/html;charset=utf-8");
	include_once(dirname(dirname(dirname(dirname(__FILE__))))."/Php/lib/plugin/share.php");

	$base = base();
	$url = $base -> ip."/API/RData/GetMultiDeviceVariantData/?type_id=".implode(",",$_POST["device"])."&entity_str=".$_POST["entity"]."&haschild=1";
	if(isset($_POST["filter_str"]))$url .= "&filter_str=".$_POST["filter_str"];
	$node = curl($url);
	if(count($node) && is_array($node))$node = intactAttr($node,$_POST["app"]);
	$access = checkAccess($_POST["app"],$_POST["entity"],$_POST["type"]);
	$result = array(
		"node" => $node,
		"access" => $access
	);
	ob_clean();
	echo json_encode($result);
?>
