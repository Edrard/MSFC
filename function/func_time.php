<?php
    /*
    * Project:     Clan Stat
    * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
    * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
    * -----------------------------------------------------------------------
    * Began:       2011
    * Date:        $Date: 2013-11-22 00:00:00 +0200 $
    * -----------------------------------------------------------------------
    * @author      $Author: Edd, Exinaus, SHW  $
    * @copyright   2011-2013 Edd - Aleksandr Ustinov
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 3.1.0 $
    *
    */



function went_players($rosterid,$start = 0,$end = -1){
   global $db;
   $losed = array();
   if($end == -1){
      $end = now();
   }
   $sql = "SELECT account_id,nickname,updated_at,role,created_at FROM `col_players` WHERE updated_at <= '".$end."' AND updated_at >= '".$start."' ORDER BY updated_at ASC;";
   $q = $db->prepare($sql);
   if ($q->execute() == TRUE) {
       $result = $q->fetchAll(PDO::FETCH_ASSOC);
   }   else {
       die(show_message($q->errorInfo(),__line__,__file__,$sql));
   }
   foreach($result as $val){
      if (!isset($rosterid[$val['account_id']])){
           $losed[$val['nickname']] = $val;
      }
   }
   return $losed;
}

function new_players($rosterid,$start = 0,$end = -1){
   global $db;
   $come = array();
   if ($end == -1){
       $end = now();
   }
   $sql = "SELECT account_id,nickname,updated_at,role,created_at FROM `col_players` WHERE created_at <= '".$end."' AND created_at >= '".$start."' ORDER BY created_at DESC;";
   $q = $db->prepare($sql);
   if ($q->execute() == TRUE) {
       $result = $q->fetchAll(PDO::FETCH_ASSOC);
   }   else {
       die(show_message($q->errorInfo(),__line__,__file__,$sql));
   }
   //print_r($result);
   foreach($result as $val){
      if (($val['created_at'] <=$end) && ($val['created_at'] >= $start))
      $come[$val['nickname']] = $val;
   }
   return $come;
}

function player_progress_main($rosterid = null, $start = 0,$end = -1){
   global $db;
   $diff = $dfirst_new = array();
   $diff['main'] = $diff['delta'] = $diff['total'] = $diff['average'] = array();
   if ($end == -1){
       $end = now();
   }

   $sql = "SELECT DISTINCT updated_at FROM `col_players` WHERE updated_at < '".$end."' AND updated_at >= '".$start."' ORDER BY updated_at DESC;";
   $q = $db->prepare($sql);
   if ($q->execute() == TRUE) {
       $result = $q->fetchAll(PDO::FETCH_ASSOC);
   }   else {
       die(show_message($q->errorInfo(),__line__,__file__,$sql));
   }

   if (count($result) > 1){
       $first = array_pop($result);
       $first = $first['updated_at'];
       $last = $result[0]['updated_at'];
       $sql = "SELECT * FROM `col_players` WHERE updated_at = '".$first."';";
       $q = $db->prepare($sql);
       if ($q->execute() == TRUE) {
           $dfirst = $q->fetchAll(PDO::FETCH_ASSOC);
       }   else {
           die(show_message($q->errorInfo(),__line__,__file__,$sql));
       }
       $sql = "SELECT * FROM `col_players` WHERE updated_at = '".$last."';";
       $q = $db->prepare($sql);
       if ($q->execute() == TRUE) {
           $dlast = $q->fetchAll(PDO::FETCH_ASSOC);
       }   else {
           die(show_message($q->errorInfo(),__line__,__file__,$sql));
       }

       foreach($dfirst as $val){
          if (isset($rosterid[$val['account_id']])) {
              $dfirst_new[$val['account_id']] = $val;
          }
       }
       $statwls = array('spotted', 'hits', 'battle_avg_xp', 'draws', 'wins', 'losses', 'capture_points',
                       'battles', 'damage_dealt', 'hits_percents', 'damage_received', 'shots', 'xp', 'frags',
                       'survived_battles', 'dropped_capture_points');
       $statacc = array('all', 'clan', 'company');

       foreach($dlast as $vals){
          if (isset($dfirst_new[$vals['account_id']])){
              foreach ($statwls as $valwls) {
                 foreach ($statacc as $valacc) {
                    if ($vals[$valacc.'_battles'] != 0){
                        $f[$valacc][$valwls] = $vals[$valacc.'_'.$valwls]/$vals[$valacc.'_battles']*100;
                    }   else {
                        $f[$valacc][$valwls] = 0;
                    }
                    if ($dfirst_new[$vals['account_id']][$valacc.'_battles'] != 0){
                        $s[$valacc][$valwls] = $dfirst_new[$vals['account_id']][$valacc.'_'.$valwls]/$dfirst_new[$vals['account_id']][$valacc.'_battles']*100;
                    }   else {
                        $s[$valacc][$valwls] = 0;
                    }
                    $diff['main'][$vals['account_id']][$valacc][$valwls] = round($f[$valacc][$valwls] - $s[$valacc][$valwls],4);
                    if (($vals[$valacc.'_battles'] - $dfirst_new[$vals['account_id']][$valacc.'_battles']) != 0) {
                         $diff['average'][$vals['account_id']][$valacc][$valwls] =  round(($vals[$valacc.'_'.$valwls] - $dfirst_new[$vals['account_id']][$valacc.'_'.$valwls])/(($vals[$valacc.'_battles'] - $dfirst_new[$vals['account_id']][$valacc.'_battles'])),4);
                    }    else {
                         $diff['average'][$vals['account_id']][$valacc][$valwls] =  0;
                    }
                    $diff['delta'][$vals['account_id']][$valacc][$valwls] =  ($vals[$valacc.'_'.$valwls] - $dfirst_new[$vals['account_id']][$valacc.'_'.$valwls]);
                    if (!isset($diff['totaldiff'][$valacc][$valwls])) $diff['totaldiff'][$valacc][$valwls] = 0;
                         $diff['totaldiff'][$valacc][$valwls] += $diff['delta'][$vals['account_id']][$valacc][$valwls];
                    if (!isset($diff['totalavr'][$valacc][$valwls])) $diff['totalavr'][$valacc][$valwls] = 0;
                         $diff['totalavr'][$valacc][$valwls] += $diff['average'][$vals['account_id']][$valacc][$valwls];
                    if (!isset($diff['total'][$valacc][$valwls])) $diff['total'][$valacc][$valwls] = 0;
                         $diff['total'][$valacc][$valwls] += $vals[$valacc.'_'.$valwls];
                 }
              }

          } //if isset dlast
       } //foreach dlast
   } //count res>1
   return $diff;
}

function new_tanks($rosterid,$tables,$start = 0,$end = -1){
   global $db;
   $new = array();
   if ($end == -1){
       $end = now();
   }
   foreach ($rosterid as $acc_id) {
      foreach ($tables as $valt){
         $sql = "SELECT * FROM `".$valt."` WHERE account_id = '".$acc_id['account_id']."' AND updated_at < '".$end."' AND updated_at >= '".$start."' ORDER BY updated_at DESC;";
         $q = $db->prepare($sql);
         if ($q->execute() == TRUE) {
             $tank[$valt] = $q->fetchAll();
         }   else {
             die(show_message($q->errorInfo(),__line__,__file__,$sql));
         }

         foreach ($tank as $vals) {
            $first = count($vals) - 1;
            if (isset($vals[0])){
                foreach($vals[0] as $key => $val){
                   if (!is_numeric($key) && $key != 'account_id' && $key != 'updated_at'){
                        $tmp = explode('_',$key);
                        if ($tmp[1] == 'battles'){
                            if (!isset($vals[$first][$key]) || ($vals[$first][$key] == 0 && $val > 0)){
                                 $new[] = array ('account_id' => $acc_id['account_id'], 'tank_id' => ($tmp[0]));
                            }
                        }
                   }
                }
            }
         }
         unset ($tank);
      }
   }

   return $new;
}

function best_player_progress_main($data){
   $max = array();
   foreach ($data as $accid => $valtemp){
      foreach ($valtemp as $valacc => $valtemp2){
         foreach ($valtemp2 as $valwls => $val) {
            if (!isset($max[$valacc][$valwls]['value'])){
                 $max[$valacc][$valwls]['value'] = $val;
                 $max[$valacc][$valwls]['account_id'] = $accid;
            }    else {
                 if ($max[$valacc][$valwls]['value'] < $val){
                     $max[$valacc][$valwls]['value'] = $val;
                     $max[$valacc][$valwls]['account_id'] = $accid;
                 }
            }
         }
      }
   }
   return $max;
}

function medal_progress($rosterid = null, $medals, $start = 0,$end = -1){
   global $db;
   $diff['unsort'] = array();
   $diff['sorted'] = array();
   if ($end == -1){
       $end = now();
   }

   $sql = "SELECT DISTINCT updated_at FROM `col_medals` WHERE updated_at < '".$end."' AND updated_at >= '".$start."' ORDER BY updated_at DESC;";
   $q = $db->prepare($sql);
   if ($q->execute() == TRUE) {
       $result = $q->fetchAll();
   }   else {
       die(show_message($q->errorInfo(),__line__,__file__,$sql));
   }
   if (count($result) > 1){
       $first = array_pop($result);
       $first = $first['updated_at'];
       $last = $result[0]['updated_at'];

       $sql = "SELECT * FROM `col_medals` WHERE updated_at = '".$first."';";
       $q = $db->prepare($sql);
       if ($q->execute() == TRUE) {
           $dfirst = $q->fetchAll();
       }   else {
           die(show_message($q->errorInfo(),__line__,__file__,$sql));
       }
       foreach($dfirst as $val){
          if (isset($rosterid[$val['account_id']])) {
              $dfirst_new[$val['account_id']] = $val;
          }
       }
       $sql = "SELECT * FROM `col_medals` WHERE updated_at = '".$last."';";
       $q = $db->prepare($sql);
       if ($q->execute() == TRUE) {
           $dlast = $q->fetchAll();
       }   else {
           die(show_message($q->errorInfo(),__line__,__file__,$sql));
       }
       foreach($dlast as $vals){
          if (isset($dfirst_new[$vals['account_id']])){
              foreach($vals as $key => $val){
                 if(isset($medals[$key]['section']) && !is_numeric($key) && $key != 'account_id' && $medals[$key]['section'] != 'class'){
                     $diff['unsort'][$vals['account_id']][$key] = abs($val - $dfirst_new[$vals['account_id']][$key]);
                 }
              }
          }
       }

   }
   foreach($diff['unsort'] as $id => $vals){
      foreach($vals as $key => $val){
         $diff['sorted'][$medals[$key]['section']][$id][$key] = $val;
      }
   }
   return $diff;
}

function medals_resort($medal_progress,$rosterid) {
   $new['list'] = array();
   $new['data'] = array();
   foreach($medal_progress['sorted'] as $type => $player){
      foreach($player as $id => $medals){
         if (isset($rosterid[$id])){
             foreach($medals as $med_name => $val){
                if ($val != 0){
                    $new['data'][(str_replace(range(0,9),'',$type))][$id][$med_name] = $val;
                    if (!isset($new['list'][(str_replace(range(0,9),'',$type))][$med_name])){
                         $new['list'][(str_replace(range(0,9),'',$type))][$med_name] = TRUE;
                    }
                    if (isset($new['id'][$id])){
                        $new['id'][$id] += $val;
                    }   else {
                        $new['id'][$id] = $val;
                    }
                }
             }
         }
      }
   }
   return $new;
}

function best_medal_progress($data){
  $max = array();
  foreach($data as $id => $vals){
    foreach($vals as $key => $val){
      if (!isset($max[$key]['value'])){
           $max[$key]['value'] = $val;
           $max[$key]['account_id'] = $id;
      }    else {
           if ($max[$key]['value'] < $val){
               $max[$key]['value'] = $val;
               $max[$key]['account_id'] = $id;
           }
      }
    }
  }
  return $max;
}

function player_progress($account_id, $tables, $tanks, $start = 0,$end = -1){
   global $db;
   if ($end == -1){
       $end = now();
   }
   $sql = "SELECT * FROM `col_players` WHERE account_id = '".$account_id."' AND updated_at < '".$end."' AND updated_at >= '".$start."' ORDER BY updated_at DESC;";
   $q = $db->prepare($sql);
   if ($q->execute() == TRUE) {
       $players = $q->fetchAll();
   }   else {
       die(show_message($q->errorInfo(),__line__,__file__,$sql));
   }

   foreach($players as $vals){
      foreach($vals as $key => $val){
         if (!is_numeric($key)){
              $pnew[$vals['updated_at']][$key] = $val;
         }
      }
   }
   $sql = "SELECT * FROM `col_medals` WHERE account_id = '".$account_id."' AND updated_at < '".$end."' AND updated_at >= '".$start."' ORDER BY updated_at DESC;";
   $q = $db->prepare($sql);
   if ($q->execute() == TRUE) {
       $medals = $q->fetchAll();
   }   else {
       die(show_message($q->errorInfo(),__line__,__file__,$sql));
   }
   foreach($medals as $vals){
      foreach($vals as $key => $val){
         if (!is_numeric($key) && $key != 'account_id' && $key != 'updated_at'){
              $pnew[$vals['updated_at']]['medals'][$key] = $val;
         }
      }
   }

   foreach($tables as $val){
      $tmp = explode('_',$val);
      $sql = "SELECT * FROM `".$val."` WHERE account_id = '".$account_id."' AND updated_at < '".$end."' AND updated_at >= '".$start."' ORDER BY updated_at DESC;";
      $q = $db->prepare($sql);
      if ($q->execute() == TRUE) {
          $ftank[$tmp[2]] = $q->fetchAll();
      }   else {
          die(show_message($q->errorInfo(),__line__,__file__,$sql));
      }
   }

   $newt = array();
   foreach($ftank as $nat){
      foreach($nat as $vals){
         foreach($vals as $key => $val){
            if (!is_numeric($key)){
                 $newt[$vals['updated_at']][$key] = $val;
            }
         }
      }
   }

   foreach ($tanks as $tank_id => $val) {
      foreach($newt as $time => $dd){
         if (isset($dd[$tank_id.'_battles'])){
             if ($dd[$tank_id.'_battles'] > 0){
                 $pnew[$time]['tanks'][$val['level']][$val['type']][$val['tank_id']]['name_i18n']  = $val['name_i18n'];
                 $pnew[$time]['tanks'][$val['level']][$val['type']][$val['tank_id']]['link']       = $val['image_small'];
                 $pnew[$time]['tanks'][$val['level']][$val['type']][$val['tank_id']]['nation']     = $val['nation'];
                 $pnew[$time]['tanks'][$val['level']][$val['type']][$val['tank_id']]['is_premium'] = $val['is_premium'];
                 $pnew[$time]['tanks'][$val['level']][$val['type']][$val['tank_id']]['level']      = $val['level'];
                 $pnew[$time]['tanks'][$val['level']][$val['type']][$val['tank_id']]['type']       = $val['type'];
                 $pnew[$time]['tanks'][$val['level']][$val['type']][$val['tank_id']]['battles']    = $dd[$tank_id.'_battles'];
                 $pnew[$time]['tanks'][$val['level']][$val['type']][$val['tank_id']]['wins']       = $dd[$tank_id.'_wins'];
             }
         }
      }
   }
   return $pnew;
}

function time_summer($array,$name){
   $sum = 0;
   foreach($array as $val){
      $sum += $val[$name];
   }
   return $sum;
}
?>