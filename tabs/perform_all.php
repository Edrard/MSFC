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
    * @copyright   2011-2013 Edd - Aleksandr Ustinov
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 3.1.2 $
    *
    */
?>
<div align="center">
<script>
$(document).ready(function() {
    $( "#triggerperform" ).buttonset();
    $(".fs").hide();
    $(".as").show();
    $("#change_button_averageshow").click(function() {
      $(".fs").hide();
      $(".as").show();
      return false;
    });
    $("#change_button_fullshow").click(function() {
      $(".as").hide();
      $(".fs").show();
      return false;
    });
});
</script>
<form>
    <div id="triggerperform" align="center" class="table-id-<?=$key;?>">
        <input type="radio" id="change_button_fullshow" name="triggerperform" /><label for="change_button_fullshow"><?=$lang['show_full_perform'];?></label>
        <input type="radio" id="change_button_averageshow" name="triggerperform" checked="checked" /><label for="change_button_averageshow"><?=$lang['show_average_perform'];?></label>
    </div>
</form>
    <table id="tabs-sort-<?=$key;?>" width="100%" cellspacing="1" class="table-id-<?=$key;?>">
        <thead>
            <tr>
                <th><?=$lang['name']; ?></th>
                <? if($config['company'] == 1 and in_array($key,$company['tabs'])) { ?>
                    <th><?=$lang['company']; ?></th>
                <? } ?>
                <? $perform = array ('hits_percents', 'frags',  'damage_dealt', 'damage_received', 'spotted', 'capture_points', 'dropped_capture_points');
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
                    <? if($config['company'] == 1 and in_array($key,$company['tabs'])) { ?>
                        <td><?=in_array($val['data']['account_id'],$company['in_company'])?$company['company_names'][$company['by_id'][$val['data']['account_id']]]:'';?></td>
                    <? } ?>
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