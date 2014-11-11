<?php
    /*
    * Project:     Clan Stat
    * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
    * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
    * -----------------------------------------------------------------------
    * Began:       2011
    * Date:        $Date: 2013-10-20 00:00:00 +0200 $
    * -----------------------------------------------------------------------
    * @author      $Author: Edd, Exinaus, SHW  $
    * @copyright   2011-2013 Edd - Aleksandr Ustinov
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 3.1.2 $
    *
    */
?>
<div align="center">
    <div id="player_result"></div>
    <table id="tabs-sort-<?=$key;?>" width="100%" cellspacing="1" class="table-id-<?=$key;?>">
        <thead>
            <tr>
                <th class='sorter-false'>&nbsp;</th>
                <th><?=$lang['name']; ?></th>
                <? if($config['company'] == 1 and in_array($key,$company['tabs'])) { ?>
                    <th><?=$lang['company']; ?></th>
                <? } ?>
                <th>ID</th>
                <th class="{sorter: 'shortDate'}"><?=$lang['in_clan']; ?></th>
                <th><?=$lang['day_clan']; ?></th>
                <th><?=$lang['role']; ?></th>
                <th><?=$lang['dateof']; ?></th>
                <th><?=$lang['last_battle_time']; ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($multiclan_info[$config['clan']]['data'][$config['clan']]['members'] as $id => $val){

                    if (isset($res[$val['account_name']]['data']['logout_at']) && $res[$val['account_name']]['data']['logout_at'] > 0) {

                        $diff_date = round(((time() - $res[$val['account_name']]['data']['logout_at']) / 86400),0);

                        switch ($diff_date+1) { // Ну вот глючит switch при 0-м значении, хоть убей его. Кто в курсе решения проблемы, сообщите
                        case ($diff_date <= 3):
                        $color = 'col_blue';
                        break;
                        case ($diff_date <= 7):
                        $color = 'col_green';
                        break;
                        case ($diff_date <= 14):
                        $color = 'col_grey';
                        break;
                        case ($diff_date <= 30):
                        $color = 'col_red';
                        break;
                        default:
                        $color = 'col_black';
                        break;
                        }
                    }   else {
                        $color = 'col_black';
                    }
                ?>
                <tr>
                    <td align="center" ><a href="#tabs-<?=$key;?>" onclick="plmagic(this)" target="_self" alt='<?=$val['account_id'];?>'>
                       <div style="background-origin: content-box; padding: 0; margin: 0; " class="ui-accordion-header-icon ui-icon ui-icon-info">
                          &nbsp;
                        </div></a></td>
                    <td class="<?=$color?>"><a href="<?php echo $config['base'],$val['account_name'],'/'; ?>" target="_blank"><?=$val['account_name']; ?></a></td>
                    <? if($config['company'] == 1 and in_array($key,$company['tabs'])) { ?>
                        <td><?=in_array($id,$company['in_company'])?$company['company_names'][$company['by_id'][$id]]:'';?></td>
                    <? } ?>
                    <td><?=$val['account_id']; ?></td>
                    <td><?=($val['created_at'] == '')?$lang['na']:date('Y.m.d',$val['created_at']); ?></td>
                    <td><?=(is_numeric($val['created_at']))?floor((time() - $val['created_at'])/(86400)):$lang['na']; ?></td>
                    <td><span class="hidden"><?php echo roster_num($val['role']); ?></span><?php echo $val['role_i18n']; ?></td>
                    <td><?=(isset($res[$val['account_name']]['data']['logout_at'])&&$res[$val['account_name']]['data']['logout_at']>0)?'<span class="hidden">'.$res[$val['account_name']]['data']['logout_at'].'</span>'.date('Y.m.d (H:i)',$res[$val['account_name']]['data']['logout_at']):$lang['na'];?></td>
                    <td><?=(isset($res[$val['account_name']]['data']['last_battle_time'])&&$res[$val['account_name']]['data']['last_battle_time']>0)?'<span class="hidden">'.$res[$val['account_name']]['data']['last_battle_time'].'</span>'.date('Y.m.d (H:i)',$res[$val['account_name']]['data']['last_battle_time']):$lang['na'];?></td>
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
<? unset($date,$color,$roster_local_num,$diff_date); ?>