<?php
    /*
    * Project:     Clan Stat
    * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
    * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
    * -----------------------------------------------------------------------
    * Began:       2012
    * Date:        $Date: 2012-12-01
    * -----------------------------------------------------------------------
    * @author      $Author: SHW $
    * @copyright   2012-2012 SHW
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 2.2.0 $
    *
    */
?>
<?php
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

    include_once(ROOT_DIR.'/function/auth.php');
    include_once(ROOT_DIR.'/function/mysql.php');
    if (isset($_POST['db_pref']) ) {
        $db->change_prefix($_POST['db_pref']);
    }   else{
        if (isset($_GET['db_pref']) ) {
            $db->change_prefix($_GET['db_pref']);
        }   };
    if (isset($_POST['multi']) ) {
        $multi_get = '&multi='.$_POST['multi'];
    }   else{
        if (isset($_GET['multi']) ) {
           $multi_get = '&multi='.$_GET['multi'];
        }   };
    include_once(ROOT_DIR.'/function/func.php');
    include_once(ROOT_DIR.'/function/rating.php');
    include_once(ROOT_DIR.'/function/func_main.php');
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
?>
<script type="text/javascript" id="js">
   $(document).ready(function()
   {  $("#player1")
      <? for ($i=2; $i<=9; $i++) {?>
              .add("#player<?=$i;?>")
      <? } ;?>
      .tablesorter({
          widgets : ['uitheme', 'zebra'],
          widthFixed : false,
          sortList:[[0,0]],
          headers:{ 0: { sorter: false}, 1: {sorter: false}, 2: {sorter: false}, 3: {sorter: false}, 4: {sorter: false},
                    5: { sorter: false}, 6: {sorter: false}, 7: {sorter: false}, 8: {sorter: false}, 9: {sorter: false}
                  },
          theme : 'bootstrap'
      });
      $("#players1").add("#players2").add("#players3")
      .tablesorter({
          headerTemplate : '<div style="padding-right: 12px;">{content}</div>{icon}',
          widgets : ['uitheme', 'zebra'],
          widthFixed : false,
          sortList:[[0,0]],
          headers:{ 0: { sorter: false}},
          theme : 'jui'
      });
      $("#players4")
      <? for ($i=5; $i<=8; $i++) {?>
              .add("#players<?=$i;?>")
      <? } ;?>
      .tablesorter({
          headerTemplate : '<div style="padding-right: 12px;">{content}</div>{icon}',
          widgets : ['uitheme', 'zebra'],
          widthFixed : false,
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
      $('#id--6533').click(function() {
         $("#player1").add("#players1").add("#player9")
         <? for ($i=2; $i<=8; $i++) {?>
              .add("#player<?=$i;?>").add("#players<?=$i;?>")
         <? } ;?>
         .trigger('applyWidgets');
         return false;
      });
   });
</script>

<style>
.a_num {
    position:absolute; 
    bottom:1px; 
    padding:0px 3px; 
    text-align:center; 
    right:1px; 
    min-width:18px; 
    color: black; 
    border:1px solid black;
    font-size:10px;
}
.medalDiv {
    text-align:center;
    width:70px; 
    height:75px; 
    *display:inline; 
    *float:left;
    display:inline-block; 
    margin:5px; 
    position:relative;
}
.clanDiv {
    text-align:center;
    *display:inline; 
    *float:left;
    display:inline-block; 
    margin:5px; 
    position:relative;
    vertical-align: top;
}
.faded {
    opacity:0.4;
    filter: alpha(opacity=40);
}
.medalContainer {
    border:1px solid #666; text-align:center;
}
.levelup {
    background:url(./images/upgrade.png) center center no-repeat;
    width:16px;
    height:16px;
    position:absolute;
    top:0;
    right:0;
}
</style>
<div align="right">
<a href="./main.php?<?=$multi_url;?>" target="_self">
<div style="background-origin: content-box; padding: 0; margin: 0; " class="ui-accordion-header-icon ui-icon ui-icon-circle-close">
</div></a>
</div>
<?php
    $pres = $cache->get($nickname, 0, ROOT_DIR.'/cache/players/');

function p_info($res) {
foreach($res as $lvl => $tmp) { // begin 1
    foreach($tmp as $class => $tn) { // begin 2
        foreach($tn as $tname) { //begin 3

            @$chart1[$class]['total'] += $tname['total'];
            @$chart1[$class]['win'] += $tname['win'];

            if($tname['total'] >= 20) {
                if ($class == 'lightTank' || $class == 'mediumTank' || $class == 'heavyTank'){
                    @$chart2[$class][$tname['type']]['total'] += $tname['total'];
                    @$chart2[$class][$tname['type']]['win'] += $tname['win'];
                } else {
                    @$chart5[$class][$tname['type']]['total'] += $tname['total'];
                    @$chart5[$class][$tname['type']]['win'] += $tname['win'];
                }
            }

            @$chart3[$tname['nation']]['total'] += $tname['total'];
            @$chart3[$tname['nation']]['win'] += $tname['win'];

            @$chart4[$lvl]['total'] += $tname['total'];
            @$chart4[$lvl]['win'] += $tname['win'];
        } //end 3
    } //end 2
}//end 1
if(isset($chart1)) $tstat['table1'] = $chart1;
if(isset($chart2)) $tstat['table2'] = $chart2;
if(isset($chart3)) $tstat['table3'] = $chart3;
if(isset($chart4)) $tstat['table4'] = $chart4;
if(isset($chart5)) $tstat['table5'] = $chart5;

return $tstat;

}
if (isset($pres['tank'])) {
    $info = p_info($pres['tank']);
} else {
  $info = array();
};
$temp[$nickname] = $pres;
$eff_ratpl = eff_rating($temp, $lang);
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
    $textt = 'Виртуоз<br>(рейтинг выше, чем у 99% игроков)';$imgg='http://armor.kiev.ua/wot/images/classVf.png';
    break;
  case ($eff_ratpl[$nickname]['brone'] > 5571):
    $textt = 'Мастер-танкист<br>(рейтинг выше, чем у 95% игроков)';$imgg='http://armor.kiev.ua/wot/images/classMf.png';
    break;
  case ($eff_ratpl[$nickname]['brone'] > 3851):
    $textt = 'Танкист 1-го класса<br>(рейтинг выше, чем у 80% игроков)';$imgg='http://armor.kiev.ua/wot/images/class1f.png';
    break;
  case ($eff_ratpl[$nickname]['brone'] > 2736):
    $textt = 'Танкист 2-го класса<br>(рейтинг выше, чем у 60% игроков)';$imgg='http://armor.kiev.ua/wot/images/class2f.png';
    break;
  case ($eff_ratpl[$nickname]['brone'] > 2084):
    $textt = 'Танкист 3-го класса<br>(рейтинг выше, чем у 45% игроков)';$imgg='http://armor.kiev.ua/wot/images/class3f.png';
    break;
  case ($eff_ratpl[$nickname]['brone'] > 1452):
    $textt = 'Оленевод 3-го класса<br>(рейтинг выше, чем у 30% игроков)';$imgg='http://armor.kiev.ua/wot/images/deer3f.png';
    break;
  case ($eff_ratpl[$nickname]['brone'] > 1010):
    $textt = 'Оленевод 2-го класса<br>(рейтинг выше, чем у 20% игроков)';$imgg='http://armor.kiev.ua/wot/images/deer2f.png';
    break;
  case ($eff_ratpl[$nickname]['brone'] > 517):
    $textt = 'Оленевод 1-го класса<br>(рейтинг выше, чем у 10% игроков)';$imgg='http://armor.kiev.ua/wot/images/deer1f.png';
    break;
  default:
    $textt = 'Мастер-оленевод<br>(рейтинг ниже, чем у 10% игроков)';$imgg='http://armor.kiev.ua/wot/images/deerMf.png';
    break;
};
if ($pres['overall'][$lang['games_p']] == 0) {
    $pres['overall'][$lang['games_p']] = 0.0001;
}
?>

<div align="center">
  <table cellspacing="2" cellpadding="2" width="100%" id="tmain2">
   <tbody>
    <tr>
     <td valign="top" width="20%">
       <table cellspacing="1" cellpadding="1" width="100%" align="center" id="player5">
         <thead>
           <tr>
            <th align="center" colspan="2" ><span class="bb" style="border-bottom: 1px dashed #666666; cursor: pointer;" title="<?=$lang['overall_eff_table'];?>">Рейтинг эффективности (c)</span>
            <br><a href="http://wot-news.com/" target="_blank">wot-news.com</a></th>
           </tr>
         </thead>
         <tbody>
           <tr>
             <td align="center"><font color="<?=$color; ?>"><?=$eff_ratpl[$nickname]['eff']; ?></font></td>
             </td>
           </tr>
         </tbody>
       </table>
       <table cellspacing="1" cellpadding="1" width="100%" align="center" id="player7">
         <thead>
           <tr>
             <th align="center" colspan="3">Стиль игры (c) <br><a href="http://emem.ru/" target="_blank">emem.ru</a></th>
           </tr>
         </thead>
         <tbody>
           <tr>
             <td><span class="hidden">1</span><span class="bb" style="border-bottom: 1px dashed #666666; cursor: pointer;" title="Отношение суммы уничтоженных и обнаруженных врагов к количеству проведенных боев">Общая агресивность</span>:</td>
             <td><?php echo number_format(($pres['perform'][$lang['spotted']]+$pres['perform'][$lang['destroyed']])/$pres['overall'][$lang['games_p']],3); ?></td>
           </tr>
           <tr>
             <td><span class="hidden">2</span><span class="bb" style="border-bottom: 1px dashed #666666; cursor: pointer;" title="Отношение уничтоженных врагов к количеству проведенных боев">Боец</span>:</td>
             <td><?php echo number_format($pres['perform'][$lang['destroyed']]/$pres['overall'][$lang['games_p']],3); ?></td>
           </tr>
           <tr>
             <td><span class="hidden">3</span><span class="bb" style="border-bottom: 1px dashed #666666; cursor: pointer;" title="Отношение обнаруженных врагов к количеству проведенных боев">Разведчик</span>:</td>
             <td><?php echo number_format($pres['perform'][$lang['spotted']]/$pres['overall'][$lang['games_p']],3); ?></td>
           </tr>
           <tr>
             <td><span class="hidden">4</span><span class="bb" style="border-bottom: 1px dashed #666666; cursor: pointer;" title="Отношение количества очков захвата базы к количеству проведенных боев">Захватчик</span>:</td>
             <td><?php echo number_format($pres['perform'][$lang['capture']]/$pres['overall'][$lang['games_p']],3); ?></td>
           </tr>
           <tr>
             <td><span class="hidden">5</span><span class="bb" style="border-bottom: 1px dashed #666666; cursor: pointer;" title="Отношение количества очков защиты базы к количеству проведенных боев">Защитник</span>:</td>
             <td><?php echo number_format($pres['perform'][$lang['defend']]/$pres['overall'][$lang['games_p']],3); ?></td>
           </tr>
         </tbody>
       </table>
       <table cellspacing="1" cellpadding="1" width="100%" align="center" id="player6">
         <thead>
           <tr>
            <th align="center" colspan="2" ><span class="bb" style="border-bottom: 1px dashed #666666; cursor: pointer;"
                title="Данный рейтинг не имеет четких показательных границ.">Рейтинг бронесайта (c)</span>
                <br><a href="http://armor.kiev.ua/wot/" target="_blank">armor.kiev.ua/wot</a></th>
           </tr>
         </thead>
         <tbody>
           <tr>
             <td align="center"><?=$eff_ratpl[$nickname]['brone']; ?></td>
           </tr>
           <tr>
             <td align="center" colspan="2"><?php echo '<img src="'.$imgg.'" />'; ?></td>
           </tr>
           <tr>
             <td align="center" colspan="2"><?=$textt; ?></td>
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
           <?php $i=1; foreach($pres['overall'] as $name => $val) { ?>
           <tr>
             <td><span class="hidden"><? echo $i;++$i; ?></span><?=$name; ?>:</td>
             <td<?php if ($name == $lang['games_p']) {echo ' align="center" colspan="2"';}
                      echo '>'.number_format($val, 0, '.', '').'</td>';
                      if ($name != $lang['games_p']) {echo '<td>'.number_format($val/$pres['overall'][$lang['games_p']]*100,2).'%</td>'; } ?>
           </tr>
           <?php } ?>
           <tr>
             <td><span class="hidden"><? echo $i;++$i; ?></span><?=$lang['dead_heat']; ?>:</td>
             <td><?php if ($pres['overall'][$lang['games_p']] == '0.0001') {
                           echo '0</td><td>0.00%';
                       }   else {
                           echo ($pres['overall'][$lang['games_p']]-$pres['overall'][$lang['victories']]-$pres['overall'][$lang['defeats']]).'</td><td>'.number_format(($pres['overall'][$lang['games_p']]-$pres['overall'][$lang['victories']]-$pres['overall'][$lang['defeats']])/$pres['overall'][$lang['games_p']]*100,2).'%';
                       }   ?></td>
           </tr>
         </tbody>
         <tfoot>
           <tr>
             <td><span class="hidden"><? echo $i;++$i; ?></span>Дата обновления статистики:</td>
             <td  align="center" colspan="2"><?php echo date('d.m.Y',$pres['date']['local_num']); ?></td>
           </tr>
         </tfoot>
       </table>

       <table cellspacing="1" cellpadding="1" width="100%" align="center" id="player2">
         <thead>
           <tr>
             <th align="center" colspan="3">Боевая эффективность</th>
           </tr>
         </thead>
         <tbody>
           <?php $i=1; foreach($pres['perform'] as $name => $val) { ?>
           <tr>
             <td><span class="hidden"><? echo $i;++$i; ?></span><?=$name; ?>:</td>
             <?php if ($name == $lang['accure']) {
                       echo '<td colspan="2" align="center">'.$val.'%</td>';
                   }   else {
                       echo '<td>'.$val.'</td>';
                       echo '<td>'.number_format($val/$pres['overall'][$lang['games_p']], 2, '.', '').'</td>';
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
           <?php $i=1; foreach($pres['exp'] as $name => $val) { ?>
           <tr>
             <td><span class="hidden"><? echo $i;++$i; ?></span><?=$name; ?>:</td>
             <td><?=$val; ?></td>
           </tr>
           <?php } ?>
         </tbody>
       </table>

       <table cellspacing="1" cellpadding="1"  width="100%" align="center" id="player3">
         <thead>
          <tr>
            <th align="center"><?=$lang['rating_title'];?></th>
            <th align="center"><?=$lang['value'];?></th>
            <th align="center"><?=$lang['place'];?></th>
          </tr>
         </thead>
         <tbody>
           <?php foreach($pres['rating'] as $name => $val) { ?>
           <tr>
             <td><?=$name; ?>:</td>
             <td><?=$val['value']; ?></td>
             <td><?=$val['place']; ?></td>
           </tr>
           <?php } ?>
         </tbody>
       </table>
     </td>
     <td valign="top" width="38%">
       <?php if(isset($info['table1'])) {?>
       <table cellspacing="1" cellpadding="1" align="center" width="100%" id="players1">
         <thead>
           <tr>
             <th colspan="4" align="center">Боевая эффективность по классам техники</th>
           </tr>
           <tr>
             <th align="center">Класс техники</th>
             <th align="center">Боев</th>
             <th align="center">Побед</th>
             <th align="center">% Побед</th>
           </tr>
         </thead>
         <tbody>
           <?php foreach($info['table1'] as $name => $val) { ?>
           <tr>
             <td><?=$lang[$name]; ?>:</td>
             <td><?=$val['total']; ?></td>
             <td><?=$val['win']; ?></td>
             <td><?php echo number_format($val['win']/$val['total']*100,2); ?>%</td>
           </tr>
           <?php } ?>
         </tbody>
       </table>
       <?php };?>
       <table cellspacing="1" cellpadding="1" align="center" width="100%" id="players2">
         <thead>
           <tr>
             <td colspan="4" align="center">Боевая эффективность по нациям</td>
           </tr>
           <tr>
             <th align="center">Нация</th>
             <th align="center">Боев</th>
             <th align="center">Побед</th>
             <th align="center">% Побед</th>
           </tr>
         </thead>
         <tbody>
           <?php $temp_nation = tanks_nations();
                 foreach($temp_nation as $name) {
                   if(!isset($info['table3'][$name['nation']])) {
                      $info['table3'][$name['nation']]['total'] = 0.0001;
                      $info['table3'][$name['nation']]['win'] = 0;
                   }
                 ;?>
           <tr>
             <td><?=$lang[$name['nation']]; ?>:</td>
             <td><? echo number_format($info['table3'][$name['nation']]['total'],0, '.', ''); ?></td>
             <td><?=$info['table3'][$name['nation']]['win']; ?></td>
             <td><?php echo number_format($info['table3'][$name['nation']]['win']/$info['table3'][$name['nation']]['total']*100,2); ?>%</td>
           </tr>
           <?php } ?>
         </tbody>
       </table>
       <?php if(isset($info['table4'])) {?>
       <table cellspacing="1" cellpadding="1" align="center" width="100%" id="players3">
         <thead>
           <tr>
             <th colspan="4" align="center">Боевая эффективность по уровням техники</th>
           </tr>
           <tr>
             <th align="center">Уровень</th>
             <th align="center">Боев</th>
             <th align="center">Побед</th>
             <th align="center">% Побед</th>
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

$result = get_player_clans($nickname, $config['server']);

if($result) { $end = end($result); ?>

<table cellspacing="1" cellpadding="1" width="100%" id="player8" >
  <thead>
    <th align="center"> Статистика принадлежности игрока кланам (c) <a href="https://duckandcover.ru/wotka" target="_blank">duckandcover.ru/wotka</a></th>
  </thead>
  <tbody>
    <tr>
      <td class="medalContainer" >
      <?php foreach($result as $val) { ?>
        <div class="clanDiv">
        <img width="64" height="64" class="hint_small" src="<?=$config['clan_img'].$val['clanids']; ?>/emblem_64x64.png">
        <br>
        <a href="http://<?php echo $config['gm_url'].'/community/clans/'.$val['clanids']; ?>/" target="_blank">[<?=$val['clantags']; ?>]</a>
        <br>
        <?=$val['member_sinces']; ?>&nbsp;&mdash;&nbsp;<?php if($val['member_untils'] != 'undef') {echo $val['member_untils'];} else { if($val['member_sinces'] == $end['member_sinces']) {echo 'По сей день';} else {echo 'Неизвестно';} } ?>
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
<?php } ?>


<table cellspacing="1" cellpadding="1" width="100%" id="player9">
  <thead>
    <tr>
      <th>Статистика по наградам и достижениям</th>
    </tr>
  </thead>
  <tbody>
    <?php $i=1; $lvlup = 0; foreach($pres['medals'] as $type => $tmp) { ?>
    <tr class="colNames">
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
        <?php foreach($tmp as $tm => $val) {
          if($type == "special") {
            if($tm == 'titleSniper' || $tm == 'armorPiercer' || $tm == 'handOfDeath'){
                $val['value'] = $val['max'];    
            }
            if($tm == 'invincible'){ 
                if($val['value'] > 0 ){
                    $val['value'] = $val['max'];        
                }else{
                    $val['value'] = '0 ('.$val['max'].' <span style="color:red;">*</span>)';
                }
            }
            if($tm == 'diehard'){ 
                if($val['value'] == 0 ){
                    $val['value'] = '0 ('.$val['max'].' <span style="color:red;">**</span>)';
                }
            }
        }
if (($tm=='mechanicEngineer')&&($val['value']=='')) $val['value']=0;

if($type == "major"){
$lvlup_title = '';

switch ($tm) { 

case ('medalKay'):
$number = 0;
foreach($pres['medals']['hero'] as $t => $vl) { $number += $vl['value'];}
$lvlup = 1;

switch ($val['value']) {

case (1):
$lvlup = 0;
break;

case (2):
$left = 1000-$number;
$lvlup_title .= 'Для получения I степени медали Кея поребуется '.$left.' званий \'Герой Битвы\'.';
if($number){$lvlup_title .= '<br>При текущем уровне игры для этого потребуется примерно '.ceil(($pres['overall'][$lang['games_p']]/$number)*$left).' боев';}
break;

case (3):
$left = 100-$number;
$lvlup_title .= 'Для получения II степени медали Кея поребуется '.$left.' званий \'Герой Битвы\'.';
if($number){$lvlup_title .= '<br>При текущем уровне игры для этого потребуется примерно '.ceil(($pres['overall'][$lang['games_p']]/$number)*$left).' боев';}
break;

case (4):
$left = 10-$number;
$lvlup_title .= 'Для получения III степени медали Кея поребуется '.$left.' званий \'Герой Битвы\'.';
if($number){$lvlup_title .= '<br>При текущем уровне игры для этого потребуется примерно '.ceil(($pres['overall'][$lang['games_p']]/$number)*$left).' боев';}
break;

case (0):
$lvlup_title .= 'Для получения IV степени медали Кея поребуется 1 звание \'Герой Битвы\'. <br>При сопутствиии удачи это может произойти в следующем бою.';
break;

}
break;

case ('medalCarius'):

$number = $pres['perform'][$lang['destroyed']];
$lvlup = 1;
switch ($val['value']) {

case (1):
$lvlup = 0;
break;

case (2):
$left = 10000-$number;
$lvlup_title .= 'Для получения I степени медали Кариуса поребуется еще '.$left.' единиц уничтоженной бронетехники.';
if($number){$lvlup_title .= '<br>При текущем уровне игры для этого потребуется примерно '.ceil(($pres['overall'][$lang['games_p']]/$number)*$left).' боев.'; }
break;

case (3):
$left = 1000-$number;
$lvlup_title .= 'Для получения II степени медали Кариуса поребуется еще '.$left.' единиц уничтоженной бронетехники.';
if($number){$lvlup_title .= '<br>При текущем уровне игры для этого потребуется примерно '.ceil(($pres['overall'][$lang['games_p']]/$number)*$left).' боев.'; }
break;

case (4):
$left = 100-$number;
$lvlup_title .= 'Для получения III степени медали Кариуса поребуется еще '.$left.' единиц уничтоженной бронетехники.';
if($number){$lvlup_title .= '<br>При текущем уровне игры для этого потребуется примерно '.ceil(($pres['overall'][$lang['games_p']]/$number)*$left).' боев.'; }
break;

case (0):
$left = 10-$number;
$lvlup_title .= 'Для получения IV степени медали Кариуса поребуется еще '.$left.' единиц уничтоженной бронетехники.';
if($number){$lvlup_title .= '<br>При текущем уровне игры для этого потребуется примерно '.ceil(($pres['overall'][$lang['games_p']]/$number)*$left).' боев.'; }
break;

}
break;

case ('medalLeClerc'):

$number = $pres['perform'][$lang['capture']];
$lvlup = 1;
switch ($val['value']) {

case (1):
$lvlup = 0;
break;

case (2):
$left = 30000-$number;
$lvlup_title .= 'Для получения I степени медали Леклерка поребуется еще '.$left.' очков захвата базы.';
if($number){$lvlup_title .= '<br>При текущем уровне игры для этого потребуется примерно '.ceil(($pres['overall'][$lang['games_p']]/$number)*$left).' боев.'; }
break;

case (3):
$left = 3000-$number;
$lvlup_title .= 'Для получения II степени медали Леклерка поребуется еще '.$left.' очков захвата базы.';
if($number){$lvlup_title .= '<br>При текущем уровне игры для этого потребуется примерно '.ceil(($pres['overall'][$lang['games_p']]/$number)*$left).' боев.'; }
break;

case (4):
$left = 300-$number;
$lvlup_title .= 'Для получения III степени медали Леклерка поребуется еще '.$left.' очков захвата базы.';
if($number){$lvlup_title .= '<br>При текущем уровне игры для этого потребуется примерно '.ceil(($pres['overall'][$lang['games_p']]/$number)*$left).' боев.'; }
break;

case (0):
$left = 30-$number;
$lvlup_title .= 'Для получения IV степени медали Леклерка поребуется еще '.$left.' очков захвата базы.';
if($number){$lvlup_title .= '<br>При текущем уровне игры для этого потребуется примерно '.ceil(($pres['overall'][$lang['games_p']]/$number)*$left).' боев.'; }
break;

}
break;

case ('medalAbrams'):

$number = $pres['overall'][$lang['battles_s']];
$lvlup = 1;
switch ($val['value']) {

case (1):
$lvlup = 0;
break;

case (2):
$left = 10000-$number;
$lvlup_title .= 'Для получения I степени медали Абрамса требуется победить и выжить в '.$left.' боях.';
if($number){$lvlup_title .= '<br>При текущем уровне игры для этого потребуется примерно '.ceil(($pres['overall'][$lang['games_p']]/$number)*$left).' боев.'; }
break;

case (3):
$left = 1000-$number;
$lvlup_title .= 'Для получения II степени медали Абрамса требуется победить и выжить в '.$left.' боях.';
if($number){$lvlup_title .= '<br>При текущем уровне игры для этого потребуется примерно '.ceil(($pres['overall'][$lang['games_p']]/$number)*$left).' боев.'; }
break;

case (4):
$left = 100-$number;
$lvlup_title .= 'Для получения III степени медали Абрамса требуется победить и выжить в '.$left.' боях.';
if($number){$lvlup_title .= '<br>При текущем уровне игры для этого потребуется примерно '.ceil(($pres['overall'][$lang['games_p']]/$number)*$left).' боев.'; }
break;

case (0):
$left = 10-$number;
$lvlup_title .= 'Для получения IV степени медали Абрамса требуется победить и выжить в '.$left.' боях.';
if($number){$lvlup_title .= '<br>При текущем уровне игры для этого потребуется примерно '.ceil(($pres['overall'][$lang['games_p']]/$number)*$left).' боев.'; }
break;

}
break;

case ('medalPoppel'):

$number = $pres['perform'][$lang['spotted']];
$lvlup = 1;
switch ($val['value']) {

case (1):
$lvlup = 0;
break;

case (2):
$left = 20000-$number;
$lvlup_title .= 'Для получения I степени медали Попеля требуется обнаружить в бою '.$left.' едениц вражеской техники.';
if($number){$lvlup_title .= '<br>При текущем уровне игры для этого потребуется примерно '.ceil(($pres['overall'][$lang['games_p']]/$number)*$left).' боев.'; }
break;

case (3):
$left = 2000-$number;
$lvlup_title .= 'Для получения II степени медали Попеля требуется обнаружить в бою '.$left.' едениц вражеской техники.';
if($number){$lvlup_title .= '<br>При текущем уровне игры для этого потребуется примерно '.ceil(($pres['overall'][$lang['games_p']]/$number)*$left).' боев.'; }
break;

case (4):
$left = 200-$number;
$lvlup_title .= 'Для получения III степени медали Попеля требуется обнаружить в бою '.$left.' едениц вражеской техники.';
if($number){$lvlup_title .= '<br>При текущем уровне игры для этого потребуется примерно '.ceil(($pres['overall'][$lang['games_p']]/$number)*$left).' боев.'; }
break;

case (0):
$left = 20-$number;
$lvlup_title .= 'Для получения IV степени медали Попеля требуется обнаружить в бою '.$left.' едениц вражеской техники.';
if($number){$lvlup_title .= '<br>При текущем уровне игры для этого потребуется примерно '.ceil(($pres['overall'][$lang['games_p']]/$number)*$left).' боев.'; }
break;

}
break;

case ('medalLavrinenko'):

$number = $pres['perform'][$lang['defend']];
$lvlup = 1;
switch ($val['value']) {

case (1):
$lvlup = 0;
break;

case (2):
$left = 30000-$number;
$lvlup_title .= 'Для получения I степени медали Лавриненко поребуется еще '.$left.' очков защиты базы.';
if($number){$lvlup_title .= '<br>При текущем уровне игры для этого потребуется примерно '.ceil(($pres['overall'][$lang['games_p']]/$number)*$left).' боев.';}
break;

case (3):
$left = 3000-$number;
$lvlup_title .= 'Для получения II степени медали Лавриненко поребуется еще '.$left.' очков защиты базы.';
if($number){$lvlup_title .= '<br>При текущем уровне игры для этого потребуется примерно '.ceil(($pres['overall'][$lang['games_p']]/$number)*$left).' боев.';}
break;

case (4):
$left = 300-$number;
$lvlup_title .= 'Для получения III степени медали Лавриненко поребуется еще '.$left.' очков защиты базы.';
if($number){$lvlup_title .= '<br>При текущем уровне игры для этого потребуется примерно '.ceil(($pres['overall'][$lang['games_p']]/$number)*$left).' боев.';}
break;

case (0):
$left = 30-$number;
$lvlup_title .= 'Для получения IV степени медали Лавриненко поребуется еще '.$left.' очков защиты базы.';
if($number){$lvlup_title .= '<br>При текущем уровне игры для этого потребуется примерно '.ceil(($pres['overall'][$lang['games_p']]/$number)*$left).' боев.';}
break;

}
break;

default:
$lvlup = 0;
break;
}

}?>
         <div class="medalDiv">
            <img width="67" height="71" title="<?php echo '<center>'.$val['title'].'</center>'.$lang['title_'.$tm]; ?>" class="bb <?php if($val['value'] == 0) {echo 'faded';} ?>" alt="<?=$val['title']; ?>" src="<?=$val['img']; ?>">
            <div class="a_num ui-state-highlight ui-widget-content"><?=$val['value']; ?></div>
<? if($lvlup) { ?>
            <div class="bb levelup" title="<?php echo $lvlup_title; ?>"></div>
<? } ?>
        </div>
<?php
$lvlup = 0; } ?>
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
$i=4;
$arsd = array('table2', 'table5');

foreach ($arsd as $val) {
  if(isset($info[$val]) and is_array(array_keys($info[$val]))) { ?>
    <table cellspacing="0" cellpadding="0" width="100%" id="<?=$val; ?>">
      <thead>
        <?php if ($val=='table2') {?>
        <tr>
          <th colspan="5" align="center" class="tablesorter-header ui-widget-header ui-corner-all ui-state-default">Статистика по отдельным машинам<sup>*</sup></th>
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
            <table cellspacing="2" cellpadding="0" width="100%" id="players<?php echo $i; ++$i; ?>" style="margin-top: 0px !important;">
              <thead>
                <tr>
                  <th>Название</th>
                  <th>боев</th>
                  <th>побед</th>
                  <th>%побед</th>
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
<?}
};
if(isset($info['table5']) || isset($info['table2']))
   echo "<div class='ui-state-highlight ui-widget-content'>*В таблице отображается только та техника, на которой проведено не менее 20 боев</div>";
unset($info); unset($pres); unset($tmp); ?>
</div>