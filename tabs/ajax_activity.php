<?php
    /*
    * Project:     Clan Stat
    * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
    * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
    * -----------------------------------------------------------------------
    * Began:       2011
    * Date:        $Date: 2013-11-22 00:00:00 +0200 $
    * -----------------------------------------------------------------------
    * @author      $Author: Edd, Exinaus, SHW  $
    * @copyright   2011-2013 Edd - Aleksandr Ustinov
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 3.1.0 $
    *
    */
?>
<?php
$sql = "SELECT MIN(updated_at) FROM `col_players`;";
$q = $db->prepare($sql);
     if ($q->execute() == TRUE) {
         $mindate = $q->fetchColumn();
     }   else {
         $mindate = 0;
     }
?>
<script type="text/javascript">

    $(document).ready(function(){
        $( "#a_from" ).datepicker({
            defaultDate: "-8d",
            firstDay: "1",
            minDate: new Date(<?=$mindate;?>*1000),
            maxDate: "0",
            dateFormat: 'dd.mm.yy',
            onSelect: function( selectedDate ) {
                $( "#a_to" ).datepicker( "option", "minDate", selectedDate );
                //alert(selectedDate);
            }
        });
        $( "#a_to" ).datepicker({
            defaultDate: "-1d",
            firstDay: "1",
            dateFormat: 'dd.mm.yy',
            maxDate: "0",
            onSelect: function( selectedDate ) {
                $( "#a_from" ).datepicker( "option", "maxDate", selectedDate );
            }
        });
        $( "#a_from" ).datepicker( "setDate", "-8d" );
        $( "#a_to" ).datepicker( "setDate", "-1d" );

        $("#a_show_activity").button();

        $("#a_show_activity").click( function() {
            $.ajax({
                cache: true,
                type: "POST",
                data: {
                  a_from  :  $('#a_from').val(),
                  a_to    :  $('#a_to').val(),
                  db_pref : '<?php echo $db->prefix; ?>',
                  key     : '<?=$key;?>'
                },
                url: "./ajax/ajax_activity.php",
                success: function(msg){
                    $("#activity_result").html(msg).show();
                }
            });
            return false;
        });
        $("#a_show_activity").click();
        $('#id-<?=$key;?>').click(function() {
           $("#activity_table").trigger('applyWidgets');
           return false;
        });
});
</script>
<div align="center" id="ajax_activity_width">
    <form method="post" enctype="multipart/form-data">
    <br />
    <?=$lang['activity_1'];?>
    <input type="text" id="a_from" name="a_from" value="" />
    <?=$lang['activity_2'];?>
    <input type="text" id="a_to" name="a_to" value="" />
    <a href="#tabs-<?php echo $key; ?>" id="a_show_activity"><?=$lang['select_show'];?></a>

    </form>

    <div id="activity_result"></div>
</div>