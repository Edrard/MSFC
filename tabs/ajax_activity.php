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
<script type="text/javascript">

    $(document).ready(function(){
        $( "#a_from" ).datepicker({
            defaultDate: "-1d",
            firstDay: 1,
            maxDate: 0,
            dateFormat: 'dd.mm.yy',
            onSelect: function( selectedDate ) {
                $( "#a_to" ).datepicker( "option", "minDate", selectedDate );
                //alert(selectedDate);
            }
        });
        $( "#a_to" ).datepicker({
            defaultDate: 0,
            firstDay: 1,
            dateFormat: 'dd.mm.yy',
            maxDate: 0,
            onSelect: function( selectedDate ) {
                $( "#a_from" ).datepicker( "option", "maxDate", selectedDate );
            }
        });
        $("#a_show_activity").button();

        $("#a_show_activity").click( function() {
            $.ajax({
                cache: true,
                type: "POST",
                data: {
                  a_from  :  $('#a_from').val(),
                  a_to    :  $('#a_to').val(),
                  a_all   : ($("#a_all").is(":checked") ? 1:0),
                  a_total : ($("#a_total").is(":checked") ? 1:0),
                  a_cat_1 : ($("#a_cat_1").is(":checked") ? 1:0),
                  a_cat_2 : ($("#a_cat_2").is(":checked") ? 1:0),
                  a_cat_3 : ($("#a_cat_3").is(":checked") ? 1:0),
                  a_cat_4 : ($("#a_cat_4").is(":checked") ? 1:0),
                  db_pref : '<?php echo $db->prefix; ?>'
                },
                url: "./ajax/activity.php",
                success: function(msg){
                    $("#activity_result").html(msg).show();
                    check_Width($("table#activity_table"), $("div#ajax_activity_width"));
                }
            });
            return false;
        });
        $.ajax({
            cache: true,
            type: "POST",
            //data: 'a_from='+ $('#a_from').val()+'&a_to='+$('#a_to').val()+'&a_all=1&a_cat_1=1&a_cat_2=1&a_cat_3=1&a_cat_4=1',
            data: {
              a_from  :  $('#a_from').val(),
              a_to    :  $('#a_to').val(),
              a_all   :  1,
              a_total :  0,
              a_cat_1 :  1,
              a_cat_2 :  1,
              a_cat_3 :  1,
              a_cat_4 :  1,
              db_pref : '<?php echo $db->prefix; ?>'
            },
            url: "./ajax/activity.php",
            success: function(msg){
                $("#activity_result").html(msg).show();
                check_Width($("table#activity_table"), $("div#ajax_activity_width"));
            }
        });
        $("#activity_settings_b").button({
            icons: {
            primary: "ui-icon-gear",
            secondary: "ui-icon-triangle-1-s"
            },
            text: false
        }).click( function() {
          $("#activity_settings_m").toggle();
          return false;
        });
        $("#activity_settings_m").hide();
        $('#id-<?=$key;?>').click(function() {
           $("#activity_table").trigger('applyWidgets');
           return false;
        });

<?php if($logged > 1) { ?>

        $("#activity_add_replay").button({
            icons: {
            primary: "ui-icon-plus"
            },
            text: false
        });
        var id = 2;
        var form = '';
        $("#activity_add_replay").click( function() {
          //1
          $("#activity_upload_form").append('<br /><input type="file" name="filename'+id+'">');
          form = form + '<select name="cat'+id+'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
          form = form + '<option value="cat_1" selected="selected"><?=$lang["a_cat_1"];?></option>';
          form = form + '<option value="cat_2"><?=$lang["a_cat_2"];?></option>';
          form = form + '<option value="cat_3"><?=$lang["a_cat_3"];?></option>';
          form = form + '<option value="cat_4"><?=$lang["a_cat_4"];?></option>';
          form = form + '</select>';
          $("#activity_upload_form").append(form);
          id = id + 1;
          form = '';
          return false;
        });
        $("#activity_upload_b").button({
            icons: {
            primary: "ui-icon-folder-open"
            },
            text: false
        }).click( function() {
          $("#activity_upload_form_main").toggle();
          return false;
        });
        $("#activity_upload_form_main").hide();
<? } ?>
});
</script>
<div align="center" id="ajax_activity_width">
    <form method="post" enctype="multipart/form-data">
    <br />
    <?=$lang['activity_1'];?>
    <input type="text" id="a_from" name="a_from" value="" />
    <?=$lang['activity_2'];?>
    <input type="text" id="a_to" name="a_to" value="" />
    <button id="activity_settings_b">Settings</button>
    <?php if($logged > 1) { ?>
    <button id="activity_upload_b">Upload</button>
    <?php } ?>
    <a href="#tabs-<?php echo $key; ?>" id="a_show_activity"><?=$lang['select_show'];?></a>
    <br />
    <div id="activity_settings_m" style="text-align: left; width: 400px;" align="center">
    <div align="center"><?=$lang['activity_6'];?></div>
    <input type="checkbox" name="a_cat_1" id="a_cat_1" style="margin:0px; padding:0px;" checked="checked" /> - <?=$lang['a_cat_1'];?><br />
    <input type="checkbox" name="a_cat_2" id="a_cat_2" style="margin:0px; padding:0px;" checked="checked" /> - <?=$lang['a_cat_2'];?><br />
    <input type="checkbox" name="a_cat_3" id="a_cat_3" style="margin:0px; padding:0px;" checked="checked" /> - <?=$lang['a_cat_3'];?><br />
    <input type="checkbox" name="a_cat_4" id="a_cat_4" style="margin:0px; padding:0px;" checked="checked" /> - <?=$lang['a_cat_4'];?><br />
    <br />
    <input type="checkbox" name="a_all" id="a_all" checked="checked" style="margin:0px; padding:0px;" /> - <?=$lang['activity_3'];?><br />
    <input type="checkbox" name="a_total" id="a_total" style="margin:0px; padding:0px;" /> - <?=$lang['activity_5'];?><br />
    </div>
    </form>
    <? if($logged > 1) { ?>
    <br />
    <div align="left" id="activity_upload_form_main">
    <form action="./main.php#tabs-<?php echo $key; ?>" method="post" enctype="multipart/form-data">
    <span id="activity_upload_form">
    <input type="file" name="filename1"><select name="cat1">
      <option value="cat_1" selected="selected"><?=$lang['a_cat_1'];?></option>
      <option value="cat_2"><?=$lang['a_cat_2'];?></option>
      <option value="cat_3"><?=$lang['a_cat_3'];?></option>
      <option value="cat_4"><?=$lang['a_cat_4'];?></option>
    </select>
    </span>
    <button id="activity_add_replay">Add replay</button>
    <br />
    <input type="submit" value="<? echo $lang['gk_info_1']; ?>" class="gksubmit" name="activityreplay">
    </form>
    </div>
    <?php } ?>
    <? if(isset($activity_error)) { echo '<div align="left">'.$activity_error.'</div>'; } ?>
    <div id="activity_result"></div>
</div>
