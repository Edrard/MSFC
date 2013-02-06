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

<script type="text/javascript" id="js">
   $(document).ready(function() {
      check_Width($("table#active_medal_1"), $("div#active_medal_width"));
   });
</script>

<?php if($config['cron'] == 1 && $col_check > 2 && count($main_progress['main']) > 0){  ?>
    <div  id="active_medal_width" align="center">
         <?php $mg = array();
               $acc_wm = array();
               $medals = array();
               $medals_count = array();
               $act_play_count = 0;
               if (count($medal_progress['unsort']) >0 ) {
                  foreach($medal_progress['sorted'] as $medal_group_key => $medal_group) {
                     foreach($medal_group as $account_id_key => $account_id) {
                        foreach($account_id as $medal_name => $medal_count){
                              if ((!in_array($medal_group_key, $mg))&& $medal_count > 0) $mg[] = $medal_group_key;
                              if ((!in_array($account_id_key, $acc_wm))&& $medal_count > 0) $acc_wm[] = $account_id_key;
                              if ((!in_array($medal_name, $medals))&& $medal_count > 0) $medals[] = $medal_name;
                              if (isset($medals_count[$account_id_key])) {
                              $medals_count[$account_id_key] += $medal_count;}
                              else {  $medals_count[$account_id_key]= $medal_count;};
                        }
                     }
                  };
                  foreach ($acc_wm as $acc_wm_key){
                    if(isset($roster_id[$acc_wm_key]['account_name'])){
                       ++$act_play_count;
                    }
                  }
                  if ($act_play_count > 0) {
         ?>
            <table id="active_medal_1" width="100%" cellspacing="1">
                   <thead>
                      <tr>
                         <th><?=$lang['name'];?></th>
                          <?php for ($i = 0; $i < sizeof($medals); $i++) {
                                $medal_key_img = './images/medals/'.ucfirst($medals[$i]).'.png';
                          ?>
                                <th align='center' class="{sorter: 'digit'} bb" <?php echo 'title="<table width=\'100%\' border=\'0\' class=\'ui-widget-content\' cellspacing=\'0\' cellpadding=\'0\'><tr><td><img src=\'./'.$medal_key_img.'\' /></td><td><span align=\'center\' style=\'font-weight: bold;\'>'.$lang['medal_'.$medals[$i]].'.</span><br> '.$lang['title_'.$medals[$i]].'</td></tr></table>"';?>><?php echo '<img src=\'./'.$medal_key_img.'\' style="width:60px;" /><br>'.$lang['medal_'.$medals[$i]]; ?></th>
                          <?php  }; ?>

                         <th align='center' class="{sorter: 'digit'} bb"><?=$lang['activity_4'];?></th>
                      </tr>
                   </thead>
                        <tbody>
                            <?php
                               foreach ($acc_wm as $acc_wm_key){
                                  if(isset($roster_id[$acc_wm_key]['account_name'])){?>
                                    <tr>
                                        <td><a href="<?=$config['base'].$roster_id[$acc_wm_key]['account_name'].'/'; ?>"
                                                target="_blank"><?=$roster_id[$acc_wm_key]['account_name']; ?></a></td>
                                     <?php
                                     foreach ($mg as $mg_key){
                                        foreach ($medals as $medal_key){
                                           if (isset ($medal_progress['sorted'][$mg_key][$acc_wm_key][$medal_key]))
                                           {?>
                                           <td align="center"><?=$medal_progress['sorted'][$mg_key][$acc_wm_key][$medal_key];?></td>
                                           <?php
                                           };
                                        }
                                     } ?>
                                       <td align='center' class="bb"><?=$medals_count[$acc_wm_key];?></th>
                                    </tr>
                                    <?php }
                                  }; ?>
                        </tbody>
             </table>
               <?php
                  } else {echo '<div class="num">'.$lang["err_med1"].'</div>';}
               } else {echo '<div class="num">'.$lang["err_med2"].'</div>';} ?>
    </div>
    <?php }else{ ?>
    <div class="num"><?=$lang['error_cron_off_or_none'];?></div>
    <?php } ?>