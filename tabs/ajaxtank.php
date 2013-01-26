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
    * @version     $Rev: 2.1.6 $
    *
    */
?>
<?php
    $tanks_nation = tanks_nations();
    $tanks_types = tanks_types();
    $tanks_lvl = tanks_lvl();
    sort($tanks_lvl);
?>
<script type="text/javascript">

    $(document).ready(function(){
        $("#change_button_tanks").button();
        $("#change_button_tanks").click( function() {
            $.ajax({
                cache: true,
                type: "POST",
                //data: 'type='+ $('#type').val()+'&nation='+$('#nation').val()+'&lvl='+$('#lvl').val(),
                data: {
                  type    :  $('#type').val(),
                  nation  :  $('#nation').val(),
                  lvl     :  $('#lvl').val(),
                  db_pref : '<?php echo $db->prefix; ?>'
                },
                url: "./ajax/tanks.php",
                success: function(msg){
                    $("#result").html(msg).show();
                }
            });
        });
        $.ajax({
            cache: true,
            type: "POST",
            //data: 'type=all&nation=all&lvl=1',
            data: {
              type    :  'all',
              nation  :  'all',
              lvl     :  1,
              db_pref : '<?php echo $db->prefix; ?>'
            },
            url: "./ajax/tanks.php",
            success: function(msg){
                $("#result").html(msg).show();
            }
        });
    });
</script>
<div align="center">
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
        <?php foreach($tanks_lvl as $val){?>
            <option value="<?=$val['lvl']?>"><?=$val['lvl']?></option>
            <?php } ?>
    </select>
    <a href="#" id="change_button_tanks"><?=$lang['select_show'];?></a>
    <br>
    <div id="result" ></div>  
</div>
