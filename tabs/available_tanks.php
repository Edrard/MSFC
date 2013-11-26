<!-- Наличие техники -->
<?php
$topTanki = get_available_tanks();
if (isset($topTanki) and count($topTanki) > 0) {
    $avalTanks = get_available_tanks_index();
    $y = array();
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
                       check_Width($('table.table-id-$key'), $('div#tabs-$key'));
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
<?php }

echo '<table id="available_tanks" width="100%" cellspacing="1" class="ui-widget-content table-id-',$key,'">';
echo '<thead><tr>';
echo '<th>',$lang['name'],'</th>';
foreach($topTanki as $column => $val){ ?>
   <th align='center' class="{sorter: 'digit'} atanks_hide atanks_<?=$val['index'];?>" style="min-width: 30px;">
   <?php if($val['title'] != '') {echo $val['title'];} else {echo $column;} ?></th><?php
}
foreach($avalTanks['index'] as $index){
   echo '<th class="atanks_hide atanks_',$index,'" align="center" style="min-width: 30px;">',$lang['toptank_1'],'</th>';
}
echo '</tr></thead><tbody>';
foreach($res as $name => $val){
   $x = array();
   echo '<tr><td><a href="',$config['base'],$name,'/','" target="_blank">',$name,'</a></td>';
   foreach($topTanki as $tank => $stat){
      if (!isset($countTanks[$tank])) $countTanks[$tank] = 0;
      echo '<td align="center" class="atanks_hide atanks_',$stat['index'],'" style="padding: 0px !important; vertical-align: middle;">';
      $present = 0;
      foreach ($val['data']['tanks'] as $key => $val2) {
         if ($val2['tank_id'] == $stat['tank_id'] ) {
             if ($val2['statistics']['battles'] > 0) {
                 if (isset($blocked[$name][$stat['tank_id']])) {
                     echo '<span style="display: none;">1</span>';
                 }   else {
                     echo '<span style="display: none;">2</span>';
                 }
                 echo '<img class="bb" src="./images/yes.png" title="'.$lang['all_battles'].': '.$val2['statistics']['battles'].'<br />'.$lang['all_wins'].': '.round($val2['statistics']['wins']/$val2['statistics']['battles']*100,2).'%">';
                 @$countTanks[$tank] +=1;@$x[$stat['index']]++; @$y[$stat['index']]++; $present++;
             }
             if (isset($blocked[$name][$stat['tank_id']])) {
                 echo '<img src="./images/no2.png">';
             }
         }
      }
      if ($present==0) echo '<span style="display: none;">0</span>';
      echo '</td>';
   }
   foreach($avalTanks['index'] as $index){
      echo '<td class="atanks_hide atanks_',$index,'" align="center">',isset($x[$index])?$x[$index]:0,'</td>';
   }
   echo '</tr>';
} ?>
        </tbody>
     <tfoot>
      <tr>
                <th><?=$lang['toptank_3']?>:</th>
                <?php foreach($topTanki as $column => $val){ ?>
                  <th class="atanks_hide atanks_<?=$val['index'];?>" align="center"><?php echo @$countTanks[$column]; ?></th>
                <?php } ?>
                <?php foreach($avalTanks['index'] as $index){ ?>
                  <th class="atanks_hide atanks_<?=$index;?>" align="center"><?php echo isset($y[$index])?$y[$index]:0; ?></th>
                <?php } ?>
      </tr>
            <tr>
                <th>&nbsp;</th>
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
<? unset($name); unset($val); unset($column); unset($x); unset($y); unset($tank); unset($stat); unset($countTanks); unset($topTanki); ?>
<? } else { echo $lang['toptank_2']; } ?>
<!-- Наличие техники -->