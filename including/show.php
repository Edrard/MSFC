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
        if ((empty($multiclan_info[$clan['id']])) || (!isset($multiclan_info[$clan['id']]['status']))) {
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
    if ( ($multiclan_info[$clan['id']] === FALSE) || (empty($multiclan_info[$clan['id']])) || (!isset($multiclan_info[$clan['id']]['status'])) ||
         ((isset($multiclan_info[$clan['id']]['status']))&&($multiclan_info[$clan['id']]['status'] != 'ok'))  ) {
        if ($clan['id'] == $config['clan']) {
            $multiclan_info[$clan['id']] = $cache->get('get_last_roster_'.$clan['id'], 0);
        }
        if (($multiclan_info[$clan['id']] === FALSE) or (empty($multiclan_info[$clan['id']])) ){
            die('No cahced data! ClanID='.$clan['id'].', ('.$message.')');
        }
    }
}

//Starting geting data for players
if ((isset($multiclan_info[$config['clan']]['status'])) && ($multiclan_info[$config['clan']]['status'] == 'ok')){
    $roster = roster_sort($multiclan_info[$config['clan']]['data'][$config['clan']]['members']);
    $roster_id = roster_resort_id($roster);

    //check is any players data to load
    $links = array();
    foreach($roster as $name => $pldata){
        $tmp = $cache->get($pldata['account_id'], $config['cache']*3600+1, ROOT_DIR.'/cache/players/');
        if( ($tmp === FALSE) || (empty($tmp)) || ((isset($tmp['status'])) && ($tmp['status']<>'ok')) ) {
            $cache->clear($pldata['account_id'],ROOT_DIR.'/cache/players/');
            $links[] = $pldata['account_id'];
        } else {
          $res[$name] = $tmp;
        }
        unset($tmp);
    }
    $links = array_chunk($links,$config['multiget']*5);
    unset($pldata,$tmp);
    if (!empty($links)) {
        foreach($links as $urls){
            $res_base['info'] = multiget_v2($urls, 'account/info', $config);
            $res_base['tanks'] = multiget_v2($urls, 'account/tanks', $config, array('mark_of_mastery', 'tank_id', 'statistics.battles', 'statistics.wins')); //loading only approved fields
            $res_base['ratings'] = multiget_v2($urls, 'ratings/accounts', $config, array(), array('type'=>'all'));

            foreach ($res_base['info'] as $key => $val) {
                if ((isset ($val['status'])) && ($val['status'] == 'ok')) {
                     if ((isset ($res_base['tanks'][$key]['status'])) && ($res_base['tanks'][$key]['status'] == 'ok')) {
                          $val['data']['tanks'] = $res_base['tanks'][$key]['data'];
                          if (isset ($res_base['ratings'][$key]['data'])){
                              $val['data']['ratings'] = $res_base['ratings'][$key]['data'];
                              $cache->set($key, $val, ROOT_DIR.'/cache/players/');
                              $res[$val['data']['nickname']] = $val;
                          }
                     }    else {
                          $message = "Can't load data on ".$key." (tank info)";
                     }
                }  else {
                   $message = "Can't load data on ".$key." (main player info)";
                }
            } 
        }
        unset($links, $res_base);

    }
}

//Autocleaner
autoclean((86400*7), $multiclan, $config, ROOT_DIR.'/cache/players/');

$tanks = tanks();
// update list of all tanks in game from api if need
if (empty($tanks)) {
    update_tanks_db();
    $tanks = tanks();
}

/* code for wn8 */
$wn8 = $cache->get('wn8', 7*24*60*60, ROOT_DIR.'/cache/other/'); //once in 7 days
if(($wn8 === FALSE) or !isset($wn8['data']) or empty($wn8['data'])) {
  $wn8_get = get_wn8();
  if(isset($wn8_get['header']['version']) and isset($wn8_get['data'])) {
    $wn8 = array_resort($wn8_get['data'],'IDNum');
    $cache->clear('wn8',ROOT_DIR.'/cache/other/');
    $cache->set('wn8', $wn8, ROOT_DIR.'/cache/other/');
  } else {
    $wn8 = array();
  }
  unset($wn8_get);
}
/* end wn8 */

$eff_rating = eff_rating($res,$wn8);
$tanks_nation = tanks_nations();
$tanks_types = tanks_types();
$tanks_lvl = tanks_lvl();
$medn = medn($tanks_nation);

sort($tanks_lvl);

if($config['company'] == 1 ) {
  $company = $cache->get('company_'.$config['clan'],0,ROOT_DIR.'/cache/other/');
  if(!isset($company['in_company'])) {
    $company['in_company'] = array();
  }
  if(!isset($company['tabs'])) {
    $company['tabs'] = array();
  }
  if(!isset($company['company_names']) or empty($company['company_names'])) {
    for($i=1;$i<=$config['company_count'];$i++) {
      $company['company_names'][$i] = $i;
    }
  }
}


?>