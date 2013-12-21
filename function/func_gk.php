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
    * @version     $Rev: 3.0.2 $                                                                                          
    *                                                                                                                     
    */                                                                                                                    

    function gk_get_all($db) //Получаем список всей заблокированной техники
    {
        $sql = "SELECT g.time AS time, t.tank_id AS tank, g.name AS name
        FROM `gk` g
        LEFT OUTER JOIN `tanks` t
        ON g.tank = t.tank_id
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
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }

    }
    function gk_insert_tanks($array,$time,$db) //Добавляем информацию о заблокированных танках
    {
        $sql = "INSERT INTO `gk` (name,tank,time) VALUES ('{$array['name']}','{$array['vehicleType']}','{$time}');";
        $q = $db->prepare($sql);
        if ($q->execute() != TRUE) {
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }
    }
    function gk_parse_file($file,$res,$gk_time,$lang,$db,$reducer = 0) // Обработка реплея.
    {
        $file_error = null;
        $show_table = null;
        $team_id = null;
        $battle_time = null;
        $reduce = 1;

        if($file['filename']['size'] > (1024*6*1024)) { $file_error .= $lang['gk_error_1']; }

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

            if(!preg_match('/{\"clientVersionFromXml(.*)\"}/', $handle, $gk_result)) {
                $file_error .= $lang['gk_error_3'];
            }
            /*
            if(!preg_match('/{\"crewActivityFlags(.*)arenaTypeID\"(.*?)}/', $handle, $gk_result2)) {
              if($file_error) {
                  $file_error .= $lang['gk_error_4'];
                } else {
                  $show_table = true;
                }
            }
            */
            if(preg_match('/\[(.*)\]/', $handle, $gk_result2)) {
              $gk_tmp = explode('}, {',$gk_result2['1']);
              //print_r($res);
              $gk_tmp['0'] = $gk_tmp['0'].'}';
              $gk_tmp['1'] = '{'.$gk_tmp['1'].'}';
              //$gk_tmp['2'] = '{'.$gk_tmp['2'];
              $gk_data2 = json_decode($gk_tmp['0'], true);
              $gk_data3 = json_decode($gk_tmp['1'], true);
              if(!isset($gk_data2['isWinner'])) {
                if($file_error) {
                    $file_error .= $lang['gk_error_4'];
                } else {
                    $show_table = true;
                }
              } else { // begin else
                $lineCount = count($lines);
                $output = array();
                for ($i = 1; $i < $lineCount; $i += 1) {
                  if(substr($lines[$i],0,2)=='s.') { break; }
                  //Vehicle Lock Mode
                  if(preg_match('/vehLockMode/',$lines[$i])) {
                    if(substr($lines[$i+2],1) == 1) { $gk_data2['vehicleLockMode'] = 1; } else { $gk_data2['vehicleLockMode'] = 0;}
                  }
                  //Battle duration
                  if(preg_match('/duration/',$lines[$i])) {
                    $num = substr($lines[$i+2],1);
                    if(is_numeric($num)) { $gk_data2['lifeTime'] = $num; } else { $gk_data2['lifeTime'] = 15*60;}
                  }
                }
                if($gk_data2['isWinner'] == 1) {
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
              } // end else
            }
            unset($lines,$gk_result2);
        }

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

            unset($gk_result);

            if(!in_array($gk_data['playerName'], $res)) {
                $file_error .= $lang['gk_error_5'];
            }

            if($gk_data2['vehicleLockMode'] != 1 ) {
                $file_error .= $lang['gk_error_6'];
            }

        }

        if(!$file_error) {
            if(is_array($gk_data3)) {
              foreach($gk_data3 as $id => $val) {
                  $pieces = explode(':', $val['vehicleType']);
                  $teams[$val['team']][$val['name']]['vehicleType'] = $pieces['1'];
                  $teams[$val['team']][$val['name']]['isAlive'] = $val['isAlive'];
                  $teams[$val['team']][$val['name']]['name'] = $val['name'];
                  if($val['name'] == $gk_data['playerName']) {
                    $team_id = $val['team'];
                  }
              }
            } else {
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
            }
            unset($handle);

            foreach($teams[$team_id] as $name => $value) {
                $eb = $gk_data2['arenaCreateTime']+$gk_data2['lifeTime']+(($gk_time[$value['vehicleType']])/$reduce*60*60);
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
            $r['reduce'] = $reducer;
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
        $sql = "DELETE FROM `gk` WHERE `time` < '".time()."';";
        $q = $db->prepare($sql);
        if($q->execute() != true) {
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }
        return 0;
    }
?>