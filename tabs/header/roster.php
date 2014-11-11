<?
  if($config['company'] == 1 and in_array($key,$company['tabs'])) {
   $roster_sortlist = '[[6,0],[5,1],[1,0]]';
  } else {
   $roster_sortlist = '[[5,0],[4,1],[1,0]]';
  }
?>
<script type="text/javascript">
$(document).ready(function() {
  var sorting = <?=$roster_sortlist;?>;
  $("#tabs-sort-<?=$key;?>").trigger("sorton",[sorting]);

  $( "#player_result" ).dialog({
        title: "<?=$lang['st_title'];?>",
        appendTo: "#allcontainer",
        dialogClass: 'pstat',
        position: { my: "center", at: "top", of: "#allcontainer" },
        autoOpen: false,
        draggable: false,
        resizable: false,
        width: 1024,
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
        }
  });
});
</script>