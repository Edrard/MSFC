<?php
    /*
    * Project:     Clan Stat
    * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
    * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
    * -----------------------------------------------------------------------
    * Began:       2011
    * Date:        $Date: 2011-10-24 11:54:02 +0200 $
    * -----------------------------------------------------------------------
    * @author      $Author: Edd $
    * @copyright   2011-2012 Edd - Aleksandr Ustinov
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 2.1.6 $
    *
    */
?>
<?php

    if (preg_match ("/config.php/", $_SERVER['PHP_SELF']))
    {
        if (!headers_sent()) {
          header ("Location: /index.php");
          exit;
        } else { print_R('<script type="text/javascript">
          location.replace("/index.php");
          </script>');
        }
    }

    $config = get_config();


    $data = array();
    $links = array();
    $res_tmp = array();
    $result = array();
    $res = array();

    $exec_time = ini_get('max_execution_time');
    $exec_refresh = ($exec_time + 10)/2;
    if($exec_time === FALSE || !is_numeric($exec_time)){
        $exec_time = 300;    
        $exec_refresh = 29;
    } 
    if($exec_time == 0){
        $exec_time = 300;
        $exec_refresh = 29;    
    }

    //Special function


?>