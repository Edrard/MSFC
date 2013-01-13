<script type="text/javascript" id="js">
        $(document).ready(function()
        {
            $("#avt1").tablesorter({widthFixed: false, headerTemplate : '{content} {icon}', widgets: ['uitheme', 'zebra'], widgetOptions: {uitheme : 'bootstrap'} });
            $("#avt2").tablesorter({widthFixed: false, headerTemplate : '{content} {icon}', widgets: ['uitheme', 'zebra'], widgetOptions: {uitheme : 'bootstrap'} });
        });
</script>

<div align="center">
 <?php $avt_memb = $new['data']['members_count'];
       $avt = rating($res, $lang);
       $avtdmg = $avt['rat'];
       $darkgreen = '<span style="color:DarkGreen;"><img style="vertical-align: -5%;" width="11" height="11" src="./images/up.png">&nbsp;';
       $darkred = '<span style="color:DarkRed;"><img style="vertical-align: -5%;" width="11" height="11" src="./images/down.png">&nbsp;';

   foreach($res as $name => $val){
    $avt_eff[round($eff_rating[$name])] = $name;
    $avt_games[] = $val['overall'][$lang['total']];
    $avt_win[] = $val['overall'][$lang['victories']];
    $avt_lose[] = $val['overall'][$lang['defeats']];
    $avt_surv[] = $val['overall'][$lang['alive']];
    $avt_seek[] = $val['perform'][$lang['spotted']];
    $avt_dest[] = $val['perform'][$lang['destroyed']];
    $avt_pers[] = $val['perform'][$lang['hit_ratio']];
    $avt_dama[] = $val['perform'][$lang['damage']];
    $avt_capt[] = $val['perform'][$lang['capture']];
    $avt_defe[] = $val['perform'][$lang['defend']];
    $avt_summ[] = $val['exp'][$lang['total_exp']];
    $avt_max[] = $val['exp'][$lang['exp_max']];
 };
?>

<table cellspacing="1" cellpadding="8" width="100%" id="avt1">
   <thead style="font-weight: bold;">
    <tr>
     <th width="20%" align="center">ТOП 5 по эффективности</th>
     <th width="20%" align="center">ТOП 5 по урону</th>
     <th width="20%" align="center">ТOП 5 по засвету</th>
     <th width="20%" align="center">ТOП 5 по захвату</th>
     <th width="20%" align="center">ТOП 5 по защите</th>
    </tr>
   </thead>
   <tbody>
    <tr>
    <td align="center">
        <table cellspacing="0" cellpadding="2" width="100%">
         <tbody>
          <?php krsort($avt_eff);
          $five = 1;
          foreach($avt_eff as $name => $val){ ?>
            <tr <?php if($five%2 == 0){ echo 'class="ui-priority-secondary"'; } else { echo 'class="ui-widget-content"'; } ?>>
              <td><?=$val; ?></td>
              <td style="font-weight:bold;"><?=$eff_rating[$val];?></td>
            </tr>
           <?php $five++;
                 if($five>5) break;} ?>
         </tbody>
        </table>
    </td>
    <?php $skr = array('mdmg', 'mspo', 'mcap', 'mdef');
          foreach($skr as $val) {
          $five = 1;
    ?>
    <td align="center">
        <table cellspacing="0" cellpadding="2" width="100%">
         <tbody>
          <?php foreach($avtdmg[$val] as $name => $val2){ ?>
            <tr <?php if($five%2 == 0){ echo 'class="ui-priority-secondary"'; } else { echo 'class="ui-widget-content"'; } ?>>
             <td><?=$name; ?></td>
             <td style="font-weight:bold;"><?=$val2; ?></td>
            </tr>
           <?php $five++;} ?>
         </tbody>
        </table>
    </td>
    <? };?>
    </tr>
   </tbody>
</table>
<br>
<?php

if($config['cron'] == 1 && $col_check > 2 && count($main_progress) > 0){ 
$h24total = $h24win = $h24lose = $h24alive = $h24spot = $h24des = $h24dmg = $h24cap = $h24def = $h24exp = 0;
//ничья ++
// ср. опыт ++
// кол-во игроков =(
// процент попадания =(
// макс опыт =(
        $jeys_array = array('win', 'lose', 'alive');
        foreach($main_progress as $name => $vals){
            foreach($vals as $key => $val){
              If (in_array($key, $jeys_array)) {
                   $val2 = substr($val, 32);
                   $val2 = substr($val2, 0, strlen($val2)-7);
                   $val3 = explode('<br>', $val2);
                   unset($val);
                   if (isset($val3[0])) $main_progress2[$name][$key] = $val3[0];
                   }
            }
        }
foreach($main_progress2 as $name => $vals){
    $h24win = $h24win + $vals['win'];
    $h24lose = $h24lose + $vals['lose'];
    $h24alive = $h24alive + $vals['alive'];
}

foreach($main_progress as $name => $vals){
    $h24total = $h24total + $vals['total'];
    $h24spot = $h24spot + $vals['spot'];
    $h24des = $h24win + $vals['des'];
    $h24dmg = $h24dmg + $vals['dmg'];
    $h24cap = $h24cap + $vals['cap'];
    $h24def = $h24def + $vals['def'];
    $h24exp = $h24exp + $vals['exp'];
    };
}; ?>

 <table cellspacing="1" cellpadding="2" width="100%" style="text-align:right" id="avt2">
   <thead>
    <tr style="font-weight: bold;">
    <th align="center" colspan="3">Общие показатели клана:</th>
    <th align="center" colspan="3">Боевые показатели клана:</th>
    <th align="center" colspan="3">Боевой опыт клана:</th>
    </tr>
    <tr style="font-weight: bold;">
    <th>&nbsp;</th>
        <th align="center">Сут</th>
        <th align="center">Общ</th>
    <th>&nbsp;</th>
        <th align="center">Сут</th>
        <th align="center">Общ</th>
    <th>&nbsp;</th>
        <th align="center">Сут</th>
        <th align="center">Общ</th>
    </tr>
   </thead>
   <tbody>
    <tr>
    <td align="left">Личный состав:</td>
    <td colspan="2" align="center"><b><?=$avt_memb ?></b></td>
    <td align="left">Обнаружено техники:</td>
    <td><?php if(isset($h24total)) {echo $h24spot;}else {echo "Н/Д";}; ?> (
        <?php if(isset($h24total)){
            if (round($h24spot/$h24total,2) >= round(array_sum($avt_seek)/array_sum($avt_games),2)) {
                echo $darkgreen;
                } else {
                echo $darkred;
                };
            echo round($h24spot/$h24total,2);  ?>
                </span>
        <?php }
        else{echo "Н/Д";}; ?>)</td>
    <td><?php echo array_sum($avt_seek) ?> (<?php echo round(array_sum($avt_seek)/array_sum($avt_games),2) ?>)</td>
    <td align="left">Суммарный опыт:</td>
    <td><?php if(isset($h24exp)) {echo $h24exp;}else {echo "Н/Д";}; ?></td>
    <td><?php echo array_sum($avt_summ) ?></td>
    </tr>

    <tr>
    <td align="left">Проведено боёв:</td>
    <td><?php if(isset($h24total)) {echo $h24total;}else {echo "Н/Д";}; ?></td>
    <td><?php echo array_sum($avt_games) ?></td>
    <td align="left">Уничтожено техники:</td>
    <td><?php if(isset($h24des)) {echo $h24des;}else {echo "Н/Д";}; ?> (
        <?php if(isset($h24des)){
            if (round($h24des/$h24total,2) >= round(array_sum($avt_dest)/array_sum($avt_games),2)) {
                echo $darkgreen;
                } else {
                echo $darkred;
                };
        echo round($h24des/$h24total,2);  ?>
        </span>
        <?php }
        else{echo "Н/Д";}; ?>)</td>
    <td><?php echo array_sum($avt_dest) ?> (<?php echo round(array_sum($avt_dest)/array_sum($avt_games),2) ?>)</td>
    <td align="left">Средний опыт за бой:</td>
    <td><?php if(isset($h24exp) && isset($h24total)) {
            if (round($h24exp/$h24total,2) >= round(array_sum($avt_summ)/array_sum($avt_games),2)) {
                echo $darkgreen;
                } else {
                echo $darkred;
                };
        echo round($h24exp/$h24total,2);  ?>
        </span>
        <?php }
        else{echo "Н/Д";}; ?></td>
    <td><?php echo round(array_sum($avt_summ)/array_sum($avt_games),2) ?></td>
    </tr>

    <tr>
    <td align="left">Побед:</td>
    <td><?php if(isset($h24win)) {echo $h24win;}else {echo "Н/Д";}; ?> (
        <?php if(isset($h24win) && isset($h24total)) {
            if (round($h24win/$h24total*100,2) >= round(array_sum($avt_win)/array_sum($avt_games)*100,2)) {
                echo $darkgreen;
                } else {
                echo $darkred;
                };
        echo round($h24win/$h24total*100,2); ?>
                %</span>
        <?php }
        else{echo "Н/Д";}; ?>)</td>
    <td><?php echo array_sum($avt_win) ?> (<?php echo round(array_sum($avt_win)/array_sum($avt_games)*100,2) ?>%)</td>
    <td align="left">Средний % попадания:</td>
    <td>Н/Д</td>
    <td><?php echo round(array_sum($avt_pers)/$avt_memb,2) ?>%</td>
    <td align="left">Максимальный опыт за бой:</td>
    <td>Н/Д</td>
    <td><?php echo max($avt_max) ?></td>
    </tr>
    <tr>
    <td align="left">Поражений:</td>
    <td><?php if(isset($h24lose)) {echo $h24lose;}else {echo "Н/Д";}; ?> (
        <?php if(isset($h24lose) && isset($h24total)) {
            if (round($h24lose/$h24total*100,2) >= round(array_sum($avt_lose)/array_sum($avt_games)*100,2)) {
                echo $darkgreen;
                } else {
                echo $darkred;
                };
        echo round($h24lose/$h24total*100,2); ?>
                %</span>
        <?php }
        else{echo "Н/Д";}; ?>)</td>
    <td><?php echo array_sum($avt_lose) ?> (<?php echo round(array_sum($avt_lose)/array_sum($avt_games)*100,2) ?>%)</td>
    <td align="left">Нанесенные повреждения:</td>
    <td><?php if(isset($h24dmg)) {echo $h24dmg;}else {echo "Н/Д";}; ?> (
        <?php if(isset($h24dmg) && isset($h24total)) {
            if (round($h24dmg/$h24total,2) >= round(array_sum($avt_dama)/array_sum($avt_games),2)) {
                echo $darkgreen;
                } else {
                echo $darkred;
                };
        echo round($h24dmg/$h24total,2); ?>
                </span>
        <?php }
        else{echo "Н/Д";}; ?>)</td>
    <td><?php echo array_sum($avt_dama) ?> (<?php echo round(array_sum($avt_dama)/array_sum($avt_games),2) ?>)</td>
    <td></td>
    <td></td>
    <td></td>
    </tr>
    <tr>
    <td align="left">Ничьих:</td>
    <td><?php if(isset($h24total) && isset($h24win) && isset($h24lose)) {echo (($h24total-$h24win)-$h24lose);}else {echo "Н/Д";}; ?> (
        <?php if(isset($h24win) && isset($h24lose) && isset($h24total)) {
            if (round((($h24total-$h24win)-$h24lose)/$h24total*100,2) >= round(((array_sum($avt_games)-array_sum($avt_win))-array_sum($avt_lose))/array_sum($avt_games)*100,2)) {
                echo $darkgreen;
                } else {
                echo $darkred;
                };
        echo round((($h24total-$h24win)-$h24lose)/$h24total*100,2); ?>
        %</span>
        <?php }
        else{echo "Н/Д";}; ?>)</td>
    <td><?php echo ((array_sum($avt_games)-array_sum($avt_win))-array_sum($avt_lose)) ?> (<?php echo round(((array_sum($avt_games)-array_sum($avt_win))-array_sum($avt_lose))/array_sum($avt_games)*100,2) ?>%)</td>
    <td align="left">Очки захвата базы:</td>
    <td><?php if(isset($h24cap)) {echo $h24cap;}else {echo "Н/Д";}; ?> (
        <?php if(isset($h24cap) && isset($h24total)) {
            if (round($h24cap/$h24total,2) >= round(array_sum($avt_capt)/array_sum($avt_games),2)) {
                echo $darkgreen;
                } else {
                echo $darkred;
                };
        echo round($h24cap/$h24total,2); ?>
                </span>
        <?php }
        else{echo "Н/Д";}; ?>)</td>
    <td><?php echo array_sum($avt_capt) ?> (<?php echo round(array_sum($avt_capt)/array_sum($avt_games),2) ?>)</td>
    <td></td>
    <td></td>
    <td></td>
    </tr>

    <tr>
    <td align="left">Выживаемость в битвах:</td>
    <td><?php if(isset($h24alive)) {echo $h24alive;}else {echo "Н/Д";}; ?> (
        <?php if(isset($h24alive) && isset($h24total)) {
            if (round($h24alive/$h24total*100,2) >= round(array_sum($avt_surv)/array_sum($avt_games)*100,2)) {
                echo $darkgreen;
                } else {
                echo $darkred;
                };
        echo round($h24alive/$h24total*100,2); ?>
                %</span>
        <?php }
        else{echo "Н/Д";}; ?>)</td>
    <td><?php echo array_sum($avt_surv) ?> (<?php echo round(array_sum($avt_surv)/array_sum($avt_games)*100,2) ?>%)</td>
    <td align="left">Очки защиты базы:</td>
    <td><?php if(isset($h24def)) {echo $h24def;}else {echo "Н/Д";}; ?> (
        <?php if(isset($h24def) && isset($h24total)) {
            if (round($h24def/$h24total,2) >= round(array_sum($avt_defe)/array_sum($avt_games),2)) {
                echo $darkgreen;
                } else {
                echo $darkred;
                };
        echo round($h24def/$h24total,2); ?>
                </span>
        <?php }
        else{echo "Н/Д";}; ?>)</td>
    <td><?php echo array_sum($avt_defe) ?> (<?php echo round(array_sum($avt_defe)/array_sum($avt_games),2) ?>)</td>
    <td></td>
    <td></td>
    <td></td>
    </tr>
   </tbody>
 </table>
<br>
    <?php if($config['cron'] == 1 && $col_check > 2 && count($main_progress) > 0){ ?>
        <table cellspacing="0" cellpadding="5" width="100%" >
            <thead style="font-weight: bold;">
                <tr>
                    <th align="center"><?=$lang['loosed_today']?></th>
                    <th align="center"><?=$lang['new_tanks']?></th>
                    <th align="center"><?=$lang['new_players']?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td valign="top">
                        <table cellspacing="2" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <?php if (count($we_loosed) > 0){
                                         foreach($we_loosed as $val){ ?>
                                        <tr>
                                            <td width="34%" align="left"><a href="<?php echo $config['base'].$val['name'].'/'; ?>" 
                                                    target="_blank"><?=$val['name'];?></a></td>
                                            <td width="33%" align="center"><?=$val['role']?></td>
                                            <td width="33%" align="center"><?php echo floor((time() - mktime(0, 0, 0, date("m", $val['member_since']), date("d", $val['member_since']), date("Y", $val['member_since'])))/(3600*24)); ?> <?=$lang['days']?></td>
                                        </tr>
                                        <?php }
                                          }else{ ?>
                                    <tr>
                                        <td align="center"><?=$lang['noone'];?></td>
                                    </tr>
                                    <?php } ?>
                            </tbody>
                        </table>
                    </td>
                    <td valign="top">
                        <table cellspacing="2" cellpadding="0" border="0" width="100%" style="position: relative;">
                            <tbody>
                                <?php $sql = "SELECT tank,nation,link FROM tanks;";
                                      $q = $db->prepare($sql);
                                      if ($q->execute() == TRUE) {
                                         $tanks_list = $q->fetchAll();
                                      } else {
                                         print_r($q->errorInfo());
                                         die();
                                      };
                                      if (count($new_tanks) > 0){
                                      foreach($new_tanks as $name => $val){
                                        if(isset($roster_id[$val]['name'])){ ?>
                                        <tr style="height:31px;">
                                            <td width="30%" align="left"><a href="<?php echo $config['base'].$roster_id[$val]['name'].'/'; ?>" 
                                                    target="_blank"><?=$roster_id[$val]['name'];?></a></td>
                                            <td width="30%" align="right">
                                              <?php echo $name; ?>
                                            <td width="40%" align="right">
                                              <?php
                                              foreach ($tanks_list as $tanks_list_key => $wall)
                                                 if ($wall['tank'] == $name) {
                                                    print_R('<img style="right: 50px; position: absolute;" src="'.$config['old'].'/static/3.6.0.1/common/img/nation/'.$wall['nation'].'.png" />');
                                                    print_R('<img style="right: 0px; position: absolute;" src="'.$config['old'].$wall['link'].'" />');};
                                              ?>
                                            </td>
                                        </tr>
                                        <?php }
                                          }}else{ ?>
                                    <tr>
                                        <td align="center">Новая техника отсутствует</td>
                                    </tr>
                                    <?php } ?>
                            </tbody>
                        </table>
                    </td>
                    <td valign="top">
                        <table cellspacing="2" cellpadding="0" border="0" width="100%">
                            <tbody> 
                                <?php if (count($new_players) > 0){
                                         foreach($new_players as $name => $val){ ?>
                                        <tr>
                                            <td width="34%" align="left"><a href="<?php echo $config['base'].$val['name'].'/'; ?>" 
                                                    target="_blank"><?=$val['name'];?></a></td>
                                            <td width="33%" align="center"><?=$val['role']?></td>
                                            <td width="33%" align="center"><?php echo floor((time() - mktime(0, 0, 0, date("m", $val['member_since']), date("d", $val['member_since']), date("Y", $val['member_since'])))/(3600*24)); ?><?=$lang['days']?></td>
                                        </tr>
                                        <?php }
                                          }else{ ?>
                                    <tr>
                                        <td align="center"><?=$lang['noone'];?></td>
                                    </tr>
                                    <?php } ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <? };?>
</div>