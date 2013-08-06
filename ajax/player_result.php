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
    if (file_exists(dirname(__FILE__).'/func_ajax.php')) {
        define('LOCAL_DIR', dirname(__FILE__));
        include_once (LOCAL_DIR.'/func_ajax.php');
        define('ROOT_DIR', base_dir('ajax'));
    }else{
        define('LOCAL_DIR', '.');
        include_once (LOCAL_DIR.'/func_ajax.php');
        define('ROOT_DIR', '..');
    };
    include_once(ROOT_DIR.'/function/auth.php');
    include_once(ROOT_DIR.'/function/mysql.php');
    if (isset($_POST['db_pref']) ) {
        $db->change_prefix($_POST['db_pref']);
    }   else{
        if (isset($_GET['db_pref']) ) {
            $db->change_prefix($_GET['db_pref']);
        }   };
    include_once(ROOT_DIR.'/function/func.php');
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
   {  $("#t-table1")
      <? for ($i=2; $i<=13; $i++) {?>
              .add("#t-table<?=$i;?>")
      <? } ;?>
      .tablesorter({
          headerTemplate : '<div style="padding: 0px;">{content}</div>{icon}',
          widgets : ['uitheme', 'zebra'],
          headers:{ 0: { sorter: false}, 1: {sorter: false}, 2: {sorter: false}, 3: {sorter: false}, 4: {sorter: false},
                    5: { sorter: false}, 6: {sorter: false}, 7: {sorter: false}, 8: {sorter: false}, 9: {sorter: false},
                    10:{ sorter: false}, 11:{sorter: false}, 12:{sorter: false}, 13:{sorter: false}, 14:{sorter: false},
                    15:{ sorter: false}, 16:{sorter: false}, 17:{sorter: false}, 18:{sorter: false}, 19:{sorter: false},
                    20:{ sorter: false}, 21:{sorter: false}, 22:{sorter: false}, 23:{sorter: false}, 24:{sorter: false}
                  },
          theme : 'bootstrap'
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
.faded {
    opacity:0.4;
    filter: alpha(opacity=40);
}
</style>

<?   $not_incl = array ('account_id', 'name', 'role', 'server', 'reg', 'local', 'member_since');
     $darkgreen = '<span style="color:DarkGreen;"><img style="vertical-align: -5%;" width="11" height="11" src="./images/up.png">&nbsp;';
     $darkred = '<span style="color:DarkRed;"><img style="vertical-align: -5%;" width="11" height="11" src="./images/down.png">&nbsp;';
     $darkend = '</span>';
     $b_info_nation = $b_info_type = $b_info_lvl = $b_diff_played = $b_played_tanks = $effect = $b_pl_mp = $diff = $last = array();
     global $db;
     $b_nation = tanks_nations();

     $sql = "SELECT * FROM `tanks` ORDER BY id ASC;";
     $q = $db->prepare($sql);
     if ($q->execute() == TRUE) {
        $b_tank_name_tmp = $q->fetchAll(PDO::FETCH_ASSOC);
     }  else {
        die(show_message($q->errorInfo(),__line__,__file__,$sql));
     };
     foreach($b_tank_name_tmp as $tmp) {
        $b_tank_name[$tmp['id']] = $tmp;
     };
     unset($b_tank_name_tmp);

    if (isset($_POST['b_from']) ) {
        $b_from = $_POST['b_from'];
    }else{
        $b_from = '';
    };
    if (isset($_POST['b_to']) ) {
        $b_to = $_POST['b_to'];
    }else{
        $b_to = '';
    };
    if (isset($_POST['b_player']) ) {
        $b_player = $_POST['b_player'];
    }else{
        $b_player = '';
    };

If (($b_from<>'') && ($b_to<>'') ) {
    $b_from1 = explode('.',$b_from);
    $b_from11 = mktime(0, 0, 0, $b_from1['1'], $b_from1['0'], $b_from1['2']);
    $b_to1 = explode('.',$b_to);
    $b_to11 = mktime(23, 59, 59, $b_to1['1'], $b_to1['0'], $b_to1['2']);
    $resall = $cache->get('get_last_roster_'.$config['clan'],0);
    foreach($resall['data']['members'] as $id1 => $val ) {
       if ($val['account_name']==$b_player) {
           $b_res = $val;
       }
     };
     if (empty($b_res)) {die('No cached data');};
     $sql = "SELECT * FROM `col_tank_".$b_nation[0]['nation']."` WHERE account_id = '".$b_res['account_id']."' AND up < '".$b_to11."' AND up >= '".$b_from11."' ORDER BY up DESC;";
          $q = $db->prepare($sql);
          if ($q->execute() == TRUE) {
              $b_tanks = $q->fetchAll();
          }   else {
              die(show_message($q->errorInfo(),__line__,__file__,$sql));
          };
     $sql = "SELECT * FROM `col_players` WHERE account_id = '".$b_res['account_id']."' AND up < '".$b_to11."' AND up >= '".$b_from11."' ORDER BY up DESC;";
          $q = $db->prepare($sql);
          if ($q->execute() == TRUE) {
              $b_player_all = $q->fetchAll();
          }   else {
              die(show_message($q->errorInfo(),__line__,__file__,$sql));
          };

if (count($b_player_all) >1) {

//new tanks
     foreach ($b_nation as $val) {
        $sql = "SELECT * FROM `col_tank_".$val['nation']."` WHERE account_id = '".$b_res['account_id']."' AND up < '".$b_to11."' AND up >= '".$b_from11."' ORDER BY up DESC;";
                $q = $db->prepare($sql);
                if ($q->execute() == TRUE) {
                    $tanks = $q->fetchAll();
                } else {
                    die(show_message($q->errorInfo(),__line__,__file__,$sql));
                };

        foreach ($b_tank_name as $id => $val3) {
                 if (!isset ($b_diff_played['nation'][$b_tank_name[$id]['nation']]['win']))    $b_diff_played['nation'][$b_tank_name[$id]['nation']]['win'] = 0;
                 if (!isset ($b_diff_played['nation'][$b_tank_name[$id]['nation']]['total']))  $b_diff_played['nation'][$b_tank_name[$id]['nation']]['total'] = 0;
                 if (!isset ($b_diff_played['lvl'][$b_tank_name[$id]['lvl']]['win']))          $b_diff_played['lvl'][$b_tank_name[$id]['lvl']]['win'] = 0;
                 if (!isset ($b_diff_played['lvl'][$b_tank_name[$id]['lvl']]['total']))        $b_diff_played['lvl'][$b_tank_name[$id]['lvl']]['total'] = 0;
                 if (!isset ($b_diff_played['type'][$b_tank_name[$id]['type']]['win']))        $b_diff_played['type'][$b_tank_name[$id]['type']]['win'] = 0;
                 if (!isset ($b_diff_played['type'][$b_tank_name[$id]['type']]['total']))      $b_diff_played['type'][$b_tank_name[$id]['type']]['total'] = 0;

                 if (!isset ($b_info['nation'][$b_tank_name[$id]['nation']]['win']))           $b_info['nation'][$b_tank_name[$id]['nation']]['win'] = 0;
                 if (!isset ($b_info['nation'][$b_tank_name[$id]['nation']]['total']))         $b_info['nation'][$b_tank_name[$id]['nation']]['total'] = 0;
                 if (!isset ($b_info['lvl'][$b_tank_name[$id]['lvl']]['win']))                 $b_info['lvl'][$b_tank_name[$id]['lvl']]['win'] = 0;
                 if (!isset ($b_info['lvl'][$b_tank_name[$id]['lvl']]['total']))               $b_info['lvl'][$b_tank_name[$id]['lvl']]['total'] = 0;
                 if (!isset ($b_info['type'][$b_tank_name[$id]['type']]['win']))               $b_info['type'][$b_tank_name[$id]['type']]['win'] = 0;
                 if (!isset ($b_info['type'][$b_tank_name[$id]['type']]['total']))             $b_info['type'][$b_tank_name[$id]['type']]['total'] = 0;

           If (isset($tanks[0][$id.'_t'])) {

              if (($tanks[count($tanks)-1][$id.'_t'] == '0')&&($tanks[0][$id.'_t'] > 0)) $b_new_tank[$id] = $b_tank_name[$id];
              if ($tanks[count($tanks)-1][$id.'_t'] <> $tanks[0][$id.'_t']) {
                 $b_played_tanks[$id]['tank'] =  $b_tank_name[$id]['tank'];
                 $b_played_tanks[$id]['nation'] = $val['nation'];
                 $b_played_tanks[$id]['total'] = $tanks[0][$id.'_t'];
                 $b_played_tanks[$id]['win'] = $tanks[0][$id.'_w'];
                 $b_played_tanks[$id]['total_d'] = $tanks[0][$id.'_t']-$tanks[count($tanks)-1][$id.'_t'];
                 $b_played_tanks[$id]['win_d'] = $tanks[0][$id.'_w']-$tanks[count($tanks)-1][$id.'_w'];

                 $b_diff_played['nation'][$b_tank_name[$id]['nation']]['total'] += ($tanks[0][$id.'_t']- $tanks[count($tanks)-1][$id.'_t']);
                 $b_diff_played['nation'][$b_tank_name[$id]['nation']]['win'] +=   ($tanks[0][$id.'_w']- $tanks[count($tanks)-1][$id.'_w']);
                 $b_diff_played['lvl'][$b_tank_name[$id]['lvl']]['total'] +=       ($tanks[0][$id.'_t']- $tanks[count($tanks)-1][$id.'_t']);
                 $b_diff_played['lvl'][$b_tank_name[$id]['lvl']]['win'] +=         ($tanks[0][$id.'_w']- $tanks[count($tanks)-1][$id.'_w']);
                 $b_diff_played['type'][$b_tank_name[$id]['type']]['total'] +=     ($tanks[0][$id.'_t']- $tanks[count($tanks)-1][$id.'_t']);
                 $b_diff_played['type'][$b_tank_name[$id]['type']]['win'] +=       ($tanks[0][$id.'_w']- $tanks[count($tanks)-1][$id.'_w']);
                 }
              if ($tanks[0][$id.'_t'] <> '0') {
                 $b_info['nation'][$b_tank_name[$id]['nation']]['win'] += $tanks[0][$id.'_w'];
                 $b_info['nation'][$b_tank_name[$id]['nation']]['total'] += $tanks[0][$id.'_t'];
                 $b_info['lvl'][$b_tank_name[$id]['lvl']]['win'] += $tanks[0][$id.'_w'];
                 $b_info['lvl'][$b_tank_name[$id]['lvl']]['total'] += $tanks[0][$id.'_t'];
                 $b_info['type'][$b_tank_name[$id]['type']]['win'] += $tanks[0][$id.'_w'];
                 $b_info['type'][$b_tank_name[$id]['type']]['total'] += $tanks[0][$id.'_t'];
              }
           }
        }
     };


//main_data w/o tanks
     foreach ($b_player_all[0] as $key => $val) {
        $last[$key] = $b_player_all[0][$key];
        if ((!is_numeric($key)) && (!In_array($key,$not_incl))) {
              $diff[$key] = $b_player_all[0][$key] - $b_player_all[count($b_player_all)-1][$key];
              $first[$key] = $b_player_all[count($b_player_all)-1][$key];
        }
     };
     $first['dead_heat'] = $first['total']-$first['lose']-$first['win'] ;
     $last['dead_heat'] = $last['total']-$last['lose']-$last['win'] ;
     $diff['dead_heat'] = $first['dead_heat'] - $last['dead_heat'];
//efficient
     If ($last['total'] == 0)  {$last['total'] = 1/100000;}
     If ($first['total'] == 0) {$first['total'] = 1/100000;}
     $effect['des'] = $last['des'] / $last['total'];
     $effect['dmg'] = $last['dmg'] / $last['total'];
     $effect['spot'] = $last['spot'] / $last['total'];
     $effect['def'] = $last['def'] / $last['total'];
     $effect['cap'] = $last['cap'] / $last['total'];
     $effect['lvl'] = 0;
     foreach ($b_info['lvl'] as $lvl_key => $val)
        $effect['lvl'] += $lvl_key*$val['total']/$last['total'];
     $effect['lvl'] = number_format($effect['lvl'], 2, '.', '');
     $eff_rating  = number_format($effect['dmg']*(10/($effect['lvl'] +2 ))*(0.23+2*$effect['lvl']/100) + $effect['des']*0.25*1000 + $effect['spot']*0.15*1000 + log($effect['cap']+1,1.732)*0.15*1000 + $effect['def']*0.15*1000,2, '.', '');
     $effect['des2'] = ($first['des'])  / ($first['total']);
     $effect['dmg2'] = ($first['dmg'])  / ($first['total']);
     $effect['spot2'] = ($first['spot']) / ($first['total']);
     $effect['def2'] = ($first['def'])  / ($first['total']);
     $effect['cap2'] = ($first['cap'])  / ($first['total']);
     $effect['lvl2'] = 0;
     foreach ($b_info['lvl'] as $lvl_key => $val)
        $effect['lvl2'] += $lvl_key*$val['total']/($first['total']);
     $effect['lvl2'] = number_format($effect['lvl'], 2, '.', '');
     $eff_rating_ = number_format($effect['dmg2']*(10/($effect['lvl2'] +2 ))*(0.23+2*$effect['lvl2']/100) + $effect['des2']*0.25*1000 + $effect['spot2']*0.15*1000 + log($effect['cap2']+1,1.732)*0.15*1000 + $effect['def2']*0.15*1000,2, '.', '');
     $eff_rating2 = number_format($eff_rating - $eff_rating_, 2, '.', '');
     $eff_ratingb = round((log($last['total'])/10)*(($last['averag_exp']*1)+($effect['dmg']*(($last['win']/$last['total'])*2+$effect['des']*0.9+$effect['spot']*0.5+$effect['def']*0.5+$effect['cap']*0.5))),0);
     $eff_ratingb_ = round((log($first['total'])/10)*((($first['averag_exp'])*1)+($effect['dmg2']*((($first['win'])/($first['total']))*2+$effect['des2']*0.9+$effect['spot2']*0.5+$effect['def2']*0.5+$effect['cap2']*0.5))),0);
     $eff_ratingb2 = $eff_ratingb- $eff_ratingb_;

     switch ($eff_rating) {
        case ($eff_rating < 600):
           $color = 'red';
           break;
        case ($eff_rating < 900):
           $color = 'slategray';
           break;
        case ($eff_rating < 1200):
           $color = 'green';
           break;
        case ($eff_rating < 1500):
           $color = 'royalblue';
           break;
        case ($eff_rating < 1800):
           $color = 'purple';
           break;
        default:
           $color = '#FF7900';
           break;
     };

     switch ($eff_ratingb+1) {
        case ($eff_ratingb > 7294):
           $textt = 'Виртуоз<br>(рейтинг выше, чем у 99% игроков)';$imgg='http://armor.kiev.ua/wot/images/classVf.png';
           break;
        case ($eff_ratingb > 5571):
           $textt = 'Мастер-танкист<br>(рейтинг выше, чем у 95% игроков)';$imgg='http://armor.kiev.ua/wot/images/classMf.png';
           break;
        case ($eff_ratingb > 3851):
           $textt = 'Танкист 1-го класса<br>(рейтинг выше, чем у 80% игроков)';$imgg='http://armor.kiev.ua/wot/images/class1f.png';
           break;
        case ($eff_ratingb > 2736):
           $textt = 'Танкист 2-го класса<br>(рейтинг выше, чем у 60% игроков)';$imgg='http://armor.kiev.ua/wot/images/class2f.png';
           break;
        case ($eff_ratingb > 2084):
           $textt = 'Танкист 3-го класса<br>(рейтинг выше, чем у 45% игроков)';$imgg='http://armor.kiev.ua/wot/images/class3f.png';
           break;
        case ($eff_ratingb > 1452):
           $textt = 'Оленевод 3-го класса<br>(рейтинг выше, чем у 30% игроков)';$imgg='http://armor.kiev.ua/wot/images/deer3f.png';
           break;
        case ($eff_ratingb > 1010):
           $textt = 'Оленевод 2-го класса<br>(рейтинг выше, чем у 20% игроков)';$imgg='http://armor.kiev.ua/wot/images/deer2f.png';
           break;
        case ($eff_ratingb > 517):
           $textt = 'Оленевод 1-го класса<br>(рейтинг выше, чем у 10% игроков)';$imgg='http://armor.kiev.ua/wot/images/deer1f.png';
           break;
        default:
           $textt = 'Мастер-оленевод<br>(рейтинг ниже, чем у 10% игроков)';$imgg='http://armor.kiev.ua/wot/images/deerMf.png';
           break;
     };

     $roster = roster_sort($resall['data']['members']);
     $roster_id = roster_resort_id($roster);
     $b_pl_mp1 = medal_progress($roster_id, $b_from11, $b_to11);
     unset($resall, $roster, $roster_id);
     $count_med = 0;
     Unset($b_pl_mp1['unsort']);
     if (isset($b_pl_mp1['sorted'])) {
         foreach ($b_pl_mp1['sorted'] as $mdtype => $val) {
            foreach ($val as $id =>$val2) {
               if ($id == $b_res['account_id']) $b_pl_mp[$mdtype] = $val2;
            }
        }
     }
     ksort($b_pl_mp);
     foreach ($b_pl_mp as $keytype => $val){
        $ctyp = 0;
        foreach ($val as $keymedal => $val2){
           $count_med += $val2;
           $ctyp += $val2;
        }
        if ($ctyp == 0) {unset ($b_pl_mp[$keytype]);}
     }
     Unset ($b_pl_mp1);
?>

<div align="center">
<div align="center" class="ui-state-highlight ui-widget-content">Период отображаемых данных c <?php echo date('d.m.Y',$first['up']); ?> по <?php echo date('d.m.Y',$last['up']); ?></div>
  <table cellspacing="2" cellpadding="2" width="100%" id="tmain">
   <tbody>
    <tr>
     <td valign="top" width="20%">
       <table cellspacing="1" cellpadding="1" width="100%" align="center" id="t-table8">
         <thead>
           <tr>
            <th align="center" colspan="2" ><span class="bb" style="border-bottom: 1px dashed #666666; cursor: pointer;" title="<?=$lang['overall_eff_table'];?>">Рейтинг эффективности (c)</span>
            <br><a href="http://wot-news.com/" target="_blank">wot-news.com</a></th>
           </tr>
         </thead>
         <tbody>
           <tr>
             <td align="center"><font color="<?=$color; ?>"><?=$eff_rating; ?></font></td>
             <td align="center"><?php if ($eff_rating2 >  0) echo $darkgreen.'+'.$eff_rating2.$darkend;
                                      if ($eff_rating2 <  0) echo $darkred.$eff_rating2.$darkend;
                                      if ($eff_rating2 == 0) echo '0';?>
             </td>
           </tr>
         </tbody>
       </table>
       <table cellspacing="1" cellpadding="1" width="100%" align="center" id="t-table10">
         <thead>
           <tr>
             <th align="center" colspan="3">Стиль игры (c) <br><a href="http://emem.ru/" target="_blank">emem.ru</a></th>
           </tr>
         </thead>
         <tbody>
           <tr>
             <td><span class="hidden">1</span><span class="bb" style="border-bottom: 1px dashed #666666; cursor: pointer;" title="Отношение суммы уничтоженных и обнаруженных врагов к количеству проведенных боев">Общая агресивность</span>:</td>
             <td><?php $showl = ($last['spot']+$last['des'])/$last['total'];
                       echo number_format($showl,3); ?>
             </td>
             <td><?php $shown = number_format($showl-($first['spot']+$first['des'])/$first['total'],3);
                       if ($shown >  0) echo $darkgreen.'+'.$shown.$darkend;
                       if ($shown <  0) echo $darkred.$shown.$darkend;
                       if ($shown == 0) echo '0'; ?>
             </td>
           </tr>
           <tr>
             <td><span class="hidden">2</span><span class="bb" style="border-bottom: 1px dashed #666666; cursor: pointer;" title="Отношение уничтоженных врагов к количеству проведенных боев">Боец</span>:</td>
             <td><?php echo number_format(($last['des'])/$last['total'],3);?></td>
             <td><?php $shown = round (($last['des']/$last['total']-$first['des']/$first['total'] ), 3);
                       if ($shown >  0) echo $darkgreen.'+'.$shown.$darkend;
                       if ($shown <  0) echo $darkred.$shown.$darkend;
                       if ($shown == 0) echo '0';
                 ?></td>
           </tr>
           <tr>
             <td><span class="hidden">3</span><span class="bb" style="border-bottom: 1px dashed #666666; cursor: pointer;" title="Отношение обнаруженных врагов к количеству проведенных боев">Разведчик</span>:</td>
             <td><?php echo number_format($last['spot']/$last['total'],3);?></td>
             <td><?php $shown = round (($last['spot']/$last['total']-$first['spot']/$first['total'] ), 3);
                       if ($shown >  0) echo $darkgreen.'+'.$shown.$darkend;
                       if ($shown <  0) echo $darkred.$shown.$darkend;
                       if ($shown == 0) echo '0';
                 ?></td>
           </tr>
           <tr>
             <td><span class="hidden">4</span><span class="bb" style="border-bottom: 1px dashed #666666; cursor: pointer;" title="Отношение количества очков захвата базы к количеству проведенных боев">Захватчик</span>:</td>
             <td><?php echo number_format($last['cap']/$last['total'],3);?></td>
             <td><?php $shown = round (($last['cap']/$last['total']-$first['cap']/$first['total'] ), 3);
                       if ($shown >  0) echo $darkgreen.'+'.$shown.$darkend;
                       if ($shown <  0) echo $darkred.$shown.$darkend;
                       if ($shown == 0) echo '0';
                 ?></td>
           </tr>
           <tr>
             <td><span class="hidden">5</span><span class="bb" style="border-bottom: 1px dashed #666666; cursor: pointer;" title="Отношение сколичества очков защиты базы к количеству проведенных боев">Защитник</span>:</td>
             <td><?php echo number_format($last['def']/$last['total'],3);?></td>
             <td><?php $shown = round (($last['def']/$last['total']-$first['def']/$first['total'] ), 3);
                       if ($shown >  0) echo $darkgreen.'+'.$shown.$darkend;
                       if ($shown <  0) echo $darkred.$shown.$darkend;
                       if ($shown == 0) echo '0';
                 ?></td>
           </tr>
         </tbody>
       </table>
       <table cellspacing="1" cellpadding="1" width="100%" align="center" id="t-table9">
         <thead>
           <tr>
            <th align="center" colspan="2" ><span class="bb" style="border-bottom: 1px dashed #666666; cursor: pointer;"
                title="Данный рейтинг не имеет четких показательных границ.">Рейтинг бронесайта (c)</span>
                <br><a href="http://armor.kiev.ua/wot/" target="_blank">armor.kiev.ua/wot</a></th>
           </tr>
         </thead>
         <tbody>
           <tr>
             <td align="center"><?=$eff_ratingb; ?></td>
             <td align="center"><?php if ($eff_ratingb2 >  0) echo $darkgreen.'+'.$eff_ratingb2.$darkend;
                                      if ($eff_ratingb2 <  0) echo $darkred.$eff_ratingb2.$darkend;
                                      if ($eff_ratingb2 == 0) echo '0';
                                ?>
             </td>
           </tr>
           <tr>
             <td align="center" colspan="2"><?php echo '<img src="'.$imgg.'" />'; ?></td>
           </tr>
           <tr>
             <td align="center" colspan="2"><?=$textt; ?></td>
           </tr>
         </tbody>
       </table>
       <?php if (isset($b_new_tank)) { ?>
       <table cellspacing="1" cellpadding="1" width="100%" align="center" id="t-table11" style="position: relative;">
         <thead>
          <tr>
            <th align="center" colspan="2"><?=$lang['new_tanks'];?></th>
          </tr>
         </thead>
       <tbody>
         <?php foreach($b_new_tank as $id => $val) { //  id, title,tank,nation,lvl, type,link?>
         <tr style="height:31px;">
            <td>
              <span class="hidden"><?=$val['lvl'];?></span>
              <?php if (strlen($val['tank']) > 20) {
                        $trimmed = substr($val['tank'], 0, 18 );
                        echo $trimmed.'...';
                    }   else {
                        echo $val['tank'];
                    } ?>
            </td>
            <td style="width:131px;">
               <span class="bb" title="<?php echo $val['tank'].'<br>'.$lang[$val['nation']].'<br>'.$val['lvl'].' lvl<br>'.$lang[$val['type']]; ?>">
                 <?php echo '<img src="http://'.$config['gm_url'].'/static/3.6.0.1/common/img/nation/'.$val['nation'].'.png" />';
                       echo '<img style="right: -45px; position: absolute;" src="http://'.$config['gm_url'].$val['link'].'" />'; ?>
               </span>
            </td>
         </tr>
         <?php } ?>
       </tbody>
       </table>
       <?php };?>
     </td>
     <td valign="top" width="42%">
       <?php $misc4 = array ('total', 'win', 'lose', 'alive', 'dead_heat');?>
       <table cellspacing="1" cellpadding="1" width="100%" align="center" id="t-table1">
         <thead>
           <tr>
             <th align="center" colspan="5"><?=$lang['overall_title'];?></th>
           </tr>
         </thead>
         <tbody>
           <?php $i=1; foreach ($misc4 as $val) {?>
           <tr>
             <td><span class="hidden"><?=$i; ?></span><?=$lang[$val];?>:</td>
             <td><?php echo round($last[$val]); ?></td>
             <td><?php if ($diff[$val]> 0) echo '+'; echo round($diff[$val]); ?></td>
             <td><?php
                 if ($val == 'total') {
                     echo ' ';
                 } else {
                    if ($last['total'] <> 0 ){
                       echo round($last[$val]/$last['total']*100,2).'% ';
                    }  else {echo '0%';}
                 }?></td>
             <td><?php
                 if ($last['total'] <> 0) {
                     if ($val == 'total') {
                         $delta = round($diff['total']/$last['total']*100,3);
                     }   else {
                         $delta = round(($last[$val]/$last['total']-$first[$val]/$first['total'])*100,3);
                     }
                 }   else $delta = 0;
                 if ($delta >0) echo $darkgreen.'+'.$delta.'%'.$darkend;
                 if ($delta <0) echo $darkred.$delta.'%'.$darkend;
                 if ($delta == 0) echo '0%';
               ?></td>
           </tr>
           <?php ++$i;}; ?>
       </table>

       <?php $i = 2; $misc = array ('des', 'spot', 'dmg', 'cap', 'def');?>
       <table cellspacing="1" cellpadding="1" width="100%" align="center" id="t-table2">
         <thead>
           <tr>
             <th align="center" colspan="5"><?=$lang['perform_title'];?></th>
           </tr>
         </thead>
         <tbody>
           <tr>
             <td><span class="hidden">1</span><?=$lang['accure'];?>:</td>
             <?php echo '<td>'.$last['accure'].'%</td><td colspan="2">'.' '.'</td><td>';
                   if ($diff['accure']> 0) {
                       echo $darkgreen.'+'.$diff['accure'].'%'.$darkend;
                   }   else {echo '0';} ?>
             </td>
           </tr>
           <?php foreach ($misc as $val) {?>
           <tr>
             <td><span class="hidden"><?=$i; ?></span><?=$lang[$val];?>:</td>
             <td><?php echo $last[$val].'</td><td>';
                       if ($diff[$val]> 0) echo '+';
                       echo $diff[$val]?></td>
             <td><?php
                 if ($last['total'] <> 0 ){
                    echo round($last[$val]/$last['total'],2);
                 }  else {echo '0';}?></td>
             <td><?php
                 $delta=round(($last[$val]/$last['total']-$first[$val]/$first['total']),2);
                 if ($delta >0) echo $darkgreen.'+'.$delta.$darkend;
                 if ($delta <0) echo $darkred.$delta.$darkend;
                 if ($delta == 0) echo '0';?></td>
           </tr>
           <?php ++$i; }; ?>
         </tbody>
       </table>

       <?php $misc3 = array ('exp', 'averag_exp', 'max_exp');?>
       <table cellspacing="1" cellpadding="1" width="100%" align="center" id="t-table7">
         <thead>
           <tr>
             <th align="center" colspan="3"><?= $lang['battel_title'];?></th>
           </tr>
         </thead>
         <tbody>
               <?php $i=1;foreach($misc3 as $val) { ?>
           <tr>
             <td><span class="hidden"><?php echo $i;++$i; ?></span><?=$lang[$val]; ?>:</td>
             <td><?=$last[$val]; ?></td>
             <td><?php
                   if ($diff[$val] >  0) echo $darkgreen.'+'.$diff[$val].$darkend;
                   if ($diff[$val] <  0) echo $darkred.$diff[$val].$darkend;
                   if ($diff[$val] == 0) echo '0';
                 ?></td>
           </tr>
               <?php } ?>
         </tbody>
       </table>

       <?php $misc2 = array ('gr', 'wb', 'eb', 'win', 'gpl', 'cpt', 'dmg', 'dpt' ,'frg', 'spt', 'exp');
             $misc22 = array('_v','_p') ?>
       <table cellspacing="1" cellpadding="0" width="100%" align="center" id="t-table3">
         <thead>
          <tr>
            <th align="center"><?=$lang['rating_title'];?></th>
            <th align="center" colspan="2"><?=$lang['value'];?></th>
            <th align="center" colspan="2"><?=$lang['place'];?></th>
          </tr>
         </thead>
         <tbody>
               <?php $i = 1; foreach($misc2 as $val) { ?>
           <tr>
             <td><span class="hidden"><?=$i; ?></span><?=$lang[$val];?>:</td>
             <?php foreach ($misc22 as $val2) { ?>
             <td><?=$last[$val.$val2]; ?></td>
             <td><?php
                   if ($diff[$val.$val2] >  0) echo $darkgreen.'+'.$diff[$val.$val2].$darkend;
                   if ($diff[$val.$val2] <  0) echo $darkred.$diff[$val.$val2].$darkend;
                   if ($diff[$val.$val2] == 0) echo '0';
                 ?></td> <? } ?>
           </tr>
               <?php ++$i; } ?>
         </tbody>
       </table>
     </td>
     <td valign="top" width="38%">
       <?php $misc4 = array ('type', 'nation', 'lvl');
             $i=4;
             foreach ($misc4 as $mkey) {?>

       <table cellspacing="1" cellpadding="1"  width="100%" align="center" id="t-table<?=$i;?>">
         <thead>
           <tr>
             <th colspan="7" align="center">Боевая эффективность по
             <?php if ($mkey == 'type') echo ' классам техники</td></tr><tr><th align="center">Класс техники</th>';
                   if ($mkey == 'nation') echo ' нациям</td></tr><tr><th align="center" >Нация</th> ';
                   if ($mkey == 'lvl') echo ' уровням техники</td></tr><tr><th align="center">Уровень</th>';
             ?>
             <th align="center" colspan="2">Боев</th>
             <th align="center" colspan="2">Побед</th>
             <th align="center" colspan="2">% Побед</th>
           </tr>
         </thead>
         <tbody>
           <?php foreach($b_info[$mkey] as $key_key =>$val) { ?>
           <tr>
             <td><?php if ($mkey <> 'lvl') {
                           echo $lang[$key_key] ;
                       } else {
                        echo $key_key;
                       };
                 ?></td>
             <td><?=$val['total']; ?></td>
             <td><?php if ($b_diff_played[$mkey][$key_key]['total'] <> 0) echo '+';
                       echo $b_diff_played[$mkey][$key_key]['total']?></td>
             <td><?=$val['win']; ?></td>
             <td><?php if ($b_diff_played[$mkey][$key_key]['win'] <> 0) echo '+';
                       echo $b_diff_played[$mkey][$key_key]['win'];?></td>
             <td><?php if ($val['total']<> 0) {
                          echo round($val['win']/$val['total']*100,2);
                       } else {
                          echo '0';
                       }
                 ?>%</td>
             <td><?php
                 if (($b_diff_played[$mkey][$key_key]['total'] <> 0)&&($val['total'] <> $b_diff_played[$mkey][$key_key]['total'])) {
                   $rett1 = round( $val['win']/$val['total']*100 - ($val['win'] - $b_diff_played[$mkey][$key_key]['win'])/($val['total'] - $b_diff_played[$mkey][$key_key]['total'])*100,2);
                 } else {
                   $rett1 = 0;
                 }
                 if ($rett1 >0) echo $darkgreen.'+'.$rett1.'%'.$darkend;
                 if ($rett1 <0) echo $darkred.$rett1.'%'.$darkend;
                 if ($rett1 == 0) echo '0';
                 ?></td>
           </tr>
           <?php } ?>
         </tbody>
       </table>
       <?php ++$i;}; //foreach?>
     </td>
    </tr>
  </tbody>
</table>

</div>
<? if (count($b_played_tanks)>0) { ?>
<div align="center">
<br>
<table cellspacing="1" cellpadding="1" width="100%" align="center" id="t-table12">
    <thead>
      <tr>
        <th colspan="23" align="center">Статистика по отдельным машинам</th>
      </tr>
      <tr>
        <th align="center" rowspan="2"><?=$lang['name'];?></th>
        <th align="center" rowspan="2" colspan="2">Боев</th>
        <th align="center" colspan="4"><?=$lang['victories'];?></th>
        <th align="center" colspan="4"><?=$lang['battles_s'];?></th>
        <th align="center" colspan="4"><?=$lang['spo'];?></th>
        <th align="center" colspan="4"><?=$lang['damage'];?></th>
        <th align="center" colspan="4"><?=$lang['destroyed'];?></th>
      </tr>
      <tr>
        <th align="center" colspan="2">Кол-во</th>
        <th align="center" colspan="2">%</th>
        <th align="center" colspan="2">Кол-во</th>
        <th align="center" colspan="2">%</th>
        <th align="center" colspan="2">Кол-во</th>
        <th align="center" colspan="2">Среднее за бой</th>
        <th align="center" colspan="2">Кол-во</th>
        <th align="center" colspan="2">Среднее за бой</th>
        <th align="center" colspan="2">Кол-во</th>
        <th align="center" colspan="2">Среднее за бой</th>
      </tr>
    </thead>
    <tbody>
    <?
       foreach ($b_played_tanks as $idkey => $val) {
          $sql = "SELECT * FROM `col_rating_tank_".$val['nation']."` WHERE account_id = '".$b_res['account_id']."' AND up < '".$b_to11."' AND up >= '".$b_from11."' ORDER BY up DESC;";
               $q = $db->prepare($sql);
               if ($q->execute() == TRUE) {
                   $b_tanks = $q->fetchAll();
               }   else {
                   die(show_message($q->errorInfo(),__line__,__file__,$sql));
               };
       $b_misc8_sp = array ('_sp', '_dd', '_fr'); ?>

      <tr>
        <td><span class="hidden"><?=$val['lvl'];?></span>
            <?php if (strlen($val['tank']) > 20) {
                      $trimmed = substr($val['tank'], 0, 18 );
                      echo $trimmed.'...';
                  }   else {
                      echo $val['tank'];
                  } ?>
        </td>
        <td><?=$val['total']; ?></td>
        <?php if ($val['total'] <> $val['total_d']) { ?>
        <td>+<?=$val['total_d']; ?></td>
        <td><?=$val['win']; ?></td>
        <td><?php if ($val['win_d']<>0) echo '+';
                  echo $val['win_d']; ?></td>
        <td><?php echo round ($val['win']/$val['total']*100,2); ?>%</td>
        <td><?php $shown = Round($val['win']/$val['total']*100 - ($val['win']- $val['win_d'])/($val['total'] - $val['total_d'])*100,2);
                  if ($shown >  0) echo $darkgreen.'+'.$shown.'%'.$darkend;
                  if ($shown <  0) echo $darkred.$shown.'%'.$darkend;
                  if ($shown == 0) echo '0%';
            ?></td>
        <td><?php echo $b_tanks[0][$idkey.'_sb']; ?></td>
        <td><?php $shown = $b_tanks[0][$idkey.'_sb']-$b_tanks[count($b_tanks)-1][$idkey.'_sb'];
                  if ($shown<>0) echo '+';
                  echo $shown;
            ?></td>
        <td><?php echo Round($b_tanks[0][$idkey.'_sb']/$val['total']*100,2); ?>%</td>
        <td><?php $shown = Round($b_tanks[0][$idkey.'_sb']/$val['total']*100 - ($b_tanks[count($b_tanks)-1][$idkey.'_sb'])/($val['total'] - $val['total_d'])*100,2);
                  if ($shown >  0) echo $darkgreen.'+'.$shown.'%'.$darkend;
                  if ($shown <  0) echo $darkred.$shown.'%'.$darkend;
                  if ($shown == 0) echo '0%';
            ?></td>
        <?php foreach ($b_misc8_sp as $misk_key) { ?>

        <td><?php echo $b_tanks[0][$idkey.$misk_key]; ?></td>
        <td><?php $shown = $b_tanks[0][$idkey.$misk_key]-$b_tanks[count($b_tanks)-1][$idkey.$misk_key];
                  if ($shown<>0) echo '+';
                  echo $shown;
            ?></td>
        <td><?php echo Round($b_tanks[0][$idkey.$misk_key]/$val['total'],2); ?></td>
        <td><?php $shown = Round($b_tanks[0][$idkey.$misk_key]/$val['total'] - ($b_tanks[count($b_tanks)-1][$idkey.$misk_key])/($val['total'] - $val['total_d']),2);
                  if ($shown >  0) echo $darkgreen.'+'.$shown.$darkend;
                  if ($shown <  0) echo $darkred.$shown.$darkend;
                  if ($shown == 0) echo '0';
            ?></td>


        <?php } ;

        } else { ?>
        <td><?php echo '-'; ?></td>
        <td><?=$val['win']; ?></td>
        <td><?php echo '-'; ?></td>
        <td><?php echo round ($val['win']/$val['total']*100,2); ?>%</td>
        <td><?php echo '-'; ?></td>
        <td><?php echo $b_tanks[0][$idkey.'_sb']; ?></td>
        <td><?php echo '-'; ?></td>
        <td><?php echo Round($b_tanks[0][$idkey.'_sb']/$val['total']*100,2); ?>%</td>
        <td><?php echo '-'; ?></td>
        <?php foreach ($b_misc8_sp as $misk_key) { ?>

        <td><?php echo $b_tanks[0][$idkey.$misk_key]; ?></td>
        <td><?php echo '-'; ?></td>
        <td><?php echo Round($b_tanks[0][$idkey.$misk_key]/$val['total'],2); ?></td>
        <td><?php echo '-'; ?></td>
        <?php } }; ?>
      </tr>
<?   $i++;  } ?>
    </tbody>
</table>
</div>
<? };
if ($count_med>0) {
      if (isset($b_pl_mp['major'])) {
         $sql = "SELECT medalCarius, medalEkins, medalKay, medalLeClerc, medalAbrams, medalPoppel, medalLavrinenko, medalKnispel FROM `col_medals` WHERE account_id = '".$b_res['account_id']."' AND up < '".$b_to11."' AND up >= '".$b_from11."' ORDER BY up DESC;";
         $q = $db->prepare($sql);
         if ($q->execute() == TRUE) {
             $b_medals = $q->fetchAll();
         }   else {
             die(show_message($q->errorInfo(),__line__,__file__,$sql));
         };
         foreach ($b_pl_mp['major'] as $key => $val){
            if (($val < 0) ) {
                 $b_pl_mp['major'][$key]=$b_medals[0][$key];
            }
         }
      }; ?>
<br>
<div align="center">
<table cellspacing="1" cellpadding="1" width="100%" id="t-table13">
  <thead>
    <tr>
      <th>Статистика по наградам и достижениям</th>
    </tr>
  </thead>
  <tbody>
  <?php $i=1; foreach($b_pl_mp as $type_key => $tmp) { ?>
    <tr>
      <td align="center">
          <?php echo '<span class="hidden">'.$i.'</span>';
                if ($type_key[strlen($type_key)-1]== '2') {
                    $out = substr($type_key, 0, strlen($type_key)-1);
                    echo $lang[$out].' - 2';
                }   else {
                    echo $lang[$type_key];
                }   ++$i; ?>
      </td>
    </tr>
    <tr>
      <td style="border:1px solid #666; text-align:center;"><span class="hidden"><?=$i;?>'</span>
      <?php foreach($tmp as $tm => $val) {
               $tm2 = ucfirst($tm);
               if (($type_key == 'major') && ($val <> '0')) {$tm2 .= $val;};
               if ($tm<>'lumberjack') { ?>
                  <div class="medalDiv">
                    <img width="67" height="71" title="<?php echo '<center>'.$lang['medal_'.$tm].'</center>'.$lang['title_'.$tm]; ?>" class="bb <?php if($val == 0) {echo 'faded';} ?>" alt="<?=$lang['title_'.$tm]; ?>" src="<?php echo './images/medals/'.$tm2.'.png'; ?>">
                    <div class="a_num ui-state-highlight ui-widget-content"><?=$val; ?></div>
                  </div>
          <?   }
            } ?>
      </td>
    </tr>
  <? ++$i; }; ?>
  </tbody>
</table>
</div>
<? };
} else {?>
    <div align="center" class="ui-state-highlight ui-widget-content"><?=$lang['error_cron_off_or_none'];?></div>
  <?php };
} else echo '<div align="center" class="ui-state-highlight ui-widget-content">Выберите две корректные даты!</div>';
Unset($b_tanks);
?>