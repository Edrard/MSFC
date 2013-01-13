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
    * @version     $Rev: 2.0.0 $
    *
    */
?>
<div align="center">
<script>
$(document).ready(function() {
    $("#change_ratingplace").button();
    $("#change_ratingvalue").button().addClass("ui-state-focus");
    $(".ratingplace").hide();
    $("#change_ratingplace").click(function() {
      $("#change_ratingvalue").removeClass("ui-state-focus");
      $("#change_ratingplace").addClass("ui-state-focus");
      //hide all
      $(".ratingvalue").hide();
      //show selected
      $(".ratingplace").show();
      return false;
    });
    $("#change_ratingvalue").click(function() {
      $("#change_ratingplace").removeClass("ui-state-focus");
      $("#change_ratingvalue").addClass("ui-state-focus");
      //hide all
      $(".ratingplace").hide();
      //show selected
      $(".ratingvalue").show();
      return false;
    });
});
</script>
  <a href="#" id="change_ratingvalue"><?=$lang['show_ratingvalue'];?></a>
  &nbsp;&nbsp;&nbsp;
  <a href="#" id="change_ratingplace"><?=$lang['show_ratingplace'];?></a>
    <table id="rating_all" class="tablesorter" cellspacing="1" style="width: 100%;">
        <thead> 
            <tr>
                <th><?php echo $lang['name']; ?></th> 
                <?php foreach(array_keys($res[$rand_keys]['rating']) as $column){ 
                     $array = $res[$rand_keys]['rating'][$column];
                     echo "<th align='center' class=\"{sorter: 'digit'} ratingplace\"><img class='bb' src='{$array['link']}' title='<font color=\"royalblue\">{$array['type']}</font><br>{$array['name']}'><br>$column</th>";
                     echo "<th align='center' class=\"{sorter: 'digit'} ratingvalue\"><img class='bb' src='{$array['link']}' title='<font color=\"royalblue\">{$array['type']}</font><br>{$array['name']}'><br>$column</th>";
                     } ?>
            </tr>  
        </thead>
        <tbody>
            <?php foreach($res as $name => $val){ ?>
                <tr>
                    <td><a href="<?php echo $config['base'].$name.'/'; ?>"
                            target="_blank"><?php echo $name; ?></a></td>
                    <?php foreach($val['rating'] as $result){ ?>
                        <td class="ratingplace"><?php echo $result['place']; ?></td>
                        <td class="ratingvalue"><?php echo $result['value']; ?></td>
                        <?php } ?>
                </tr>
                <?php } ?>
        </tbody>  
    </table>
  </div>