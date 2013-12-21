<?php
/*
* Project:     Clan Stat
* License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
* Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
* -----------------------------------------------------------------------
* Began:       2011
* Date:        $Date: 2013-11-20 11:54:02 +0200 $
* -----------------------------------------------------------------------
* @author      $Author: Edd, Exinaus, Shw  $
* @copyright   2011-2013 Edd - Aleksandr Ustinov
* @link        http://wot-news.com
* @package     Clan Stat
* @version     $Rev: 3.0.2 $
*
*/
$col_tables = get_tables_like_col_tank($dbname);  
$col_check = get_updated_at();

$multiclan = read_multiclan();
$multiclan_main = multi_main($multiclan);
//Starting geting data for clans
foreach($multiclan as $clan){
    $multiclan_info[$clan['id']] = $cache->get('get_last_roster_'.$clan['id'], 0);
    if (($multiclan_info[$clan['id']] === FALSE) or (empty($multiclan_info[$clan['id']])) or ($clan['id'] == $config['clan'])) {
        $multiclan_info[$clan['id']] = get_clan_v2($clan['id'],'info', $config);
        if (empty($multiclan_info[$clan['id']])) {
            $multiclan_info[$clan['id']]['status'] = 'error';
        }
        if ($multiclan_info[$clan['id']]['status'] == 'ok'){
            $cache->clear('get_last_roster_'.$clan['id']);
            $cache->set('get_last_roster_'.$clan['id'], $multiclan_info[$clan['id']]);
        }
    }
    if (isset($multiclan_info[$clan['id']]['error']['message']) ) {
        $message = $multiclan_info[$clan['id']]['error']['message'];
    }   else {
        $message = '';
    }
    if (($multiclan_info[$clan['id']] === FALSE) or (empty($multiclan_info[$clan['id']])) or ($multiclan_info[$clan['id']]['status'] != 'ok')) {
        if ($clan['id'] == $config['clan']) {
            $multiclan_info[$clan['id']] = $cache->get('get_last_roster_'.$clan['id'], 0);
        }
        if (($multiclan_info[$clan['id']] === FALSE) or (empty($multiclan_info[$clan['id']])) ){
            die('No cahced data! ClanID='.$clan['id'].', ('.$message.')');
        }
    }
}

//Starting geting data for players
if($multiclan_info[$config['clan']]['status'] == 'ok'){
    $roster = roster_sort($multiclan_info[$config['clan']]['data'][$config['clan']]['members']);
    $roster_id = roster_resort_id($roster);

    //check is any players data to load
    $links = array();
    foreach($roster as $name => $pldata){
        $tmp = $cache->get($pldata['account_id'], $config['cache']*3600+1, ROOT_DIR.'/cache/players/');
        if( ($tmp === FALSE) || (empty($tmp)) || ((isset($tmp['status'])) && ($tmp['status']<>'ok')) ) {
            $cache->clear($pldata['account_id'],ROOT_DIR.'/cache/players/');
            $links[] = $pldata['account_id'];
        }
    }
    unset($pldata,$tmp);
    if (!empty($links)) {
        $res_base['info'] = multiget_v2($links, 'account/info', $config);
        $res_base['tanks'] = multiget_v2($links, 'account/tanks', $config, array('mark_of_mastery', 'tank_id', 'statistics.battles', 'statistics.wins')); //loading only approved fields
        $res_base['ratings'] = multiget_v2($links, 'account/ratings', $config);

        foreach ($res_base['info'] as $key => $val) {
            if ($val['status'] == 'ok' && $res_base['tanks'][$key]['status'] == 'ok' && $res_base['ratings'][$key]['status'] == 'ok') {
                $val['data']['tanks'] = $res_base['tanks'][$key]['data'];
                if (isset ($res_base['ratings'][$key]['data'])){ 
                    $val['data']['ratings'] = $res_base['ratings'][$key]['data']; 
                }
                $cache->set($key, $val, ROOT_DIR.'/cache/players/');
            }   else {
                //show error with getting data
            }
        }
        unset($links, $res_base);
    }
    foreach($roster as $name => $pldata){
        $tmp = $cache->get($pldata['account_id'], $config['cache']*3600+1, ROOT_DIR.'/cache/players/');
        if( ($tmp === FALSE) || (empty($tmp)) || ((isset($tmp['status'])) && ($tmp['status']<>'ok')) ) {
            $res[$name] = $pldata;
        }    else {
            $res[$name] = $tmp;
        }
    }
    unset($tmp);
}

//Autocleaner
autoclean((86400*7), $multiclan, $config, ROOT_DIR.'/cache/players/');

$tanks = tanks();
// update list of all tanks in game from api if need
if (empty($tanks)) {
    include_once(ROOT_DIR.'/admin/func_admin.php');
    update_tanks_db();
    $tanks = tanks();
}

$eff_rating = eff_rating($res);
$tanks_nation = tanks_nations();
$tanks_types = tanks_types();
$tanks_lvl = tanks_lvl();
$medn = medn($tanks_nation);

sort($tanks_lvl);
?>