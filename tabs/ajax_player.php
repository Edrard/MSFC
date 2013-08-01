<?php
    /*
    * Project:     Clan Stat
    * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
    * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
    * -----------------------------------------------------------------------
    * Began:       2012
    * Date:        $Date: 2012-12-01 $
    * -----------------------------------------------------------------------
    * @author      $Author: SHW $
    * @copyright   2011-2012 SHW
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 2.2.0 $
    *
    */
?>
<?php $mindate = array();
      foreach($res as $name => $val){
         $arrname[$name]= strtoupper($name);
      }
      asort($arrname);
      foreach($arrname as $key2 => $name){
         $sql = "SELECT MIN(up) FROM `col_players` where name='".$key2."';";
         $q = $db->prepare($sql);
         if ($q->execute() == TRUE) {
             $mindate[$key2] = $q->fetchColumn();
         }   else {
             $mindate[$key2] = 0;
         }
      }
?>
<script type="text/javascript">
function smtmagic()
        {
            miscval = <?php if (isset($mindate)) {echo json_encode($mindate);} else {echo 'array ();';}; ?>;
            $( "#b_from" ).datepicker( "option", "minDate", new Date(miscval[$('#b_player').val()]*1000) );
            if ($('#b_from').val() >= $('#b_to').val()) { $( "#b_to" ).datepicker( "setDate", new Date()); }
        };
    $(document).ready(function(){
        $( "#b_from" ).datepicker({
            defaultDate: "-1d",
            altFormat: '@' ,
            firstDay: 1,
            maxDate: 0,
            dateFormat: 'dd.mm.yy',
            onSelect: function( selectedDate ) {
                $( "#b_to" ).datepicker( "option", "minDate", selectedDate );
            }
        });
        $( "#b_to" ).datepicker({
            defaultDate: 0,
            firstDay: 1,
            dateFormat: 'dd.mm.yy',
            maxDate: 0,
            onSelect: function( selectedDate ) {
                $( "#b_from" ).datepicker( "option", "maxDate", selectedDate );
            }
        });
        $("#b_show_activity").button();

        $("#b_show_activity").click( function() {
            $.ajax({
                cache: true,
                type: "POST",
                data: ({
                  b_from   : $('#b_from').val(),
                  b_to     : $('#b_to').val(),
                  b_player : $('#b_player').val(),
                  db_pref : '<?php echo $db->prefix; ?>'
                }),
                url: "./ajax/player_result.php",
                success: function(msg){
                    $("#player_result").addClass("ui-state-disabled");
                    $("#player_result").html(msg).show();
                },
                complete: function() {
                  $("#player_result").removeClass("ui-state-disabled");
                  check_Width($("table#tmain"), $("div#tabs-<?=$key;?>"));
                }
            });
            return false;
        });
        $("#b_show_activity").click();
        smtmagic();
});
</script>
<?php $mindate = array(); ?>
<div align="center" id="ajax_player_result_width">
    <form method="post" enctype="multipart/form-data">
    <br />
    <?=$lang['name'];?>
    <select id="b_player" onchange="smtmagic();">
            <?php foreach($arrname as $key2 => $name){ ?>
            <option value="<?=$key2;?>"><?=$key2;?></option>
            <?php } ?>
    </select>
    <?=$lang['activity_1'];?>
    <input type="text" id="b_from" name="b_from" value="" />
    <?=$lang['activity_2'];?>
    <input type="text" id="b_to" name="b_to" value="" />
    <a href="#tabs-<?php echo $key; ?>" id="b_show_activity"><?=$lang['select_show'];?></a>
    </form>
    <div id="player_result"></div>
</div>