<div align="center">
  <?php
    $darkgreen = '<span style="color:DarkGreen;"><img style="vertical-align: -5%;" width="11" height="11" src="./images/up.png">&nbsp;';
    $darkred = '<span style="color:DarkRed;"><img style="vertical-align: -5%;" width="11" height="11" src="./images/down.png">&nbsp;';
    $avt = rating($res, $lang);
    $avtdmg = $avt['rat'];
    $avt_ = array('total', 'win', 'lose', 'alive', 'spot', 'des', 'hit_r', 'dmg', 'cap', 'def', 'exp');
    $avt_ = array_fill_keys($avt_, 0);
    foreach($res as $name => $val){
      $avt_eff[$eff_rating[$name]['eff']] = $name;
      $avt_mexp[] = $val['exp'][$lang['exp_max']];
      $avt_['total'] += $val['overall'][$lang['total']];
      $avt_['win']   += $val['overall'][$lang['victories']];
      $avt_['lose']  += $val['overall'][$lang['defeats']];
      $avt_['alive'] += $val['overall'][$lang['alive']];
      $avt_['spot']  += $val['perform'][$lang['spotted']];
      $avt_['des']   += $val['perform'][$lang['destroyed']];
      $avt_['hit_r'] += $val['perform'][$lang['hit_ratio']];
      $avt_['dmg']   += $val['perform'][$lang['damage']];
      $avt_['cap']   += $val['perform'][$lang['capture']];
      $avt_['def']   += $val['perform'][$lang['defend']];
      $avt_['exp']   += $val['exp'][$lang['total_exp']];
    }

    krsort($avt_eff);
    $avt_eff = array_slice($avt_eff, 0, 5, true);
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
          <?php foreach($avt_eff as $name => $val){ ?>
            <tr>
              <td><?=$val; ?></td>
              <td style="font-weight:bold;"><?=$eff_rating[$val]['eff'];?></td>
            </tr>
           <?php } ?>
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
    <?php $sub = array ('4' => 'mspo', '5' => 'mcap', '6' => 'mdef');
      for ($i=4; $i<=6; $i++) { ?>
        <td align="center">
          <table cellspacing="2" cellpadding="0" width="100%" id="avt<?=$i; ?>">
           <thead style="font-weight: bold;">
              <th align="center" colspan="2"><?=$lang['greeting_top'.($i-1)]; ?></th>
           </thead>
           <tbody>
             <?php foreach($avtdmg[$sub[$i]] as $name => $val){ ?>
               <tr>
                <td><?=$name; ?></td>
                <td style="font-weight:bold;"><?=$val; ?></td>
               </tr>
             <?php } ?>
           </tbody>
          </table>
        </td>
      <? }?>
    </tr>
   </tbody>
</table>

<?php
$rowss=2;
if($config['cron'] == 1 && $col_check > 2 && count($main_progress['main']) > 0){ 
  $h24total = $main_progress['total']['total'];
  $h24win   = $main_progress['total']['win'];
  $h24lose  = $main_progress['total']['lose'];
  $h24alive = $main_progress['total']['alive'];
  $h24spot  = $main_progress['total']['spot'];
  $h24des   = $main_progress['total']['des'];
  $h24dmg   = $main_progress['total']['dmg'];
  $h24cap   = $main_progress['total']['cap'];
  $h24def   = $main_progress['total']['def'];
  $h24exp   = $main_progress['total']['exp'];
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
     <td align="left"><span class="hidden">1</span><?=$lang['roster']; ?>:</td>
     <td colspan="<?=($rowss-1); ?>" align="center"><strong><?=$new['data']['members_count']; ?></strong></td>
     <td align="left"><?=$lang['spo']; ?>:</td>
     <?php if ($rowss<>'2') { ?>
     <td><?php
           echo $h24spot,' (';
           if(($h24total<>0) && ($avt_['total']<>0)){
             if (round($h24spot/$h24total,2) >= round($avt_['spot']/$avt_['total'],2)) {
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
     <td><?=$avt_['spot']; ?> (<?php echo round($avt_['spot']/$avt_['total'],2) ?>)</td>
     <td align="left"><?=$lang['total_exp']; ?>:</td>
     <?php if ($rowss<>'2') { ?>
     <td><?=$h24exp;?></td>
     <? } ?>
     <td><?=$avt_['exp']; ?></td>
    </tr>

    <tr>
     <td align="left"><span class="hidden">2</span><?=$lang['games_p']; ?>:</td>
     <?php if ($rowss<>'2') { ?>
     <td><?=$h24total;?></td>
     <? } ?>
     <td><?=$avt_['total']; ?></td>
     <td align="left"><?=$lang['destroyed']; ?>:</td>
     <?php if ($rowss<>'2') { ?>
     <td><?php
           echo $h24des,' (';
           if(($h24total<>0) && ($avt_['total']<>0)){
             if (round($h24des/$h24total,2) >= round($avt_['des']/$avt_['total'],2)) {
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
     <td><?=$avt_['des'] ?> (<?php echo round($avt_['des']/$avt_['total'],2) ?>)</td>
     <td align="left"><?=$lang['averag_exp']; ?>:</td>
     <?php if ($rowss<>'2') { ?>
     <td><?php
           echo $h24exp,' (';
           if(($h24total<>0) && ($avt_['total']<>0)){
             if (round($h24exp/$h24total,2) >= round($avt_['exp']/$avt_['total'],2)) {
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
     <td><?php echo round($avt_['exp']/$avt_['total'],2) ?></td>
    </tr>

    <tr>
     <td align="left"><span class="hidden">3</span><?=$lang['victories']; ?>:</td>
     <?php if ($rowss<>'2') { ?>
     <td><?php
           echo $h24win,' (';
           if(($h24total<>0) && ($avt_['total']<>0)){
             if (round($h24win/$h24total*100,2) >= round($avt_['win']/$avt_['total']*100,2)) {
                 echo $darkgreen;
                 } else {
                 echo $darkred;
                 };
             echo round($h24win/$h24total*100,2);?>
             %</span>
         <?php } else {
                 echo "0";
               }; ?>)</td>
         <? } ?>
     <td><?=$avt_['win']; ?> (<?php echo round($avt_['win']/$avt_['total']*100,2) ?>%)</td>
     <td align="left"><?=$lang['averag_hit_ratio']; ?>:</td>
     <td colspan="<?=($rowss-1); ?>" align="center"><?php echo round($avt_['hit_r']/$new['data']['members_count'],2) ?>%</td>
     <td align="left"><?=$lang['max_exp']; ?>:</td>
     <td colspan="<?=($rowss-1); ?>" align="center"><?php echo max($avt_mexp) ?></td>
    </tr>

    <tr>
     <td align="left"><span class="hidden">4</span><?=$lang['defeats']; ?>:</td>
     <?php if ($rowss<>'2') { ?>
     <td><?php
           echo $h24lose,' (';
           if(($h24total<>0) && ($avt_['total']<>0)){
             if (round($h24lose/$h24total*100,2) >= round($avt_['lose']/$avt_['total']*100,2)) {
                 echo $darkgreen;
                 } else {
                 echo $darkred;
                 };
             echo round($h24lose/$h24total*100,2);?>
             %</span>
         <?php } else {
                 echo "0";
               }; ?>)</td>
         <? } ?>
     <td><?=$avt_['lose']; ?> (<?php echo round($avt_['lose']/$avt_['total']*100,2) ?>%)</td>
     <td align="left"><?=$lang['damage']; ?>:</td>
     <?php if ($rowss<>'2') { ?>
     <td><?php
           echo $h24dmg,' (';
           if(($h24total<>0) && ($avt_['total']<>0)){
             if (round($h24dmg/$h24total,2) >= round($avt_['dmg']/$avt_['total'],2)) {
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
     <td><?=$avt_['dmg']; ?> (<?php echo round($avt_['dmg']/$avt_['total'],2) ?>)</td>
     <td colspan="<?=$rowss; ?>"></td>
    </tr>
    <tr>
     <td align="left"><span class="hidden">5</span><?=$lang['dead_heat']; ?>:</td>
     <?php if ($rowss<>'2') { ?>
     <td><?php
           echo ($h24total-$h24win-$h24lose),' (';
           if(($h24total<>0) && ($avt_['total']<>0)){
             if (round((($h24total-$h24win)-$h24lose)/$h24total*100,2) >= round(($avt_['total']-$avt_['win']-$avt_['lose'])/$avt_['total']*100,2)) {
                 echo $darkgreen;
                 } else {
                 echo $darkred;
                 };
             echo round((($h24total-$h24win)-$h24lose)/$h24total*100,2); ?>
             %</span>
         <?php } else {
                 echo "0";
               }; ?>)</td>
         <? } ?>
     <td><?php echo ($avt_['total']-$avt_['win']-$avt_['lose']) ?> (<?php echo round(($avt_['total']-$avt_['win']-$avt_['lose'])/$avt_['total']*100,2) ?>%)</td>
     <td align="left"><?=$lang['capture']; ?>:</td>
     <?php if ($rowss<>'2') { ?>
     <td><?php
           echo $h24cap,' (';
           if(($h24total<>0) && ($avt_['total']<>0)){
             if (round($h24cap/$h24total,2) >= round($avt_['cap']/$avt_['total'],2)) {
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
     <td><?=$avt_['cap']; ?> (<?php echo round($avt_['cap']/$avt_['total'],2) ?>)</td>
     <td colspan="<?=$rowss; ?>"></td>
    </tr>

    <tr>
     <td align="left"><span class="hidden">6</span><?=$lang['averag_surv']; ?>:</td>
     <?php if ($rowss<>'2') { ?>
     <td><?php
           echo $h24alive,' (';
           if(($h24total<>0) && ($avt_['total']<>0)){
             if (round($h24alive/$h24total*100,2) >= round($avt_['alive']/$avt_['total']*100,2)) {
                 echo $darkgreen;
                 } else {
                 echo $darkred;
                 };
             echo round($h24alive/$h24total*100,2);  ?>
             %</span>
         <?php } else {
                 echo "0";
               }; ?>)</td>
         <? } ?>
     <td><?=$avt_['alive']; ?> (<?php echo round($avt_['alive']/$avt_['total']*100,2) ?>%)</td>
     <td align="left"><?=$lang['defend']; ?>:</td>
     <?php if ($rowss<>'2') { ?>
     <td><?php
           echo $h24def,' (';
           if(($h24total<>0) && ($avt_['total']<>0)){
             if (round($h24def/$h24total,2) >= round($avt_['def']/$avt_['total'],2)) {
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
     <td><?=$avt_['def']; ?> (<?php echo round($avt_['def']/$avt_['total'],2) ?>)</td>
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
                               <tr>
                                 <th colspan="4" align="center"><?=$lang['loosed_today'];?></th>
                               </tr>
                            </thead>
                            <tbody>
                                <?php if (count($we_loosed) > 0){
                                         foreach($we_loosed as $val){ ?>
                                        <tr>
                                            <td align="left"><a href="<?php echo $config['base'],$val['name'],'/'; ?>"
                                                    target="_blank"><?=$val['name'];?></a></td>
                                            <td align="center"><?=$val['role'];?></td>
                                            <td align="center"><?php echo floor(($val['up'] - mktime(0, 0, 0, date("m", $val['member_since']), date("d", $val['member_since']), date("Y", $val['member_since'])))/(3600*24)),$lang['days'];?></td>
                                            <td align="center"><span class="hidden"><?=$val['up'];?></span><?php echo date('d.m.Y',$val['up']);?></td>
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
                                <?php if (count($new_tanks) > 0){
                                      foreach($new_tanks as $val){ ?>
                                        <tr style="height:31px;">
                                            <td align="left"><a href="<?php echo $config['base'],$roster_id[$val['account_id']]['account_name'],'/'; ?>"
                                                    target="_blank"><?=$roster_id[$val['account_id']]['account_name'];?></a></td>
                                            <td align="right">
                                              <?=$val['tank']; ?>
                                            </td>
                                            <td width="130px" align="right">
                                              <?php echo '<img src="http://',$config['gm_url'],'/static/3.6.0.1/common/img/nation/',$val['nation'],'.png" />',
                                                         '<img style="right: -50px; position: absolute;" src="http://',$config['gm_url'],$val['link'],'" />'; ?>
                                            </td>
                                        </tr>
                                        <?php } } else { ?>
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
                              <tr>
                               <th colspan="4" align="center"><?=$lang['new_players'];?></th>
                              </tr>
                            </thead>
                            <tbody> 
                                <?php if (count($new_players) > 0){
                                         foreach($new_players as $name => $val){ ?>
                                        <tr>
                                            <td align="left"><a href="<?php echo $config['base'],$val['name'],'/'; ?>"
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
<?php unset($avtdmg,$avt_eff,$avt,$avt_,$avt_mexp); ?>