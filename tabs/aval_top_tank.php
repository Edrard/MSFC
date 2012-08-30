﻿<!-- Наличие ТОП техники -->
<?php
$index = 1;
/****** Редактировать на свой страх и риск / Do not edit below *****/
$topTanki = get_top_tanks_tab($index);
if(isset($topTanki) and count($topTanki) > 0) {
$y = 0;
echo '
<script type="text/javascript" id="js">

        $(document).ready(function()
        {
            $("#aval_top_tank'.$index.'").tablesorter({sortList:[[0,0]], widgets: [\'zebra\']});
        }
        );
</script>
'; ?>
 <div align="center">
    <table id="aval_top_tank<?=$index?>" class="tablesorter wid" cellspacing="1">
        <thead>
            <tr>
                <th><?php echo $lang['name']; ?></th>
                <?php foreach($topTanki as $column => $val){ ?>
                    <th align='center' style="min-width: 30px;"><?php if($val['short'] != '') {echo $val['short'];} else {echo $column;} ?></th>
                    <?php } ?>
                <th align='center' style="min-width: 30px;"><?=$lang['toptank_1']?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($res as $name => $val){ $x = 0; ?>
                <tr>
                    <td><a href="<?php echo $config['base'].$name.'/'; ?>" target="_blank"><?php echo $name; ?></a></td>
                    <?php foreach($topTanki as $tank => $stat){ ?>
                        <td align='center' style='padding: 0px !important; vertical-align: middle;'><?php if(isset($val['tank'][$stat['lvl']][$stat['type']][$tank]) and $val['tank'][$stat['lvl']][$stat['type']][$tank]['total'] > 0) { echo '<img src="./images/yes.png">'; @$countTanks[$tank] +=1; ++$x; ++$y;} ?></td>
                        <?php } ?>
                    <td align='center'><?php echo $x; ?></td>
                </tr>
                <?php } ?>
        </tbody>
	 <tfoot>
	  <tr>
                    <th><?=$lang['toptank_3']?>:</th>
                <?php foreach(array_keys($topTanki) as $column){ ?>
                    <th align='center'><?php echo @$countTanks[$column]; ?></th>
                    <?php } ?>
                    <th align='center'><?php echo $y; ?></th>
	  </tr>
            <tr>
                <th>&nbsp;</th>
                <?php foreach($topTanki as $column => $val){ ?>
                    <th align='center'><?php if($val['short'] != '') {echo $val['short'];} else {echo $column;} ?></th>
                    <?php } ?>
                <th align='center'><?=$lang['toptank_1']?></th>
            </tr>
	 </tfoot>
    </table>
  </div>
<? unset($name); unset($val); unset($column); unset($x); unset($y); unset($tank); unset($stat); unset($countTanks); unset($topTanki); ?>
<? } else { echo $lang['toptank_2']; } ?>
<!-- Наличие ТОП техники -->