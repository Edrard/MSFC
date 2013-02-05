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
<script type="text/javascript">
    $(document).ready(function()
    {
        $('#id-<?=$key;?>').click(function() {
           check_Width($("table.table-id-<?=$key;?>"), $("div#tabs-<?=$key;?>"));
           return false;
        });
    });
</script>
<div align="center">
    <table id="overall" width="100%" cellspacing="1" class="table-id-<?=$key;?>">
        <thead>
            <tr>
                <th><?php echo $lang['name']; ?></th> 
                <?php foreach(array_keys($res[$rand_keys]['overall']) as $column){ ?>
                    <th class="{sorter: 'digit'}"><?php echo $column; ?></th>
                    <?php } ?>
                <th><?php echo $lang['eff_ret']; ?></th>
            </tr>  
        </thead>
        <tbody>
            <?php foreach($res as $name => $val){ ?>
            <?
            switch ($eff_rating[$name]+1) {
            case ($eff_rating[$name] > 1800):
            $color = '#FF8000';
            break;
            case ($eff_rating[$name] > 1500):
            $color = 'purple';
            break;
            case ($eff_rating[$name] > 1200):
            $color = 'royalblue';
            break;
            case ($eff_rating[$name] > 900):
            $color = 'green';
            break;
            case ($eff_rating[$name] > 600):
            $color = 'slategray';
            break;
            default:
            $color = 'red';
            break;
            }
            ?>
                <tr> 
                    <td><a href="<?php echo $config['base'].$name.'/'; ?>" 
                            target="_blank"><?php echo $name; ?></a></td>
                    <?php foreach($val['overall'] as $result){ ?>
                        <td><?php echo $result; ?></td>                             
                        <?php } ?>
                    <td><?php if(is_numeric($eff_rating[$name])) { echo '<font color="'.$color.'">'.$eff_rating[$name].'</font>'; } else { echo '<font color="red">0</font>';} ?></td>
                </tr>
                <?php } ?>
        </tbody>  
    </table>
    <?=$lang['overall_eff_table']?>
        </div>