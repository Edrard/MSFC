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
    * @version     $Rev: 2.0.0 $
    *
    */
?>
<?php

$gk_blocked = array();
$gk_blocked_tanks = array();

if($logged > 0){

/********** Begin **********/

$gk_error = null;
$gk_block['heavyTank']['10'] = 168;
$gk_block['heavyTank']['9'] = 120;
$gk_block['heavyTank']['8'] = 96;
$gk_block['heavyTank']['7'] = 72;
$gk_block['heavyTank']['6'] = 48;
$gk_block['heavyTank']['5'] = 30;
$gk_block['heavyTank']['4'] = 24;
$gk_block['heavyTank']['3'] = 0;
$gk_block['heavyTank']['2'] = 0;
$gk_block['heavyTank']['1'] = 0;
$gk_block['mediumTank']['10'] = 120;
$gk_block['mediumTank']['9'] = 96;
$gk_block['mediumTank']['8'] = 72;
$gk_block['mediumTank']['7'] = 48;
$gk_block['mediumTank']['6'] = 30;
$gk_block['mediumTank']['5'] = 25;
$gk_block['mediumTank']['4'] = 16;
$gk_block['mediumTank']['3'] = 4;
$gk_block['mediumTank']['2'] = 1;
$gk_block['mediumTank']['1'] = 0;
$gk_block['lightTank']['10'] = 0;
$gk_block['lightTank']['9'] = 0;
$gk_block['lightTank']['8'] = 48;
$gk_block['lightTank']['7'] = 16;
$gk_block['lightTank']['6'] = 16;
$gk_block['lightTank']['5'] = 16;
$gk_block['lightTank']['4'] = 4;
$gk_block['lightTank']['3'] = 2;
$gk_block['lightTank']['2'] = 1;
$gk_block['lightTank']['1'] = 0;
$gk_block['SPG']['10'] = 0;
$gk_block['SPG']['9'] = 0;
$gk_block['SPG']['8'] = 74;
$gk_block['SPG']['7'] = 50;
$gk_block['SPG']['6'] = 36;
$gk_block['SPG']['5'] = 27;
$gk_block['SPG']['4'] = 18;
$gk_block['SPG']['3'] = 8;
$gk_block['SPG']['2'] = 4;
$gk_block['SPG']['1'] = 0;
$gk_block['AT-SPG']['10'] = 96;
$gk_block['AT-SPG']['9'] = 96;
$gk_block['AT-SPG']['8'] = 72;
$gk_block['AT-SPG']['7'] = 48;
$gk_block['AT-SPG']['6'] = 30;
$gk_block['AT-SPG']['5'] = 25;
$gk_block['AT-SPG']['4'] = 16;
$gk_block['AT-SPG']['3'] = 4;
$gk_block['AT-SPG']['2'] = 1;
$gk_block['AT-SPG']['1'] = 0;

if(isset($_FILES['filename']['name']) and ($logged == 2)) {
  $gk_time = gk_tanks($gk_block,$db);
  $gk_fresult = gk_parse_file($_FILES,array_keys($res),$gk_time,$lang,$db);
  unset($gk_time);
  }

if(isset($_POST['gkdestroyed']) && isset($_POST['Array']) && ($logged == 2)){

  $res_check = array_keys($res);
  $gk_time = gk_tanks($gk_block,$db);

  foreach($_POST['Array']['result'] as $val) {
    if(isset($val['killed']) and in_array($val['name'], $res_check)) {
        $eb = $_POST['Array']['time'] + (($gk_time[$val['vehicleType']])*60*60);
        gk_insert_tanks($val,$eb,$db);  // запись в бд
    }
  }
  unset($res_check);
  unset($gk_time);
}

gk_clean_db($db);

$gk_result5 = gk_get_all($db);
foreach($gk_result5 as $value) {
 $blocked[$value['name']][$value['tank']] = $value['time'];
 if(!in_array($value['tank'], $gk_blocked_tanks)) {
    $gk_blocked_tanks[] = $value['tank'];
 }
}
unset($gk_result5);

/********** End **********/

}
?>
