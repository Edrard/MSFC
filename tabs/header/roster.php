<?
  if($config['company'] == 1 and in_array($key,$company['tabs'])) {
   $roster_sortlist = '[[6,0],[5,1],[1,0]]';
  } else {
   $roster_sortlist = '[[5,0],[4,1],[1,0]]';
  }
?>
<script type="text/javascript">
var rosterWindow = 0;
$(document).ready(function() {
  var sorting = <?=$roster_sortlist; ?>;
  $("#tabs-sort-<?=$key;?>").trigger("sorton",[sorting]);

  $( "#player_result" ).dialog({
        title: "<?=$lang['st_title'];?>",
        appendTo: "#allcontainer",
        dialogClass: 'pstat',
        position: { my: "center", at: "top", of: "#allcontainer" },
        autoOpen: false,
        draggable: false,
        resizable: false,
        minWidth: 1024,
        closeOnEscape: true,
        modal: true,
        zIndex: 5,
        open: function( event, ui ) {
          sorting = $("#tabs-sort-<?=$key;?>")[0].config.sortList;
          $("#tabs-sort-<?=$key;?>").trigger("destroy");
          $('.pstat').css({'top': '100px'});
        },
        beforeClose: function( event, ui ) {
          $("#tabs-sort-<?=$key;?>").tablesorter({sortList: sorting});
          $("#allcontainer").css({'min-height': '100%'});
          location.href = "#tabs-<?=$key;?>";
          $(window).scrollTop( rosterWindow );
        }
  });
});

function plmagic(elem){
  $.ajax({
      cache: true,
      type: "POST",
      data: ({
        nickname   : $(elem).attr("alt")
      }),
      url: "ajax/ajax_player.php",
      beforeSend : function(data){
        rosterWindow = $(window).scrollTop();
        $("#player_result").dialog('open');
        $("#player_result").html("<center><?=$lang['index_loading'];?><br /><img src='./images/ajax-loader.gif'></center>");
      },
      success: function(msg){
        $("#player_result").empty();
        $("#player_result").html(msg);
        $("#allcontainer").css({'min-height': ($('.pstat').outerHeight(true) + 200) + 'px'});
      },
      complete: function() {
        $('body,html').scrollTop( 0 );
        $("#player0").add("#player01")
          <? for ($i=1; $i<=9; $i++) { ?>
            .add("#player<?=$i;?>")
          <? } ;?>
        .tablesorter({sortList:[[0,0]], theme : 'bootstrap'});
        $("#player10")
        <? for ($i=11; $i<=17; $i++) {?>
            .add("#player<?=$i;?>")
        <? } ;?>
        .tablesorter({sortList:[[1,1]], theme : 'jui'});

        $('.bb[title]').tooltip({
            track: false,
            delay: 0,
            fade: 250,
            items: "[title]",
            open: function (event, ui) {
                ui.tooltip.css("min-width", "400px");
            },
            content: function() {
                var element = $( this );
                if ( element.is( "[title]" ) ) {
                     return element.attr( "title" );
                }
            }
        });
      }
  });
  return true;
}
</script>