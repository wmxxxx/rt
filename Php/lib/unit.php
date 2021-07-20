<?php
    /*
     * Created on 2018-11-30
     *
     * To change the template for this generated file go to
     * Window - Preferences - PHPeclipse - PHP - Code Templates
     */
 
    header("content-type:text/html; charset=utf-8"); //设置编码

    class SystemRun {
        public static function getFilePath($fileName, $content)
        {
            $path = dirname(__FILE__) . "\\vbs\\$fileName";
            if (!file_exists($path)) {
                file_put_contents($path, $content);
            }
            return $path;
        }
        public static function getCupUsageVbsPath()
        {   
            return SystemRun::getFilePath(
                'cpu_usage.vbs',
                "On Error Resume Next 
                Set objProc = GetObject(\"winmgmts:\\\\.\\root\cimv2:win32_processor='cpu0'\") 
                WScript.Echo(objProc.LoadPercentage)"
            );
        }
        public static function getMemoryUsageVbsPath()
        {
            return SystemRun::getFilePath(
                'memory_usage.vbs',
                "On Error Resume Next
                Set objWMI = GetObject(\"winmgmts:\\\\.\\root\cimv2\")
                Set colOS = objWMI.InstancesOf(\"Win32_OperatingSystem\")
                For Each objOS in colOS
                Wscript.Echo(\"{\"\"TotalVisibleMemorySize\"\":\" & objOS.TotalVisibleMemorySize & \",\"\"FreePhysicalMemory\"\":\" & objOS.FreePhysicalMemory & \"}\")
                Next"
            );
        }
        public static function getCpuUsage()
        {
            $path = SystemRun::getCupUsageVbsPath();
            exec("cscript -nologo $path", $usage);
            return $usage[0];
        }
        public static function getMemoryUsage()
        {
            $path = SystemRun::getMemoryUsageVbsPath();
            exec("cscript -nologo $path", $usage);
            $memory = json_decode($usage[0], true);
            $memory['usage'] = Round((($memory['TotalVisibleMemorySize'] - $memory['FreePhysicalMemory']) / $memory['TotalVisibleMemorySize']) * 100);
            return $memory;
        }
    }
?>
