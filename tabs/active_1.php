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
    * @version     $Rev: 2.1.2 $
    *
    */
?>
<?php if($config['cron'] == 1 && $col_check > 2 && count($main_progress) > 0){  ?>
    <div align="center">
        <div id="acc_medals">
            <h3><a href="#"><?=$lang['epic']?></a></h3>
            <div>
                <div align="center">
                    <?php $rand_medal_progress = array_rand($medal_progress['sorted']['epic'], 1); ?>
                    <table id="active_medal_1" class="tablesorter" cellspacing="1">              
                        <thead> 
                            <tr>
                                <th><?=$lang['name'];?></th>
                                <?php foreach(array_keys($medal_progress['sorted']['epic'][$rand_medal_progress]) as $title){?>
                                    <th class="bb" <?php echo 'title="<table width=\'100%\' border=\'0\' cellspacing=\'0\' cellpadding=\'0\'><tr><td><img src=\'./images/medals/'.ucfirst($title).'.png\' /></td><td><span align=\'center\' style=\'font-weight: bold;\'>'.$lang['medal_'.$title].'.</span><br> '.$lang['title_'.$title].'</td></tr></table>"';?>><?=$lang['medal_'.$title];?></th>
                                    <?php } ?>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach($medal_progress['sorted']['epic'] as $account_id => $vals){ 
                                    if(isset($roster_id[$account_id]['name'])){?>
                                    <tr> 
                                        <td><a href="<?php echo $config['base'].$roster_id[$account_id]['name'].'/'; ?>" 
                                                target="_blank"><?php echo $roster_id[$account_id]['name']; ?></a></td>
                                        <?php foreach($vals as $val){  ?>
                                            <td><?=$val;?></td>
                                            <?php } ?>
                                    </tr>
                                    <?php } ?>
                                <?php } ?>
                        </tbody>  
                    </table>
                </div>
            </div>
            <h3><a href="#"><?=$lang['special']?></a></h3>
            <div>
                <div align="center">
                    <?php $rand_medal_progress = array_rand($medal_progress['sorted']['special'], 1); ?>
                    <table id="active_medal_2" class="tablesorter" cellspacing="1">              
                        <thead> 
                            <tr>
                                <th><?=$lang['name'];?></th>
                                <?php foreach(array_keys($medal_progress['sorted']['special'][$rand_medal_progress]) as $title){?>
                                    <th class="bb" <?php echo 'title="<table width=\'100%\' border=\'0\' cellspacing=\'0\' cellpadding=\'0\'><tr><td><img src=\'./images/medals/'.ucfirst($title).'.png\' /></td><td><span align=\'center\' style=\'font-weight: bold;\'>'.$lang['medal_'.$title].'.</span><br> '.$lang['title_'.$title].'</td></tr></table>"';?>><?=$lang['medal_'.$title];?></th>
                                    <?php } ?>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach($medal_progress['sorted']['special'] as $account_id => $vals){ 
                                    if(isset($roster_id[$account_id]['name'])){?>
                                    <tr> 
                                        <td><a href="<?php echo $config['base'].$roster_id[$account_id]['name'].'/'; ?>" 
                                                target="_blank"><?php echo $roster_id[$account_id]['name']; ?></a></td>
                                        <?php foreach($vals as $val){  ?>
                                            <td><?=$val;?></td>
                                            <?php } ?>
                                    </tr>
                                    <?php } ?>
                                <?php } ?>
                        </tbody>  
                    </table>
                </div>
            </div>
            <h3><a href="#"><?=$lang['major']?></a></h3>
            <div>
                <div align="center">
                    <?php $rand_medal_progress = array_rand($medal_progress['sorted']['major'], 1); ?>
                    <table id="active_medal_3" class="tablesorter" cellspacing="1">              
                        <thead> 
                            <tr>
                                <th><?=$lang['name'];?></th>
                                <?php foreach(array_keys($medal_progress['sorted']['major'][$rand_medal_progress]) as $title){?>
                                    <th class="bb" <?php echo 'title="<table width=\'100%\' border=\'0\' cellspacing=\'0\' cellpadding=\'0\'><tr><td><img src=\'./images/medals/'.ucfirst($title).'.png\' /></td><td><span align=\'center\' style=\'font-weight: bold;\'>'.$lang['medal_'.$title].'.</span><br> '.$lang['title_'.$title].'</td></tr></table>"';?>><?=$lang['medal_'.$title];?></th>
                                    <?php } ?>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach($medal_progress['sorted']['major'] as $account_id => $vals){ 
                                    if(isset($roster_id[$account_id]['name'])){?>
                                    <tr> 
                                        <td><a href="<?php echo $config['base'].$roster_id[$account_id]['name'].'/'; ?>" 
                                                target="_blank"><?php echo $roster_id[$account_id]['name']; ?></a></td>
                                        <?php foreach($vals as $val){  ?>
                                            <td><?=$val;?></td>
                                            <?php } ?>
                                    </tr>
                                    <?php } ?>
                                <?php } ?>
                        </tbody>  
                    </table>
                </div>
            </div>
            <h3><a href="#"><?=$lang['hero']?></a></h3>
            <div>
                <div align="center">
                    <?php $rand_medal_progress = array_rand($medal_progress['sorted']['hero'], 1); ?>
                    <table id="active_medal_4" class="tablesorter" cellspacing="1">              
                        <thead> 
                            <tr>
                                <th><?=$lang['name'];?></th>
                                <?php foreach(array_keys($medal_progress['sorted']['hero'][$rand_medal_progress]) as $title){?>
                                    <th class="bb" <?php echo 'title="<table width=\'100%\' border=\'0\' cellspacing=\'0\' cellpadding=\'0\'><tr><td><img src=\'./images/medals/'.ucfirst($title).'.png\' /></td><td><span align=\'center\' style=\'font-weight: bold;\'>'.$lang['medal_'.$title].'.</span><br> '.$lang['title_'.$title].'</td></tr></table>"';?>><?=$lang['medal_'.$title];?></th>
                                    <?php } ?>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach($medal_progress['sorted']['hero'] as $account_id => $vals){ 
                                    if(isset($roster_id[$account_id]['name'])){?>
                                    <tr> 
                                        <td><a href="<?php echo $config['base'].$roster_id[$account_id]['name'].'/'; ?>" 
                                                target="_blank"><?php echo $roster_id[$account_id]['name']; ?></a></td>
                                        <?php foreach($vals as $val){  ?>
                                            <td><?=$val;?></td>
                                            <?php } ?>
                                    </tr>
                                    <?php } ?>
                                <?php } ?>
                        </tbody>  
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php }else{ ?>
    <div class="num"><?=$lang['error_cron_off_or_none'];?></div>
    <?php } ?>


