<?php
    function base_dir($local = null)
    {
        if($local == null){
            $local = dirname($_SERVER['PHP_SELF']);
        }
        $full = dirname(__FILE__);
        $public_base = str_replace($local, "", $full);

        return $public_base;
    }
?>
