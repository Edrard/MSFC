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
    * @version     $Rev: 3.1.0 $
    *
    */
?>
<div align="center">
    <table id="battel" width="100%" cellspacing="1" cellpadding="2" class="ui-widget-content table-id-<?=$key;?>">
        <thead> 
            <tr>
            <?php echo '<th>'.$lang['name'].'</th>';
                  if($config['company'] == 1 and in_array($key,$company['tabs'])) {
                    echo '<th>',$lang['company'],'</th>';
                  }
                  $exp = array ('xp', 'battle_avg_xp', 'max_xp');
                  foreach($exp as $name){ ?>
                    <th class="{sorter: 'digit'}"><?php if ($name =='max_xp') {echo $lang[$name];} else {echo $lang['all_'.$name];} ?></th>
            <?php } ?>
            </tr>  
        </thead>
        <tbody>
            <?php foreach($res as $name => $val){ ?>
                <tr> 
                    <td><a href="<?php echo $config['base'],$name,'/'; ?>" target="_blank"><?=$name; ?></a></td>
                    <? if($config['company'] == 1 and in_array($key,$company['tabs'])) { ?>
                        <td><?=in_array($val['data']['account_id'],$company['in_company'])?$company['company_names'][$company['by_id'][$val['data']['account_id']]]:'';?></td>
                    <? } ?>
                    <?php foreach($exp as $column => $cat){
                             echo '<td>';
                             if ($cat=='max_xp') {
                                 echo $val['data']['statistics'][$cat];
                             }   else {
                                 echo $val['data']['statistics']['all'][$cat];
                             }
                             echo '</td>';
                          } ?>
                </tr>
                <?php } ?>
        </tbody>  
    </table>
</div>