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
<?php if($config['cron'] == 1 && $col_check > 2 && isset($main_progress['main'])) { ?>
    <div  id="active_medal_width" align="center">
        <?php if(!empty($medal_resort['list'])){ ?>
            <table id="active_medal_1" width="100%" cellspacing="1" class="table-id-<?=$key;?>">
                <thead>
                    <tr>
                        <th><?=$lang['name'];?></th>
                        <?php foreach($medal_resort['list'] as $mval) {
                                foreach(array_keys($mval) as $medals){
                                    $medal_key_img = './images/medals/'.ucfirst($medals).'.png';
                                ?>
                                <th align='center' class="{sorter: 'digit'} bb" <?php echo 'title="<table width=\'100%\' border=\'0\' class=\'ui-widget-content\' cellspacing=\'0\' cellpadding=\'0\'><tr><td><img src=\'./'.$medal_key_img.'\' /></td><td><span align=\'center\' style=\'font-weight: bold;\'>'.$lang['medal_'.$medals].'.</span><br> '.$lang['title_'.$medals].'</td></tr></table>"';?>><?php echo '<img src=\'./'.$medal_key_img.'\' style="width:60px;" /><br>'.$lang['medal_'.$medals]; ?></th>
                                <?php  } ?>
                            <?php  } ?>

                        <th align='center' class="{sorter: 'digit'} bb"><?=$lang['activity_4'];?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($medal_resort['id'] as $id => $total){ ?>
                        <tr>
                            <td><a href="<?=$config['base'].$roster_id[$id]['account_name'].'/'; ?>"
                                target="_blank"><?=$roster_id[$id]['account_name']; ?></a></td>
                            <?php
                                foreach ($medal_resort['list'] as $type => $mval){
                                    foreach(array_keys($mval) as $medals){
                                        if (isset ($medal_resort['data'][$type][$id][$medals])){ ?>
                                        <td align="center"><?=$medal_resort['data'][$type][$id][$medals];?></td>
                                        <?php }else{ ?>
                                        <td align="center">0</td>
                                        <?php }      
                                    }
                                } 
                            ?>
                            <td align='center' class="bb"><?=$total;?></td>
                        </tr>
                        <?php }  ?>
                </tbody>
            </table>
            <?php
                // } else {echo '<div class="num">'.$lang["err_med1"].'</div>';}
            } else {echo '<div class="num">'.$lang["err_med2"].'</div>';} ?>
    </div>
    <?php }else{ ?>
    <div class="num"><?=$lang['error_cron_off_or_none'];?></div>
    <?php } ?>