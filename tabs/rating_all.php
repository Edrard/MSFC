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
<div align="center">
<script>
$(document).ready(function() {
  $( "#triggerrating" ).buttonset();
    $(".ratingplace").hide();
    $("#change_ratingplace").click(function() {
      $(".ratingvalue").hide();
      $(".ratingplace").show();
      check_Width($("table.table-id-<?=$key;?>"), $("div#tabs-<?=$key;?>"));
      return false;
    });
    $("#change_ratingvalue").click(function() {
      $(".ratingplace").hide();
      $(".ratingvalue").show();
      check_Width($("table.table-id-<?=$key;?>"), $("div#tabs-<?=$key;?>"));
      return false;
    });
    $('#id-<?=$key;?>').click(function() {
       check_Width($("table.table-id-<?=$key;?>"), $("div#tabs-<?=$key;?>"));
       return false;
    });
});
</script>
<form>
    <div id="triggerrating" align="center">
        <input type="radio" id="change_ratingvalue" name="triggerrating" checked="checked" /><label for="change_ratingvalue"><?=$lang['show_ratingvalue'];?></label>
        <input type="radio" id="change_ratingplace" name="triggerrating" /><label for="change_ratingplace"><?=$lang['show_ratingplace'];?></label>
    </div>
</form>

    <table id="rating_all" cellspacing="1" style="width: 100%;" class="table-id-<?=$key;?>">
        <thead> 
            <tr>
                <th><?=$lang['name']; ?></th>
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
                        <td><a href="<?=$config['base'].$name.'/'; ?>" target="_blank"><?=$name; ?></a></td>
                    <?php foreach($val['rating'] as $result){ ?>
                        <td class="ratingplace"><?=$result['place']; ?></td>
                        <td class="ratingvalue"><?=$result['value']; ?></td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </tbody>  
    </table>
  </div>