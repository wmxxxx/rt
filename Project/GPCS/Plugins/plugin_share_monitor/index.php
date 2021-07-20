<?php
	$url = "location:index.html?code=".$_GET["code"]."&fun=".$_GET["fun"]."&app=".$_GET["app"]."&user=".$_GET["user"];
	if(array_key_exists('data',$_GET))$url.="&data=".$_GET["data"];
	header($url);
?>
