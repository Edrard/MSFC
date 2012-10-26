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
    * @version     $Rev: 2.1.4 $
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
                data: 'a_from='+ $('#a_from').val()+'&a_to='+$('#a_to').val()+'&a_all='+($("#a_all").is(":checked") ? 1:0),
                url: "./ajax/activity.php",
                success: function(msg){
                    $("#activity_result").html(msg).show();
                }
            });
        });
        $.ajax({
            cache: true,
            type: "POST",
            data: 'a_from='+ $('#a_from').val()+'&a_to='+$('#a_to').val()+'&a_all=1',
            url: "./ajax/activity.php",
            success: function(msg){
                $("#activity_result").html(msg).show();
            }
        });
});
</script>
<div align="center">
    <form method="post" enctype="multipart/form-data">
    <br />
    <?=$lang['activity_1'];?>
    <input type="text" id="a_from" name="a_from" value="" />
    <?=$lang['activity_2'];?>
    <input type="text" id="a_to" name="a_to" value="" />
    <a href="#" id="a_show_activity"><?=$lang['select_show'];?></a>
    <br />
    <input type="checkbox" name="a_all" id="a_all" checked="checked" style="margin:0px; padding:0px;" /> - <?=$lang['activity_3'];?>
    </form>
    <? if($logged > 1) { ?>
    <form action="./main.php#tabs-<?php echo $key; ?>" method="post" enctype="multipart/form-data">
    <input type="file" name="filename">
    <input type="submit" value="<? echo $lang['gk_info_1']; ?>" class="gksubmit" name="activityreplay">
    </form>
    <?php } ?>
    <? if(isset($activity_error)) { echo '<br />'.$activity_error; } ?>
    <div id="activity_result"></div>
</div>
