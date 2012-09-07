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
    function gk_get_all($db) //Получаем список всей заблокированной техники
    {
        $sql = "SELECT g.time AS time, t.tank AS tank, g.name AS name
        FROM gk g
        LEFT OUTER JOIN tanks t
        ON g.tank = t.title
        ORDER BY g.time ASC;";
        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            return $q->fetchAll(PDO :: FETCH_ASSOC);
        } else {
            print_r($q->errorInfo());
            die();
        }
    }
    function gk_tanks($gk_block,$db) // Получаем список танков в клане, с данными о времени блокировки
    {
        $sql = "SELECT `title`, `lvl`, `type` FROM `tanks`;";
        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            $tresult = $q->fetchAll(PDO :: FETCH_ASSOC);
            foreach($tresult as $tvalue) {
              if(isset($gk_block[$tvalue['type']][$tvalue['lvl']])) {
                $r[$tvalue['title']] = $gk_block[$tvalue['type']][$tvalue['lvl']];
              } else {
                $r[$tvalue['title']] = 0;
              }
            }
            return $r;
        } else {
            print_r($q->errorInfo());
            die();
        }

    }
    function gk_insert_tanks($array,$time,$db) //Добавляем информацию о заблокированных танках
    {
        $sql = "INSERT INTO gk (name,tank,time) VALUES ('{$array['name']}','{$array['vehicleType']}','{$time}');";
        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {

        } else {
            print_r($q->errorInfo());
            die();
        }
    }
    function gk_parse_file($file,$res,$gk_time,$lang,$db) // Обработка реплея.
    {
        $file_error = null;
        $show_table = null;
        $team_id = null;
        $battle_time = null;

        if($file['filename']['size'] > (1024*3*1024)) { $file_error .= $lang['gk_error_1']; }

        if(is_uploaded_file($file['filename']['tmp_name']) and $file['filename']['error'] == 0 and !isset ($file_error)) {
            $lines = file($_FILES["filename"]["tmp_name"]);
        }else{
            $file_error .= $lang['gk_error_2'];
        }
        unset($file);
        unset($_FILES);

        if(!$file_error ) {
            $handle  = trim($lines[0]);
            if(isset($lines[1])) {$handle .= trim($lines[1]);}

            if(!preg_match('/{\"mapName(.*)\"}/', $handle, $gk_result)) {
                $file_error .= $lang['gk_error_3'];
            }

            if(!preg_match('/{\"crewActivityFlags(.*)arenaTypeID\"(.*?)}/', $handle, $gk_result2)) {
              if($file_error) {
                  $file_error .= $lang['gk_error_4'];
                } else {
                  $show_table = true;
                }
            }
        }
        unset($lines);

        if($show_table){

          $file_error .= $lang['gk_error_4'];
          $gk_data = json_decode($gk_result['0'], true);
          unset($gk_result);

          foreach($gk_data['vehicles'] as $val) {
            if(in_array($val['name'],$res)) {
              $pieces = explode(':', $val['vehicleType']);
              $teams[$val['team']][$val['name']] = $pieces['1'];
              if($val['name'] == $gk_data['playerName']) {
                $team_id = $val['team'];
              }
            }
          }

          $check_time = date_parse($gk_data['dateTime']);
          if($check_time['error_count'] == 0) {
          	$battle_time = mktime($check_time['hour'],$check_time['minute'],$check_time['second'],$check_time['month'],$check_time['day'],$check_time['year']);
            $file_error .= $lang['gk_error_10'];
          } else {
            $show_table = false;
            $file_error .= ':-( :\'(';
          }

          unset($gk_data);
        }

        if(!$file_error) {

            $gk_data = json_decode($gk_result['0'], true);
            $gk_data2 = json_decode($gk_result2['0'], true);

            unset($gk_result);
            unset($gk_result2);

            if(!in_array($gk_data['playerName'], $res)) {
                $file_error .= $lang['gk_error_5'];
            }

            if($gk_data2['vehicleLockMode'] != 1 ) {
                $file_error .= $lang['gk_error_6'];
            }

        }

        if(!$file_error) {
            foreach(array_keys($gk_data['vehicles']) as $id) {
                preg_match_all('/\"'.$id.'\": {\"vehicleType\"(.*?)\"isTeamKiller\"(.*?)}/', $handle, $gk_result3);
                $gk_result3['0']['1'] = '{'.$gk_result3['0']['1'].'}';
                $info = json_decode($gk_result3['0']['1'], true);
                $pieces = explode(':', $info[$id]['vehicleType']);
                $teams[$info[$id]['team']][$info[$id]['name']]['vehicleType'] = $pieces['1'];
                $teams[$info[$id]['team']][$info[$id]['name']]['isAlive'] = $info[$id]['isAlive'];
                $teams[$info[$id]['team']][$info[$id]['name']]['name'] = $info[$id]['name'];

                if($info[$id]['name'] == $gk_data['playerName']) {
                  $team_id = $info[$id]['team'];
                }
            }
            unset($handle);

            foreach($teams[$team_id] as $name => $value) {
                $eb = $gk_data2['arenaCreateTime']+$gk_data2['lifeTime']+(($gk_time[$value['vehicleType']])*60*60);
                if(!$value['isAlive'] and in_array($name, $res)) {
                    gk_insert_tanks($value,$eb,$db);  // запись в бд
                }
            }

        }
        unset($gk_data);
        unset($gk_data2);

        if($show_table and isset($teams[$team_id])) {
            $r['error'] = $file_error;
            $r['team'] = $teams[$team_id];
            $r['time'] = $battle_time+15*60;
            return $r;
        }

        if(!$file_error) {
            return 0;
        } else {
            $r['error'] = $file_error;
            return $r;
        }
    }
    function gk_clean_db($db)  //удаляем из бд старые записи
    {
        $sql = "DELETE FROM `gk` WHERE `time` < '".mktime()."';";
        $q = $db->prepare($sql);
        if($q->execute() != true) {
            print_r($q->errorInfo());
            die();
        }
        return 0;
    }
?>
