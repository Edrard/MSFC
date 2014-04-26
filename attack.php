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
* @version     $Rev: 3.0.4 $
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
$maps_link = array( 1 => 'globalmap', 2 => 'eventmap' );
$maps_active = array();
$maps = get_api('globalwar/maps');

if(isset($maps['status']) and $maps['status'] == 'ok') {
  foreach($maps['data'] as $val) {
    if($val['state'] == 'active' ) { $maps_active[] = $val['map_id']; }
  }
}

foreach($maps_active as $maps_id) {
    $prov = $p_info = $owner = $owner_info = array();
    $battel = get_api('clan/battles',array('map_id' => $maps_id, 'clan_id' => $config['clan']));
    if(isset($battel['status']) and $battel['status'] == 'ok' and !empty($battel['data'][$config['clan']])) {
      foreach($battel['data'][$config['clan']] as $val) {
        $prov = array_merge($prov, $val['provinces']);
      }
      $provinces = get_api('globalwar/provinces',array('map_id' => $maps_id, 'province_id' => $prov, 'fields' => 'province_i18n,prime_time,clan_id,revenue'));
      if(isset($provinces['status']) and $provinces['status'] == 'ok') {
        $p_info = $provinces['data'];
        foreach($p_info as $val) {
          if(!in_array($val['clan_id'],$owner)) {
            $owner[] = $val['clan_id'];
          }
        }
        $tmp = get_api('clan/info',array('clan_id' => $owner, 'fields' => 'emblems.small,clan_color,abbreviation'));
        if(isset($tmp['status']) and $tmp['status'] == 'ok') {
          $owner_info = $tmp['data'];
        }
      } else {
        foreach ($battel['data'][$config['clan']] as $val) {
          foreach($val['provinces'] as $p) {
            $p_info[$p]['province_i18n'] = '***';
            $p_info[$p]['prime_time'] = '***';
          }
        }
      }
    }

    ?>
    <script type="text/javascript" id="js">
       $(document).ready(function()
       {
           $("#attack<?=$maps_id;?>").tablesorter({sortList:[[1,0]]});
       });
    </script>
    <div align="center">
        <?=$lang['global_map_n'],$maps_id;?>
        <table id="attack<?=$maps_id;?>" cellspacing="1" cellpadding="2" width="100%">
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
            <? if(!isset($maps_id) or !isset($battel['data'])) { ?>
                <tr><td colspan="7" align="center"><?=$lang['error_1'].(isset($battel['error']['message'])?' ('.$battel['error']['message'].')':'');?></td></tr>
            <? } elseif(empty($battel['data'][$config['clan']])) { ?>
                <tr><td colspan="7" align="center"><?=$lang['no_war'];?></td></tr>
            <? } else { ?>
                <? foreach($battel['data'][$config['clan']] as $val){ ?>
                    <tr>
                        <td align="center"><img src="./images/<?=$val['type'];?>.png"></td>
                        <td align="center"><?=($val['time'] > 1)?date('H:i',$val['time']).' +':'--:--'; ?></td>
                        <td align="center"><?=is_numeric($p_info[$val['provinces']['0']]['prime_time'])?date('H',mktime($p_info[$val['provinces']['0']]['prime_time']+$config['time'])).':00':'--:--'; ?></td>
                        <td align="center">
                          <a href="<?=$config['clan_link']; ?>maps/<?=$maps_link[$maps_id];?>/?province=<?=$val['provinces']['0']; ?>" target="_blank"><?=$p_info[$val['provinces']['0']]['province_i18n']; ?></a>
                            <? if(count($val['provinces'])>1) { ?>
                            &nbsp;x&nbsp;<a href="<?=$config['clan_link']; ?>maps/<?=$maps_link[$maps_id];?>/?province=<?=$val['provinces']['1']; ?>" target="_blank"><?=$p_info[$val['provinces']['1']]['province_i18n']; ?></a>
                            <? } ?>
                        </td>
                        <td align="center">
                          <?=$val['arenas']['0']['name_i18n']; ?>
                          <? if(count($val['arenas'])>1) { ?>
                            &nbsp;x&nbsp;<?=$val['arenas']['1']['name_i18n']; ?>
                          <? } ?>
                        </td>
                        <td align="center" style="color: #ba904d;"><?=isset($p_info[$val['provinces']['0']]['revenue'])?$p_info[$val['provinces']['0']]['revenue']:0;?> <img src="./images/currency-gold.png" border="0"></td>
                        <? if(!empty($owner_info)) { ?>
                          <td align="center" valign="middle"><img style="height: 16px; vertical-align: middle; width: 16px;" src="<?=$owner_info[$p_info[$val['provinces']['0']]['clan_id']]['emblems']['small'];?>" border="0"> <a href="http://worldoftanks.ru/community/clans/<?=$p_info[$val['provinces']['0']]['clan_id'];?>/" target="_blank" style="color: <?=$owner_info[$p_info[$val['provinces']['0']]['clan_id']]['clan_color'];?>;">[<?=$owner_info[$p_info[$val['provinces']['0']]['clan_id']]['abbreviation'];?>]</a></td>
                        <? } else { ?>
                          <td align="center" valign="middle" >---</td>
                        <? } ?>
                    </tr>
                <? } ?>
            <? } ?>
            </tbody>
        </table>
    </div>
    <br>
<? } ?>
<? if(empty($maps_active)) { ?>
  <div align="center" class="ui-state-highlight ui-corner-all "><?=$lang['global_map_frozen'];?></div>
<? } ?>