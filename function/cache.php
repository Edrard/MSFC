<?php
    class Cache {  

        function __construct($dir)  
        {  
            $this->dir = $dir;
        }  

        private function _name($key)  
        {  
            return sprintf("%s/%s", $this->dir, sha1($key));  
        }  

        public function get($key, $expiration = 3600)  //$expiration = 0 No check
        {  

            if ( !is_dir($this->dir) OR !is_writable($this->dir))  
            {  
                return FALSE;  
            }  

            $cache_path = $this->_name($key);  

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

        public function set($key, $data)  
        {  

            if ( !is_dir($this->dir) OR !is_writable($this->dir))  
            {  
                return FALSE;  
            }  

            $cache_path = $this->_name($key);  

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

        public function clear($key)  
        {
            $cache_path = $this->_name($key);

            if (file_exists($cache_path))  
            {  
                unlink($cache_path);
                return TRUE;
            }

            return FALSE;  
        }

        public function clear_all($exclude_option = NULL)
        {
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
           $clear_files = array_diff(scandir($this->dir), $exclude_list);

           foreach($clear_files as $files){
               if(is_file($this->dir.$files)){
                 unlink($this->dir.$files);
               }
           }
        }
    }  
?>