<div align="center">
 <?php $avt_memb = $new['data']['members_count'];
       $avt = rating($res, $lang);
       $avtdmg = $avt['rat'];
       $darkgreen = '<span style="color:DarkGreen;"><img style="vertical-align: -5%;" width="11" height="11" src="./images/up.png">&nbsp;';
       $darkred = '<span style="color:DarkRed;"><img style="vertical-align: -5%;" width="11" height="11" src="./images/down.png">&nbsp;';

   foreach($res as $name => $val){
    $avt_eff[round($eff_rating[$name],2)*100] = $name;
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
 krsort($avt_eff);

?>

<table cellspacing="0" cellpadding="0" width="100%" style="border-width: 0; ">
   <tbody>
    <tr>
      <td align="center">
        <table cellspacing="2" cellpadding="0" width="100%" id="avt2">
         <thead style="font-weight: bold;">
            <th align="center" colspan="2"><?=$lang['greeting_top1']; ?></th>
         </thead>
         <tbody>
          <?php
          $five = 1;
          foreach($avt_eff as $name => $val){ ?>
            <tr>
              <td><?=$val; ?></td>
              <td style="font-weight:bold;"><?=number_format($eff_rating[$val], 2, '.', '');?></td>
            </tr>
           <?php $five++;
                 if($five>5) break;} ?>
         </tbody>
        </table>
    </td>
    <td align="center">
        <table cellspacing="2" cellpadding="0" width="100%" id="avt3">
         <thead style="font-weight: bold;">
            <th align="center" colspan="2"><?=$lang['greeting_top2']; ?></th>
         </thead>
         <tbody>
          <?php foreach($avtdmg['mdmg'] as $name => $val){ ?>
            <tr> 
             <td><?=$name; ?></td>
             <td style="font-weight:bold;"><?=number_format($val, 2, '.', ''); ?></td>
            </tr>
           <?php } ?>
         </tbody>
        </table>
    </td>
    <td align="center">
        <table cellspacing="2" cellpadding="0" width="100%" id="avt4">
         <thead style="font-weight: bold;">
            <th align="center" colspan="2"><?=$lang['greeting_top3']; ?></th>
         </thead>
         <tbody>
          <?php foreach($avtdmg['mspo'] as $name => $val){ ?>
            <tr> 
             <td><?=$name; ?></td>
             <td style="font-weight:bold;"><?=$val; ?></td>
            </tr>
           <?php } ?>
         </tbody>
        </table>
    </td>
    <td align="center">
        <table cellspacing="2" cellpadding="0" width="100%" id="avt5">
         <thead style="font-weight: bold;">
            <th align="center" colspan="2"><?=$lang['greeting_top4']; ?></th>
         </thead>
         <tbody>
          <?php foreach($avtdmg['mcap'] as $name => $val){ ?>
            <tr> 
             <td><?=$name; ?></td>
             <td style="font-weight:bold;"><?=$val; ?></td>
            </tr>
           <?php } ?>
         </tbody>
        </table>
    </td>
    <td align="center">
        <table cellspacing="2" cellpadding="0" width="100%" id="avt6">
         <thead style="font-weight: bold;">
            <th align="center" colspan="2"><?=$lang['greeting_top5']; ?></th>
         </thead>
         <tbody>
          <?php foreach($avtdmg['mdef'] as $name => $val){ ?>
            <tr> 
             <td><?=$name; ?></td>
             <td style="font-weight:bold;"><?=$val; ?></td>
            </tr>
           <?php } ?>
         </tbody>
        </table>
    </td>
    </tr>
   </tbody>
</table>


<?php
$rowss=2;
if($config['cron'] == 1 && $col_check > 2 && count($main_progress['main']) > 0){ 
  $h24total = $h24win = $h24lose = $h24alive = $h24spot = $h24des = $h24dmg = $h24cap = $h24def = $h24exp = 0;
  foreach($main_progress['main'] as $name => $vals){
    $h24total += $vals['total'];
    $h24win   += $vals['win'];
    $h24lose  += $vals['lose'];
    $h24alive += $vals['alive'];
    $h24spot  += $vals['spot'];
    $h24des   += $vals['des'];
    $h24dmg   += $vals['dmg'];
    $h24cap   += $vals['cap'];
    $h24def   += $vals['def'];
    $h24exp   += $vals['exp'];
  };
  $rowss=3;
};
?>

<table cellspacing="1" width="100%" style="text-align:right; font-size: 0.75em !important" id="avt1">
  <thead>
    <tr style="font-weight: bold;">
      <th align="center" width="33%" colspan="<?=$rowss; ?>"><?=$lang['greeting_h1']; ?>:</th>
      <th align="center" width="37%" colspan="<?=$rowss; ?>"><?=$lang['greeting_h2']; ?>:</th>
      <th align="center" width="31%" colspan="<?=$rowss; ?>"><?=$lang['greeting_h3']; ?>:</th>
    </tr>
    <?php if ($rowss<>'2') { ?>
    <tr style="font-weight: bold;">
    <? for ($i=0; $i<3; $i++) {?>
        <th class="{sorter: false}">&nbsp;</th>
        <th align="center" class="{sorter: false}"><?=$lang['greeting_sub1']; ?></th>
        <th align="center" class="{sorter: false}"><?=$lang['greeting_sub2']; ?></th>
        <? } ?>
    </tr>
    <? } ?>
  </thead>
  <tbody>
    <tr>
     <td align="left"><?=$lang['roster']; ?>:</td>
     <td colspan="<?=($rowss-1); ?>" align="center"><strong><?=$avt_memb ?></strong></td>
     <td align="left"><?=$lang['spo']; ?>:</td>
     <?php if ($rowss<>'2') { ?>
     <td><?php
           echo $h24spot.' (';
           if(($h24total<>0) && ($avt_games<>0)){
             if (round($h24spot/$h24total,2) >= round(array_sum($avt_seek)/array_sum($avt_games),2)) {
                 echo $darkgreen;
                 } else {
                 echo $darkred;
                 };
             echo round($h24spot/$h24total,2);?>
                 </span>
         <?php } else {
                 echo "0";
               };?>)</td>
         <? } ?>
     <td><?php echo array_sum($avt_seek) ?> (<?php echo round(array_sum($avt_seek)/array_sum($avt_games),2) ?>)</td>
     <td align="left"><?=$lang['total_exp']; ?>:</td>
     <?php if ($rowss<>'2') { ?>
     <td><?=$h24exp;?></td>
     <? } ?>
     <td><?php echo array_sum($avt_summ) ?></td>
    </tr>

    <tr>
     <td align="left"><?=$lang['games_p']; ?>:</td>
     <?php if ($rowss<>'2') { ?>
     <td><?=$h24total;?></td>
     <? } ?>
     <td><?php echo array_sum($avt_games) ?></td>
     <td align="left"><?=$lang['destroyed']; ?>:</td>
     <?php if ($rowss<>'2') { ?>
     <td><?php
           echo $h24des.' (';
           if(($h24total<>0) && ($avt_games<>0)){
             if (round($h24des/$h24total,2) >= round(array_sum($avt_dest)/array_sum($avt_games),2)) {
                 echo $darkgreen;
                 } else {
                 echo $darkred;
                 };
             echo round($h24des/$h24total,2);  ?>
                 </span>
         <?php } else {
                 echo "0";
               }; ?>)</td>
         <? } ?>
     <td><?php echo array_sum($avt_dest) ?> (<?php echo round(array_sum($avt_dest)/array_sum($avt_games),2) ?>)</td>
     <td align="left"><?=$lang['averag_exp']; ?>:</td>
     <?php if ($rowss<>'2') { ?>
     <td><?php
           echo $h24exp.' (';
           if(($h24total<>0) && ($avt_games<>0)){
             if (round($h24exp/$h24total,2) >= round(array_sum($avt_summ)/array_sum($avt_games),2)) {
                 echo $darkgreen;
                 } else {
                 echo $darkred;
                 };
             echo round($h24exp/$h24total,2);  ?>
                 </span>
         <?php } else {
                 echo "0";
               }; ?>)</td>
         <? } ?>
     <td><?php echo round(array_sum($avt_summ)/array_sum($avt_games),2) ?></td>
    </tr>

    <tr>
     <td align="left"><?=$lang['victories']; ?>:</td>
     <?php if ($rowss<>'2') { ?>
     <td><?php
           echo $h24win.' (';
           if(($h24total<>0) && ($avt_games<>0)){
             if (round($h24win/$h24total,2) >= round(array_sum($avt_win)/array_sum($avt_games),2)) {
                 echo $darkgreen;
                 } else {
                 echo $darkred;
                 };
             echo round($h24win/$h24total,2).'%';?>
             </span>
         <?php } else {
                 echo "0";
               }; ?>)</td>
         <? } ?>
     <td><?php echo array_sum($avt_win) ?> (<?php echo round(array_sum($avt_win)/array_sum($avt_games)*100,2) ?>%)</td>
     <td align="left"><?=$lang['averag_hit_ratio']; ?>:</td>
     <td colspan="<?=($rowss-1); ?>" align="center"><?php echo round(array_sum($avt_pers)/$avt_memb,2) ?>%</td>
     <td align="left"><?=$lang['max_exp']; ?>:</td>
     <td colspan="<?=($rowss-1); ?>" align="center"><?php echo max($avt_max) ?></td>
    </tr>

    <tr>
     <td align="left"><?=$lang['defeats']; ?>:</td>
     <?php if ($rowss<>'2') { ?>
     <td><?php
           echo $h24lose.' (';
           if(($h24total<>0) && ($avt_games<>0)){
             if (round($h24lose/$h24total,2) >= round(array_sum($avt_lose)/array_sum($avt_games),2)) {
                 echo $darkgreen;
                 } else {
                 echo $darkred;
                 };
             echo round($h24lose/$h24total,2);  ?>
                 %</span>
         <?php } else {
                 echo "0";
               }; ?>)</td>
         <? } ?>
     <td><?php echo array_sum($avt_lose) ?> (<?php echo round(array_sum($avt_lose)/array_sum($avt_games)*100,2) ?>%)</td>
     <td align="left"><?=$lang['damage']; ?>:</td>
     <?php if ($rowss<>'2') { ?>
     <td><?php
           echo $h24dmg.' (';
           if(($h24total<>0) && ($avt_games<>0)){
             if (round($h24dmg/$h24total,2) >= round(array_sum($avt_lose)/array_sum($avt_games),2)) {
                 echo $darkgreen;
                 } else {
                 echo $darkred;
                 };
             echo round($h24dmg/$h24total,2);  ?>
                 </span>
         <?php } else {
                 echo "0";
               }; ?>)</td>
         <? } ?>
     <td><?php echo array_sum($avt_dama) ?> (<?php echo round(array_sum($avt_dama)/array_sum($avt_games),2) ?>)</td>
     <td colspan="<?=$rowss; ?>"></td>
    </tr>
    <tr>
     <td align="left"><?=$lang['dead_heat']; ?>:</td>
     <?php if ($rowss<>'2') { ?>
     <td><?php
           print_R((($h24total-$h24win)-$h24lose).' (');
           if(($h24total<>0) && ($avt_games<>0)){
             if (round((($h24total-$h24win)-$h24lose)/$h24total*100,2) >= round(((array_sum($avt_games)-array_sum($avt_win))-array_sum($avt_lose))/array_sum($avt_games)*100,2)) {                 echo $darkgreen;
                 } else {
                 echo $darkred;
                 };
             echo round((($h24total-$h24win)-$h24lose)/$h24total*100,2); ?>
                 %</span>
         <?php } else {
                 echo "0";
               }; ?>)</td>
         <? } ?>
     <td><?php echo ((array_sum($avt_games)-array_sum($avt_win))-array_sum($avt_lose)) ?> (<?php echo round(((array_sum($avt_games)-array_sum($avt_win))-array_sum($avt_lose))/array_sum($avt_games)*100,2) ?>%)</td>
     <td align="left"><?=$lang['capture']; ?>:</td>
     <?php if ($rowss<>'2') { ?>
     <td><?php
           echo $h24cap.' (';
           if(($h24total<>0) && ($avt_games<>0)){
             if (round($h24cap/$h24total,2) >= round(array_sum($avt_capt)/array_sum($avt_games),2)) {
                 echo $darkgreen;
                 } else {
                 echo $darkred;
                 };
             echo round($h24cap/$h24total,2);  ?>
                 </span>
         <?php } else {
                 echo "0";
               }; ?>)</td>
         <? } ?>
     <td><?php echo array_sum($avt_capt) ?> (<?php echo round(array_sum($avt_capt)/array_sum($avt_games),2) ?>)</td>
     <td colspan="<?=$rowss; ?>"></td>
    </tr>

    <tr>
     <td align="left"><?=$lang['averag_surv']; ?>:</td>
     <?php if ($rowss<>'2') { ?>
     <td><?php
           echo $h24alive.' (';
           if(($h24total<>0) && ($avt_games<>0)){
             if (round($h24alive/$h24total,2) >= round(array_sum($avt_surv)/array_sum($avt_games),2)) {
                 echo $darkgreen;
                 } else {
                 echo $darkred;
                 };
             echo round($h24alive/$h24total,2);  ?>
                 %</span>
         <?php } else {
                 echo "0";
               }; ?>)</td>
         <? } ?>
     <td><?php echo array_sum($avt_surv) ?> (<?php echo round(array_sum($avt_surv)/array_sum($avt_games)*100,2) ?>%)</td>
     <td align="left"><?=$lang['defend']; ?>:</td>
     <?php if ($rowss<>'2') { ?>
     <td><?php
           echo $h24def.' (';
           if(($h24total<>0) && ($avt_games<>0)){
             if (round($h24def/$h24total,2) >= round(array_sum($avt_defe)/array_sum($avt_games),2)) {
                 echo $darkgreen;
                 } else {
                 echo $darkred;
                 };
             echo round($h24def/$h24total,2);  ?>
                 </span>
         <?php } else {
                 echo "0";
               }; ?>)</td>
         <? } ?>
     <td><?php echo array_sum($avt_defe) ?> (<?php echo round(array_sum($avt_defe)/array_sum($avt_games),2) ?>)</td>
     <td colspan="<?=$rowss; ?>"></td>
    </tr>
   </tbody>
 </table>

    <?php if($config['cron'] == 1 && $col_check > 2 && count($main_progress['main']) > 0){ ?>
        <table cellspacing="0" cellpadding="0" width="100%" style="border-width: 0; ">
            <tbody>
                <tr>
                    <td valign="top" width="30%">
                        <table cellspacing="2" cellpadding="0" width="100%" id="avt7">
                            <thead style="font-weight: bold;">
                               <th colspan="4" align="center"><?=$lang['loosed_today'];?></th>
                            </thead>
                            <tbody>
                                <?php if (count($we_loosed) > 0){
                                         foreach($we_loosed as $val){ ?>
                                        <tr>
                                            <td align="left"><a href="<?php echo $config['base'].$val['name'].'/'; ?>"
                                                    target="_blank"><?=$val['name'];?></a></td>
                                            <td align="center"><?=$lang[$val['role']];?></td>
                                            <td align="center"><?php echo floor(($val['up'] - mktime(0, 0, 0, date("m", $val['member_since']), date("d", $val['member_since']), date("Y", $val['member_since'])))/(3600*24)),$lang['days'];?></td>
                                            <td align="center"><?php echo date('d.m.Y',$val['up']);?></td>
                                        </tr>
                                        <?php }
                                          }else{ ?>
                                    <tr>
                                        <td colspan="4" align="center"><?=$lang['noone'];?></td>
                                    </tr>
                                    <?php } ?>
                            </tbody>
                        </table>
                    </td>
                    <td valign="top">
                        <table cellspacing="2" cellpadding="0" width="100%" id="avt8" style="position: relative;">
                            <thead style="font-weight: bold;">
                               <th colspan="3" align="center"><?=$lang['new_tanks'];?></th>
                            </thead>
                            <tbody>
                                <?php $sql = "SELECT tank,title,nation,link FROM `tanks`;";
                                      $q = $db->prepare($sql);
                                      if ($q->execute() == TRUE) {
                                         $tanks_list = $q->fetchAll();
                                      } else {
                                         print_r($q->errorInfo());
                                         die();
                                      };
                                      if (count($new_tanks) > 0){
                                      foreach($new_tanks as $val){
                                        if(isset($roster_id[$val['account_id']]['account_id'])){ ?>
                                        <tr style="height:31px;">
                                            <td align="left"><a href="<?php echo $config['base'].$roster_id[$val['account_id']]['account_name'].'/'; ?>"
                                                    target="_blank"><?=$roster_id[$val['account_id']]['account_name'];?></a></td>
                                            <td align="right">
                                              <?=$val['tank']; ?>
                                            </td>
                                            <td width="130px" align="right">
                                              <?php
                                              foreach ($tanks_list as $tanks_list_key => $wall)
                                                 if ($wall['title'] == $val['title']) {
                                                    print_R('<img src="http://'.$config['gm_url'].'/static/3.6.0.1/common/img/nation/'.$wall['nation'].'.png" />');
                                                    print_R('<img style="right: -50px; position: absolute;" src="http://'.$config['gm_url'].$wall['link'].'" />');};
                                              ?>
                                            </td>
                                        </tr>
                                        <?php }
                                          }}else{ ?>
                                    <tr>
                                        <td colspan="3" align="center"><?=$lang['no_new']; ?></td>
                                    </tr>
                                    <?php } ?>
                            </tbody>
                        </table>
                    </td>
                    <td valign="top" width="30%">
                        <table cellspacing="2" cellpadding="0" width="100%" id="avt9">
                            <thead style="font-weight: bold;">
                               <th colspan="4" align="center"><?=$lang['new_players'];?></th>
                            </thead>
                            <tbody> 
                                <?php if (count($new_players) > 0){
                                         foreach($new_players as $name => $val){ ?>
                                        <tr>
                                            <td align="left"><a href="<?php echo $config['base'].$val['name'].'/'; ?>"
                                                    target="_blank"><?=$val['name'];?></a></td>
                                            <td align="center"><?=$lang[$val['role']];?></td>
                                            <td align="center"><?php echo floor((time() - mktime(0, 0, 0, date("m", $val['member_since']), date("d", $val['member_since']), date("Y", $val['member_since'])))/(3600*24)),$lang['days'];?></td>
                                            <td align="center"><?php echo date('d.m.Y',$val['member_since']);?></td>
                                        </tr>
                                        <?php }
                                          }else{ ?>
                                    <tr>
                                        <td colspan="4" align="center"><?=$lang['noone'];?></td>
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