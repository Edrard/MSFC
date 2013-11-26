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
    * @version     $Rev: 3.0.0 $
    *
    */
?>
<div align="center">
    <table id="roster" width="100%" cellspacing="1" class="table-id-<?=$key;?>">
        <thead>
            <tr>
                <th>&nbsp;</th>
                <th><?=$lang['name']; ?></th>
                <th>ID</th> 
                <th class="{sorter: 'shortDate'}"><?=$lang['in_clan']; ?></th>
                <th><?=$lang['day_clan']; ?></th>
                <th><?=$lang['role']; ?></th>
                <th><?=$lang['dateof']; ?></th>
            </tr>  
        </thead>
        <tbody>
            <?php foreach($multiclan_info[$config['clan']]['data'][$config['clan']]['members'] as $id => $val){
                    if($val['created_at'] == ''){
                        $val['created_at'] = 'Н/Д';
                        $date = $val['created_at'];
                    } else {
                        $date = date('Y.m.d',$val['created_at']);
                    }
                    if (isset($res[$val['account_name']]['data']['updated_at']) && ($res[$val['account_name']]['data']['updated_at'] <>0)) {
                        $roster_local_num = $res[$val['account_name']]['data']['updated_at'];
                        $diff_date = round(((time() - $roster_local_num) / 86400),0);

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
                        $roster_local_num = 'Н/Д';
                        $color = 'col_black';
                    }
                ?>
                <tr>
                    <td align="center" ><a href="#" id="id--6531" onclick="plmagic(this)" target="_self" alt='<?=$val['account_id'];?>'>
                       <div style="background-origin: content-box; padding: 0; margin: 0; " class="ui-accordion-header-icon ui-icon ui-icon-info">
                          &nbsp;
                        </div></a></td>
                    <td class="<?=$color?>"><a href="<?php echo $config['base'],$val['account_name'],'/'; ?>" target="_blank"><?=$val['account_name']; ?></a></td>
                    <td><?=$val['account_id']; ?></td>
                    <td><?=$date; ?></td>
                    <td><?php If (is_numeric($val['created_at'])){
                                       echo floor((time() - mktime(0, 0, 0, date("m", $val['created_at']), date("d", $val['created_at']), date("Y", $val['created_at'])))/(3600*24));
                              } else { echo $val['created_at']; }; ?></td>
                    <td><span class="hidden"><?php echo roster_num($val['role']); ?></span><?php echo $val['role_i18n']; ?></td>
                    <td>
                        <?php if (is_numeric($roster_local_num)){ ?>
                                 <span class="hidden"><?php echo $roster_local_num; ?></span>
                                 <?php echo date('Y.m.d (H:i)',$roster_local_num);
                              } else {echo $roster_local_num;}; ?></td>
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
          <div id="player_result"></div>
<? unset($date,$color,$roster_local_num,$diff_date); ?>