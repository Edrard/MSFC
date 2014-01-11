<!-- Наличие техники -->
<?php
$topTanki = get_available_tanks();
if (isset($topTanki) and count($topTanki) > 0) {
    $avalTanks = get_available_tanks_index();
    $y = array_fill_keys($avalTanks['index'],0);
    if ($avalTanks['count'] > 1) { ?>
        <script type="text/javascript" id="js">
          $(document).ready(function() {
              $("#atankstrigger").buttonset();
              $(".atanks_hide").hide();
              $(".atanks_1").show();
              <?php foreach($avalTanks['index'] as $index) {
                echo " $('#show_atanks_$index').click(function() {
                       $('.atanks_hide').hide();
                       $('.atanks_$index').show();
                    }); ";
              } ?>
          });
        </script>
        <?php $atank_but = $cache->get('available_tanks_'.$config['clan'],0, ROOT_DIR.'/cache/other/');
    } ?>
<div align="center">
<?php if($avalTanks['count'] > 1) { ?>
<form>
    <div id="atankstrigger" align="center">
        <?php foreach($avalTanks['index'] as $index) { ?>
        <input type="radio" id="show_atanks_<?=$index;?>" name="atankstrigger" <? if($index==1){?>checked="checked"<?}?> /><label for="show_atanks_<?=$index;?>">
          <? if(isset($atank_but[$index])) { echo $atank_but[$index]; } else { echo $lang['toptank_4'].$index; } ?>
        </label>
        <?php } ?>
    </div>
</form>
<? } ?>

<table id="available_tanks" width="100%" cellspacing="1" class="ui-widget-content table-id-<?=$key;?>">
    <thead>
      <tr>
          <th><?=$lang['name'];?></th>
          <? if($config['company'] == 1 and in_array($key,$company['tabs'])) { ?>
              <th><?=$lang['company']; ?></th>
          <? } ?>
          <? foreach($topTanki as $column => $val){ ?>
             <th align='center' class="{sorter:'digit'} atanks_hide atanks_<?=$val['index'];?>" style="min-width: 30px;">
               <?php if($val['title'] != '') {echo $val['title'];} else {echo $column;} ?>
             </th>
          <? }
             foreach($avalTanks['index'] as $index){ ?>
               <th align='center' class="{sorter:'digit'} atanks_hide atanks_<?=$index;?>" align="center" style="min-width: 30px;"><?=$lang['toptank_1']?></th>
          <? } ?>
      </tr>
    </thead>
    <tbody>
      <? foreach($res as $name => $val){ $x = array(); ?>
      <tr>
          <td><a href="<?php echo $config['base'].$name.'/'; ?>" target="_blank"><?=$name;?></a></td>
          <? if($config['company'] == 1 and in_array($key,$company['tabs'])) { ?>
              <td>
              <?=in_array($val['data']['account_id'],$company['in_company'])?$company['company_names'][$company['by_id'][$val['data']['account_id']]]:'';?>
              </td>
          <? } ?>
          <? foreach($topTanki as $tank_name => $tank_stat) { ?>
             <td align="center" class="atanks_hide atanks_<?=$tank_stat['index'];?>" style="padding: 0px !important; vertical-align: middle;">
             <?
             if (!isset($countTanks[$tank_name])) { $countTanks[$tank_name] = 0; }
             if (!isset($x[$tank_stat['index']])) { $x[$tank_stat['index']] = 0; }
             $present = 0;
             foreach ($val['data']['tanks'] as $t => $val2) {
               if ($val2['tank_id'] == $tank_stat['tank_id'] and $val2['statistics']['battles'] > 0) {
                 if (isset($blocked[$name][$tank_stat['tank_id']])) {
                     echo '<span style="display: none;">1</span>';
                 }   else {
                     echo '<span style="display: none;">2</span>';
                 }
                 echo '<img class="bb" src="./images/yes.png" title="'.$lang['all_battles'].': '.$val2['statistics']['battles'].'<br />'.$lang['all_wins'].': '.round($val2['statistics']['wins']/$val2['statistics']['battles']*100,2).'%">';
                 if (isset($blocked[$name][$tank_stat['tank_id']])) { echo '<img src="./images/no2.png">'; }
                 $countTanks[$tank_name] +=1; $x[$tank_stat['index']]++; $y[$tank_stat['index']]++; $present++;
               }
               if ($present==0) echo '<span style="display: none;">0</span>';
             }
             ?>
             </td>
          <? } ?>
          <? foreach($avalTanks['index'] as $index) { ?>
            <td class="atanks_hide atanks_<?=$index;?>" align="center"><?=isset($x[$index])?$x[$index]:0;?></td>
          <? } ?>
      </tr>
      <? } ?>
     </tbody>
     <tfoot>
      <tr>
                <th><?=$lang['toptank_3']?>:</th>
                <? if($config['company'] == 1 and in_array($key,$company['tabs'])) { ?>
                    <th>&nbsp;</th>
                <? } ?>
                <?php foreach($topTanki as $column => $val){ ?>
                  <th class="atanks_hide atanks_<?=$val['index'];?>" align="center"><?php echo @$countTanks[$column]; ?></th>
                <?php } ?>
                <?php foreach($avalTanks['index'] as $index){ ?>
                  <th class="atanks_hide atanks_<?=$index;?>" align="center"><?php echo isset($y[$index])?$y[$index]:0; ?></th>
                <?php } ?>
      </tr>
            <tr>
                <th>&nbsp;</th>
                <? if($config['company'] == 1 and in_array($key,$company['tabs'])) { ?>
                    <th><?=$lang['company']; ?></th>
                <? } ?>
                <?php foreach($topTanki as $column => $val){ ?>
                  <th class="atanks_hide atanks_<?=$val['index'];?>" align="center"><?php if($val['title'] != '') {echo $val['title'];} else {echo $column;} ?></th>
                <?php } ?>
                <?php foreach($avalTanks['index'] as $index){ ?>
                  <th class="atanks_hide atanks_<?=$index;?>" class="ui-widget-header ui-state-default" align="center"><?=$lang['toptank_1']?></th>
                <?php } ?>
            </tr>
     </tfoot>
    </table>
  </div>
<? unset($name,$val,$column,$x,$y,$tank_name,$tank_stat,$countTanks,$topTanki,$t); ?>
<? }  else { echo $lang['toptank_2']; }  ?>
<!-- Наличие техники -->