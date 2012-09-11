<?php
    /*
    * Project:     Clan Stat
    * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
    * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
    * -----------------------------------------------------------------------
    * Began:       2011
    * Date:        $Date: 2011-10-24 11:54:02 +0200 $
    * -----------------------------------------------------------------------
    * @author      $Author: Edd $
    * @copyright   2011-2012 Edd - Aleksandr Ustinov
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 2.1.3 $
    *
    */
?>
<div align="center">
<script>
$(document).ready(function() {
    $("#change_button_averageshow").button();
    $("#change_button_fullshow").button().addClass("ui-state-focus");
    $(".averageshow").hide();
    $("#change_button_averageshow").click(function() {
      $("#change_button_fullshow").removeClass("ui-state-focus");
      $("#change_button_averageshow").addClass("ui-state-focus");
      //hide all
      $(".fullshow").hide();
      //show selected
      $(".averageshow").show();
      return false;
    });
    $("#change_button_fullshow").click(function() {
      $("#change_button_averageshow").removeClass("ui-state-focus");
      $("#change_button_fullshow").addClass("ui-state-focus");
      //hide all
      $(".averageshow").hide();
      //show selected
      $(".fullshow").show();
      return false;
    });
});
</script>
<a href="#" id="change_button_fullshow"><?=$lang['show_full_perform'];?></a>&nbsp;&nbsp;&nbsp;<a href="#" id="change_button_averageshow"><?=$lang['show_average_perform'];?></a>
    <table id="perform_all" class="tablesorter wid" cellspacing="1">
        <thead>
            <tr>
                <th><?php echo $lang['name']; ?></th> 
                <?php foreach($res[$rand_keys]['perform'] as $column => $cat){ ?>
                    <?php if($column == $lang['hit_ratio']) { ?>
                            <th class='fullshow'><?=$column;?></th>
                    <? } else { ?>
                            <th class='averageshow'><?=$column;?></th>
                            <th class='fullshow'><?=$column;?></th>
                    <?php } } ?>
            </tr>  
        </thead>
        <tbody>
            <?php foreach($res as $name => $val){  ?>
                <tr> 
                    <td><a href="<?php echo $config['base'].$name.'/'; ?>" 
                            target="_blank"><?php echo $name; ?></a></td>
                    <?php foreach($val['perform'] as $cat => $result){ ?>
                        <?php if($cat == $lang['hit_ratio']) { ?>
                			<td class='fullshow'>
                			<?php echo $result; ?>
                			</td>
                        <?php } else { ?>
                			<td class='averageshow'>
                			<? if($val['overall'][$lang['games_p']] > 0) { echo round($result/$val['overall'][$lang['games_p']],2); } else { echo '0'; } ?>
                			</td>
                			<td class='fullshow'>
                			<?php echo $result; ?>
                			</td>
                       <?php }
                       } ?>
                </tr>
                <?php } ?>
        </tbody>  
    </table>
</div>
<? unset($column); unset($cat); unset($name); unset($val); ?>
