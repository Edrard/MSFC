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
* @version     $Rev: 3.1.2 $
*
*/

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
        $col_pl['created_at'] = $data['joined_at'];
        $col_pl['role'] = $data['role'];

        foreach ($medals as $key => $val) {
          if(isset($data['achievements'][$key])) {
            $col_med[$key] = $data['achievements'][$key];
          } else {
            $col_med[$key] = 0;
          }
        }
        foreach ($data['tanks'] as $key => $val) {
          if(isset($tanks[$val['tank_id']])) {
            $col_tanks[$tanks[$val['tank_id']]['nation']][$val['tank_id'].'_battles'] = $val['statistics']['battles'];
            $col_tanks[$tanks[$val['tank_id']]['nation']][$val['tank_id'].'_wins'] = $val['statistics']['wins'];
            $col_tanks[$tanks[$val['tank_id']]['nation']][$val['tank_id'].'_mark_of_mastery'] = $val['mark_of_mastery'];
          }
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
    /*TODO: Посмотреть, можно ли сделать один запрос с множеством VALUES, а не много запросов. */
    // print_R($sqlarr);
    if (!empty($sqlarr)) {
        foreach ($sqlarr as $sql) {
          $db->insert($sql,__line__,__file__);
        }
    }
}
?>