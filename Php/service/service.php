<?php
	/*
	 * Created on 2014-4-14
	 *
	 * To change the template for this generated file go to
	 * Window - Preferences - PHPeclipse - PHP - Code Templates
	 */
	header("content-type:text/html; charset=utf-8"); //设置编码
    
	$win_service = new WinService($_SERVER["DOCUMENT_ROOT"]);
    $win_service ->install();

    class WinService  
    {
        public $name;
        public $info_name;  
        public $path;
        public $params;
        
        public function __construct($root)  
        {  
            $this -> name = "Things Smart Service";  
            $this -> display = "Things Smart Service";  
            $this -> path = "D:\wamp\bin\php\php5.3.10\php.exe"; 
            $this -> params = "D:\\wamp\\www\\Php\\service\\task.php";
        } 
        public function install()  
        {
            /* 注册服务  */  
            $x = win32_create_service(array(  
                'service' => $this -> name,  
                'display' => $this -> display,  
                'path' => $this -> path,  
                'params' => $this -> params,  
            ));
  
            if ($x !== true) {
                die ($this -> name . '服务创建失败！（'. $x . '）');  
            } else {
                /* 启动服务 */  
                win32_start_service($this -> name);  
                die ($this -> name . '服务创建成功！');  
            }
        }
        public function uninstall()
        {
            /* 移除服务 */
            $removeService = win32_delete_service($this -> name);  
            switch ($removeService) {  
                case 1060:  
                    die($this -> name . '服务不存在！');  
                    break;  
                case 1072:  
                    die($this -> name . '服务不能被正常移除！');  
                    break;  
                case 0:  
                    die($this -> name . '服务已被成功移除！');  
                    break;  
                default:  
                    die();  
                    break;  
            }
        }
        public function restart()  
        {
            /* 重启服务 */  
            $svcStatus = win32_query_service_status($this -> name);  
  
            if ($svcStatus == 1060) {  
                echo $this -> name . "服务未安装，请先安装！";  
            } else {  
                if ($svcStatus['CurrentState'] == 1) {  
                    $s = win32_start_service($this -> name);  
                    if ($s != 0) {  
                        echo $this -> name . "服务无法被启动，请重试！";  
                    } else {  
                        echo $this -> name . "服务已启动！";  
                    }
                } else {  
                    $s = win32_stop_service($this -> name);  
                    if ($s != 0) {  
                        echo $this -> name . "服务正在执行，请重试！";  
                    } else {  
                        $s = win32_start_service($this -> name);  
                        if ($s != 0) {  
                            echo $this -> name . "服务无法被启动，请重试！";  
                        } else {  
                            echo $this -> name . "服务已启动！";  
                        }
                    }
                }
            }
        }
        public function start()  
        {  
            $s = win32_start_service($this -> name);  
            if ($s != 0) {  
                echo $this -> name . "服务正在运行中！";  
            } else {  
                echo $this -> name . "服务已启动！";  
            }  
        }
        public function stop()  
        {  
            $s = win32_stop_service($this -> name);  
            if ($s != 0) {  
                echo $this -> name . "服务未启动！";  
            } else {  
                echo $this -> name . "服务已停止！";  
            }  
        }
    }
?>
