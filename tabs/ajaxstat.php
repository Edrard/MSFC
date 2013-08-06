<?php
    /*
    * Project:     Clan Stat
    * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
    * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
    * -----------------------------------------------------------------------
    * Began:       2011
    * Date:        $Date: 2011-10-24 11:54:02 +0200 $
    * -----------------------------------------------------------------------
    * @author      $Author: SHW $
    * @copyright   2012-2012 SHW
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 2.2.0 $
    *
    */
?>
<?php
    $lang['tank_choise'] = 'Выберите фильтр или танк:';
    $tanks_nation_array = tanks_nations();
    $tanks_types_array = tanks_types();
    $tanks_lvl_array = tanks_lvl();
    sort($tanks_lvl_array);

    $sql = "SELECT * FROM `tanks` ORDER BY tank;";
    $q = $db->prepare($sql);
    if ($q->execute() == TRUE) {
        $tanksks = $q->fetchAll();
    } else {
        die(show_message($q->errorInfo(),__line__,__file__,$sql));
    }

    foreach($tanksks as $val){
    $tank_array[] = $val['tank'];
    }
    sort($tank_array);
?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#change_button_tank").button();
        $("#change_button_tank").click( function() {
            $.ajax({
                cache: true,
                type: "POST",
                data: ({
                  type    : $('#typeocc').val(),
                  nation  : $('#nationocc').val(),
                  lvl     : $('#lvlocc').val(),
                  tank    : $('#tankocc').val(),
                  db_pref : '<?php echo $db->prefix; ?>',
                  key     : '<?=$key;?>'
                }),
                url: "./ajax/tankstat.php",
                success: function(msg){
                    $("#result_tankstat").html(msg).show();
                },
                complete: function() {
                  check_Width($("table.table-id-<?=$key;?>"), $("div#tabs-<?=$key;?>"));
                }
            });
            return false;
        });
        $('#lvlocc').val(function() {
        return '-1';
        });
        $("#change_button_tank").click();
        $('#lvlocc').val(function() {
        return 'all';
        });
    });

   function onComboChange() {
      var nationocc = document.getElementById('nationocc');
      var typeocc = document.getElementById('typeocc');
      var lvlocc = document.getElementById('lvlocc');
      var TankList = document.forms["allt"].elements["tankocc"];
      var tank_array = new Array ('Все' <?php foreach($tanksks as $val){echo ", '".$val['tank']."'";}?>);
      var nation_array = new Array ('' <?php foreach($tanksks as $val){echo ", '".$val['nation']."'";}?>);
      var type_array = new Array ('' <?php foreach($tanksks as $val){echo ", '".$val['type']."'";}?>);
      var lvl_array = new Array ('' <?php foreach($tanksks as $val){echo ", '".$val['lvl']."'";}?>);
      TankList.length = 0;
      for (var i = 0; i< tank_array.length; i++) {
          if ((nationocc.value == 'all')||(nationocc.value == nation_array[i]) || (i==0)){
             if ((typeocc.value == 'all')||(typeocc.value == type_array[i]) || (i==0)){
                if ((lvlocc.value == 'all')||(lvlocc.value == lvl_array[i]) || (i==0)){
                   if (document.createElement){
                      var newTankListOption = document.createElement("OPTION");
                      newTankListOption.text = tank_array[i];
                      if (i==0) tank_array[i] = 'all';
                      newTankListOption.value = tank_array[i];
                      (TankList.options.add) ? TankList.options.add(newTankListOption) : TankList.add(newTankListOption, null);
                      }else{
                      // для NN3.x-4.x
                      //TankList.options[i] = new Option(tank_array[i], tank_array[i], false, false);
                      }
                }
             }
          }
      }
   }

</script>
<div align="center" id="tankstat_width">
    <br>
    <?=$lang['tank_choise'];?>
<form name="allt" action="#">
    <select name="nationocc" id="nationocc" onchange="onComboChange();">
        <option value="all"><?=$lang['alltanks_all']?></option>
        <?php foreach($tanks_nation_array as $val){?>
            <option value="<?=$val['nation']?>"><?=$lang[$val['nation']]?></option>
            <?php } ?>
    </select>        
    <select id="typeocc" id="typeocc" onchange="onComboChange();">
        <option value="all"><?=$lang['alltanks_all']?></option>
        <?php foreach($tanks_types_array as $val){?>
            <option value="<?=$val['type']?>"><?=$lang[$val['type']]?></option>
            <?php } ?>
    </select>
    <select id="lvlocc" id="lvlocc" onchange="onComboChange();">
        <option value="all"><?=$lang['alltanks_all']?></option>
        <?php foreach($tanks_lvl_array as $val){?>
            <option value="<?=$val['lvl']?>"><?=$val['lvl']?></option>
            <?php } ?>
            <option value="-1" style="display:none;" disabled="disabled">-1</option>
    </select>

    <select name="tankocc" id="tankocc" style="width: 200px;">
        <option value="all"><?=$lang['alltanks_all']?></option>
        <?php foreach($tanksks as $val){?>
            <option height="14px" class="tankss" value="<?=$val['tank']?>"><?=$val['tank']?></option>
            <?php }; ?>
    </select>
    <a href="#tabs-<?=$key; ?>" id="change_button_tank"><?=$lang['select_show'];?></a>
</form>
    <br>
    <div id="result_tankstat" ></div>
</div>