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
    * @version     $Rev: 2.1.1 $
    *
    */
?>
<div align="center">
    <table id="average_perform" class="tablesorter wid" cellspacing="1">              
        <thead> 
            <tr>
                <th><?php echo $lang['name']; ?></th> 
                <?php foreach(array_keys($res[$rand_keys]['perform']) as $column){ ?>
                    <?php if($column != $lang['hit_ratio']) echo '<th>'.$column.'</th>'; ?>
                    <?php } ?>
            </tr>  
        </thead>
        <tbody>
            <?php foreach($res as $name => $val){  ?>
                <tr> 
                    <td><a href="<?php echo $config['base'].$name.'/'; ?>" 
                            target="_blank"><?php echo $name; ?></a></td>
                    <?php foreach($val['perform'] as $cat => $result){ ?>
                        <?php if($cat != $lang['hit_ratio']) { ?> 
			<td>
			<? if($val['overall'][$lang['games_p']] > 0) { echo round($result/$val['overall'][$lang['games_p']],2); } else { echo '0'; } ?>
			</td>
                        <?php } ?> 
                   <?php } ?>
                </tr>
                <?php } ?>
        </tbody>  
    </table>
</div>
<? unset($column); unset($cat); unset($name); unset($val); ?>
