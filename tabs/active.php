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
<?php 
if($config['cron'] == 1 && $col_check > 2 && count($main_progress) > 0){ 
    $rand_main_progress = array_rand($main_progress, 1); 
    $slice = array_slice($main_progress[$rand_main_progress], 0, 16); 

?>

<div align="center">
    <table id="active_main" cellspacing="1">
        <thead> 
            <tr>
                <th><?=$lang['name'];?></th>
                <?php foreach(array_keys($slice) as $title){?>
                    <th class="{sorter: 'digit'}"><?=$lang[$title];?></th>
                    <?php } ?>
            </tr>  
        </thead>
        <tbody>
            <?php foreach($main_progress as $name => $vals){ ?>
                <?php $slice = array_slice($vals, 0, 16); ?>
                <tr> 
                    <td><a href="<?php echo $config['base'].$name.'/'; ?>" 
                            target="_blank"><?php echo $name; ?></a></td>
                    <?php foreach($slice as $val){  ?>
                        <td><?=$val;?></td>
                        <?php } ?>
                </tr>
                <?php } ?>
        </tbody>  
    </table>
</div>
<?php }else{ ?>
<div class="num"><?=$lang['error_cron_off_or_none'];?></div>
<?php } ?>