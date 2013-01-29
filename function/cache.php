<?php
    /*
    * Project:     Clan Stat
    * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
    * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
    * -----------------------------------------------------------------------
    * Began:       2011
    * Date:        $Date: 2011-10-24 11:54:02 +0200 $
    * -----------------------------------------------------------------------
    * @author      $Author: Edd, Exinaus, Shw  $
    * @copyright   2011-2012 Edd - Aleksandr Ustinov
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 2.2.0 $
    *
    */
?>
<?php
    class Cache {  

        function __construct($dir)  
        {  
            $this->dir = $dir;
        }  

        private function _name($key, $dir = FALSE)  
        {                                         
            if($dir == FALSE){
                $dir = $this->dir;
            }
            return sprintf("%s/%s", $dir, sha1($key));  
        }  

        public function get($key, $expiration = 3600, $dir = FALSE)  //$expiration = 0 No check
        {  
            if($dir == FALSE){            
                $dir = $this->dir;
            }

            if ( !is_dir($dir) OR !is_writable($dir))  
            {  
                return FALSE;  
            }  

            $cache_path = $this->_name($key,$dir);  

            if (!@file_exists($cache_path))  
            {  
                return FALSE;  
            }  
            if($expiration != 0){
                if (filemtime($cache_path) < (time() - $expiration))  
                {  
                    $this->clear($key);  
                    return FALSE;  
                }  
            }

            if (!$fp = @fopen($cache_path, 'rb'))  
            {  
                return FALSE;  
            }  

            flock($fp, LOCK_SH);  

            $cache = '';  

            if (filesize($cache_path) > 0)  
            {  
                $cache = unserialize(fread($fp, filesize($cache_path)));  
            }  
            else  
            {  
                $cache = NULL;  
            }  

            flock($fp, LOCK_UN);  
            fclose($fp);  

            return $cache;  
        }  

        public function set($key, $data, $dir = FALSE)  
        {  
            
            if($dir == FALSE){
                $dir = $this->dir;
            }
            if ( !is_dir($dir) OR !is_writable($dir))  
            {  
                return FALSE;  
            }  

            $cache_path = $this->_name($key,$dir);  

            if ( ! $fp = fopen($cache_path, 'wb'))  
            {  
                return FALSE;  
            }  

            if (flock($fp, LOCK_EX))  
            {  
                fwrite($fp, serialize($data));  
                flock($fp, LOCK_UN);  
            }  
            else  
            {  
                return FALSE;  
            }  
            fclose($fp);  
            @chmod($cache_path, 0777);  
            return TRUE;  
        }  

        public function clear($key, $dir = FALSE)  
        {
            if($dir == FALSE){
                $dir = $this->dir;
            }

            $cache_path = $this->_name($key,$dir);

            if (file_exists($cache_path))  
            {  
                unlink($cache_path);
                return TRUE;
            }

            return FALSE;  
        }

        public function clear_all($exclude_option = NULL,$dir = FALSE)
        {
            if($dir == FALSE){
                $dir = $this->dir;
            }

            $exclude_list = array('.', '..');
            $exclude_last = array();

            if(isset($exclude_option))
            {
                if(is_array($exclude_option)) {
                    foreach($exclude_option as $n => $t) { $exclude_last[] = sha1($t); }
                } elseif(is_string($exclude_option)) {
                    $exclude_last[] = sha1($exclude_option);
                }
            }

            $exclude_list = array_merge($exclude_list,$exclude_last);
            $clear_files = array_diff(scandir($dir), $exclude_list);

            foreach($clear_files as $files){
                if(is_file($dir.$files)&& !is_dir($dir.$files)){
                    unlink($dir.$files);
                }
            }
        }
    }  
?>
