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
<?php
    if($config['cron'] == 1 && $col_check > 2 && count($main_progress['main']) > 0){ 
        $rand_main_progress = array_rand($main_progress['main'], 1); 
        $slice = array_slice($main_progress['main'][$rand_main_progress], 0, 16); 
    ?>
    <script type="text/javascript">
        $(document).ready(function(){
            $(".all_progress_hide").hide();
            $(".main_progress").show();
            $("#trigger_progress").buttonset();
            $("#show_main_progress").click(function() {
                $(".all_progress_hide").hide();
                $(".main_progress").show();
                check_Width($("table.table-id-<?=$key;?>"), $("div#tabs-<?=$key;?>"));
                return false;
            });
            $("#show_average_progress").click(function() {
                $(".all_progress_hide").hide();
                $(".average_progress").show();
                check_Width($("table.table-id-<?=$key;?>"), $("div#tabs-<?=$key;?>"));
                return false;
            });
        });
    </script>
    <div align="center">
        <form>
            <div id="trigger_progress" align="center">
                <input type="radio" id="show_main_progress" name="trigger_progress" checked="checked" /><label for="show_main_progress"><?=$lang['activity_main_progress'];?></label>
                <input type="radio" id="show_average_progress" name="trigger_progress" /><label for="show_average_progress"><?=$lang['activity_average_progress'];?></label>
            </div>
        </form>
        <table id="active_main" cellspacing="1" class="table-id-<?=$key;?> ui-widget-content">
            <thead>
                <tr>
                    <th><?=$lang['name'];?></th>
                    <?php foreach(array_keys($slice) as $title){?>
                        <?php if(isset($main_progress['average'][$rand_main_progress][$title])){ ?>
                            <th class="{sorter: 'digit'} all_progress_hide average_progress"><?=$lang[$title];?></th>
                            <?php } ?>
                        <th class="{sorter: 'digit'} all_progress_hide main_progress"><?=$lang[$title];?></th>
                        <?php } ?>
                </tr>  
            </thead>
            <tbody>
                <?php foreach($main_progress['main'] as $name => $vals){ ?>
                    <?php $slice = array_slice($vals, 0, 16); ?>
                    <tr> 
                        <td><a href="<?php echo $config['base'],$name,'/'; ?>" target="_blank"><?=$name; ?></a></td>
                        <?php foreach($slice as $title => $val){  ?>
                            <?php if(isset($main_progress['average'][$rand_main_progress][$title])){ ?>
                                <td class="all_progress_hide average_progress"><?=($main_progress['average'][$name][$title]!=0)?$main_progress['average'][$name][$title]:'';?></td>
                            <?php } ?>
                            <td class="all_progress_hide main_progress"><?=($val!=0)?$val:'';?></td>
                        <?php } ?>
                    </tr>
                    <?php } ?>
            </tbody>  
        </table>
    </div>
    <?php }else{ ?>
    <div class="num"><?=$lang['error_cron_off_or_none'];?></div>
    <?php } ?>