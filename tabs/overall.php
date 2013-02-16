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
<script>
$(document).ready(function() {
    $( "#triggeroverall" ).buttonset();
    $(".overall_average").hide();
    $(".overall_value").show();
    $("#change_overall_value").click(function() {
      $(".overall_average").hide();
      $(".overall_value").show();
      check_Width($("table.table-id-<?=$key;?>"), $("div#tabs-<?=$key;?>"));
      return false;
    });
    $("#change_overall_average").click(function() {
      $(".overall_value").hide();
      $(".overall_average").show();
      check_Width($("table.table-id-<?=$key;?>"), $("div#tabs-<?=$key;?>"));
      return false;
    });
});
</script>
<form>
    <div id="triggeroverall" align="center">
        <input type="radio" id="change_overall_value" name="triggeroverall" checked="checked" /><label for="change_overall_value"><?=$lang['overall_value'];?></label>
        <input type="radio" id="change_overall_average" name="triggeroverall" /><label for="change_overall_average"><?=$lang['overall_average'];?></label>
    </div>
</form>
<div align="center">
    <table id="overall" width="100%" cellspacing="1" class="table-id-<?=$key;?>">
        <thead>
            <tr>
                <th><?php echo $lang['name']; ?></th> 
                <?php foreach(array_keys($res[$rand_keys]['overall']) as $column){ ?>
                    <th class="{sorter: 'digit'} <? if($lang['games_p'] != $column) {echo'overall_value';} ?>"><?=$column;= ?></th>
                <?php } ?>
                <?php foreach(array_keys($res[$rand_keys]['overall']) as $column){ ?>
                    <?php if($lang['games_p'] != $column) { ?>
                    <th class="{sorter: 'digit'} overall_average sorter-percent"><?=$column;?></th>
                    <?php } ?>
                <?php } ?>
                <th class="overall_value overall_average"><?=$lang['eff_ret'];?></th>
            </tr>  
        </thead>
        <tbody>
            <?php foreach($res as $name => $val){ ?>
            <?
            switch ($eff_rating['eff'][$name]+1) {
            case ($eff_rating['eff'][$name] > 1800):
            $color = '#FF8000';
            break;
            case ($eff_rating['eff'][$name] > 1500):
            $color = 'purple';
            break;
            case ($eff_rating['eff'][$name] > 1200):
            $color = 'royalblue';
            break;
            case ($eff_rating['eff'][$name] > 900):
            $color = 'green';
            break;
            case ($eff_rating['eff'][$name] > 600):
            $color = 'slategray';
            break;
            default:
            $color = 'red';
            break;
            }
            ?>
                <tr> 
                    <td><a href="<?php echo $config['base'].$name.'/'; ?>" 
                            target="_blank"><?php echo $name; ?></a></td>
                    <?php foreach($val['overall'] as $column => $result){ ?>
                        <td class="<? if($lang['games_p'] != $column) {echo'overall_value';} ?>"><?=$result;?></td>
                    <?php } ?>
                    <?php foreach($val['overall'] as $column => $result){ ?>
                        <?php if($lang['games_p'] != $column) { ?>
                        <td class="overall_average"><?php
                          if ($val['overall'][$lang['games_p']]<> 0) {
                              echo (number_format($result/$val['overall'][$lang['games_p']]*100,2));
                          }   else {
                              echo '0';
                          }; ?>%</td>
                        <?php } ?>
                    <?php } ?>
                    <td class="overall_value overall_average"><font color="<?=$color;?>"><?=($eff_rating['eff'][$name]>0)?$eff_rating['eff'][$name]:'0';?></font></td>
                </tr>
                <?php } ?>
        </tbody>  
    </table>
    <?=$lang['overall_eff_table']?>
</div>