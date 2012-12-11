<?php
    /*
    * Project:     Clan Stat
    * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
    * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
    * -----------------------------------------------------------------------
    * Began:       2011
    * Date:        $Date: 2011-10-24 11:54:02 +0200 $
    * -----------------------------------------------------------------------
    * @author      $Author: Edd $
    * @copyright   2011-2012 Edd - Aleksandr Ustinov
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 2.1.6 $
    *
    */
?>
<div align="center">
    <?php if($config['cron'] == 1 && $col_check > 2 && count($main_progress) > 0){ ?>
        <table cellspacing="1" cellpadding="8" border="0">
            <thead>
                <tr>
                    <th align="center" style="font-size: 15px;font-weight: bold;"><?=$lang['loosed_today']?></th>
                    <th align="center" style="font-size: 15px;font-weight: bold;"><?=$lang['new_tanks']?></th>
                    <th align="center" style="font-size: 15px;font-weight: bold;"><?=$lang['new_players']?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td valign="top">
                        <table cellspacing="2" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <?php if (count($we_loosed) > 0){?>
                                    <?php foreach($we_loosed as $val){ ?>
                                        <tr>
                                            <td width="34%" align="left"><a href="<?php echo $config['base'].$val['name'].'/'; ?>" 
                                                    target="_blank"><?=$val['name'];?></a></td>
                                            <td width="33%" align="center"><?=$val['role']?></td>
                                            <td width="33%" align="center"><?php echo floor((time() - mktime(0, 0, 0, date("m", $val['member_since']), date("d", $val['member_since']), date("Y", $val['member_since'])))/(3600*24)); ?><?=$lang['days']?></td>
                                        </tr>
                                        <?php } ?>
                                    <?php }else{ ?>
                                    <tr>
                                        <td align="center"><?=$lang['noone'];?></td>
                                    </tr>
                                    <?php } ?>
                            </tbody>
                        </table>
                    </td>
                    <td valign="top">
                        <table cellspacing="2" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <?php foreach($new_tanks as $name => $val){ ?>
                                    <?php if(isset($roster_id[$val]['name'])){ ?>
                                        <tr>
                                            <td width="30%" align="left"><a href="<?php echo $config['base'].$roster_id[$val]['name'].'/'; ?>" 
                                                    target="_blank"><?=$roster_id[$val]['name'];?></a></td>
                                            <td width="70%" align="right"><?=$name;?></td>
                                        </tr>
                                        <?php } ?>
                                    <?php } ?>
                            </tbody>
                        </table>
                    </td>
                    <td valign="top">
                        <table cellspacing="2" cellpadding="0" border="0" width="100%">
                            <tbody> 
                                <?php if (count($new_players) > 0){?>
                                    <?php foreach($new_players as $name => $val){ ?>
                                        <tr>
                                            <td width="34%" align="left"><a href="<?php echo $config['base'].$val['name'].'/'; ?>" 
                                                    target="_blank"><?=$val['name'];?></a></td>
                                            <td width="33%" align="center"><?=$val['role']?></td>
                                            <td width="33%" align="center"><?php echo floor((time() - mktime(0, 0, 0, date("m", $val['member_since']), date("d", $val['member_since']), date("Y", $val['member_since'])))/(3600*24)); ?><?=$lang['days']?></td>
                                        </tr>
                                        <?php } ?>
                                    <?php }else{ ?>
                                    <tr>
                                        <td align="center"><?=$lang['noone'];?></td>
                                    </tr>
                                    <?php } ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <? } ?>
    <table id="roster" class="tablesorter wid" cellspacing="1">              
        <thead> 
            <tr>
                <th><?php echo $lang['name']; ?></th> 
                <th>ID</th> 
                <th><?php echo $lang['in_clan']; ?></th>
                <th><?php echo $lang['day_clan']; ?></th> 
                <th><?php echo $lang['role']; ?></th>
                <th><?php echo $lang['dateof']; ?></th>
            </tr>  
        </thead>
        <tbody>
            <?php foreach($new['data']['request_data']['items'] as $val){ 
                    if($val['member_since'] == ''){
                        $val['member_since'] = '1300000000';
                    }
                    $date = date('Y.m.d',$val['member_since']);
                    if(!isset($res[$val['name']]['date']['local_num'])) {$roster_local_num = '1300000000';} else {$roster_local_num = $res[$val['name']]['date']['local_num'];}
                    $diff_date = round(((mktime() - $roster_local_num) / 86400),0);

                    switch ($diff_date+1) { // Ну вот глючит switch при 0-м значении, хоть убей его. Кто в курсе решения проблемы, сообщите
                    case ($diff_date <= 3):
                    $color = 'col_blue';
                    break;
                    case ($diff_date <= 7):
                    $color = 'col_green';
                    break;
                    case ($diff_date <= 14):
                    $color = 'col_red';
                    break;
                    case ($diff_date <= 30):
                    $color = 'col_grey';
                    break;
                    default:
                    $color = 'col_black';
                    break;
                    }

                ?>
                <tr>
                    <td class="<?=$color?>"><a href="<?php echo $config['base'].$val['name'].'/'; ?>"
                            target="_blank"><?php echo $val['name']; ?></a></td>
                    <td><?php echo $val['account_id']; ?></td>
                    <td><?php echo $date; ?></td>
                    <td><?php echo floor((time() - mktime(0, 0, 0, date("m", $val['member_since']), date("d", $val['member_since']), date("Y", $val['member_since'])))/(3600*24)); ?></td>
                    <td><?php echo $val['role']; ?></td>
                    <td><?php echo date('Y.m.d',$roster_local_num); ?></td>
                </tr>
                <?php } ?>
        </tbody>  
    </table>
        </div>
          <span class="col_blue"><?=$lang['roster_blue']?></span><br>
          <span class="col_green"><?=$lang['roster_green']?></span><br>
          <span class="col_grey"><?=$lang['roster_grey']?></span><br>
          <span class="col_red"><?=$lang['roster_red']?></span><br>
          <span class="col_black"><?=$lang['roster_black']?></span>