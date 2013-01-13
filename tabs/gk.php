<?
if(isset($_GET['checkdate'])) {
echo $lang['gk_info_7'].date('d.m.Y H:i:s', mktime());
echo $lang['gk_info_8'].date('d.m.Y H:i:s', mktime()+$config['time']*60*60);
echo '<br />';
}
$cur_time = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
?>

<script type="text/javascript">
$(document).ready(function() {
$('.gksubmit').css('font-weight','normal').button();
});
</script>
<div class="num" align="left">
<? if($logged > 1) { ?>
<form action="./main.php#tabs-<?php echo $key; ?>" method="post" enctype="multipart/form-data">
<input type="file" name="filename">
<input type="submit" value="<? echo $lang['gk_info_1']; ?>" class="gksubmit" name="gkreplay">
</form>
<br />
<? } ?>
<? if(isset($gk_fresult['error']) and !is_null($gk_fresult['error'])) { echo $gk_fresult['error']; } ?>
<? if(isset($gk_fresult['team']) and !is_null($gk_fresult['team'])) { ?>
<script type="text/javascript" id="js">

        $(document).ready(function()
        {
            $("#gk_destroyed").tablesorter({sortList:[[0,0]], widgets: ['zebra']});
            $('.gkcheckbox').addClass('ui-checkbox');
        }
        );
</script>
<div align="center">
    <form action="./main.php#tabs-<?php echo $key; ?>" method="post">
       <input type="hidden" value="<?=$gk_fresult['time'];?>" name="Array[time]">
        <table id="gk_destroyed" class="tablesorter" cellspacing="1">
            <thead>
                <tr>
                    <th align="center"><?=$lang['name'];?></th>
                    <th align="center"><?=$lang['gk_info_10'];?></th>
                    <th align="center"><?=$lang['gk_info_11'];?></th>
                </tr>
            </thead>
            <tbody>
                <? foreach($gk_fresult['team'] as $name => $val) { ?>
                    <tr>
                        <td align="center"><?=$name;?><input type="hidden" value="<?=$name;?>" name="Array[result][<?=$name;?>][name]"></td>
                        <td align="center"><?=$val;?><input type="hidden" value="<?=$val;?>" name="Array[result][<?=$name;?>][vehicleType]"></td>
                        <td align="center"><input type="checkbox" class="gkcheckbox" style="margin: 0px; padding: 0px;" name="Array[result][<?=$name;?>][killed]"></td>
                    </tr>
                    <? } ?>
            </tbody>
        </table>
        <p><input type="submit" value="<?=$lang['gk_info_9'];?>" name="gkdestroyed" class="gksubmit"></p>
    </form>
</div>
<? } ?>
</div>
   <div align="center">
   <? if(isset($gk_blocked_tanks) and count($gk_blocked_tanks) > 0 ) { ?>
      <table id="blocked" class="tablesorter" cellspacing="1" style="width: 100%;">
      <thead>
         <tr>
            <th><?php echo $lang['name'];?></th>
            <?php foreach($gk_blocked_tanks as $column) {?>
            <th style="padding-right:20px;" align="center"><?echo $column;?></th>
            <?}?>
         </tr>
      </thead>
      <tbody>
         <?php foreach($res as $name => $val) {?>
         <tr>
            <td><a href="<?php echo $config['base'].$name.'/';?>" target="_blank"><?php echo $name;?></a></td>
               <?php foreach($gk_blocked_tanks as $column) {?>
                  <td align='center' style='padding: 0px !important; vertical-align: middle;'>
                     <?php
                     if(isset ($blocked[$name][$column])) {
                        $gk_days = floor(($blocked[$name][$column]-$cur_time)/(24*60*60));
                        $gk_hours = floor(($blocked[$name][$column]-$cur_time-($gk_days*24*60*60))/(60*60));
                        $gk_min = floor(($blocked[$name][$column]-$cur_time-($gk_days*24*60*60)-($gk_hours*60*60))/(60));
                        $gk_title = $lang['gk_info_2'];
                        $gk_title .= ' '.date('d.m.Y H:i:s', ($blocked[$name][$column]+$config['time']*60*60)).'<br /> ';
                        $gk_title .= $lang['gk_info_3'];
                        $gk_title .= $lang['gk_info_4'].$gk_days.'<br /> ';
                        $gk_title .= $lang['gk_info_5'].$gk_hours.'<br /> ';
                        $gk_title .= $lang['gk_info_6'].$gk_min;
                        echo '<img src="./images/no2.png" class="bb" title="'.$gk_title.'">';
                     } ?>
                  </td>
               <?php }?>
         </tr>
         <?php }?>
      </tbody>
      </table>
      <? } else {
                    echo $lang['gk_error_9'];
                } ?>
 </div>


