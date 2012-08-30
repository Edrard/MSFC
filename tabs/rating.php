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
  <div align="center">
    <table id="rating" class="tablesorter" cellspacing="1" style="width: 100%;">              
        <thead> 
            <tr>
                <th><?php echo $lang['name']; ?></th> 
                <?php foreach(array_keys($res[$rand_keys]['rating']) as $column){ 
                     $array = $res[$rand_keys]['rating'][$column];
                     echo "<th align='center' class=\"{sorter: 'digit'}\"><img class='bb' src='{$array['link']}' title='<font color=\"royalblue\">{$array['type']}</font><br>{$array['name']}'><br>$column</th>";
                     } ?>
            </tr>  
        </thead>
        <tbody>
            <?php foreach($res as $name => $val){ ?>
                <tr> 
                    <td><a href="<?php echo $config['base'].$name.'/'; ?>" 
                            target="_blank"><?php echo $name; ?></a></td>
                    <?php foreach($val['rating'] as $result){ ?>
                        <td><?php echo $result['place']; ?></td>                             
                        <?php } ?>
                </tr>
                <?php } ?>
        </tbody>  
    </table>
  </div>