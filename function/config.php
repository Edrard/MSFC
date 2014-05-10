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
* @copyright   2011-2013 Edd - Aleksandr Ustinov
* @link        http://wot-news.com
* @package     Clan Stat
* @version     $Rev: 3.1.0 $
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
      if(isset($config['dst'])) {$config['time'] += $config['dst'];}
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

//Updater
if (!preg_match ("/update.php/", $_SERVER['PHP_SELF']))
{
  if(isset($config['version']) and ( !is_numeric($config['version']) or (310.1 - (float) $config['version']) > 0 ) ) {
    if(preg_match ("/\/admin\//", $_SERVER['PHP_SELF'])) { //admin cp
      header ("Location: ../update.php");
      die('Module version are outdated, rediecting to updater: <a href="../update.php" target="_self" title="update">Update</a>');
    } else { // not admin cp
      header ("Location: update.php");
      die('Module version are outdated, rediecting to updater: <a href="./update.php" target="_self" title="update">Update</a>');
    }
  }
}
?>