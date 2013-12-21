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
    * @version     $Rev: 3.0.2 $
    *
    */


    if($config['cron'] == 1 && $col_check > 2 && count($main_progress['main']) > 0){ 
        $rand_main_progress = array_rand($main_progress['main'], 1);
        $stats2 = array('wins', 'losses', 'survived_battles', 'frags',
                        'spotted', 'damage_dealt', 'damage_received',
                        'capture_points', 'dropped_capture_points',  'xp'
                        // 'shots',  'hits',  'draws', 'battle_avg_xp', 'hits_percents',
                         );
        $stats7 = array('losses', 'wins', 'survived_battles');
        $ni = array ('battles');
    ?>
    <script type="text/javascript">
        $(document).ready(function(){
            $(".all_progress_hide").hide();
            $(".main_progress").show();
            $("#trigger_progress").buttonset();
            $("#show_main_progress").click(function() {
                $(".all_progress_hide").hide();
                $(".main_progress").show();
                check_Width($("table.table-id-<?=$key;?>"), $("div#tabs-<?=$key;?>"));
                return false;
            });
            $("#show_average_progress").click(function() {
                $(".all_progress_hide").hide();
                $(".average_progress").show();
                check_Width($("table.table-id-<?=$key;?>"), $("div#tabs-<?=$key;?>"));
                return false;
            });
        });
    </script>
    <div align="center">
        <form>
            <div id="trigger_progress" align="center">
                <input type="radio" id="show_main_progress" name="trigger_progress" checked="checked" /><label for="show_main_progress"><?=$lang['activity_main_progress'];?></label>
                <input type="radio" id="show_average_progress" name="trigger_progress" /><label for="show_average_progress"><?=$lang['activity_average_progress'];?></label>
            </div>
        </form>
        <table id="active_main" cellspacing="1" class="table-id-<?=$key;?> ui-widget-content">
            <thead>
                <tr>
                  <th><?=$lang['name'];?></th>
                  <th><?=$lang['all_battles'];?></th>
                  <?
                  foreach ($stats2 as $val) {
                     if ($main_progress['totaldiff']['all'][$val] <>0) {?>
                         <th class="{sorter: 'digit'} all_progress_hide main_progress"><?=$lang['all_'.$val];?></th>
                  <? }
                     if (($main_progress['totalavr']['all'][$val] <>0)&&(!in_array($key,$ni))) {?>
                         <th class="{sorter: 'digit'} all_progress_hide average_progress"><?=$lang['all_'.$val];?></th>
                  <? }
                  } ?>
                </tr>  
            </thead>
            <tbody>
            <?php foreach ($roster_id as $acc_id =>$val2) {
                     echo '<tr>';
                     echo '<td><a href="',$config['base'],$roster_id[$acc_id]['account_name'],'/','" target="_blank">',$roster_id[$acc_id]['account_name'],'</a></td>';
                     echo '<td>';
                     if (isset($main_progress['delta'][$acc_id]['all']['battles']) && ($main_progress['delta'][$acc_id]['all']['battles']<>0)) {
                                echo $main_progress['delta'][$acc_id]['all']['battles'];
                            }
                     echo '</td>';
                     foreach ($stats2 as $val) {

                        if ($main_progress['totaldiff']['all'][$val] <>0) {
                            echo '<td class="all_progress_hide main_progress">';
                            if (isset($main_progress['delta'][$acc_id]['all'][$val]) && ($main_progress['delta'][$acc_id]['all'][$val]<>0)) {
                                echo $main_progress['delta'][$acc_id]['all'][$val];
                            }
                            echo '</td>';
                        }

                        if (($main_progress['totalavr']['all'][$val] <>0)&&(!in_array($val,$ni))) {
                            echo '<td class="all_progress_hide average_progress">';
                            if (isset($main_progress['average'][$acc_id]['all'][$val]) && ($main_progress['average'][$acc_id]['all'][$val]<>0)) {
                                if (in_array($val,$stats7)) {
                                    echo '<span class ="bb" title="';
                                    if ($main_progress['main'][$acc_id]['all'][$val]>0) echo '+';
                                    echo $main_progress['main'][$acc_id]['all'][$val],'">';
                                    echo $main_progress['average'][$acc_id]['all'][$val]*100,'%</span>';
                                }   else {
                                    echo round($main_progress['average'][$acc_id]['all'][$val],2);
                                }
                            }
                            echo '</td>';
                        }
                     }
                     echo '</tr>';
                 } ?>
            </tbody>  
        </table>
    </div>
    <?php }else{ ?>
    <div class="num"><?=$lang['error_cron_off_or_none'];?></div>
    <?php } ?>