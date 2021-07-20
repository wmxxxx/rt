<?php
	/*
	 * Created on 2014-4-14
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
    
    $redis = new Redis();
    $redis -> connect('127.0.0.1', 6379);
    if(!$redis -> get("config")){
        $config_str = file_get_contents('../../data.json');
        if (preg_match('/^\xEF\xBB\xBF/',$config_str)){
	        $config_str = substr($config_str,3);
        }
        $redis -> set("config",$config_str);
    }
    $config = json_decode($redis -> get("config"),true);        
	echo json_encode('{ "p_cname":"' . $config['custom']['p_cname'] . '","p_ename":"' . $config['custom']['p_ename'] . '","c_cname":"' . $config['custom']['c_cname'] . '","c_ename":"' . $config['custom']['c_ename'] . '","copr":"' . $config['custom']['copr'] . '","support":"' . $config['custom']['support'] . '","url":"' . $config['custom']['url'] . '","productKey":"' . (array_key_exists('productKey',$config['settings']) ? $config['settings']['productKey'] : '') . '"}');
?>
