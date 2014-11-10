<?
  if($config['company'] == 1 and in_array($key,$company['tabs'])) {
   $roster_sortlist = '[6,0],[5,1],[1,0]';
  } else {
   $roster_sortlist = '[5,0],[4,1],[1,0]';
  }
?>
var sorting = [<?=$roster_sortlist;?>];
$("#tabs-sort-<?=$key;?>").trigger("sorton",[sorting]);