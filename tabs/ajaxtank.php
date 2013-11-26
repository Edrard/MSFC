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
    * @version     $Rev: 3.0.0 $
    *
    */
?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#change_button_tanks").button();
        $("#change_button_tanks").click( function() {
            $.ajax({
                cache: true,
                type: "POST",
                data: {
                  type    :  $('#type').val(),
                  nation  :  $('#nation').val(),
                  lvl     :  $('#lvl').val(),
                  db_pref : '<?php echo $db->prefix; ?>',
                  key     : '<?=$key;?>'
                },
                url: "./ajax/tanks.php",
                success: function(msg){
                    $("#result").html(msg).show();
                },
                complete: function() {
                  check_Width($("table.table-id-<?=$key;?>"), $("div#tabs-<?=$key;?>"));
                }
            });
            return false;
        });
        $("#change_button_tanks").click();
        $('#id-<?=$key;?>').click(function() {
           $("#tankslist").trigger('applyWidgets');
           return false;
        });
    });   
</script>
<div align="center" id="ajax_tanks_width">
    <br>
    <?=$lang['alltanks_choise_tank']?>


    <select name="nation" id="nation">
        <option value="all"><?=$lang['alltanks_all']?></option>
        <?php foreach($tanks_nation as $val){?>
            <option value="<?=$val['nation']?>"><?=$lang[$val['nation']]?></option>
            <?php } ?>
    </select>        
    <select id="type">
        <option value="all"><?=$lang['alltanks_all']?></option>
        <?php foreach($tanks_types as $val){?>
            <option value="<?=$val['type']?>"><?=$lang[$val['type']]?></option>
            <?php } ?>
    </select>
    <select id="lvl">
        <option value="all"><?=$lang['alltanks_all']?></option>
        <?php foreach($tanks_lvl as $key => $val){?>
            <option value="<?=$val['level']?>" <?php if ($val['level']=='1') {echo 'selected="selected"';}; ?>><?=$val['level']?></option>
            <?php } ?>
    </select>
    <a href="#tabs-<?php echo $key; ?>" id="change_button_tanks"><?=$lang['select_show'];?></a>
    <br>
    <div id="result" ></div>  
</div>