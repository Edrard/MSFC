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
    * @version     $Rev: 3.0.0 $
    *
    */



    error_reporting(E_ALL & ~E_STRICT);
    ini_set("display_errors", 1);
    if (!defined('ROOT_DIR')) {
    if (file_exists(dirname(__FILE__).'/func_ajax.php')) {
        define('LOCAL_DIR', dirname(__FILE__));
        include_once (LOCAL_DIR.'/func_ajax.php');
        define('ROOT_DIR', base_dir('ajax'));
    }else{
        define('LOCAL_DIR', '.');
        include_once (LOCAL_DIR.'/func_ajax.php');
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
    include_once(ROOT_DIR.'/function/auth.php');
    include_once(ROOT_DIR.'/function/mysql.php');
    include_once(ROOT_DIR.'/function/func.php');
    include_once(ROOT_DIR.'/function/rating.php');
    include_once(ROOT_DIR.'/function/func_main.php');
    include_once(ROOT_DIR.'/function/func_get.php');
    include_once(ROOT_DIR.'/function/func_time.php');
    include_once(ROOT_DIR.'/function/config.php');
    include_once(ROOT_DIR.'/config/config_'.$config['server'].'.php');

    foreach(scandir(ROOT_DIR.'/translate/') as $files){
        if (preg_match ("/_".$config['lang'].".php/", $files)){
            include_once(ROOT_DIR.'/translate/'.$files);
        }
    } 
    //cache
    include_once(ROOT_DIR.'/function/cache.php');
    $cache = new Cache(ROOT_DIR.'/cache/');

function p_info($res, $t) {
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
?>
<script type="text/javascript" id="js">
function updateall() {
$("#player0")
  <? for ($i=1; $i<=17; $i++) {?>
  .add("#player<?=$i;?>")
  <? } ;?>
  .trigger('applyWidgetId', ['zebra']).trigger('update');
return false;
}

   $(document).ready(function() {
   $("#player0")
      <? for ($i=1; $i<=9; $i++) {?>
              .add("#player<?=$i;?>")
      <? } ;?>
      .tablesorter({
          sortList:[[0,0]],
          headers:{ 0: { sorter: false}, 1: {sorter: false}, 2: {sorter: false}, 3: {sorter: false}, 4: {sorter: false},
                    5: { sorter: false}, 6: {sorter: false}, 7: {sorter: false}, 8: {sorter: false}, 9: {sorter: false}
                  },
          theme : 'bootstrap'
      }); 
      $("#player10").add("#player11").add("#player12")
      .tablesorter({
          headerTemplate : '<div style="padding-right: 12px;">{content}</div>{icon}',
          sortList:[[0,0]],
          headers:{ 0: { sorter: false}},
          theme : 'jui'
      });
      $("#player13")
      <? for ($i=14; $i<=17; $i++) {?>
              .add("#player<?=$i;?>")
      <? } ;?>
      .tablesorter({
          headerTemplate : '<div style="padding-right: 12px;">{content}</div>{icon}',
          sortList:[[0,0]],
          theme : 'jui'
      });
      $('.bb[title]').tooltip({
          track: false,
          delay: 0,
          fade: 250,
          items: "[title]",
          content: function() {
              var element = $( this );
              if ( element.is( "[title]" ) ) {
                   return element.attr( "title" );
              }
          }
      });
   });
</script>

<?php
$pres = $cache->get($nickname, 0, ROOT_DIR.'/cache/players/');
$tanks = tanks();
$tanks_nation = tanks_nations();
$medn = medn($tanks_nation);

if (isset($pres['data']['tanks'])) {
    $info = p_info($pres['data']['tanks'], $tanks);
}   else {
    $info = array();
};
$temp[$nickname] = $pres;
$eff_ratpl = eff_rating($temp);
unset($temp);

switch ($eff_ratpl[$nickname]['eff']+1) {
  case ($eff_ratpl[$nickname]['eff'] > 1725):
    $color = '#FF8000';
    break;
  case ($eff_ratpl[$nickname]['eff'] > 1465):
    $color = 'purple';
    break;
  case ($eff_ratpl[$nickname]['eff'] > 1150):
    $color = 'royalblue';
    break;
  case ($eff_ratpl[$nickname]['eff'] > 870):
    $color = 'green';
    break;
  case ($eff_ratpl[$nickname]['eff'] > 645):
    $color = 'slategray';
    break;
  default:
    $color = 'red';
    break;
}
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
$statrat    = array( 'integrated_rating', 'battle_wins', 'battle_avg_xp', 'battle_avg_performance',
                     'battles', 'damage_dealt', 'ctf_points', 'dropped_ctf_points', 'frags', 'xp', 'spotted');

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
            <th align="center"><?=$lang['name']; ?></th>
           </tr>
         </thead>
         <tbody>
           <tr>
             <td align="center"><b><?=$pres['data']['nickname'];?></b></td>
           </tr>
         </tbody>
       </table>
       <table cellspacing="1" cellpadding="1" width="100%" align="center" id="player5">
         <thead>
           <tr>
            <th align="center" class="bb" title="<?=$lang['overall_eff_table'];?>"><?=$lang['eff_ret']; ?> (c)
            <br><a href="http://wot-news.com/" target="_blank">wot-news.com</a></th>
           </tr>
         </thead>
         <tbody>
           <tr>
             <td align="center"><font color="<?=$color; ?>"><?=$eff_ratpl[$nickname]['eff']; ?></font></td>
           </tr>
         </tbody>
       </table>
       <table cellspacing="1" cellpadding="1" width="100%" align="center" id="player6">
         <thead>
           <tr>
            <th align="center" class="bb" title="<?=$lang['brone_anno'];?>"><?=$lang['brone_ret'];?> (c)
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
             <th align="center" colspan="2"><?=$lang['emem'];?> (c) <br><a href="http://emem.ru/" target="_blank">emem.ru</a></th>
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
             <th align="center" colspan="3"><?=$lang['overall_title']?></th>
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
         <tfoot>
           <tr>
             <td><span class="hidden"><? echo $i;++$i; ?></span><?=$lang['upd_at'];?>:</td>
             <td  align="center" colspan="2"><?php echo date('d.m.Y',$pres['data']['updated_at']); ?></td>
           </tr>
         </tfoot>
       </table>

       <table cellspacing="1" cellpadding="1" width="100%" align="center" id="player2">
         <thead>
           <tr>
             <th align="center" colspan="3"><?=$lang['perform_title'];?></th>
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
             <th align="center" colspan="2"><?= $lang['battel_title'];?></th>
           </tr>
         </thead>
         <tbody>
           <tr><td><span class="hidden">1</span><?=$lang['max_xp'];?></td><td><?=$pres['data']['statistics']['max_xp']; ?></td></tr>
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
            <th align="center"><?=$lang['rating_title'];?></th>
            <th align="center"><?=$lang['value'];?></th>
            <th align="center"><?=$lang['place'];?></th>
          </tr>
         </thead>
         <tbody>
           <?php foreach($statrat as $val) { ?>
           <tr>
             <td><?=$lang['r_'.$val]; ?>:</td>
             <td><?=$pres['data']['ratings'][$val]['value']; ?></td>
             <td><?=$pres['data']['ratings'][$val]['place']; ?></td>
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
             <th colspan="4" align="center"><?=$lang['perform_title'],$lang['perform_class'];?></th>
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
             <td colspan="4" align="center"><?=$lang['perform_title'],$lang['perform_nation'];?></td>
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
             <th colspan="4" align="center"><?=$lang['perform_title'],$lang['perform_lvl'];?></th>
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
     $new = $cache->get('get_last_roster_'.$val['clanids'],0);
     if ($new === FALSE) {
         $new2 = get_clan_v2($val['clanids'], 'info', $config);
         if (($new2 === FALSE)|| ($new2['status'] <> 'ok') ) {
              $result[$key]['clanlink'] = $config['clan_img'].$val['clanids']."/emblem_64x64.png";
         }    else {
              $result[$key]['clanlink'] = $new2['data'][$val['clanids']]['emblems']['large'];
              $cache->set('get_last_roster_'.$val['clanids'], $new2);
         }
     } else {
          if ($new['data'][$val['clanids']]['emblems']['large'] <>'') {
              $result[$key]['clanlink'] = $new['data'][$val['clanids']]['emblems']['large'];
          }   else {
              $result[$key]['clanlink'] = $config['clan_img'].$val['clanids']."/emblem_64x64.png";
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
        <img class="hint_small" src="<?=$val['clanlink']; ?>">
        <br>
        <a href="http://<?php echo $config['gm_url'].'/community/clans/'.$val['clanids']; ?>/" target="_blank">[<?=$val['clantags']; ?>]</a>
        <br>
        <?=$val['member_sinces']; ?>&nbsp;&mdash;&nbsp;<?php if($val['member_untils'] != 'undef') {echo $val['member_untils'];} else { if($val['member_sinces'] == $end['member_sinces']) {echo $lang['till_today'];} else {echo $lang['unknown'];} } ?>
        <?php 
        
        $t1 = date_parse($val['member_sinces']);
        
        if($val['member_sinces'] == $end['member_sinces'] and $val['member_untils'] == 'undef') { $t2 = date_parse(date('d.m.Y')); } 
        else { $t2 = date_parse($val['member_untils']); }
        
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
      foreach ($medn as $medname => $val) {
         $medtypes[$val['type']][] = $medname;
      } ?>
<table cellspacing="1" cellpadding="1" width="100%" id="player9">
  <thead>
    <tr>
      <th><?=$lang['med_title'];?></th>
    </tr>
  </thead>
  <tbody>
    <?php $i=1; $lvlup = 0; foreach($medtypes as $type =>$medarr) { ?>
    <tr class="ui-widget-content">
      <td align="center">
          <?php echo '<span class="hidden">'.$i.'</span>';
                if ($type[strlen($type)-1]== '2') {
                    $out = substr($type, 0, strlen($type)-1);
                    echo $lang[$out].' - 2';
                }   else {
                    echo $lang[$type];
                }   ++$i; ?>
      </td>
    </tr>
    <tr>
      <td class="medalContainer"><span class="hidden"><?=$i;?>'</span>
<?php
    foreach($medarr as $mdn) {
      if ($mdn <> 'lumberjack') {
          if ($mdn == 'diehard') {
              if ($pres['data']['achievements'][$mdn] == 0) {
                  $val['value'] = '0 ('.$pres ['data']['achievements']['max_diehard_series'].' <span style="color:red;">**</span>)';
              }   else {
                  $val['value'] = $pres['data']['achievements'][$mdn];
              }
          }   elseif ($mdn == 'handOfDeath'){
              $val['value'] = $pres['data']['achievements']['max_killing_series'];
          }   elseif ($mdn == 'invincible'){
              if ($pres['data']['achievements'][$mdn] == 0) {
                  $val['value'] = '0 ('.$pres['data']['achievements']['max_invincible_series'].' <span style="color:red;">*</span>)';
              }   else {
                  $val['value'] = $pres['data']['achievements'][$mdn];
              }
          }   elseif ($mdn == 'armor_piercer'){
              $val['value'] = $pres['data']['achievements']['max_piercing_series'];
          }   elseif ($mdn == 'title_sniper'){
              $val['value'] = $pres['data']['achievements']['max_sniper_series'];
          }   elseif (!isset($val['type'])){
              //print_R($val);
          }   else {
              $val['value'] = $pres['data']['achievements'][$mdn];
          }

if($type == "major"){
$lvlup_title = '';
$lvluparr = array (0=>'IV', 2=>'I', 3 =>'II', 4=> 'III');

switch ($mdn) {
//
case ('medal_abrams'):
$number = $pres['data']['statistics']['all']['survived_battles'];
$lvlup = 1;

if ($val['value'] == 0) {
    $left = 5-$number;
}   elseif ($val['value'] == 1) {
    $lvlup = 0;
}   elseif ($val['value'] == 2) {
    $left = 5000-$number;
}   elseif ($val['value'] == 3) {
    $left = 500-$number;
}   elseif ($val['value'] == 4) {
    $left = 50-$number;
}
if ($lvlup && $number>0 && $left>0){
    $lvlup_title .= $lang['lvl_up1'].$lvluparr[$val['value']].$lang['lvl_abr'].$left.$lang['lvl_ali'];
    $lvlup_title .= $lang['lvl_up2'].ceil(($pres['data']['statistics']['all']['battles']/$number)*$left).$lang['lvl_b'];}
break;
//
case ('medal_ekins'):
$lvlup = 0;
break;
//
case ('medal_knispel'):
$number = $pres['data']['statistics']['all']['damage_dealt']+ $pres['data']['statistics']['all']['damage_received'];
$lvlup = 0;

if ($val['value'] == 0) {
    $left = 10000-$number;
}   elseif ($val['value'] == 1) {
    $lvlup = 0;
}   elseif ($val['value'] == 2) {
    $left = 10000000-$number;
}   elseif ($val['value'] == 3) {
    $left = 1000000-$number;
}   elseif ($val['value'] == 4) {
    $left = 100000-$number;
}
if ($lvlup && $number>0 && $left>0) {
    $lvlup_title .= $lang['lvl_up1'].$lvluparr[$val['value']].$lang['lvl_kni'].$left.$lang['lvl_dd'];
    $lvlup_title .= $lang['lvl_up2'].ceil(($pres['data']['statistics']['all']['battles']/$number)*$left).$lang['lvl_b'];
}
break;
//
case ('medal_kay'):
$number = 0;
$lvlup = 1;

foreach($pres['data']['achievements'] as $t => $vl) {
   if ($t == 'hero') $number += $vl['value'];
}
if ($val['value'] == 0) {
    $left = 1;
}   elseif ($val['value'] == 1) {
    $lvlup = 0;
}   elseif ($val['value'] == 2) {
    $left = 1000-$number;
}   elseif ($val['value'] == 3) {
    $left = 100-$number;
}   elseif ($val['value'] == 4) {
    $left = 10-$number;
}
if ($lvlup && $number>0 && $left>0) {
    $lvlup_title .= $lang['lvl_up1'].$lvluparr[$val['value']].$lang['lvl_kay'].$left.$lang['lvl_heroes'];
    $lvlup_title .= $lang['lvl_up2'].ceil(($pres['data']['statistics']['all']['battles']/$number)*$left).$lang['lvl_b'];
}
break;
//
case ('medal_carius'):
$number = $pres['data']['statistics']['all']['frags'];
$lvlup = 1;

if ($val['value'] == 0) {
    $left = 10-$number;
}   elseif ($val['value'] == 1) {
    $lvlup = 0;
}   elseif ($val['value'] == 2) {
    $left = 10000-$number;
}   elseif ($val['value'] == 3) {
    $left = 1000-$number;
}   elseif ($val['value'] == 4) {
    $left = 100-$number;
}
if ($lvlup && $number>0 && $left>0){
    $lvlup_title .= $lang['lvl_up1'].$lvluparr[$val['value']].$lang['lvl_car'].$left.$lang['lvl_heroes'];
    $lvlup_title .= $lang['lvl_up2'].ceil(($pres['data']['statistics']['all']['battles']/$number)*$left).$lang['lvl_b'];}
break;
//
case ('medal_le_clerc'):
$number = $pres['data']['statistics']['all']['capture_points'];
$lvlup = 1;

if ($val['value'] == 0) {
    $left = 30-$number;
}   elseif ($val['value'] == 1) {
    $lvlup = 0;
}   elseif ($val['value'] == 2) {
    $left = 30000-$number;
}   elseif ($val['value'] == 3) {
    $left = 3000-$number;
}   elseif ($val['value'] == 4) {
    $left = 300-$number;
}
if ($lvlup && $number>0 && $left>0){
    $lvlup_title .= $lang['lvl_up1'].$lvluparr[$val['value']].$lang['lvl_lac'].$left.$lang['lvl_cap'];
    $lvlup_title .= $lang['lvl_up2'].ceil(($pres['data']['statistics']['all']['battles']/$number)*$left).$lang['lvl_b'];}
break;
//

case ('medal_poppel'):
$number = $pres['data']['statistics']['all']['spotted'];
$lvlup = 1;

if ($val['value'] == 0) {
    $left = 20-$number;
}   elseif ($val['value'] == 1) {
    $lvlup = 0;
}   elseif ($val['value'] == 2) {
    $left = 20000-$number;
}   elseif ($val['value'] == 3) {
    $left = 2000-$number;
}   elseif ($val['value'] == 4) {
    $left = 200-$number;
}
if ($lvlup && $number>0 && $left>0){
    $lvlup_title .= $lang['lvl_up1'].$lvluparr[$val['value']].$lang['lvl_pop'].$left.$lang['lvl_spo'];
    $lvlup_title .= $lang['lvl_up2'].ceil(($pres['data']['statistics']['all']['battles']/$number)*$left).$lang['lvl_b'];}
break;
//
case ('medal_lavrinenko'):
$number = $pres['data']['statistics']['all']['dropped_capture_points'];
$lvlup = 1;

if ($val['value'] == 0) {
    $left = 30-$number;
}   elseif ($val['value'] == 1) {
    $lvlup = 0;
}   elseif ($val['value'] == 2) {
    $left = 30000-$number;
}   elseif ($val['value'] == 3) {
    $left = 3000-$number;
}   elseif ($val['value'] == 4) {
    $left = 300-$number;
}
if ($lvlup && $number>0 && $left>0){
    $lvlup_title .= $lang['lvl_up1'].$lvluparr[$val['value']].$lang['lvl_lav'].$left.$lang['lvl_def'];
    $lvlup_title .= $lang['lvl_up2'].ceil(($pres['data']['statistics']['all']['battles']/$number)*$left).$lang['lvl_b'];}
break;

default:
$lvlup = 0;
break;
}//switch

} //major?>
         <div class="medalDiv">
            <img width="67" height="71" title="<?php echo '<center>'.$lang['medal_'.$mdn].'</center>'.$lang['title_'.$mdn]; ?>" class="bb <?php if($val['value'] == 0) {echo 'faded';} ?>" alt="<?=$lang['medal_'.$mdn]; ?>" src="<?=$medn[$mdn]['img']; ?>">
            <div class="a_num ui-state-highlight ui-widget-content"><?=$val['value']; ?></div>
<? if($lvlup) { ?>
            <div class="bb levelup" title="<?php echo $lvlup_title; ?>"></div>
<? } ?>
        </div>
<?php
$lvlup = 0; }} ?>
    </td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
<div align="left">
    <span style="color:red;">*</span> <?=$lang['medal_max2']; ?><br />
    <span style="color:red;">**</span> <?=$lang['medal_max']; ?><br /><br />



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