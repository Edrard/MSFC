<?php
    /*
    * Project:     Clan Stat
    * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
    * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
    * -----------------------------------------------------------------------
    * Began:       2011
    * Date:        $Date: 2013-11-20 00:00:00 +0200 $
    * -----------------------------------------------------------------------
    * @author      $Author: Edd, Exinaus, SHW  $
    * @copyright   2011-2013 Edd - Aleksandr Ustinov
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 3.0.0 $
    *
    */
?>
<script>
$(document).ready(function() {
  $( "#triggerrating" ).buttonset();
    $(".ratingplace").hide();
    $("#change_ratingplace").click(function() {
      $(".ratingvalue").hide();
      $(".ratingplace").show();
      check_Width($("table.table-id-<?=$key;?>"), $("div#tabs-<?=$key;?>"));
      return false;
    });
    $("#change_ratingvalue").click(function() {
      $(".ratingplace").hide();
      $(".ratingvalue").show();
      check_Width($("table.table-id-<?=$key;?>"), $("div#tabs-<?=$key;?>"));
      return false;
    });
    $('.bb[title]').tooltip({
          track: false,
          delay: 0,
          fade: 250,
          items: "[title]",
          content: function() {
              var element = $( this );
              if ( element.is( "[title]" ) ) {
                   return element.attr( "title" );
              }
          }
      });
});
</script>
<div align="center">
  <form>
    <div id="triggerrating" align="center">
        <input type="radio" id="change_ratingvalue" name="triggerrating" checked="checked" /><label for="change_ratingvalue"><?=$lang['show_ratingvalue'];?></label>
        <input type="radio" id="change_ratingplace" name="triggerrating" /><label for="change_ratingplace"><?=$lang['show_ratingplace'];?></label>
    </div>
  </form>

  <table id="rating_all" cellspacing="1" style="width: 100%;" class="table-id-<?=$key;?>">
    <thead>
      <tr>
         <?php echo '<th>'.$lang['name'].'</th>';
               $exp = array ('gr' => 'integrated_rating', 'wb' => 'battle_avg_performance', 'eb' => 'battle_avg_xp', 'win' => 'battle_wins', 'gpl' => 'battles',
                             'cpt' => 'ctf_points', 'dmg' => 'damage_dealt', 'dpt' => 'dropped_ctf_points', 'frg' => 'frags', 'spt' => 'spotted', 'exp' => 'xp');

               foreach($exp as $column =>$name){
                  $rname = $lang['r_'.$name];
                  echo "<th align='center' class=\"{sorter: 'digit'} bb ratingplace\" title='<font color=\"royalblue\">{$name}</font><br>{$rname}'><img src='".$config['rating_link'].$column.'.png'."' /><br>$rname</th>";
                  echo "<th align='center' class=\"{sorter: 'digit'} bb ratingvalue\" title='<font color=\"royalblue\">{$name}</font><br>{$rname}'><img src='".$config['rating_link'].$column.'.png'."' /><br>$rname</th>";
               } ?>
      </tr>
    </thead>
    <tbody><?php
       foreach($res as $name => $val){
          echo '<tr><td><a href="'.$config['base'].$name.'/'.'" target="_blank">'.$name.'</a></td>';
          foreach($exp as $cat) {
             echo '<td class="ratingplace">';
             if (isset($val['data']['ratings'][$cat]['place']) && ($val['data']['ratings'][$cat]['place'] <>'')) {
                 echo $val['data']['ratings'][$cat]['place'];
             }   else {
                 echo '-';
             }
             echo '</td>';
             echo '<td class="ratingvalue">';
             if (isset($val['data']['ratings'][$cat]['value'])){
                 echo $val['data']['ratings'][$cat]['value'];
             }   else {
                 echo '-';
             }
             echo '</td>';
          }
          echo '</tr>';
       } ?>
    </tbody>
  </table>
</div>