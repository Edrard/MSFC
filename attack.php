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
* @version     $Rev: 3.2.1 $
*
*/


error_reporting(E_ALL & ~E_STRICT);
ini_set("display_errors", 1);
if (file_exists(dirname(__FILE__).'/function/mysql.php')) {
    define('ROOT_DIR', dirname(__FILE__));
}else{
    define('ROOT_DIR', '.');
}
//Starting script time execution timer
$begin_time = microtime(true);

//Checker
include(ROOT_DIR.'/including/check.php');

//MYSQL
include(ROOT_DIR.'/function/mysql.php');

//Multiget CURL
require(ROOT_DIR.'/function/curl.php');
require(ROOT_DIR.'/function/mcurl.php');

// Include Module functions
require(ROOT_DIR.'/function/func.php');
require(ROOT_DIR.'/function/func_main.php');
require(ROOT_DIR.'/function/func_get.php');

// Including main config files
include(ROOT_DIR.'/function/config.php');
require(ROOT_DIR.'/config/config_'.$config['server'].'.php');

//Loding language pack
foreach(scandir(ROOT_DIR.'/translate/') as $files){
    if (preg_match ("/_".$config['lang'].".php/", $files)){
        require(ROOT_DIR.'/translate/'.$files);
    }
}

//Определяем активную карту на ГК 
$maps_active = 0;
$maps = get_api('wot/globalmap/info');

if(isset($maps['status']) and $maps['status'] == 'ok') {
  if($maps['data']['state'] == 'active' ) { $maps_active = 1; }
}

if($maps_active) {
  $prov = $p_info = $owner = $owner_info = array();
  $battel = get_api('wot/globalmap/clanbattles',array('clan_id' => $config['clan'], 'fields' => 'front_id,time,province_id,type'));

  if(isset($battel['status']) and $battel['status'] == 'ok' and !empty($battel['data'])) {

  foreach($battel['data'] as $v) { $prov[] = $v['province_id']; $front_id = $v['front_id']; }

  $provinces = get_api('wot/globalmap/provinces',array('front_id' => $front_id, 'province_id' => $prov, 'fields' => 'province_id,province_name,arena_name,prime_time,owner_clan_id,daily_revenue,revenue_level,uri'));

    if(isset($provinces['status']) and $provinces['status'] == 'ok') {
      $p_info = array_resort($provinces['data'],'province_id');
      foreach($p_info as $val) {
        if(!in_array($val['owner_clan_id'],$owner)) {
          $owner[] = $val['owner_clan_id'];
        }
        //arrrrgh ... f***ng WG .. i hate how they change prime time display every time ...
        //fix it ...
        $p_time = explode(':',$val['prime_time']);
        $p_info[$val['province_id']]['prime_time'] = (string) ( $p_time['0'] + $config['time'] ).':'.$p_time['1'];
      }
      $tmp = get_api('wgn/clans/info',array('clan_id' => $owner, 'fields' => 'emblems.x24,color,tag'));
      if(isset($tmp['status']) and $tmp['status'] == 'ok') {
        $owner_info = $tmp['data'];
        foreach($p_info as $val) {
          $o_info = $tmp['data'][$val['owner_clan_id']];
          $p_info[$val['province_id']]['owner_info']  = '<img style="height: 16px; vertical-align: middle; width: 16px;" src="'.$o_info['emblems']['x24']['portal'].'" border="0">';
          $p_info[$val['province_id']]['owner_info'] .= '&nbsp;';
          $p_info[$val['province_id']]['owner_info'] .= '<a href="http://'.$config['gm_url'].'/community/clans/'.$val['owner_clan_id'].'/" target="_blank" style="color: '.$o_info['color'].'">['.$o_info['tag'].']</a>';
        }
      }
    }
  }
} ?>
<? if(!$maps_active) { ?>
  <div align="center" class="ui-state-highlight ui-corner-all "><?=$lang['global_map_frozen'];?></div>
<? } else { ?>
  <script type="text/javascript" id="js">
     $(document).ready(function()
     {
         $("#attack_table").tablesorter({sortList:[[1,0]]});
     });
  </script>
  <div align="center">
      <table id="attack_table" cellspacing="1" cellpadding="2" width="100%">
          <thead>
              <tr>
                  <th width="40"><?=$lang['type']; ?></th>
                  <th width="100"><?=$lang['time']; ?></th>
                  <th width="100"><?=$lang['prime_time']; ?></th>
                  <th width="25%"><?=$lang['title_name']; ?></th>
                  <th width="25%"><?=$lang['map']; ?></th>
                  <th width="100"><?=$lang['income']; ?></th>
                  <th width="150" class="sorter-false"><?=$lang['global_map_owner'];?></th>
              </tr>
          </thead>
          <tbody>
          <? if( isset($battel['error']['message']) ) { ?>
              <tr><td colspan="7" align="center"><?=$lang['error_1'].(isset($battel['error']['message'])?' ('.$battel['error']['message'].')':'');?></td></tr>
          <? } elseif( empty($battel['data']) ) { ?>
              <tr><td colspan="7" align="center"><?=$lang['no_war'];?></td></tr>
          <? } else { ?>
              <? foreach($battel['data'] as $val){ ?>
                  <tr>
                      <td align="center"><img src="./images/<?=($val['type']=='attack')?'meeting_engagement':'for_province';?>.png"></td>
                      <td align="center"><?=($val['time'] > 1)?date('H:i',$val['time']).' +':'--:--'; ?></td>
                      <td align="center"><?=isset($p_info[$val['province_id']]['prime_time'])?$p_info[$val['province_id']]['prime_time']:'--:--'; ?></td>
                      <td align="center">
                        <? if(isset($p_info[$val['province_id']]['province_name'])) { ?>
                          <a href="http://<?=$config['server'];?>.wargaming.net/globalmap<?=$p_info[$val['province_id']]['uri'];?>" target="_blank"><?=$p_info[$val['province_id']]['province_name']; ?></a>
                        <? } else { ?>
                          ---
                        <? } ?>
                      </td>
                      <td align="center"><?=isset($p_info[$val['province_id']]['arena_name'])?$p_info[$val['province_id']]['arena_name']:'--'; ?></td>
                      <td align="center" style="color: #ba904d;"><?=isset($p_info[$val['province_id']]['daily_revenue'])?$p_info[$val['province_id']]['daily_revenue']:0;?> <img src="./images/currency-gold.png" border="0"> (x<?=isset($p_info[$val['province_id']]['revenue_level'])?$p_info[$val['province_id']]['revenue_level']:0;?>)</td>
                      <td align="center"><?=isset($p_info[$val['province_id']]['owner_info'])?$p_info[$val['province_id']]['owner_info']:'---'; ?></td>
                  </tr>
              <? } ?>
          <? } ?>
          </tbody>
      </table>
  </div>
  <br>
<? } ?>