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
    * @version     $Rev: 3.0.0 $
    *
    */



    if (preg_match ("/config.php/", $_SERVER['PHP_SELF']))
    {
      header ("Location: /index.php");
      exit;
    }

    $config = get_config();
    if (function_exists('date_default_timezone_set'))
    {
        if(isset($config['time']) and $config['time'] !='none' ) {
          //date_default_timezone_set($timezones_sys[$config['time']]);
          $tz = floor($config['time']);
          if ( $tz ==  0 )
          {
          	date_default_timezone_set( 'GMT' );
          }
          elseif ( $tz > 0 )
          {
          	date_default_timezone_set( 'Etc/GMT-' . $tz );
          }
          elseif ( $tz < 0 )
          {
          	date_default_timezone_set( 'Etc/GMT+' . abs( $tz ) );
          }
        } else {
          date_default_timezone_set(@date_default_timezone_get());
        }

        unset($timezones_sys);
    }
    $data = array();
    $links = array();
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