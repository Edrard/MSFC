<!-- Наличие техники -->
<?php
$topTanki = get_available_tanks();
if(isset($topTanki) and count($topTanki) > 0) {
$avalTanks = get_available_tanks_index();
$y = array();
if($avalTanks['count'] > 1) {
echo '
<script type="text/javascript" id="js">
  $(document).ready(function()
  {';
  echo '$("#atankstrigger").buttonset();';
  echo '$(".atanks_hide").hide();';
  echo '$(".atanks_1").show();';
  foreach($avalTanks['index'] as $index) {
    //1
    echo '
        $("#show_atanks_'.$index.'").click(function() {
            $(".atanks_hide").hide();
            $(".atanks_'.$index.'").show();
        });
    ';
  }
//work code
$atank_but = $cache->get('available_tanks_'.$config['clan'],0);
echo'  });
</script>
'; }
?>
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
<?php } ?>
    <table id="available_tanks" width="100%" cellspacing="1">
        <thead>
            <tr>
                <th><?php echo $lang['name']; ?></th>
                <?php foreach($topTanki as $column => $val){ ?>
                    <th align='center' class="{sorter: 'digit'} atanks_hide atanks_<?=$val['index'];?>" style="min-width: 30px;"><?php if($val['short'] != '') {echo $val['short'];} else {echo $column;} ?></th>
                <?php } ?>
                <?php foreach($avalTanks['index'] as $index){ ?>
                    <th class="atanks_hide atanks_<?=$index;?>" align='center' style="min-width: 30px;"><?=$lang['toptank_1']?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach($res as $name => $val){ $x = array(); ?>
                <tr>
                    <td><a href="<?php echo $config['base'].$name.'/'; ?>" target="_blank"><?php echo $name; ?></a></td>
                    <?php foreach($topTanki as $tank => $stat){ ?>
                        <td align='center' class="atanks_hide atanks_<?=$stat['index'];?>" style='padding: 0px !important; vertical-align: middle;'>
                        <?php if(isset($val['tank'][$stat['lvl']][$stat['type']][$tank]) and $val['tank'][$stat['lvl']][$stat['type']][$tank]['total'] > 0)
                        {
                          echo '<span style="display: none;">1</span><img src="./images/yes.png">';
                          @$countTanks[$tank] +=1; @$x[$stat['index']]++; @$y[$stat['index']]++;
                          if(isset($blocked[$name][$tank])) { echo '<img src="./images/no2.png">'; }
                        }  else {
                          echo '<span style="display: none;">0</span>';
                        } ?>
                        </td>
                        <?php } ?>
                    <?php foreach($avalTanks['index'] as $index){ ?>
                    <td class="atanks_hide atanks_<?=$index;?>" align='center'><?php echo isset($x[$index])?$x[$index]:0; ?></td>
                    <?php } ?>
                </tr>
                <?php } ?>
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
                  <th class="atanks_hide atanks_<?=$val['index'];?>" align="center"><?php if($val['short'] != '') {echo $val['short'];} else {echo $column;} ?></th>
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