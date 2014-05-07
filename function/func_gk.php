<?php
/*
* Project:     Clan Stat
* License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
* Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
* -----------------------------------------------------------------------
* Began:       2011
* Date:        $Date: 2013-11-20 00:00:00 +0200 $
* -----------------------------------------------------------------------
* @author      $Author: Edd, Exinaus, SHW  $
* @copyright   2011-2013 Edd - Aleksandr Ustinov
* @link        http://wot-news.com
* @package     Clan Stat
* @version     $Rev: 3.1.0 $
*
*/

function gk_get_all($db) //Получаем список всей заблокированной техники
{
    $sql = "SELECT g.time AS time, t.name_i18n AS tank, g.name AS name
    FROM `gk` g
    LEFT OUTER JOIN `tanks` t
    ON g.tank = t.title
    ORDER BY g.time ASC;";
    $q = $db->prepare($sql);
    if ($q->execute() == TRUE) {
        return $q->fetchAll(PDO :: FETCH_ASSOC);
    } else {
        die(show_message($q->errorInfo(),__line__,__file__,$sql));
    }
}
function gk_tanks($gk_block,$db) // Получаем список танков в клане, с данными о времени блокировки
{
    $sql = "SELECT `title`, `level`, `type`, `tank_id` FROM `tanks`;";
    $q = $db->prepare($sql);
    if ($q->execute() == TRUE) {
        $tresult = $q->fetchAll(PDO :: FETCH_ASSOC);
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
    } else {
        die(show_message($q->errorInfo(),__line__,__file__,$sql));
    }

}
function gk_insert_tanks($array,$time) //Добавляем информацию о заблокированных танках
{
    global $db;
    $sql = "INSERT INTO `gk` (name,tank,time) VALUES ('{$array['name']}','{$array['vehicleType']}','{$time}');";
    $q = $db->prepare($sql);
    if ($q->execute() != TRUE) {
        die(show_message($q->errorInfo(),__line__,__file__,$sql));
    }
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
function gk_clean_db($db)  //удаляем из бд старые записи
{
    $sql = "DELETE FROM `gk` WHERE `time` < '".time()."';";
    $q = $db->prepare($sql);
    if($q->execute() != true) {
        die(show_message($q->errorInfo(),__line__,__file__,$sql));
    }
    return 0;
}
?>