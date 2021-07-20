<?php
    header("Content:text/html;charset=utf-8");
	/*
	 * Created on 2014-4-28
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	include_once("../lib/file.php");

	$s_tag = $_POST["s_tag"];
	$f_type = $_POST["f_type"];
	$text =$_POST["mark"];

    if(File::savefile("../../Project/" . $s_tag . "/Resources/files/" . $s_tag . "_" . $f_type . ".md", "\xEF\xBB\xBF". $text)){
        echo json_encode(array('status' => 1));
    }else{
        echo json_encode(array('status' => 0));
    }
    function unescape($str) {
	    $str = rawurldecode ( $str );
	    preg_match_all ( "/%u.{4}|&#x.{4};|&#\d+;|.+/U", $str, $r );
	    $ar = $r [0];
	    foreach ( $ar as $k => $v ) {
		    if (substr ( $v, 0, 2 ) == "%u")
			    $ar [$k] = iconv ( "UCS-2", "GBK", pack ( "H4", substr ( $v, - 4 ) ) );
		    elseif (substr ( $v, 0, 3 ) == "&#x")
			    $ar [$k] = iconv ( "UCS-2", "GBK", pack ( "H4", substr ( $v, 3, - 1 ) ) );
		    elseif (substr ( $v, 0, 2 ) == "&#") {
			    $ar [$k] = iconv ( "UCS-2", "GBK", pack ( "n", substr ( $v, 2, - 1 ) ) );
		    }
	    }
	    return join ( "", $ar );
    }
?>
