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
    * @version     $Rev: 3.2.3 $
    *
    */

/****** Функции для работы с данными *****/

function gk_tanks($gk_block,$db) // Получаем список танков в клане, с данными о времени блокировки
{
    $r = array();
    $tresult = $db->select('SELECT `title`, `level`, `type`, `tank_id` FROM `tanks`;',__line__,__file__);
    foreach($tresult as $tvalue) {
      if($tvalue['title'] == 'Bat_Chatillon155') { $tvalue['title'] = 'Bat_Chatillon155_58'; }
      if(isset($gk_block[$tvalue['type']][$tvalue['level']])) {
        $r[$tvalue['title']] = $gk_block[$tvalue['type']][$tvalue['level']];
        $r['by_id'][$tvalue['tank_id']]['time'] = $gk_block[$tvalue['type']][$tvalue['level']];
        $r['by_id'][$tvalue['tank_id']]['title'] = $tvalue['title'];
      } else {
        $r[$tvalue['title']] = 0;
        $r['by_id'][$tvalue['tank_id']]['time'] = 0;
        $r['by_id'][$tvalue['tank_id']]['title'] = $tvalue['title'];
      }
    }
    return $r;
}
function gk_insert_tanks($array,$time) //Добавляем информацию о заблокированных танках
{
    global $db;
    $db->insert("INSERT INTO `gk` (name,tank,time) VALUES ('{$array['name']}','{$array['vehicleType']}','{$time}');",__line__,__file__);
}
function gk_parse_file($file,$res,$gk_time,$lang,$db,$reducer = '') // Обработка реплея.
{
    $file_error = $show_table = $team_id = $battle_time = null;
    $teams = array();
    $reduce = 1;

   if(!is_uploaded_file($file['filename']['tmp_name']) or $file['filename']['error'] != 0) {
        return array('error' => $lang['gk_error_2']);
    }

    unset($_FILES);

    $replay = fopen($file['filename']['tmp_name'],'r');

    $blocksInfo=unpack("Lsignature/LblocksCount", fread($replay,8));
    if($blocksInfo['blocksCount'] and $blocksInfo['blocksCount'] >= 1 and $blocksInfo['blocksCount'] <= 3){
            for($i = 1; $i <= $blocksInfo['blocksCount']; $i++){
                    $blockSize=unpack('L',fread($replay,4));
                    //echo $blockSize['1'].' - '.$files.'<br>';
                    if(isset($blockSize['1']) and $blockSize['1']>0) {
                        $data[$i]=json_decode(fread($replay,$blockSize['1']),true);
                    }
            }
    }
    fclose($replay);

   if(!isset($data['1']) or empty($data['1'])) {
     return array('error' => $lang['gk_error_3']);
   }

  if(!in_array($data['1']['playerName'],$res)) {
    return array('error' => $lang['gk_error_5']);
  }

   //Broken replay, 2nd block of data not here
   if(!isset($data['2']) or empty($data['2'])) {
      foreach($data['1']['vehicles'] as $val) {
        if(in_array($val['name'],$res)) {
          $pieces = explode(':', $val['vehicleType']);
          $teams[$val['team']][$val['name']] = $pieces['1'];
          if($val['name'] == $data['1']['playerName']) {
            $team_id = $val['team'];
          }
        }
      }

      if(function_exists('date_parse_from_format')) {
        $check_time = date_parse_from_format('d.m.Y H:i:s',$data['1']['dateTime']);
      } else {
        $check_time = date_parse($data['1']['dateTime']);
      }

      if($check_time['error_count'] == 0) {
        $battle_time = mktime($check_time['hour'],$check_time['minute'],$check_time['second'],$check_time['month'],$check_time['day'],$check_time['year']);
      } else {
        return array('error' => $lang['gk_error_4'].":-( :\ :'(");
      }

      $r['error'] = $lang['gk_error_4'].$lang['gk_error_10'];
      $r['team'] = $teams[$team_id];
      $r['time'] = $battle_time+15*60;
      $r['reduce'] = $reducer;
      return $r;
   }

  if(!isset($data['2']['0']['common']['winnerTeam'])) {
    return array('error' => $lang['gk_error_4']);
  }

  if($data['2']['0']['common']['vehLockMode'] != 1) {
    return array('error' => $lang['gk_error_6']);
  }

  $team_id = $data['2']['0']['personal']['team'];

  if($data['2']['0']['common']['winnerTeam'] == $team_id) {
    switch ($reducer) {
        case 'normal':
            $reduce = 2;
            break;
        case 'start':
            $reduce = 5;
            break;
        case 'gold':
            $reduce = 10;
            break;
    }
  }

  foreach($data['2']['1'] as $id => $val) {
    if($val['team'] == $team_id and !$val['isAlive'] and in_array($val['name'], $res)) {
      $teams[$val['name']]['vehicleId'] = $data['2']['0']['vehicles'][$id]['typeCompDescr'];
      $teams[$val['name']]['vehicleType'] = $gk_time['by_id'][$teams[$val['name']]['vehicleId']]['title'];
      $teams[$val['name']]['name'] = $val['name'];
    }
  }

  foreach($teams as $name => $value) {
      $eb = $data['2']['0']['common']['arenaCreateTime']+round($data['2']['0']['common']['duration'],0)+(($gk_time['by_id'][$value['vehicleId']]['time'])/$reduce*60*60);
      gk_insert_tanks($value,$eb);  // запись в бд
  }

  return 0;

}

/***** Функции для работы с данными *****/

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
$gk_block['SPG']['10'] = 120;
$gk_block['SPG']['9'] = 74;
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

if(isset($_POST['gkreplay']) and isset($_FILES['filename']['name']) and ($auth->replays)) {
  $gk_time = gk_tanks($gk_block,$db);
  $gk_fresult = gk_parse_file($_FILES,array_keys($res),$gk_time,$lang,$db,$_POST['province_type']);
  unset($gk_time);
}

if(isset($_POST['gkdestroyed']) && isset($_POST['Array']) && ($auth->replays)){

  $res_check = array_keys($res);
  $gk_time = gk_tanks($gk_block,$db);
  $reduce = 1;

  if($_POST['Array']['win_or_lose'] == 'win') {
    switch ($_POST['Array']['reduce']) {
        case 'normal':
            $reduce = 2;
            break;
        case 'start':
            $reduce = 5;
            break;
        case 'gold':
            $reduce = 10;
            break;
    }
  }

  foreach($_POST['Array']['result'] as $val) {
    if(isset($val['killed']) and in_array($val['name'], $res_check)) {
        if(!isset($gk_time[$val['vehicleType']])) { $gk_time[$val['vehicleType']] = 168; } //временная заглушка, от несовпадения информации в апи и реплеях
        $eb = $_POST['Array']['time'] + (($gk_time[$val['vehicleType']])/$reduce*60*60);
        gk_insert_tanks($val,$eb);  // запись в бд
    }
  }
  unset($res_check);
  unset($gk_time);
}
/********** End **********/

/********** Parse activity replay **********/
if(isset($_POST['activityreplay']) and isset($_FILES) and ($auth->replays)) {

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

      if(!preg_match('/{\"clientVersionFromXml(.*)\"}/', $handle, $a_result)) {
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

$db->insert('DELETE FROM `gk` WHERE `time` < "'.time().'";',__line__,__file__);

$gk_result5 =  $db->select('SELECT g.time AS time, t.name_i18n AS tank, g.name AS name FROM `gk` g LEFT OUTER JOIN `tanks` t ON g.tank = t.title ORDER BY g.time ASC;',__line__,__file__);
foreach($gk_result5 as $value) {
 $blocked[$value['name']][$value['tank']] = $value['time'];
 if(!in_array($value['tank'], $gk_blocked_tanks)) {
    $gk_blocked_tanks[] = $value['tank'];
 }
}
unset($gk_result5);
?>
