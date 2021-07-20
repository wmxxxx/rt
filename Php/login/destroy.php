<?php
	session_start();
    $redis = new Redis();
    $redis -> connect('127.0.0.1', 6379);
    var_dump($redis -> delete('PHPREDIS_SESSION:' . session_id()));
    //var_dump($redis -> delete('config'));
?>
