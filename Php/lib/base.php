<?php
	/*
	 * Created on 2014-4-14
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
    header("content-type:text/html; charset=utf-8"); //设置编码 
    $db = new DB();
	class DB{
		public $dbHost;
		public $dbUser;
		public $dbPwd;
		public $dbName;
		public $connect;

		public function __construct(){
	        $redis = new Redis();
            $redis -> connect('127.0.0.1', 6379);
            if(!$redis -> get("config")){
                $config_str = file_get_contents(dirname(dirname(dirname(__FILE__))) . '\data.json');
                if (preg_match('/^\xEF\xBB\xBF/',$config_str)){
	                $config_str = substr($config_str,3);
                }
                $config = json_decode($config_str,false);
			    $this->dbHost = $config -> db -> dbHost;
			    $this->dbUser = $config -> db -> dbUser;
			    $this->dbPwd = $config -> db -> dbPwd;
			    $this->dbName = $config -> db -> dbName;
			    $this->connectionInfo = array("UID"=>$this->dbUser, "PWD"=>$this->dbPwd, "Database"=>$this->dbName, "CharacterSet" => "UTF-8");
			    $this->connect = sqlsrv_connect($this->dbHost, $this->connectionInfo);
                if($this->connect){
                    $redis -> set("config",$config_str);
                }else{
                    die(var_dump(sqlsrv_errors()));
                }
            }else{
                $config = json_decode($redis -> get("config"),true);
			    $this->dbHost = $config['db']['dbHost'];
			    $this->dbUser = $config['db']['dbUser'];
			    $this->dbPwd = $config['db']['dbPwd'];
			    $this->dbName = $config['db']['dbName'];
			    $this->connectionInfo = array("UID"=>$this->dbUser, "PWD"=>$this->dbPwd, "Database"=>$this->dbName, "CharacterSet" => "UTF-8");
			    $this->connect = sqlsrv_connect($this->dbHost, $this->connectionInfo) or die(var_dump(sqlsrv_errors()));
            }
            error_reporting(E_ALL);
		}
	
		public function query($sql){
			$resArray = array();
			$result = sqlsrv_query($this->connect,$sql);
			if( $result === false){
     			die( print_r( sqlsrv_errors(), true));
			}
			while( $obj = sqlsrv_fetch_object( $result)){
				array_push($resArray,$obj);
			}
			sqlsrv_free_stmt($result);
			return $resArray;
		}
        
        public function multi_query($sql){
			$resArray = array();
			$result = sqlsrv_query($this->connect,$sql);
			if( $result === false){
     			die( print_r( sqlsrv_errors(), true));
			}
            do{
                unset($resArray);
                $resArray = array();
                while( $obj = sqlsrv_fetch_object( $result)){
			        array_push($resArray,$obj);
		        }
            }while(sqlsrv_next_result($result));
			sqlsrv_free_stmt($result);
			sqlsrv_close($this->connect);
			return $resArray;
		}
	
		public function execute($sql){
			$resFlg = false;
			$result = sqlsrv_prepare( $this->connect,$sql);
			if( sqlsrv_execute( $result)){
      			$resFlg = true;
			}
			sqlsrv_free_stmt($result);
			return $resFlg;
		}
        
		public function close(){
			@sqlsrv_close($this->connect);
		}

		public function __destruct(){
			@sqlsrv_close($this->connect);
		}
	}
?>
