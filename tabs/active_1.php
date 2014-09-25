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
    * @version     $Rev: 3.1.2 $
    *
    */

 if($config['cron'] == 1 && $col_check > 2 && count($main_progress['main']) > 0){  ?>
    <div  id="active_medal_width" align="center">
        <?php if(!empty($medal_resort['list'])){ ?>
            <table id="active_medal_1" width="100%" cellspacing="1" class="table-id-<?=$key;?>">
                <thead>
                    <tr>
                        <th><?=$lang['name'];?></th>
                        <? if($config['company'] == 1 and in_array($key,$company['tabs'])) { ?>
                            <th><?=$lang['company']; ?></th>
                        <? } ?>
                        <?php foreach($medal_resort['list'] as $mval) {
                                foreach(array_keys($mval) as $medals){  $ach = $achievements[$medals]; ?>
                                <th align='center' class="{sorter: 'digit'} bb" title="<div><?=str_replace('"',"'",$ach['description']),(!empty($ach['condition'])?'<div style=\'padding:0px;margin:10px 0 0 15px\'>'.nl2br($ach['condition']).'</div>':'');?></div>"><img src="<?=$ach['image'];?>" style="width:60px;" /><br><?=$ach['name_i18n'],(($ach['type']=='series')?'&nbsp;<span style="color:red;">*</span>':'');?></th>
                                <?php  } ?>
                            <?php  } ?>

                        <th align='center' class="{sorter: 'digit'}"><?=$lang['activity_4'];?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($medal_resort['id'] as $id => $total){ ?>
                        <tr>
                            <td><a href="<?=$config['base'].$roster_id[$id]['account_name'].'/'; ?>"
                                target="_blank"><?=$roster_id[$id]['account_name']; ?></a></td>
                            <? if($config['company'] == 1 and in_array($key,$company['tabs'])) { ?>
                                <td><?=in_array($id,$company['in_company'])?$company['company_names'][$company['by_id'][$id]]:'';?></td>
                            <? } ?>
                            <?php
                                foreach ($medal_resort['list'] as $type => $mval){
                                    foreach(array_keys($mval) as $medals){  ?>
                                        <td align="center"><?=(isset($medal_resort['data'][$type][$id][$medals])?$medal_resort['data'][$type][$id][$medals]:'');?></td>
                           <?php    }
                                }
                            ?>
                            <td align='center'><?=$total;?></td>
                        </tr>
                        <?php }  ?>
                </tbody>
            </table>
            <?php
                // } else {echo '<div class="num">'.$lang["err_med1"].'</div>';}
            } else {echo '<div class="num">',$lang["err_med2"],'</div>';} ?>
    </div>
    <?php }else{ ?>
    <div class="num"><?=$lang['error_cron_off_or_none'];?></div>
    <?php } ?>