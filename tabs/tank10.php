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
<?php $clvl = '10'; ?>
<div align="center">
    <table id="stat_lvl_10" class="tablesorter wid" cellspacing="1">              
        <thead> 
            <tr>
                <th><?php echo $lang['name']; ?></th> 
                <?php foreach($tanks_group as $types){
                        if(isset($types[$clvl])){
                            foreach(array_keys($types[$clvl]) as $column){ ?>
                            <th class="{sorter: 'digit'}"><?php echo $column; ?></th>
                            <?php } 
                        }
                    }
                ?>
            </tr>  
        </thead>
        <tbody>
            <?php foreach($res as $name => $val){ ?>
                <tr> 
                    <td><a href="<?php echo $config['base'].$name.'/'; ?>" 
                            target="_blank"><?php echo $name; ?></a></td>
                    <?php foreach($tanks_group as $type => $types){
                            if(isset($types[$clvl])){
                                foreach(array_keys($types[$clvl]) as $column){ ?>
                                <td><?php  
                                        if(isset($val['tank'][$clvl][$type][$column])){
                                            if($val['tank'][$clvl][$type][$column]['total'] == 0){
                                                $percent = 0;    
                                            }else{
                                                $percent = round($val['tank'][$clvl][$type][$column]['win']*100/$val['tank'][$clvl][$type][$column]['total'],2);
                                            } 

                                            echo $percent.'% ('.$val['tank'][$clvl][$type][$column]['total'].'/'.$val['tank'][$clvl][$type][$column]['win'].')';
                                        }else{
                                            echo '0% (0/0)';
                                        } 

                                ?></td>                             
                                <?php 
                                }
                            } 
                        }
                    ?>
                </tr>
                <?php } ?>
        </tbody>  
    </table>
        </div>