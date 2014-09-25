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
    * @copyright   2011-2013 Edd - Aleksandr Ustinov
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 3.1.2 $
    *
    */
?>
<?php
    $sql = "SELECT * FROM `tanks` ORDER BY name_i18n;";
    $q = $db->prepare($sql);
    if ($q->execute() == TRUE) {
        $tank_array = $q->fetchAll();
    } else {
        die(show_message($q->errorInfo(),__line__,__file__,$sql));
    }
?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#change_button_tanks").button();
        $("#change_button_tanks").click( function() {
            $("#result").html("<div class=\"ui-state-highlight ui-widget-content\" align=\"center\"><?=$lang['index_loading'];?> <img src=\"../images/ajax-loader.gif\" align=\"middle\"></div>").show();
            $.ajax({
                cache: true,
                type: "POST",
                data: {
                  type    :  $('#type').val(),
                  nation  :  $('#nation').val(),
                  lvl     :  $('#lvl').val(),
                  tank    :  $('#tank').val(),
                  db_pref : '<?php echo $db->prefix; ?>',
                  key     : '<?=$key;?>'
                },
                url: "./ajax/tanks.php",
                success: function(msg){
                    $("#result").html(msg).show();
                }
            });
            return false;
        });
        $('#id-<?=$key;?>').click(function() {
           $("#tankslist").trigger('applyWidgets');
           return false;
        });
    });

   function onComboChange() {
      var nationocc = document.getElementById('nation');
      var typeocc = document.getElementById('type');
      var lvlocc = document.getElementById('lvl');
      var TankList = document.getElementById('tank');
      var tank_array = new Array ('<?=$lang['alltanks_all']?>'<?php foreach($tank_array as $val){echo ", '".$val['name_i18n']."'";}?>);
      var nation_array = new Array (''<?php foreach($tank_array as $val){echo ", '".$val['nation']."'";}?>);
      var type_array = new Array (''<?php foreach($tank_array as $val){echo ", '".$val['type']."'";}?>);
      var lvl_array = new Array (''<?php foreach($tank_array as $val){echo ", '".$val['level']."'";}?>);
      TankList.length = 0;
      for (var i = 0; i< tank_array.length; i++) {
          if ((nationocc.value == 'all')||(nationocc.value == nation_array[i]) || (i==0)){
             if ((typeocc.value == 'all')||(typeocc.value == type_array[i]) || (i==0)){
                if ((lvlocc.value == 'all')||(lvlocc.value == lvl_array[i]) || (i==0)){
                   var newTankListOption = document.createElement("OPTION");
                   newTankListOption.text = tank_array[i];
                   if (i==0) tank_array[i] = 'all';
                   newTankListOption.value = tank_array[i];
                   (TankList.options.add) ? TankList.options.add(newTankListOption) : TankList.add(newTankListOption, null);
                }
             }
          }
      }
   }
</script>
<div align="center" id="ajax_tanks_width">
    <br>
    <?=$lang['alltanks_choise_tank']?>

    <select id="nation" onchange="onComboChange();">
        <option value="all"><?=$lang['alltanks_all']?></option>
        <?php foreach($tanks_nation as $val){?>
            <option value="<?=$val['nation']?>"><?=$lang[$val['nation']]?></option>
            <?php } ?>
    </select>        
    <select id="type" onchange="onComboChange();">
        <option value="all"><?=$lang['alltanks_all']?></option>
        <?php foreach($tanks_types as $val){?>
            <option value="<?=$val['type']?>"><?=$lang[$val['type']]?></option>
            <?php } ?>
    </select>
    <select id="lvl" onchange="onComboChange();">
        <option value="all"><?=$lang['alltanks_all']?></option>
        <?php foreach($tanks_lvl as $key => $val){?>
            <option value="<?=$val['level']?>"><?=$val['level']?></option>
            <?php } ?>
    </select>
    <select id="tank" style="width: 200px;">
        <option value="all"><?=$lang['alltanks_all']?></option>
        <?php foreach($tank_array as $val){?>
            <option value="<?=$val['name_i18n']?>"><?=$val['name_i18n']?></option>
            <?php }; ?>
    </select>
    <a href="#tabs-<?php echo $key; ?>" id="change_button_tanks"><?=$lang['select_show'];?></a>
    <br>
    <div id="result" ></div>  
</div>