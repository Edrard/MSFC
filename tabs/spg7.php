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
    $clvl = '7'; 
    $class = 'SPG'; // heavyTank,mediumTank,lightTank,AT-SPG,SPG
?>
<div align="center">
    <?php if(isset($tanks_group[$class][$clvl])){ ?>
        <table id="spg_lvl_7" class="tablesorter wid" cellspacing="1">              
            <thead> 
                <tr>
                    <th><?php echo $lang['name']; ?></th> 
                    <?php foreach(array_keys($tanks_group[$class][$clvl]) as $column){
                        ?>
                        <th class="{sorter: 'digit'}"><?php echo $column; ?></th>
                        <?php } 
                    ?>
                </tr>  
            </thead>
            <tbody>
                <?php foreach($res as $name => $val){ ?>
                    <tr> 
                        <td><a href="<?php echo $config['base'].$name.'/'; ?>" 
                                target="_blank"><?php echo $name; ?></a></td>
                        <?php  foreach(array_keys($tanks_group[$class][$clvl]) as $column){ ?>
                            <td><?php  
                                    if(isset($val['tank'][$clvl][$class][$column])){
                                        if($val['tank'][$clvl][$class][$column]['total'] == 0){
                                            $percent = 0;    
                                        }else{
                                            $percent = round($val['tank'][$clvl][$class][$column]['win']*100/$val['tank'][$clvl][$class][$column]['total'],2);
                                        } 

                                        echo $percent.'% ('.$val['tank'][$clvl][$class][$column]['total'].'/'.$val['tank'][$clvl][$class][$column]['win'].')';
                                    }else{
                                        echo '0% (0/0)';
                                    } 

                            ?></td>                             
                            <?php 
                            } 
                        ?>
                    </tr>
                    <?php } ?>
            </tbody>  
        </table>
        <?php }else{ 
            echo $lang['error_no_veh'];
    } ?>
        </div>