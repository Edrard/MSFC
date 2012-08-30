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
    * @version     $Rev: 2.0.0 $
    *
    */
?>
<?php
$tmed = 'major';    
?>

<div align="center">
    <table id="achiv_major" class="tablesorter wid" cellspacing="1">              
        <thead> 
            <tr>
                <th><?php echo $lang['name']; ?></th> 
                <?php foreach($res[$rand_keys]['medals'][$tmed] as $tm => $column){ ?>
		<?php $column['img'] = 'images/medals/'.ucfirst($tm).'1.png'; ?>
                    <th align='center' class="{sorter: 'digit'} bb" <?php echo 'title="<table width=\'100%\' border=\'0\' cellspacing=\'0\' cellpadding=\'0\'><tr><td><img src=\'./'.$column['img'].'\' /></td><td><span align=\'center\' style=\'font-weight: bold;\'>'.$column['title'].'.</span><br> '.$lang['title_'.$tm].'</td></tr></table>"';?>><?php echo '<img src=\'./'.$column['img'].'\' /><br>'.$column['title']; ?></th>
                    <?php } ?>
            </tr>  
        </thead>
        <tbody>
            <?php foreach($res as $name => $val){ ?>
                <tr> 
                    <td><a href="<?php echo $config['base'].$name.'/'; ?>" 
                            target="_blank"><?php echo $name; ?></a></td>
                    <?php foreach($val['medals'][$tmed] as $result){ ?>
                        <td align='center'><?php echo $result['value']; ?></td>                             
                        <?php } ?>
                </tr>
                <?php } ?>
        </tbody>  
    </table>
        </div>