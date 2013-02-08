<?php
    /*
    * Project:     Clan Stat
    * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
    * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
    * -----------------------------------------------------------------------
    * Began:       2011
    * Date:        $Date: 2011-10-24 11:54:02 +0200 $
    * -----------------------------------------------------------------------
    * @author      $Author: Edd, Exinaus, Shw  $
    * @copyright   2011-2012 Edd - Aleksandr Ustinov
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 2.2.0 $
    *
    */
?>
<?
$cur_time = time();
?>

<script type="text/javascript">
    $(document).ready(function() {
        $('.gksubmit').button();
    });
</script>
<div class="num" align="left">
<? if($logged >= $config['a_rights']) { ?>
<form action="./main.php#tabs-<?php echo $key; ?>" method="post" enctype="multipart/form-data">
<input type="file" name="filename">
<input type="submit" value="<? echo $lang['gk_info_1']; ?>" class="gksubmit" name="gkreplay">
</form>
<br />
<? } ?>
<? if(isset($gk_fresult['error']) and !is_null($gk_fresult['error'])) { echo $gk_fresult['error']; } ?>
<? if(isset($gk_fresult['team']) and !is_null($gk_fresult['team'])) { ?>
<script type="text/javascript" id="js">
    $(document).ready(function(){
        $("#gk_destroyed").tablesorter();
        $('.gkcheckbox').addClass('ui-checkbox');
    });
</script>
<div align="center">
    <form action="./main.php#tabs-<?php echo $key; ?>" method="post">
       <input type="hidden" value="<?=$gk_fresult['time'];?>" name="Array[time]">
        <table id="gk_destroyed" cellspacing="1">
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
      <table id="blocked" cellspacing="1" width="100%" class="table-id-<?=$key;?>">
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
                        $gk_title .= ' '.date('d.m.Y H:i:s', ($blocked[$name][$column])).'<br /> ';
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


