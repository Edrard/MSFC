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
    * @version     $Rev: 2.1.2 $
    *
    */
?>
<?php

    if (preg_match ("/config.php/", $_SERVER['PHP_SELF']))
    {
        header ("Location: /index.php");
        exit;
    }

    $config = get_config();
        
    define("VER",'2.1.2');

  
    $data = array();
    $links = array();
    $res_tmp = array();
    $result = array();
    $res = array();
    

    //Special function


?>
