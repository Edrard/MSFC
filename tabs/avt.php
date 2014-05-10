<div align="center">
  <?php
    $darkgreen = '<span style="color:DarkGreen;"><img style="vertical-align: -5%;" width="11" height="11" src="./images/up.png">&nbsp;';
    $darkred = '<span style="color:DarkRed;"><img style="vertical-align: -5%;" width="11" height="11" src="./images/down.png">&nbsp;';
    $avt = rating($res, $config);
    $avtdmg = $avt['rat'];
    $avt_ = array('battles', 'wins', 'losses', 'draws', 'survived_battles', 'spotted', 'frags',
                  'hits_percents', 'damage_dealt', 'damage_received', 'capture_points', 'dropped_capture_points', 'xp');
    $avt_ = array_fill_keys($avt_, 0);
    foreach ($avt_ as $key =>$val)
       foreach($res as $name => $val)
          $avt_[$key] += $val['data']['statistics']['all'][$key];
    $aeff = 0;
    foreach($res as $name => $val){
      $avt_eff[$eff_rating[$name]['eff']] = $name;
      $aeff += $eff_rating[$name]['eff'];
      $avt_mexp[] = $val['data']['statistics']['max_xp'];
    }

    krsort($avt_eff);
    $avt_eff = array_slice($avt_eff, 0, $config['top'], true);
    if ($config['top'] >0) {
?>

<table cellspacing="0" cellpadding="0" width="100%" style="border-width: 0; ">
   <tbody>
    <tr>
      <td align="center">
        <table cellspacing="2" cellpadding="0" width="100%" id="avt2">
         <thead style="font-weight: bold;">
            <th align="center" colspan="2"><?=$lang['greeting_top'],$config['top'],$lang['greeting_top1']; ?></th>
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
            <th align="center" colspan="2"><?=$lang['greeting_top'],$config['top'],$lang['greeting_top2']; ?></th>
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
              <th align="center" colspan="2"><?=$lang['greeting_top'],$config['top'],$lang['greeting_top'.($i-1)]; ?></th>
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
}
$rowss=2;
if($config['cron'] == 1 && $col_check > 2 && count($main_progress['main']) > 0){
  foreach ($avt_ as $key =>$val) {
     $h24[$key] = $main_progress['totaldiff']['all'][$key];
  }
  $rowss=3;
};
?>

<table cellspacing="1" width="100%" style="text-align:right; font-size: 0.75em !important" id="avt1">
  <thead>
    <tr style="font-weight: bold;">
      <th align="center" width="33%" colspan="<?=$rowss; ?>"><?=$lang['greeting_h1']; ?>:</th>
      <th align="center" width="40%" colspan="<?=$rowss; ?>"><?=$lang['greeting_h2']; ?>:</th>
      <th align="center" width="28%" colspan="<?=$rowss; ?>"><?=$lang['greeting_h3']; ?>:</th>
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
      <td colspan="<?=($rowss-1); ?>" align="center"><strong><?=$multiclan_info[$config['clan']]['data'][$config['clan']]['members_count']; ?></strong></td>
      <td align="left"><?=$lang['averag_hit_ratio']; ?>:</td>
      <td colspan="<?=($rowss-1); ?>" align="center"><?php echo round($avt_['hits_percents']/$multiclan_info[$config['clan']]['data'][$config['clan']]['members_count'],2) ?>%</td>
      <td align="left"><?=$lang['max_xp']; ?>:</td>
      <td colspan="<?=($rowss-1); ?>" align="center"><?php echo max($avt_mexp) ?></td>
    </tr>

    <tr>
      <?php
        echo '<td align="left"><span class="hidden">2</span>',$lang['avr_eff_ret'],':</td>';
        echo '<td colspan="',$rowss-1,'" align="center">',round($aeff/$multiclan_info[$config['clan']]['data'][$config['clan']]['members_count'],2),'</td>';
        echo '<td align="left">',$lang['all_frags'],':</td>';
        if ($rowss<>'2') {
            echo '<td>',$h24['frags'],' (';
            if (($h24['battles']<>0) && ($avt_['battles']<>0)){
                 if (round($h24['frags']/$h24['battles'],2) >= round($avt_['frags']/$avt_['battles'],2)) {
                     echo $darkgreen;
                 }   else {
                     echo $darkred;
                 };
                 echo round($h24['frags']/$h24['battles'],2),'</span>';
            }    else {
                 echo '0';
            };
            echo ')</td>';
        }
        echo '<td>'.$avt_['frags'],' (',round($avt_['frags']/$avt_['battles'],2),')</td>';
        echo '<td align="left">',$lang['all_xp'],':</td>';
        if ($rowss<>'2') {
            echo '<td>',$h24['xp'],'</td>';
        }
        echo '<td>',$avt_['xp'],'</td>';?>
    </tr>

    <tr>
      <?php
        echo '<td align="left"><span class="hidden">3</span>',$lang['games_p'],':</td>';
        if ($rowss<>'2') {
            echo '<td>',$h24['battles'],'</td>';
        }
        echo '<td>'.$avt_['battles'],'</td>';
        echo '<td align="left">',$lang['all_spotted'],':</td>';
        if ($rowss<>'2') {
            echo '<td>',$h24['spotted'],' (';
            if  (($h24['battles']<>0) && ($avt_['battles']<>0)){
                  if (round($h24['spotted']/$h24['battles'],2) >= round($avt_['spotted']/$avt_['battles'],2)) {
                      echo $darkgreen;
                  }   else {
                      echo $darkred;
                  };
                  echo round($h24['spotted']/$h24['battles'],2),'</span>';
            }     else {
                  echo '0';
            }
            echo ')</td>';
        }
        echo '<td>',$avt_['spotted'],' (',round($avt_['spotted']/$avt_['battles'],2),')</td>';
        echo '<td align="left">',$lang['all_battle_avg_xp'],':</td>';
        if ($rowss<>'2') {
            echo '<td>';
            if (($h24['battles']<>0) && ($avt_['battles']<>0)){
                 if (round($h24['xp']/$h24['battles'],2) >= round($avt_['xp']/$avt_['battles'],2)) {
                     echo $darkgreen;
                 }   else {
                     echo $darkred;
                 };
                 echo round($h24['xp']/$h24['battles'],2),'</span>';
            }    else {
                 echo '0';
            };
            echo '</td>';
        }
        echo '<td>',round($avt_['xp']/$avt_['battles'],2),'</td>'; ?>
    </tr>

    <tr>
      <?php
        echo '<td align="left"><span class="hidden">4</span>',$lang['all_wins'],':</td>';
        if ($rowss<>'2') {
            echo '<td>',$h24['wins'],' (';
            if (($h24['battles']<>0) && ($avt_['battles']<>0)){
                 if (round($h24['wins']/$h24['battles']*100,2) >= round($avt_['wins']/$avt_['battles']*100,2)) {
                     echo $darkgreen;
                 }   else {
                     echo $darkred;
                 };
                 echo round($h24['wins']/$h24['battles']*100,2),'%</span>';
            }    else {
                 echo '0';
            };
            echo ')</td>';
        }
        echo '<td>',$avt_['wins'],' (',round($avt_['wins']/$avt_['battles']*100,2),'%)</td>';
        echo '<td align="left">',$lang['all_damage_dealt'],':</td>';
        if ($rowss<>'2') {
            echo '<td>',$h24['damage_dealt'],' (';
            if (($h24['battles']<>0) && ($avt_['battles']<>0)){
                 if (round($h24['damage_dealt']/$h24['battles'],2) >= round($avt_['damage_dealt']/$avt_['battles'],2)) {
                     echo $darkgreen;
                 }   else {
                     echo $darkred;
                 };
                 echo round($h24['damage_dealt']/$h24['battles'],2),'</span>';
            }    else {
                 echo '0';
            };
            echo ')</td>';
        }
        echo '<td>',$avt_['damage_dealt'],' (',round($avt_['damage_dealt']/$avt_['battles'],2),')</td>';?>
        <td colspan="<?=$rowss; ?>"></td>
    </tr>

    <tr>
      <?php
        echo '<td align="left"><span class="hidden">5</span>',$lang['all_losses'],':</td>';
        if ($rowss<>'2') {
            echo '<td>',$h24['losses'],' (';
            if  (($h24['battles']<>0) && ($avt_['battles']<>0)){
                  if (round($h24['losses']/$h24['battles']*100,2) >= round($avt_['losses']/$avt_['battles']*100,2)) {
                      echo $darkgreen;
                  }   else {
                      echo $darkred;
                  };
                  echo round($h24['losses']/$h24['battles']*100,2),'%</span>';
            }     else {
                  echo '0';
            };
            echo ')</td>';
        }
        echo '<td>',$avt_['losses'],' (',round($avt_['losses']/$avt_['battles']*100,2),'%)</td>';
        echo '<td align="left">',$lang['all_damage_received'],':</td>';
        if ($rowss<>'2') {
            echo '<td>',$h24['damage_received'],' (';
            if  (($h24['battles']<>0) && ($avt_['battles']<>0)){
                  if (round($h24['damage_received']/$h24['battles'],2) >= round($avt_['damage_received']/$avt_['battles'],2)) {
                      echo $darkgreen;
                  }   else {
                      echo $darkred;
                  };
                  echo round($h24['damage_received']/$h24['battles'],2),'</span>';
            }     else {
                  echo '0';
            };
            echo ')</td>';
        }
        echo '<td>',$avt_['damage_received'],' (',round($avt_['damage_received']/$avt_['battles'],2),')</td>';?>
        <td colspan="<?=$rowss; ?>"></td>
    </tr>

    <tr>
      <?php
        echo '<td align="left"><span class="hidden">6</span>',$lang['all_draws'],':</td>';
        if ($rowss<>'2') {
            echo '<td>',$h24['draws'],' (';
            if (($h24['battles']<>0) && ($avt_['battles']<>0)){
                 if (round($h24['draws']*100/$h24['battles'],2) >= round($avt_['draws']*100/$avt_['battles'],2)) {
                     echo $darkgreen;
                 }   else {
                     echo $darkred;
                 };
                 echo round($h24['draws']*100/$h24['battles'],2),'%</span>';
            }    else {
                 echo '0';
            };
            echo ')</td>';
        }
        echo '<td>',$avt_['draws'],' (',round($avt_['draws']*100/$avt_['battles'],2),'%)</td>';
        echo '<td align="left">',$lang['all_capture_points'],':</td>';
        if ($rowss<>'2') {
            echo '<td>',$h24['capture_points'],' (';
            if (($h24['battles']<>0) && ($avt_['battles']<>0)){
                 if (round($h24['capture_points']/$h24['battles'],2) >= round($avt_['capture_points']/$avt_['battles'],2)) {
                     echo $darkgreen;
                 }   else {
                     echo $darkred;
                 };
                 echo round($h24['capture_points']/$h24['battles'],2),'</span>';
            }    else {
                 echo '0';
            }
            echo ')</td>';
        }
        echo '<td>',$avt_['capture_points'],' (',round($avt_['capture_points']/$avt_['battles'],2),')</td>'; ?>
        <td colspan="<?=$rowss; ?>"></td>
    </tr>

    <tr>
      <?php
        echo '<td align="left"><span class="hidden">7</span>',$lang['averag_surv'],':</td>';
        if ($rowss<>'2') {
            echo '<td>',$h24['survived_battles'],' (';
            if  (($h24['battles']<>0) && ($avt_['battles']<>0)){
                if (round($h24['survived_battles']/$h24['battles']*100,2) >= round($avt_['survived_battles']/$avt_['battles']*100,2)) {
                    echo $darkgreen;
                }   else {
                    echo $darkred;
                };
                echo round($h24['survived_battles']/$h24['battles']*100,2),'%</span>';
            }   else {
                echo '0';
            };
            echo ')</td>';
        }
        echo '<td>',$avt_['survived_battles'],' (',round($avt_['survived_battles']/$avt_['battles']*100,2),'%)</td>';
        echo '<td align="left">',$lang['all_dropped_capture_points'],':</td>';
        if ($rowss<>'2') {
            echo '<td>',$h24['dropped_capture_points'],' (';
            if (($h24['battles']<>0) && ($avt_['battles']<>0)){
                 if (round($h24['dropped_capture_points']/$h24['battles'],2) >= round($avt_['dropped_capture_points']/$avt_['battles'],2)) {
                     echo $darkgreen;
                 }   else {
                     echo $darkred;
                 };
                 echo round($h24['dropped_capture_points']/$h24['battles'],2),'</span>';
            }    else {
                 echo '0';
            };
            echo ')</td>';
        }
        echo '<td>',$avt_['dropped_capture_points'],' (',round($avt_['dropped_capture_points']/$avt_['battles'],2),')</td>'; ?>
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
                              <td align="left"><a href="<?php echo $config['base'],$val['nickname'],'/'; ?>" target="_blank"><?=$val['nickname'];?></a></td>
                              <td align="center"><?=(isset($lang[$val['role']]))?$lang[$val['role']]:'--';?></td>
                              <td align="center"><?php echo floor(($val['updated_at'] - mktime(0, 0, 0, date("m", $val['created_at']), date("d", $val['created_at']), date("Y", $val['created_at'])))/(3600*24)),$lang['days'];?></td>
                              <td align="center"><span class="hidden"><?=$val['updated_at'];?></span><?php echo date('d.m.Y',$val['updated_at']);?></td>
                           </tr>
                        <?php }
                     }  else {
                        echo '<tr><td colspan="4" align="center">',$lang['noone'],'</td></tr>';
                     } ?>
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
                           <tr>
                              <td align="left"><a href="<?php echo $config['base'],$roster_id[$val['account_id']]['account_name'],'/'; ?>"
                                                target="_blank"><?=$roster_id[$val['account_id']]['account_name'];?></a></td>
                              <td width="130px" align="left">
                                 <div style="position:relative; min-height:31px; padding: 0px; background: url('http://<?=$config['gm_url'];?>/static/3.6.0.1/common/img/nation/<?=$tanks[$val['tank_id']]['nation'];?>.png') no-repeat scroll 0 0 transparent;">
                                 <img style="right:-50px; position:absolute; padding: 0px;" src="<?=$tanks[$val['tank_id']]['image_small'];?>" /></div>
                              </td>
                              <td align="center">
                                 <?=$tanks[$val['tank_id']]['name_i18n']; ?>
                              </td>
                           </tr>
                     <?php } }  else {
                                echo '<tr><td colspan="3" align="center">',$lang['no_new'],'</td></tr>';
                             } ?>
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
                              <td align="left"><a href="<?php echo $config['base'],$val['nickname'],'/'; ?>" target="_blank"><?=$val['nickname'];?></a></td>
                              <td align="center"><?=$lang[$val['role']];?></td>
                              <td align="center"><?php echo floor((time() - mktime(0, 0, 0, date("m", $val['created_at']), date("d", $val['created_at']), date("Y", $val['created_at'])))/(3600*24)),$lang['days'];?></td>
                              <td align="center"><span class="hidden"><?=$val['created_at'];?></span><?php echo date('d.m.Y',$val['created_at']);?></td>
                           </tr>
                        <?php }
                     }  else {
                        echo '<tr><td colspan="4" align="center">',$lang['noone'],'</td></tr>';
                     } ?>
                  </tbody>
               </table>
            </td>
         </tr>
      </tbody>
   </table>
<? };?>
</div>
<?php unset($avtdmg,$avt_eff,$avt,$avt_,$avt_mexp,$h24); ?>