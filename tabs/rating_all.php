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
    * @version     $Rev: 3.2.3 $
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
      return false;
    });
    $("#change_ratingvalue").click(function() {
      $(".ratingplace").hide();
      $(".ratingvalue").show();
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

  <table id="tabs-sort-<?=$key;?>" cellspacing="1" style="width: 100%;" class="table-id-<?=$key;?>">
    <thead>
      <tr>
          <th><?=$lang['name']; ?></th>
          <?php if($config['company'] == 1 and in_array($key,$company['tabs'])) { ?>
              <th><?=$lang['company']; ?></th>
          <?php } ?>
               <?php  /* Старые рейтинги
                   $exp = array ('gr' => 'integrated_rating', 'wb' => 'battle_avg_performance', 'eb' => 'battle_avg_xp', 'win' => 'battle_wins', 'gpl' => 'battles',
                             'cpt' => 'ctf_points', 'dmg' => 'damage_dealt', 'dpt' => 'dropped_ctf_points', 'frg' => 'frags', 'spt' => 'spotted', 'exp' => 'xp');
                   */
                   $exp = array ('global_rating','battles_count','wins_ratio','survived_ratio','frags_count','damage_dealt','xp_avg','xp_max','hits_ratio');

               foreach($exp as $column){
               ?>
                  <th align='center' valign='top' class="{sorter: 'digit'} ratingplace"><div align='center' class='rating_ico rating_ico_<?=$column;?>'></div><br><?=$lang['r_'.$column];?></th>
                  <th align='center' valign='top' class="{sorter: 'digit'} ratingvalue"><div align='center' class='rating_ico rating_ico_<?=$column;?>'></div><br><?=$lang['r_'.$column];?></th>
               <?php } ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach($res as $name => $val){ ?>
          <tr>
             <td><a href="<?php echo $config['base'],$val['data']['account_id'].'-'.$name,'/'; ?>" target="_blank"><?=$name; ?></a></td>
          <?php if($config['company'] == 1 and in_array($key,$company['tabs'])) { ?>
             <td><?=in_array($val['data']['account_id'],$company['in_company'])?$company['company_names'][$company['by_id'][$val['data']['account_id']]]:'';?></td>
          <?php } ?>
          <?php foreach($exp as $cat) { ?>
             <td class="ratingplace">
               <?php echo (isset($val['data']['ratings'][$cat]['rank']) && ($val['data']['ratings'][$cat]['rank'] <>''))?$val['data']['ratings'][$cat]['rank']:'-'; ?>
             </td>
             <td class="ratingvalue">
               <?=(isset($val['data']['ratings'][$cat]['value']))?$val['data']['ratings'][$cat]['value']:'-';?>
             </td>
          <?php } ?>
          </tr>
      <?php } ?>
    </tbody>
  </table>
</div>