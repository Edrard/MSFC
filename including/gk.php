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

if(isset($_POST['gkreplay']) and isset($_FILES['filename']['name']) and ($logged >= $config['a_rights'])) {
  $gk_time = gk_tanks($gk_block,$db);
  $gk_fresult = gk_parse_file($_FILES,array_keys($res),$gk_time,$lang,$db);
  unset($gk_time);
}

if(isset($_POST['gkdestroyed']) && isset($_POST['Array']) && ($logged >= $config['a_rights'])){

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

/********** Parse activity replay **********/
if(isset($_POST['activityreplay']) and isset($_FILES) and ($logged >= $config['a_rights'])) {

$activity_error = '';

  foreach($_FILES as $a_filename => $a_file) {

  $res_check = array_keys($res);
  $cache_activity = new Cache(ROOT_DIR.'/cache/activity/');
  $file_num = substr($a_filename, -1);

  $file_error = null;
  $battle_time = null;

  if($a_file['size'] > (1024*6*1024)) { $file_error .= $lang['gk_error_1']; }

  if(is_uploaded_file($a_file['tmp_name']) and $a_file['error'] == 0 and !isset ($file_error)) {
      $lines = file($a_file["tmp_name"]);
  }else{
      $file_error .= $lang['gk_error_2'];
  }
  unset($a_filename);

  if(!$file_error ) {
      $handle  = trim($lines[0]);
      if(isset($lines[1])) {$handle .= trim($lines[1]);}

      if(!preg_match('/{\"mapName(.*)\"}/', $handle, $a_result)) {
          $file_error .= $lang['gk_error_3'];
      }
  unset($lines);
  }

  if(!$file_error) {
    $a_data = json_decode($a_result['0'], true);
    $check_time = date_parse($a_data['dateTime']);
    if($check_time['error_count'] == 0) {
    	$battle_time = mktime($check_time['hour'],$check_time['minute'],$check_time['second'],$check_time['month'],$check_time['day'],$check_time['year']);
    } else {
      $file_error .= ':-( :\'(';
    }
  unset($a_result);
  }
  if(!$file_error) {
    $activity = $cache_activity->get(date('d.m.Y',$battle_time),0);
    if(empty($activity)){
        $activity = array();
    }
    if(isset($activity['map'][$a_data['mapName']])) {
       foreach($activity['map'][$a_data['mapName']] as $val) {
         if($val == $battle_time) {$file_error .= $lang['activity_error_1'];}
       }
    }
  }

  if(!$file_error){
    /* Категории файлов */
    if(isset($_POST['cat'.$file_num])) {
      $a_cat = $_POST['cat'.$file_num];
    } else {
      $a_cat = 'cat_1';
    }
    foreach($a_data['vehicles'] as $id => $val) {
      if(in_array($val['name'],$res_check)) {
        if(isset($activity[$a_cat][$val['name']])) {
          $activity[$a_cat][$val['name']] += 1;
        } else {
          $activity[$a_cat][$val['name']] = 1;
        }
      }
    }
    $activity['map'][$a_data['mapName']][] = $battle_time;
    $cache_activity->clear(date('d.m.Y',$battle_time));
    $cache_activity->set(date('d.m.Y',$battle_time),$activity);
  }

  if($file_error) {$activity_error .= $lang['activity_7'].' <b>'.$a_file['name'].'</b><br />'.$file_error;}
  unset($res_check,$a_data,$check_time,$battle_timem,$file_error,$cache_activity,$activity,$a_data,$val,$id,$file_num,$a_cat,$a_file);

  }

}
/********** End of parsing activity replay **********/
}
?>
