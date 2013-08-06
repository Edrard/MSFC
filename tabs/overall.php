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
                    <th class="{sorter: 'digit'} <? if($lang['games_p'] != $column) {echo 'overall_value';} ?>"><?=$column;?></th>
                <?php } ?>
                <?php foreach(array_keys($res[$rand_keys]['overall']) as $column){ ?>
                    <?php if($lang['games_p'] != $column) { ?>
                    <th class="{sorter: 'digit'} overall_average sorter-percent"><?=$column;?></th>
                    <?php } ?>
                <?php } ?>
                <th class="overall_value overall_average"><?=$lang['eff_ret'];?></th>
                <th class="overall_value overall_average"><?=$lang['wn6_ret'];?></th>
                <th class="overall_value overall_average"><?=$lang['brone_ret'];?></th>
            </tr>
        </thead>
        <tbody>
            <?php
//$kpd = 0;$wn6 = 0;$brone = 0;

			foreach($res as $name => $val){

//$kpd += $eff_rating[$name]['eff']; $wn6 += $eff_rating[$name]['wn6']; $brone += $eff_rating[$name]['brone'];

            switch ($eff_rating[$name]['eff']+1) {
            case ($eff_rating[$name]['eff'] > 1725):
            $color = '#FF8000';
            break;
            case ($eff_rating[$name]['eff'] > 1465):
            $color = 'purple';
            break;
            case ($eff_rating[$name]['eff'] > 1150):
            $color = 'royalblue';
            break;
            case ($eff_rating[$name]['eff'] > 870):
            $color = 'green';
            break;
            case ($eff_rating[$name]['eff'] > 645):
            $color = 'slategray';
            break;
            default:
            $color = 'red';
            break;
            }
            switch ($eff_rating[$name]['wn6']+1) {
            case ($eff_rating[$name]['wn6'] > 1880):
            $color_wn6 = '#FF8000';
            break;
            case ($eff_rating[$name]['wn6'] > 1585):
            $color_wn6 = 'purple';
            break;
            case ($eff_rating[$name]['wn6'] > 1195):
            $color_wn6 = 'royalblue';
            break;
            case ($eff_rating[$name]['wn6'] > 800):
            $color_wn6 = 'green';
            break;
            case ($eff_rating[$name]['wn6'] > 435):
            $color_wn6 = 'slategray';
            break;
            default:
            $color_wn6 = 'red';
            break;
            }
            switch ($eff_rating[$name]['brone']+1) {
            case ($eff_rating[$name]['brone'] > 7296.96):
            $color_brone = '#FF8000';
            break;
            case ($eff_rating[$name]['brone'] > 5570.97):
            $color_brone = 'purple';
            break;
            case ($eff_rating[$name]['brone'] > 3849.62):
            $color_brone = 'royalblue';
            break;
            case ($eff_rating[$name]['brone'] > 2733.38):
            $color_brone = 'green';
            break;
            case ($eff_rating[$name]['brone'] > 2080.77):
            $color_brone = 'slategray';
            break;
            default:
            $color_brone = 'red';
            break;
            }
            ?>

                <tr>
                    <td><a href="<?php echo $config['base'],$name,'/'; ?>" target="_blank"><?=$name; ?></a></td>
                    <?php foreach($val['overall'] as $column => $result){ ?>
                        <td class="<? if($lang['games_p'] != $column) {echo 'overall_value';} ?>"><?=$result;?></td>
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
                    <td class="overall_value overall_average"><font color="<?=$color;?>"><?=($eff_rating[$name]['eff']>0)?$eff_rating[$name]['eff']:'0';?></font></td>
                    <td class="overall_value overall_average"><font color="<?=$color_wn6;?>"><?=($eff_rating[$name]['wn6']>0)?$eff_rating[$name]['wn6']:'0';?></font></td>
                    <td class="overall_value overall_average"><font color="<?=$color_brone;?>"><?=($eff_rating[$name]['brone']>0)?$eff_rating[$name]['brone']:'0';?></font></td>
                </tr>
                <?php } ?>
        </tbody>  
    </table>
    <table border="0" cellpadding="2" cellpadding="2" width="100%">
    <tr>
    <td align="center" valign="top"><?=$lang['overall_eff_table']?></td>
    <td align="center" valign="top"><?=$lang['overall_wn6_table']?></td>
    <td align="center" valign="top"><?=$lang['overall_brone_table']?></td>
    </tr>
    </table>
</div>
<? unset($color_brone,$color_wn6,$color);

//echo 'kpd = ' . $kpd/100 . ' | ' . 'wn6 = ' . $wn6/100 . ' | ' . 'brone = ' . $brone/100;
?>