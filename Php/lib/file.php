<?php
    /*
     * Created on 2016-10-22
     *
     * To change the template for this generated file go to
     * Window - Preferences - PHPeclipse - PHP - Code Templates
     */
 
    header("content-type:text/html; charset=utf-8"); //设置编码

    class File {
        public static function makedir($dir){  
            if (!is_dir($dir)) {  
                if (!mkdir($dir)) {  
                    return false;  
                }  
            }
            return true;  
        }
        public static function copydir($strSrcDir, $strDstDir){  
            $dir = opendir($strSrcDir);  
            if (!$dir) {  
                return false;  
            }  
            if (!is_dir($strDstDir)) {  
                if (!mkdir($strDstDir)) {  
                    return false;  
                }  
            }  
            while (false !== ($file = readdir($dir))) {  
                if (($file != '.') && ($file != '..')) {  
                    if (is_dir($strSrcDir . '/' . $file) ) {  
                        if (!File::copydir($strSrcDir . '/' . $file, $strDstDir . '/' . $file)) {  
                            return false;  
                        }  
                    } else {  
                        if (!copy($strSrcDir . '/' . $file, $strDstDir . '/' . $file)) {  
                            return false;  
                        }  
                    }  
                }  
            }  
            closedir($dir);  
            return true;  
        }
        public static function deldir($dir) 
        {
            if (is_dir($dir)) {
                $dh = opendir($dir);
                while ($file = readdir($dh)) {
                    if($file != "." && $file != "..") {
                        $fullpath = $dir . "/" . $file;
                        if(!is_dir($fullpath)) {
                            unlink($fullpath);
                        } else {
                            File::deldir($fullpath);
                        }
                    }
                }
                closedir($dh);
                if(rmdir($dir)) {
                    return true;
                } else {
                    return false;
                }
            }else{
                return true;
            }
        }
        public static function delfile($path) 
        {
            if(unlink($path)) {
                return true;
            } else {
                return false;
            }
        }
        public static function savefile($path,$text) 
        {
            $handle = fopen($path, "wb");  
            if(fputs($handle, $text) == FALSE){
                fclose($handle); 
                return false;
            }else{
                fclose($handle); 
                return true;
            }
        }
        public static function initdir($tag) 
        {
            $path = "../../Project/" . $tag . "/";
            if (mkdir($path)
                && mkdir($path . "Resources/")
                && mkdir($path . "Resources/files/")
                && mkdir($path . "Resources/images/")
                && mkdir($path . "Resources/images/nav/")
                && mkdir($path . "Resources/images/nav/1/")
                && mkdir($path . "Resources/images/nav/2/")
                && mkdir($path . "Resources/images/nav/3/")
                && mkdir($path . "Scripts/")
                && mkdir($path . "Help/")
                && mkdir($path . "Config/")
                && copy("../../Lib/project/index.php", $path . "index.php")
                && copy("../../Lib/project/Config/main.js", $path . "Config/main.js")
                && copy("../../Lib/project/Config/index.php", $path . "Config/index.php")
                && copy("../../Lib/project/Help/main.js", $path . "Help/main.js")
                && copy("../../Lib/project/Help/index.php", $path . "Help/index.php")
                && copy("../../Lib/project/Scripts/main.js", $path . "Scripts/main.js")) {
                return true;
            }else{
                return false;
            }
        }
        public static function export_csv($filename,$data)   
        {   
            header("Content-type:text/csv");   
            header("Content-Disposition:attachment;filename=".$filename);   
            header('Cache-Control:must-revalidate,post-check=0,pre-check=0');   
            header('Expires:0');   
            header('Pragma:public');   
            echo $data;   
        }
        public static function read_csv($handle)   
        {   
            $out = array();   
            $n = 0;   
            while ($data = fgetcsv($handle))   
            {   
                $num = count($data);   
                for ($i = 0; $i < $num; $i++)   
                {   
                    $out[$n][$i] = $data[$i];   
                }   
                $n++;   
            }   
            return $out;   
        }
    }
?>
