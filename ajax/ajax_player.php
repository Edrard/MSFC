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
    * @version     $Rev: 3.2.3 $
    *
    */



    error_reporting(E_ALL & ~E_STRICT);
    ini_set("display_errors", 1);
    if (!defined('ROOT_DIR')) {
    if (file_exists(dirname(__FILE__).'/func_ajax.php')) {
        define('LOCAL_DIR', dirname(__FILE__));
        require(LOCAL_DIR.'/func_ajax.php');
        define('ROOT_DIR', base_dir('ajax'));
    }else{
        define('LOCAL_DIR', '.');
        define('ROOT_DIR', '..');
    };
    }
    if (isset($_POST['nickname']) ) {
        $nickname = $_POST['nickname'];
    }   else{
        if (isset($_GET['nickname']) ) {
            $nickname = $_GET['nickname'];
        }   else {
            $nickname = '';
        }};
    if ($nickname == '') {
        die('Player Id Undefined!');
    }

    include(ROOT_DIR.'/including/check.php');
    require(ROOT_DIR.'/admin/translate/api_lang.php');
    require(ROOT_DIR.'/function/auth.php');
    include(ROOT_DIR.'/function/mysql.php');
    require(ROOT_DIR.'/function/func.php');
    require(ROOT_DIR.'/function/rating.php');
    require(ROOT_DIR.'/function/func_main.php');
    require(ROOT_DIR.'/function/func_get.php');
    require(ROOT_DIR.'/function/func_time.php');
    include(ROOT_DIR.'/function/config.php');
    require(ROOT_DIR.'/config/config_'.$config['server'].'.php');

    foreach(scandir(ROOT_DIR.'/translate/') as $files){
        if (preg_match ("/_".$config['lang'].".php/", $files)){
            require(ROOT_DIR.'/translate/'.$files);
        }
    } 
    //cache
    require(ROOT_DIR.'/function/cache.php');
    $cache = new Cache(ROOT_DIR.'/cache/');

function p_info($res, $t) {
   $tstat = array();
   foreach($res as $key => $val) {
      @$chart1[$t[$val['tank_id']]['type']]['total'] += $val['statistics']['battles'];
      @$chart1[$t[$val['tank_id']]['type']]['win'] += $val['statistics']['wins'];

      if ($val['statistics']['battles'] >= 20) {
          if ($t[$val['tank_id']]['type'] == 'lightTank' || $t[$val['tank_id']]['type'] == 'mediumTank' || $t[$val['tank_id']]['type'] == 'heavyTank'){
               @$chart2[$t[$val['tank_id']]['type']][$t[$val['tank_id']]['name_i18n']]['total'] += $val['statistics']['battles'];
               @$chart2[$t[$val['tank_id']]['type']][$t[$val['tank_id']]['name_i18n']]['win']   += $val['statistics']['wins'];
          }    else {
               @$chart5[$t[$val['tank_id']]['type']][$t[$val['tank_id']]['name_i18n']]['total'] += $val['statistics']['battles'];
               @$chart5[$t[$val['tank_id']]['type']][$t[$val['tank_id']]['name_i18n']]['win']   += $val['statistics']['wins'];
          }
      }

      @$chart3[$t[$val['tank_id']]['nation']]['total'] += $val['statistics']['battles'];
      @$chart3[$t[$val['tank_id']]['nation']]['win']   += $val['statistics']['wins'];

      @$chart4[$t[$val['tank_id']]['level']]['total'] += $val['statistics']['battles'];
      @$chart4[$t[$val['tank_id']]['level']]['win']   += $val['statistics']['wins'];
   } //end 1

   if(isset($chart1)) $tstat['table1'] = $chart1;
   if(isset($chart2)) $tstat['table2'] = $chart2;
   if(isset($chart3)) $tstat['table3'] = $chart3;
   if(isset($chart4)) $tstat['table4'] = $chart4;
   if(isset($chart5)) $tstat['table5'] = $chart5;

   return $tstat;
}

$pres = $cache->get($nickname, 0, ROOT_DIR.'/cache/players/');
$tanks = tanks();
$tanks_nation = tanks_nations();
$achievements = achievements();
$achievements_sorted = achievements_ajax_player($achievements);

if (isset($pres['data']['tanks'])) {
    $info = p_info($pres['data']['tanks'], $tanks);
}   else {
    $info = array();
};
$temp[$nickname] = $pres;
$eff_ratpl = eff_rating($temp);
unset($temp);

switch ($eff_ratpl[$nickname]['brone']+1) {
  case ($eff_ratpl[$nickname]['brone'] > 7294):
    $textt = $lang['classVf'];$imgg='classVf.png';
    break;
  case ($eff_ratpl[$nickname]['brone'] > 5571):
    $textt = $lang['classMf'];$imgg='classMf.png';
    break;
  case ($eff_ratpl[$nickname]['brone'] > 3851):
    $textt = $lang['class1f'];$imgg='class1f.png';
    break;
  case ($eff_ratpl[$nickname]['brone'] > 2736):
    $textt = $lang['class2f'];$imgg='class2f.png';
    break;
  case ($eff_ratpl[$nickname]['brone'] > 2084):
    $textt = $lang['class3f'];$imgg='class3f.png';
    break;
  case ($eff_ratpl[$nickname]['brone'] > 1452):
    $textt = $lang['deer3f'];$imgg='deer3f.png';
    break;
  case ($eff_ratpl[$nickname]['brone'] > 1010):
    $textt = $lang['deer2f'];$imgg='deer2f.png';
    break;
  case ($eff_ratpl[$nickname]['brone'] > 517):
    $textt = $lang['deer1f'];$imgg='deer1f.png';
    break;
  default:
    $textt = $lang['deerMf'];$imgg='deerMf.png';
    break;
};

$statmain   = array('wins', 'losses', 'draws', 'survived_battles');
$statbattle = array('spotted', 'frags', 'hits_percents', 'damage_dealt', 'damage_received', 'capture_points', 'dropped_capture_points');
$statexp    = array('xp', 'battle_avg_xp');
$statrat    = array('global_rating','battles_count','wins_ratio','survived_ratio','frags_count','damage_dealt','xp_avg','xp_max','hits_ratio');

$statacc = array('all', 'clan', 'company');
foreach ($statacc as $val) {
   if ($pres['data']['statistics'][$val]['battles'] == 0) {
       $pres['data']['statistics'][$val]['battles'] = 0.0001;
   }
}
?>

<div align="center">
  <table cellspacing="2" cellpadding="2" width="100%" id="tmain2">
   <tbody>
    <tr>
     <td valign="top" width="20%">
       <table cellspacing="1" cellpadding="1" width="100%" align="center" id="player0">
         <thead>
           <tr>
            <th align="center" class="sorter-false"><?=$lang['name']; ?></th>
           </tr>
         </thead>
         <tbody>
           <tr>
             <td align="center"><b><?=$pres['data']['nickname'];?></b></td>
           </tr>
         </tbody>
       </table>
       <table cellspacing="1" cellpadding="1" width="100%" align="center" id="player01">
         <thead>
           <tr>
            <th align="center" class="sorter-false"><?=$lang['upd_at'];?></th>
           </tr>
         </thead>
         <tbody>
           <tr>
             <td align="center"><b><?php echo date('d.m.Y',$pres['data']['updated_at']); ?></b></td>
           </tr>
         </tbody>
       </table>
       <table cellspacing="1" cellpadding="1" width="100%" align="center" id="player5">
         <thead>
           <tr>
            <th align="center" class="bb sorter-false" title="<?=$lang['overall_eff_table'];?>"><?=$lang['eff_ret']; ?> (c)
            <br><a href="http://wot-news.com/" target="_blank">wot-news.com</a></th>
           </tr>
         </thead>
         <tbody>
           <tr>
             <td align="center"><font color="<?=$eff_ratpl[$nickname]['eff_color']; ?>"><?=$eff_ratpl[$nickname]['eff']; ?></font></td>
           </tr>
         </tbody>
       </table>
       <table cellspacing="1" cellpadding="1" width="100%" align="center" id="player6">
         <thead>
           <tr>
            <th align="center" class="bb sorter-false" title="<?=$lang['brone_anno'];?>"><?=$lang['brone_ret'];?> (c)
                <br><a href="http://armor.kiev.ua/wot/" target="_blank">armor.kiev.ua/wot</a></th>
           </tr>
         </thead>
         <tbody>
           <tr>
             <td align="center"><?=$eff_ratpl[$nickname]['brone']; ?></td>
           </tr>
           <tr>
             <td align="center" colspan="2"><?php echo '<img src="./images/brone/'.$imgg.'" />'; ?></td>
           </tr>
           <tr>
             <td align="center" colspan="2"><?=$textt; ?></td>
           </tr>
         </tbody>
       </table>
       <table cellspacing="1" cellpadding="1" width="100%" align="center" id="player7">
         <thead>
           <tr>
             <th align="center" class="sorter-false" colspan="2"><?=$lang['emem'];?> (c) <br><a href="http://emem.ru/" target="_blank">emem.ru</a></th>
           </tr>
         </thead>
         <tbody>
           <tr>
             <td class="bb" title="<?=$lang['emem_fsb_title'];?>"><span class="hidden">1</span><?=$lang['emem_fsb'];?>:</td>
             <td><?php echo number_format(($pres['data']['statistics']['all']['spotted']+$pres['data']['statistics']['all']['frags'])/$pres['data']['statistics']['all']['battles'],3); ?></td>
           </tr>
           <tr>
             <td class="bb" title="<?=$lang['emem_fb_title'];?>"><span class="hidden">2</span><?=$lang['emem_fb'];?>:</td>
             <td><?php echo number_format($pres['data']['statistics']['all']['frags']/$pres['data']['statistics']['all']['battles'],3); ?></td>
           </tr>
           <tr>
             <td class="bb" title="<?=$lang['emem_sb_title'];?>"><span class="hidden">3</span><?=$lang['emem_sb'];?>:</td>
             <td><?php echo number_format($pres['data']['statistics']['all']['spotted']/$pres['data']['statistics']['all']['battles'],3); ?></td>
           </tr>
           <tr>
             <td class="bb" title="<?=$lang['emem_cb_title'];?>"><span class="hidden">4</span><?=$lang['emem_cb'];?>:</td>
             <td><?php echo number_format($pres['data']['statistics']['all']['capture_points']/$pres['data']['statistics']['all']['battles'],3); ?></td>
           </tr>
           <tr>
             <td class="bb" title="<?=$lang['emem_db_title'];?>"><span class="hidden">5</span><?=$lang['emem_db'];?>:</td>
             <td><?php echo number_format($pres['data']['statistics']['all']['dropped_capture_points']/$pres['data']['statistics']['all']['battles'],3); ?></td>
           </tr>
         </tbody>
       </table>
     </td>
     <td valign="top" width="42%">
       <table cellspacing="1" cellpadding="1" width="100%" align="center" id="player1">
         <thead>
           <tr>
             <th align="center" class="sorter-false" colspan="3"><?=$lang['overall_title']?></th>
           </tr>
         </thead>
         <tbody>
           <?php $i=1; foreach($statmain as $val) {?>
           <tr>
             <td><span class="hidden"><? echo $i;++$i; ?></span><?=$lang['all_'.$val]; ?>:</td>
             <td<?php if ($val == 'battles') {echo ' align="center" colspan="2"';}
                      echo '>'.number_format($pres['data']['statistics']['all'][$val], 0, '.', '').'</td>';
                      if ($val != 'battles') {echo '<td>'.number_format($pres['data']['statistics']['all'][$val]/$pres['data']['statistics']['all']['battles']*100,2).'%</td>'; } ?>
           </tr>
           <?php } ?>
         </tbody>
       </table>

       <table cellspacing="1" cellpadding="1" width="100%" align="center" id="player2">
         <thead>
           <tr>
             <th align="center" class="sorter-false" colspan="3"><?=$lang['perform_title'];?></th>
           </tr>
         </thead>
         <tbody>
           <?php $i=1; foreach($statbattle as $val) { ?>
           <tr>
             <td><span class="hidden"><? echo $i;++$i; ?></span><?=$lang['all_'.$val]; ?>:</td>
             <?php if ($val == 'hits_percents') {
                       echo '<td colspan="2" align="center">'.$pres['data']['statistics']['all'][$val].'%</td>';
                   }   else {
                       echo '<td>'.$pres['data']['statistics']['all'][$val].'</td>';
                       echo '<td>'.number_format($pres['data']['statistics']['all'][$val]/$pres['data']['statistics']['all']['battles'], 2, '.', '').'</td>';
                   };?>
           </tr>
           <?php } ?>
         </tbody>
       </table>

       <table cellspacing="1" cellpadding="1" width="100%" align="center" id="player4">
         <thead>
           <tr>
             <th align="center" class="sorter-false" colspan="2"><?= $lang['battel_title'];?></th>
           </tr>
         </thead>
         <tbody>
           <tr><td><span class="hidden">1</span><?=$lang['max_xp'];?></td><td><?=$pres['data']['statistics']['all']['max_xp']; ?></td></tr>
           <?php $i=2; foreach($statexp as $val) { ?>
           <tr>
             <td><span class="hidden"><? echo $i;++$i; ?></span><?=$lang['all_'.$val]; ?>:</td>
             <td><?=$pres['data']['statistics']['all'][$val]; ?></td>
           </tr>
           <?php } ?>
         </tbody>
       </table>
       <?php if (isset($pres['data'])) { ?>
       <table cellspacing="1" cellpadding="1"  width="100%" align="center" id="player3">
         <thead>
          <tr>
            <th align="center" class="sorter-false"><?=$lang['rating_title'];?></th>
            <th align="center" class="sorter-false"><?=$lang['value'];?></th>
            <th align="center" class="sorter-false"><?=$lang['place'];?></th>
          </tr>
         </thead>
         <tbody>
           <?php foreach($statrat as $val) { ?>
           <tr>
             <td><?=$lang['r_'.$val]; ?>:</td>
             <td><?=$pres['data']['ratings'][$val]['value']; ?></td>
             <td><?=$pres['data']['ratings'][$val]['rank']; ?></td>
           </tr>
           <?php } ?>
         </tbody>
       </table>
       <? }; ?>
     </td>
     <td valign="top" width="38%">
       <?php if(isset($info['table1'])) {?>
       <table cellspacing="1" cellpadding="1" align="center" width="100%" id="player10">
         <thead>
           <tr>
             <th colspan="4" class="sorter-false" align="center"><?=$lang['perform_title'],$lang['perform_class'];?></th>
           </tr>
           <tr>
             <th align="center"><?=$lang['class'];?></th>
             <th align="center"><?=$lang['all_battles'];?></th>
             <th align="center"><?=$lang['all_wins'];?></th>
             <th align="center"><?=$lang['winp'];?></th>
           </tr>
         </thead>
         <tbody>
           <?php foreach($info['table1'] as $name => $val) { ?>
           <tr>
             <td><?=$lang[$name]; ?></td>
             <td><?=$val['total']; ?></td>
             <td><?=$val['win']; ?></td>
             <td><?php echo number_format($val['win']/$val['total']*100,2); ?>%</td>
           </tr>
           <?php } ?>
         </tbody>
       </table>
       <?php };?>
       <table cellspacing="1" cellpadding="1" align="center" width="100%" id="player11">
         <thead>
           <tr>
             <td colspan="4" class="sorter-false" align="center"><?=$lang['perform_title'],$lang['perform_nation'];?></td>
           </tr>
           <tr>
             <th align="center"><?=$lang['nation'];?></th>
             <th align="center"><?=$lang['all_battles'];?></th>
             <th align="center"><?=$lang['all_wins'];?></th>
             <th align="center"><?=$lang['winp'];?></th>
           </tr>
         </thead>
         <tbody>
           <?php foreach($tanks_nation as $name) {
                   if(!isset($info['table3'][$name['nation']])) {
                      $info['table3'][$name['nation']]['total'] = 0.0001;
                      $info['table3'][$name['nation']]['win'] = 0;
                   } ?>
           <tr>
             <td><?=$lang[$name['nation']]; ?></td>
             <td><? echo number_format($info['table3'][$name['nation']]['total'],0, '.', ''); ?></td>
             <td><?=$info['table3'][$name['nation']]['win']; ?></td>
             <td><?php echo number_format($info['table3'][$name['nation']]['win']/$info['table3'][$name['nation']]['total']*100,2); ?>%</td>
           </tr>
           <?php } ?>
         </tbody>
       </table>
       <?php if(isset($info['table4'])) {?>
       <table cellspacing="1" cellpadding="1" align="center" width="100%" id="player12">
         <thead>
           <tr>
             <th colspan="4" class="sorter-false" align="center"><?=$lang['perform_title'],$lang['perform_lvl'];?></th>
           </tr>
           <tr>
             <th align="center"><?=$lang['level'];?></th>
             <th align="center"><?=$lang['all_battles'];?></th>
             <th align="center"><?=$lang['all_wins'];?></th>
             <th align="center"><?=$lang['winp'];?></th>
           </tr>
         </thead>
         <tbody>
           <?php foreach($info['table4'] as $name => $val) { ?>
           <tr>
             <td><?=$name; ?></td>
             <td><?=$val['total']; ?></td>
             <td><?=$val['win']; ?></td>
             <td><?php echo number_format($val['win']/$val['total']*100,2); ?>%</td>
           </tr>
           <?php };?>
          </tbody>
       </table>
       <? } ?>
     </td>
    </tr>
   </tbody>
  </table>

<?php
function get_player_clans($nickname, $server) {
  @$result = file_get_contents('http://duckandcover.ru/site/wotka_json?nickname='.$nickname.'&server='.strtoupper($server), false);
  $new = array();
  if ($result) {
      $result = json_decode($result);
      if ($result->result == 1) {
          foreach($result as $tmp_name => $tmp) {
            if (is_array($tmp)) {
                foreach($tmp as $i => $in) { $new[$i][$tmp_name] = $in; }
            }
          }
      }
  }
  return $new;
}

$result = get_player_clans($pres['data']['nickname'], $config['server']);

if($result) { $end = end($result);
if (isset($result)) {
  foreach ($result as $key => $val ) {
     if(!isset($val['clanids'])) {
       unset($result[$key]);
       continue;
     }
     $new = $cache->get('get_last_roster_'.$val['clanids'],0);
     if ($new === FALSE) {
         $new2 = get_api('wgn/clans/info',array('clan_id' => $val['clanids']),array('emblems.x64'));
         if ($new2 != FALSE and isset($new2['status']) and $new2['status'] == 'ok' and isset($new2['data'][$val['clanids']]['emblems']['x64']['portal']) and $new2['data'][$val['clanids']]['emblems']['x64']['portal'] != '') {
              $result[$key]['clanlink'] = $new2['data'][$val['clanids']]['emblems']['x64']['portal'];
         }
     } else {
          if ($new['data'][$val['clanids']]['emblems']['x64']['portal'] <>'') {
              $result[$key]['clanlink'] = $new['data'][$val['clanids']]['emblems']['x64']['portal'];
          }
     }
  }
?>

<table cellspacing="1" cellpadding="1" width="100%" id="player8" >
  <thead>
    <th align="center"><?=$lang['duckandcover']; ?> (c) <a href="https://duckandcover.ru/wotka" target="_blank">duckandcover.ru/wotka</a></th>
  </thead>
  <tbody>
    <tr>
      <td class="medalContainer" >
      <?php foreach($result as $val) { ?>
        <div class="clanDiv">
        <img class="hint_small" src="<?=(isset($val['clanlink'])?$val['clanlink']:'./images/no_logo.png'); ?>">
        <br>
        <a href="http://<?php echo $config['gm_url'].'/community/clans/'.$val['clanids']; ?>/" target="_blank">[<?=$val['clantags']; ?>]</a>
        <br>
        <?=$val['member_sinces']; ?>&nbsp;&mdash;&nbsp;
        <?php if ($val['member_untils'] != 'undef') {
                  echo $val['member_untils'];
              }   else {
                  if ((isset($end['member_sinces'])) && ($val['member_sinces'] == $end['member_sinces'])) {
                       echo $lang['till_today'];
                  }    else {
                       echo $lang['unknown'];
                  }
              } ?>
        <?php

        $t1 = date_parse($val['member_sinces']);

        if ((isset($end['member_sinces'])) && $val['member_sinces'] == $end['member_sinces'] && $val['member_untils'] == 'undef') {
             $t2 = date_parse(date('d.m.Y'));
        }    else {
             $t2 = date_parse($val['member_untils']);
        }

            if($t1['error_count'] == 0 and $t2['error_count'] == 0) {

            $d1 = mktime(0,0,0,$t1['month'],$t1['day'],$t1['year']);
            $d2 = mktime(0,0,0,$t2['month'],$t2['day'],$t2['year']);

            $diff_days = (integer) number_format((($d2-$d1) / 86400),0);

            echo '<br>Дней в клане: '.$diff_days;
            }
        ?>
        </div>
      <?php } ?>
      </td>
    </tr>
  </tbody>
</table>
<?php } //clans
} // if result
?>
<? $data = $pres['data']['achievements'];?>
<? if (!empty($data)) { ?>
<br>
<table cellspacing="1" cellpadding="1" width="100%" id="player9">
  <thead>
    <tr>
      <th><?=$lang['med_title'];?></th>
    </tr>
  </thead>
  <tbody>
  <? $i=1; foreach($achievements_sorted['sections'] as $cat => $name) { ?>
    <tr class="ui-widget-content">
      <td align="center"><span class="hidden"><?=10*$i;?></span><?=$name;?></td>
    </tr>
    <tr>
      <td class="medalContainer"><span class="hidden"><?=10*$i+1;?></span>
      <? foreach($achievements_sorted['split'][$cat] as $id) { ?>
           <? $val = $achievements[$id];
           if(!empty($val['options'])) {
             if(isset($data[$id])) {
               $ach_name = $val['options'][$data[$id]-1]['name_i18n'];
               $ach_img  = $val['options'][$data[$id]-1]['image'];
             } else {
               $ach_name = $val['options'][(count($val['options'])-1)]['name_i18n'];
               $ach_img  = $val['options'][(count($val['options'])-1)]['image'];
             }
           } else {
             $ach_name = $val['name_i18n'];
             $ach_img  = $val['image'];
           }
           ?>
           <div class="medalDiv">
              <img width="67" height="71" title="<div style='min-width:400px;'><center><?=$ach_name;?></center><br><?=str_replace('"',"'",$val['description']),(!empty($val['condition'])?'<div style=\'padding:0px;margin:10px 0 0 15px\'>'.nl2br($val['condition']).'</div>':'');?></div>" class="bb <?=(isset($data[$id]))?'':'faded';?>" src="<?=$ach_img;?>">
              <? if(isset($data[$id])) { ?>
                <div class="a_num ui-state-highlight ui-widget-content"><?=$data[$id],(($val['type']=='series')?'&nbsp;<span style="color:red;">*</span>':'');?></div>
              <? } ?>
          </div>
      <? } ?>
      </td>
    </tr>
  <? ++$i;} ?>
  </tbody>
</table>
<? } //end of medals 
?> 
<br>
<?php
$i=13;
$arsd = array('table2', 'table5');

foreach ($arsd as $val) {
  if(isset($info[$val]) and is_array(array_keys($info[$val]))) { ?>
    <table cellspacing="0" cellpadding="0" width="100%" id="<?=$val; ?>" class="tablesorter">
      <thead>
        <?php if ($val=='table2') {?>
        <tr>
          <th colspan="5" align="center" class="tablesorter-header ui-widget-header ui-corner-all ui-state-default"><?=$lang['teh_title'];?><sup>*</sup></th>
        </tr>
        <? } ?>
        <tr>
          <?php foreach(array_keys($info[$val]) as $column) { ?>
          <th align="center" class="tablesorter-header ui-widget-header ui-corner-all ui-state-default"><?=$lang[$column]; ?></th>
          <?php } ?>
        </tr>
      </thead>
      <tbody>
        <tr>
          <?php foreach($info[$val] as $column => $tmp) { ?>
          <td valign="top">
            <table cellspacing="2" cellpadding="0" width="100%" id="player<?php echo $i; ++$i; ?>" style="margin-top: 0px !important;">
              <thead>
                <tr>
                  <th><?=$lang['title_name']; ?></th>
                  <th><?=$lang['all_battles'];?></th>
                  <th><?=$lang['all_wins'];?></th>
                  <th><?=$lang['winp'];?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($tmp as $name => $val2) { ?>
                  <tr>
                    <td><?=$name; ?></td>
                    <td><?=$val2['total']; ?></td>
                    <td><?=$val2['win']; ?></td>
                    <td><?php echo number_format($val2['win']/$val2['total']*100,2); ?>%</td>
                  </tr>
                <? } ?>
              </tbody>
            </table>
          </td>
          <?php } ?>
        </tr>
      </tbody>
    </table>
    <br clear="all" />
<?}
};
if(isset($info['table5']) || isset($info['table2']))
   echo "<div class='ui-state-highlight ui-widget-content'>".$lang['teh_anno']."</div>";
unset($info); unset($pres); unset($tmp); ?>
</div>