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
* @version     $Rev: 3.2.2 $
*
*/

//Получаем информацио о апи
$api_api = get_api('wot/encyclopedia/info');
$api_cache = $cache->get('api_info', 0, ROOT_DIR.'/cache/other/');
//Временный фикс, для тех кто использует промежуточную github версию модуля
//Фикс необходим т.к. в версии 3.1.0 нет этого в кэше, а в промежуточных github версиях есть, но данные старые, без этого параметра
//Через релиз после 3.1.0 можно удалять
if(isset($api_cache['status']) and !isset($api_cache['data']['tanks_updated_at'])) {
  $api_cache['data']['tanks_updated_at'] = 0;
}

//В кэше пусто и данные не получены
if( ( ($api_cache === FALSE) or empty($api_cache) or !isset($api_cache['status']) ) and ( !isset($api_api['status']) or $api_api['status'] != 'ok' or empty($api_api['data']) ) ) {
  die('No information about API version.');
}
//Данные о апи успешно получены
if(isset($api_api['status']) and $api_api['status'] == 'ok' and !empty($api_api['data'])) {
  //Данных в кэше нет
  if( ($api_cache === FALSE) or empty($api_cache) or !isset($api_cache['status']) ) {
      $cache->set('api_info', $api_api, ROOT_DIR.'/cache/other/');
      $api_cache = $api_api;
  }
  //Сравниваем данные о АПИ в кэше и полученные
  //Если версии отличаются, и дата обновления данных о технике не совпадает
  //TODO: если добавится дата обновления информации о наградах, добавить и ее
  if($api_api['data']['tanks_updated_at'] != $api_cache['data']['tanks_updated_at'] and $api_api['data']['game_version'] != $api_cache['data']['game_version']) {
    //обновляем кэш
    $cache->clear('api_info', ROOT_DIR.'/cache/other/');
    $cache->set('api_info', $api_api, ROOT_DIR.'/cache/other/');
    //удаляем данные о танках
    $db->insert('TRUNCATE TABLE `tanks`;',__line__,__file__);
    $cache->clear_all(array(), ROOT_DIR.'/cache/tanks/');
    //Получаем информацию о танках
    update_tanks_db();
    //удаляем данные о наградах
    $db->insert('TRUNCATE TABLE `achievements`;',__line__,__file__);
    //Получаем информацию о наградах
    update_achievements_db();
     //Получаем информацию о Укрепах
    update_stronghold_db();
  }
}

$tanks = tanks();
$achievements = achievements();
// update list of all tanks in game from api if need
if (empty($tanks)) {
    update_tanks_db();
    $tanks = tanks();
}
// update list of all achievements in game from api if need
if (empty($achievements)) {
    update_achievements_db($achievements);
    $achievements = achievements();
}

$col_tables = reform($db->select('SHOW TABLES FROM `'.$dbname.'` LIKE "col_tank_%";',__line__,__file__));
$col_check  = count($db->select('SELECT DISTINCT updated_at FROM `col_players`;',__line__,__file__));

$multiclan = read_multiclan();
$multiclan_main = multi_main($multiclan);
//Starting geting data for clans
foreach($multiclan as $clan){
    $multiclan_info[$clan['id']] = $cache->get('get_last_roster_'.$clan['id'], 0);
    if (($multiclan_info[$clan['id']] === FALSE) or (empty($multiclan_info[$clan['id']])) or ($clan['id'] == $config['clan'])) {
        $multiclan_info[$clan['id']] = get_api('wgn/clans/info',array('clan_id' => $clan['id']));
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

    if(!empty($links)) { $try = 0; $update_eff = 1;
      do {    
        $res_base = array();
        $res_base['info'] = multiget_v2('account_id', $links, 'account/info');    
        $res_base['tanks'] = multiget_v2('account_id', $links, 'account/tanks', array('mark_of_mastery', 'tank_id', 'statistics.battles', 'statistics.wins')); //loading only approved fields
        $res_base['ratings'] = multiget_v2('account_id', $links, 'ratings/accounts', array(), array('type'=>'all'));
        $res_base['achievements'] = multiget_v2('account_id', $links, 'account/achievements');

        foreach($links as $i => $p_id) {
          //info
          if( !isset($res_base['info'][$p_id]['status']) or $res_base['info'][$p_id]['status'] != 'ok' or empty($res_base['info'][$p_id]['data']) ) {
            continue;
          }
          //tanks
          if( !isset($res_base['tanks'][$p_id]['status']) or $res_base['tanks'][$p_id]['status'] != 'ok' ) {
            continue;
          }
          //ratings
          if( !isset($res_base['ratings'][$p_id]['status']) or $res_base['ratings'][$p_id]['status'] != 'ok' ) {
            continue;
          }
          //achievements
          if( !isset($res_base['achievements'][$p_id]['status']) or $res_base['achievements'][$p_id]['status'] != 'ok' or empty($res_base['achievements'][$p_id]['data']['frags']) or empty($res_base['achievements'][$p_id]['data']['max_series']) ) {
            continue;
          }

          $to_cache = array();
          $to_cache = $res_base['info'][$p_id];
          if(isset($to_cache['data']['achievements'])) {
            unset($to_cache['data']['achievements']);
          }
          $to_cache['data']['achievements'] = array_merge($res_base['achievements'][$p_id]['data']['achievements'],$res_base['achievements'][$p_id]['data']['frags'],$res_base['achievements'][$p_id]['data']['max_series']);
          $to_cache['data']['tanks'] = array_resort($res_base['tanks'][$p_id]['data'],'tank_id');
          $to_cache['data']['ratings'] = $res_base['ratings'][$p_id]['data'];

          $cache->set($p_id, $to_cache, ROOT_DIR.'/cache/players/');
          $res[$res_base['info'][$p_id]['data']['nickname']] = $to_cache;

          unset($links[$i]);
        }
        $try++;
      }  while ( !empty($links) and $try < $config['try_count'] );
    }
}

//Battle types    
$batt_types = get_battle_types($res[array_rand($res,1)]);
$batt_types_activity = array('all','clan','company');
ksort($batt_types);
//print_r($batt_types); die;

//Autocleaner
autoclean((86400*7), $multiclan, $config, ROOT_DIR.'/cache/players/');

/* code for wn8 */
$wn8 = $cache->get('wn8', 7*24*60*60, ROOT_DIR.'/cache/other/'); //once in 7 days
if(($wn8 === FALSE) or !isset($wn8['data']) or empty($wn8['data'])) {
  $wn8_get = get_url('http://www.wnefficiency.net/exp/expected_tank_values_latest.json', 1);
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

/* code for eff. ratings */
$eff_rating = $cache->get('eff_ratings_'.$config['clan'], 0, ROOT_DIR.'/cache/other/');
build_ratings_tables();

if(is_array($eff_rating)) {
  $tmp = array_diff(array_keys($roster),array_keys($eff_rating));
  if(!empty($tmp)) { $update_eff = 1; }
  unset($tmp);
}

if(isset($update_eff) or $eff_rating == false) {
  $eff_rating = eff_rating($res,$wn8);
  $cache->clear('eff_ratings_'.$config['clan'],ROOT_DIR.'/cache/other/');
  $cache->set('eff_ratings_'.$config['clan'], $eff_rating, ROOT_DIR.'/cache/other/');
}

$tanks_nation = tanks_nations();
$tanks_types = tanks_types();
$tanks_lvl = tanks_lvl();
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