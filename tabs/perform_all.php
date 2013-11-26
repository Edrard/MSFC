<?php
    /*
    * Project:     Clan Stat
    * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
    * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
    * -----------------------------------------------------------------------
    * Began:       2011
    * Date:        $Date: 2013-10-20 00:00:00 +0200 $
    * -----------------------------------------------------------------------
    * @author      $Author: Edd, Exinaus, SHW  $
    * @copyright   2011-2012 Edd - Aleksandr Ustinov
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 3.0.0 $
    *
    */
?>
<div align="center">
<script>
$(document).ready(function() {
    $( "#triggerperform" ).buttonset();
    $(".as").hide();
    $(".fs").show();
    $("#change_button_averageshow").click(function() {
      $(".fs").hide();
      $(".as").show();
      check_Width($("table.table-id-<?=$key;?>"), $("div#tabs-<?=$key;?>"));
      return false;
    });
    $("#change_button_fullshow").click(function() {
      $(".as").hide();
      $(".fs").show();
      check_Width($("table.table-id-<?=$key;?>"), $("div#tabs-<?=$key;?>"));
      return false;
    });
});
</script>
<form>
    <div id="triggerperform" align="center" class="table-id-<?=$key;?>">
        <input type="radio" id="change_button_fullshow" name="triggerperform" checked="checked" /><label for="change_button_fullshow"><?=$lang['show_full_perform'];?></label>
        <input type="radio" id="change_button_averageshow" name="triggerperform" /><label for="change_button_averageshow"><?=$lang['show_average_perform'];?></label>
    </div>
</form>
    <table id="perform_all" width="100%" cellspacing="1" class="table-id-<?=$key;?>">
        <thead>
            <tr>
                <?php echo '<th>'.$lang['name'].'</th>';
                $perform = array ('hits_percents', 'frags',  'damage_dealt', 'damage_received', 'spotted', 'capture_points', 'dropped_capture_points');
                foreach($perform as $cat){ ?>
                    <?php if($cat == 'hits_percents') { ?>
                            <th class='fs as'><?=$lang['all_'.$cat];?></th>
                    <? } else { ?>
                            <th class='as'><?=$lang['all_'.$cat];?></th>
                            <th class='fs'><?=$lang['all_'.$cat];?></th>
                    <?php } } ?>
            </tr>  
        </thead>
        <tbody>
            <?php foreach($res as $name => $val){  ?>
                <tr> 
                    <td><a href="<?php echo $config['base'],$name,'/'; ?>" target="_blank"><?=$name; ?></a></td>
                    <?php foreach($perform as $cat){ ?>
                        <?php if($cat == 'hits_percents') { ?>
                            <td class='fs as'>
                            <?php echo $val['data']['statistics']['all'][$cat]; ?>
                            </td>
                        <?php } else { ?>
                            <td class='as'>
                            <? if($val['data']['statistics']['all']['battles'] > 0) { echo round($val['data']['statistics']['all'][$cat]/$val['data']['statistics']['all']['battles'],2); } else { echo '0'; } ?>
                            </td>
                            <td class='fs'>
                            <?php echo $val['data']['statistics']['all'][$cat]; ?>
                            </td>
                       <?php }
                       } ?>
                </tr>
                <?php } ?>
        </tbody>  
    </table>
</div>
<? unset($column); unset($cat); unset($name); unset($val); ?>