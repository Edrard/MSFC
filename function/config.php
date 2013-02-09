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
    if (function_exists('date_default_timezone_set'))
    {
        $timezones_sys = array(
            '-11'=>'Pacific/Midway',
            '-10'=>'Pacific/Honolulu', //+
            '-9.5'=>'Pacific/Marquesas', //+
            '-9'=>'America/Juneau', //+
            '-8'=>'America/Los_Angeles', //+
            '-7'=>'America/Denver', //+
            '-6'=>'America/Mexico_City', //+
            '-5'=>'America/New_York', //+
            '-4.5'=>'America/Caracas', //+
            '-4'=>'America/Martinique', //+
            '-3.5'=>'America/St_Johns', //+
            '-3'=>'America/Argentina/Buenos_Aires', //+
            '-2'=>'America/Noronha', //+
            '-1'=>'Atlantic/Azores', //+
            '0'=>'Europe/London', //+
            '1'=>'Europe/Paris', //+
            '2'=>'Europe/Kiev',  //+
            '3'=>'Europe/Kaliningrad', //+
            '3.5'=>'Asia/Tehran', //+
            '4'=>'Asia/Baku', //+
            '4.5'=>'Asia/Kabul', //+
            '5'=>'Asia/Karachi',  //+
            '5.5'=>'Asia/Kolkata', //+
            '5.75'=>'Asia/Kathmandu', //+
            '6'=>'Asia/Dhaka',  //+
            '6.5'=>'Indian/Cocos', //+
            '7'=>'Asia/Bangkok', //+
            '8'=>'Asia/Singapore', //+
            '8.75'=>'Australia/Eucla',
            '9'=>'Asia/Tokyo',  //+
            '9.5'=>'Australia/Darwin', //+
            '10'=>'Pacific/Guam', //+
            '10.5'=>'Australia/Lord_Howe', //+
            '11'=>'Etc/GMT-11',
            '11.5'=>'Pacific/Norfolk',
            '12'=>'Pacific/Fiji', //+
            '12.75'=>'Pacific/Chatham', //+
            '13'=>'Pacific/Tongatapu', //+
            '14'=>'Pacific/Kiritimati', //+
        );
        if(isset($config['time']) and isset($timezones_sys[$config['time']]) and $config['time'] !='none' ) {
          date_default_timezone_set($timezones_sys[$config['time']]);
        } else {
          date_default_timezone_set(@date_default_timezone_get());
        }

        unset($timezones_sys);
    }
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