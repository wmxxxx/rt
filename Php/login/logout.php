<?php
    header("Content:text/html;charset=utf-8");
	/*
	 * Created on 2015-12-11
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
    session_start();
    session_destroy();
    echo '{"status":1}';
?>
