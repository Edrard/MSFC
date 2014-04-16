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
    * @version     $Rev: 3.0.4 $
    *
    */

 if($config['cron'] == 1 && $col_check > 2 && count($main_progress['main']) > 0){
         $stats2 = array('wins', 'losses', 'survived_battles', 'frags', 'spotted', 'damage_dealt',
                         'damage_received', 'capture_points', 'dropped_capture_points', 'xp', 'battles',
                         'draws', 'battle_avg_xp', 'hits_percents'
                         );
        $ni = array('hits', 'shots');
?>
    <div align="center">
    <table cellspacing="2" cellpadding="8" width="100%" class="ui-widget-content table-id-<?=$key;?>" style="border-width: 0; ">
            <tbody>
                <tr>
                    <td align="center" style="font-size: 15px;font-weight: bold;"><?=$lang['players_best_results'];?></td>
                    <td align="center" style="font-size: 15px;font-weight: bold;"><?=$lang['players_best_medals'];?></td>
                </tr>
                <tr>
                    <td valign="top" width="50%" align="center">
                        <?php if(time_summer($best_main_progress['all'],'value') != 0){ ?>
                            <table id="best_main" cellspacing="1" width="100%">
                                <thead> 
                                    <tr>
                                        <th></th>
                                        <th><?=$lang['name']?></th> 
                                        <th><?=$lang['value']?></th>
                                    </tr>  
                                </thead>
                                <tbody>
                                    <?php foreach($best_main_progress['all'] as $valwls => $val){ ?>
                                        <?php if($val['value'] != 0 && !in_array($valwls,$ni)){ ?>
                                            <tr> 
                                                <td><?=$lang['all_'.$valwls];?></td>
                                                <td><a href="<?php echo $config['base'],$roster_id[$val['account_id']]['account_name'],'/'; ?>" target="_blank"><?=$roster_id[$val['account_id']]['account_name']; ?></a></td>
                                                <td><?=$val['value'];?></td>
                                            </tr>
                                            <?php } ?>
                                        <?php } ?>
                                </tbody>  
                            </table>
                            <?php } ?>
                    </td>
                    <td valign="top" width="50%" align="center">
                    <?php if(!empty($medal_resort['list'])){
                           if(time_summer($best_medal_progress,'value') != 0){ ?>
                            <table id="best_medal" cellspacing="1" width="100%">
                                <thead> 
                                    <tr>
                                        <th colspan="2"><?=$lang['achiv']?></th>
                                        <th><?=$lang['name']?></th> 
                                        <th><?=$lang['value']?></th>
                                    </tr>  
                                </thead>
                                <tbody>
                                    <?php foreach($best_medal_progress as $name => $val){ ?>  
                                        <?php if($val['value'] != 0){ ?>
                                            <tr>
                                                <td align="center" class="bb" <?php echo 'title="<table width=\'100%\' border=\'0\'  class=\'ui-widget-content\' cellspacing=\'0\' cellpadding=\'0\'><tr><td><img src=\'',$medn[$name]['img'],'\' /></td><td><span align=\'center\' style=\'font-weight: bold;\'>',$lang['medal_'.$name],'.</span><br> ',$lang['title_'.$name],'</td></tr></table>"';?>><img height="25px" src='<?=$medn[$name]['img'];?>' /></td>
                                                <td valign="middle"><?=$lang['medal_'.$name];?></td>
                                                <td valign="middle"><a href="<?php echo $config['base'],$roster_id[$val['account_id']]['account_name'],'/'; ?>" target="_blank">
                                                    <?php echo $roster_id[$val['account_id']]['account_name']; ?></a></td>
                                                <td valign="middle"><?=$val['value'];?></td>
                                            </tr>
                                            <?php } ?>
                                        <?php } ?>
                                </tbody>  
                            </table>
                            <?php }
                            } else {echo '<div class="num">',$lang["err_med2"],'</div>';}?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php }else{ ?>
    <div class="num"><?=$lang['error_cron_off_or_none'];?></div>
    <?php } ?>