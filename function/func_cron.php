<?php
/*
* Project:     Clan Stat
* License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
* Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
* -----------------------------------------------------------------------
* Began:       2011
* Date:        $Date: 2013-10-20 00:00:00 +0200 $
* -----------------------------------------------------------------------
* @author      $Author: Edd, Exinaus, SHW  $
* @copyright   2011-2013 Edd - Aleksandr Ustinov
* @link        http://wot-news.com
* @package     Clan Stat
* @version     $Rev: 3.1.1 $
*
*/


function cron_update_tanks_db() {
    global $db, $config;
    $sql = "select tank_id from `tanks`;";
    $q = $db->prepare($sql);
    if ($q->execute() == TRUE) {
        $sel = $q->fetchAll();
    }   else {
        die(show_message($q->errorInfo(),__line__,__file__,$sql));
    }
    $tanks = array();

    foreach ($sel as $key => $val) {
        $tanks[$val['tank_id']] = $val['tank_id'];
    }
    unset($sel);
    $tmp = get_api('encyclopedia/tanks');
    if ((isset($tmp['status'])) && ($tmp['status'] == 'ok')) {
        $updatearr = $toload = array ();
        foreach ($tmp['data'] as $tank_id => $val) {
            if (!in_array($tank_id,$tanks)) {
                $updatearr [$tank_id]['tank_id']     = $val['tank_id'];
                $updatearr [$tank_id]['type']        = $val['type'];
                $updatearr [$tank_id]['nation_i18n'] = $val['nation_i18n'];
                $updatearr [$tank_id]['level']       = $val['level'];
                $updatearr [$tank_id]['nation']      = $val['nation'];
                $updatearr [$tank_id]['name_i18n']   = $val['name_i18n'];

                $pieces = explode(':', $val['name']);
                $updatearr [$tank_id]['title']      = $pieces['1'];

                if ($val['is_premium']== true) {
                    $updatearr [$val['tank_id']]['is_premium']      = 1;
                }   else {
                    $updatearr [$val['tank_id']]['is_premium']      = 0;
                }
                $toload[$val['tank_id']] = $val['tank_id'];
            }
        }
        unset($tmp);
        if (!empty($toload)) { $try = 0;
            do {
              $tmp = array();
              $tmp = multiget_v2('tank_id', $toload, 'encyclopedia/tankinfo', array ('contour_image', 'image', 'image_small'));
              foreach($tmp as $tank_id => $val){
                  if ((isset($val['status'])) && ($val['status'] == 'ok') && !empty($val['data'])) {
                      $updatearr [$tank_id]['image']         = $val['data']['image'];
                      $updatearr [$tank_id]['contour_image'] = $val['data']['contour_image'];
                      $updatearr [$tank_id]['image_small']   = $val['data']['image_small'];

                      unset($toload[$tank_id]);
                  }
              }
              $try++;
            }  while ( !empty($toload) and $try < $config['try_count'] );
            unset($tmp);
            //some tanks not loaded
            if(!empty($toload)) {
              die('Problem with getting tank info. Tank id:'.implode(",", $toload));
            }
            $sql = "INSERT INTO `tanks` (`tank_id`, `nation_i18n`, `level`, `nation`, `is_premium`, `title`, `name_i18n`, `type`, `image`, `contour_image`, `image_small`) VALUES ";
            foreach ($updatearr as $tank_id => $val) {
                $sql .= "('{$val['tank_id']}', '{$val['nation_i18n']}', '{$val['level']}', '{$val['nation']}', '{$val['is_premium']}',  '{$val['title']}', '{$val['name_i18n']}', '{$val['type']}', '{$val['image']}', '{$val['contour_image']}', '{$val['image_small']}'), ";
            }
            $sql = substr($sql, 0, strlen($sql)-2);
            $sql .= ';';
            $q = $db->prepare($sql);
            if ($q->execute() != TRUE) {
                die(show_message($q->errorInfo(),__line__,__file__,$sql));
            }
        }
        $message = 'ok';
    }   else {
        if (isset($tmp['error']['message'])) {
            $message = 'Some error with getting data from WG ( '.$tmp['error']['message'].' )';
        }   else {
            $message = 'Some error with getting data from WG';
        }
    }
    return $message;
}

function cron_insert_pars_data($data, $medals, $tanks, $nationsin, $time){
    global $db;
    //   print_r($data);
    $col_pl = $col_med = $col_tanks = $col_rat = $sqlarr = array();
    $stats = array('all', 'clan', 'company');
    $stats2 = array('spotted', 'hits', 'battle_avg_xp', 'draws', 'wins', 'losses', 'capture_points',
        'battles', 'damage_dealt', 'hits_percents', 'damage_received', 'shots', 'xp', 'frags',
        'survived_battles', 'dropped_capture_points');
    $stats4 = array ('rank', 'value');
    $stats5 = array ('global_rating','battles_count','wins_ratio','survived_ratio','frags_count','damage_dealt','xp_avg','xp_max','hits_ratio');
     if ((isset($data['status'])) && ($data['status'] == 'ok')) {
        $data = $data['data'];
        $col_pl['account_id'] = $col_med['account_id'] = $col_rat['account_id'] = $data['account_id'];
        $col_pl['updated_at'] = $col_med['updated_at'] = $col_rat['updated_at'] = $time;
        foreach ($nationsin as $val2){
            $col_tanks[$val2['nation']]['account_id'] = $data['account_id'];
            $col_tanks[$val2['nation']]['updated_at'] = $time;
        }

        foreach ($stats as $val){
            foreach ($stats2 as $val2){
                $col_pl[$val.'_'.$val2] = $data['statistics'][$val][$val2];
            }
        }
        $col_pl['nickname']   = $data['nickname'];
        $col_pl['max_xp'] = $data['statistics']['max_xp'];
        $col_pl['created_at'] = $data['created_at'];
        $col_pl['role'] = $data['role'];

        foreach ($medals as $key => $val) {
          if(isset($data['achievements'][$key])) {
            $col_med[$key] = $data['achievements'][$key];
          } else {
            $col_med[$key] = 0;
          }
        }
        foreach ($data['tanks'] as $key => $val) {
            $col_tanks[$tanks[$val['tank_id']]['nation']][$val['tank_id'].'_battles'] = $val['statistics']['battles'];
            $col_tanks[$tanks[$val['tank_id']]['nation']][$val['tank_id'].'_wins'] = $val['statistics']['wins'];
            $col_tanks[$tanks[$val['tank_id']]['nation']][$val['tank_id'].'_mark_of_mastery'] = $val['mark_of_mastery'];
        }
        foreach ($stats5 as $val) {
            foreach ($stats4 as $pv) {
                if (isset($data['ratings'][$val][$pv])) $col_rat[$val.'_'.$pv]  = $data['ratings'][$val][$pv];
            }
        }
    }
    unset($data, $medals, $tanks);
    if (!empty($col_pl)) {
        $sqlarr[] = "INSERT INTO `col_players` (".(implode(",",array_keys($col_pl))).") VALUES ('".(implode("','",$col_pl))."'); ";
    }
    if (!empty($col_med)) {
        $sqlarr[] = "INSERT INTO `col_medals` (".(implode(",",array_keys($col_med))).") VALUES ('".(implode("','",$col_med))."'); ";
    }
    if (!empty($col_tanks)) {
        foreach ($col_tanks as $nation =>$val){
            $sqlarr[] = "INSERT INTO `col_tank_".$nation."` (".(implode(",",array_keys($val))).") VALUES ('".(implode("','",$val))."'); ";
        }
    }
    if (!empty($col_rat)) {
        $sqlarr [] = "INSERT INTO `col_ratings` (".(implode(",",array_keys($col_rat))).") VALUES ('".(implode("','",$col_rat))."'); ";
    }

    // print_R($sqlarr);
    if (!empty($sqlarr)) {
        foreach ($sqlarr as $sql) {
            $q = $db->prepare($sql);
            if ($q->execute() != TRUE) {
                die(show_message($q->errorInfo(),__line__,__file__,$sql));
            }
        }
    }
}

function update_multi_cron($dbprefix) {
    global $db;
    $sql = "UPDATE `multiclan` SET cron = '".now()."' WHERE prefix = '".$dbprefix."';";
    $q = $db->prepare($sql);
    if ($q->execute() != TRUE) {
        die(show_message($q->errorInfo(),__line__,__file__,$sql));
    }
}

function get_config_cron_time($prefix) {
    global $db;
    $sql = "SELECT * FROM ".$prefix."config WHERE name = 'cron_time';";
    $q = $db->prepare($sql);
    if ($q->execute() == TRUE) {
        return $q->fetchAll(PDO::FETCH_ASSOC);
    }   else {
        die(show_message($q->errorInfo(),__line__,__file__,$sql));
    }
}

function cron_current_run($fh,$date) {
    global $db, $config;

    $sql = "SELECT account_id FROM `col_players` LIMIT 1;";
    $q = $db->prepare($sql);
    if ($q->execute() == TRUE) {
        $id = $q->fetchColumn();
    }   else {
        die(show_message($q->errorInfo(),__line__,__file__,$sql));
    }

    $sql = "SELECT COUNT(account_id) FROM `col_players` WHERE account_id = '".$id."';";
    $q = $db->prepare($sql);
    if ($q->execute() == TRUE) {
        $player_stat = $q->fetchColumn();
    } else {
        die(show_message($q->errorInfo(),__line__,__file__,$sql));
    }
    fwrite($fh, $date.": (Info) Current run number ".($player_stat + 1)."\n");
}
?>