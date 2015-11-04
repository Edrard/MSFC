<?php
/*
* Project:     Clan Stat
* License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
* Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
* -----------------------------------------------------------------------
* Began:       2011
* Date:        $Date: 2013-10-20 00:00:00 +0200 $
* -----------------------------------------------------------------------
* @author      $Author: Edd, Exinaus, SHW  $
* @copyright   2011-2013 Edd - Aleksandr Ustinov
* @link        http://wot-news.com
* @package     Clan Stat
* @version     $Rev: 3.2.3 $
*
*/
?>
<script>
    $(document).ready(function() {
        $("#overall-radio").buttonset();
        $('#overall-radio input:radio[value="all"]').prop('checked', true).button("refresh"); 
        //$('#type_stat').html($("#tankinfo :radio:checked").val());
        $("#overall-radio :radio").click(function(){ 
            var type = $(this).val();   
            $.ajax({
                cache: true,
                type: "POST",
                data: {
                    btype    :  type,
                    db_pref : '<?php echo $db->prefix; ?>',
                    key     : '<?=$key;?>'
                },
                url: "./ajax/ajax_overall.php",
                success: function(msg){
                    $("#overall-load").html(msg).show();
                }
            });
            return false;
        });
    });
</script>
<center><div id="overall-radio">
    <?php $i=1; foreach($batt_types as $val){ ?>
        <input value="<?=$val?>" type="radio" id="overall-radio<?=$i?>" name="overall-radio">
        <label for="overall-radio<?=$i?>"><?=$lang['btype_'.$val]?></label>
        <?php $i++; } ?>
</div></center>
<div id="overall-load">
    <script>
        $(document).ready(function() {
            $( "#triggeroverall" ).buttonset();
            $(".overall_average").hide();
            $(".overall_value").show();
            $("#change_overall_value").click(function() {
                $(".overall_average").hide();
                $(".overall_value").show();
                return false;
            });
            $("#change_overall_average").click(function() {
                $(".overall_value").hide();
                $(".overall_average").show();
                return false;
            });
        });
    </script>
    <? $eff_ratings_list = array('eff','wn7','wn8','brone'); ?>
    <form>
        <div id="triggeroverall" align="center">
            <input type="radio" id="change_overall_value" name="triggeroverall" checked="checked" /><label for="change_overall_value"><?=$lang['overall_value'];?></label>
            <input type="radio" id="change_overall_average" name="triggeroverall" /><label for="change_overall_average"><?=$lang['overall_average'];?></label>
        </div>
    </form>
    <div align="center">
        <table id="tabs-sort-<?=$key;?>" width="100%" cellspacing="1" class="table-id-<?=$key;?>">
            <thead>
                <tr>
                    <th><?=$lang['name']; ?></th>
                    <? if($config['company'] == 1 and in_array($key,$company['tabs'])) { ?>
                        <th><?=$lang['company']; ?></th>
                        <? } ?>
                    <?php
                    $overall = array ('battles', 'wins', 'losses', 'survived_battles');
                    foreach($overall as $name){ ?>
                        <th class="{sorter: 'digit'} <? if ($name != 'battles') {echo 'overall_value';} ?>"><?=$lang['all_'.$name];?></th>
                        <?php }
                    foreach($overall as $column => $name){
                        if($name != 'battles') { ?>
                            <th class="{sorter: 'digit'} overall_average sorter-percent"><?=$lang['all_'.$name];?></th>
                            <?php }
                    } ?>
                    <? foreach($eff_ratings_list as $val) { ?>
                        <th class="overall_value overall_average"><?=$lang[$val.'_ret'];?></th>
                        <? } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($res as $name => $val){ ?>
                    <tr>
                        <td><a href="<?php echo $config['base'],$val['data']['account_id'].'-'.$name,'/'; ?>" target="_blank"><?=$name; ?></a></td>
                        <? if($config['company'] == 1 and in_array($key,$company['tabs'])) { ?>
                            <td><?=in_array($val['data']['account_id'],$company['in_company'])?$company['company_names'][$company['by_id'][$val['data']['account_id']]]:'';?></td>
                            <? } ?>
                        <?php
                        foreach($overall as $column => $namec){?>
                            <td class="<? if($namec != 'battles') {echo 'overall_value';} ?>"><?= $val['data']['statistics']['all'][$namec];?></td>
                            <?php }
                        foreach($overall as $column => $namec){?>
                            <?php if($namec != 'battles') { ?>
                                <td class="overall_average"><?php
                                    if ($val['data']['statistics']['all']['battles']<> 0) {
                                        echo (number_format($val['data']['statistics']['all'][$namec]/$val['data']['statistics']['all']['battles']*100,2));
                                    }   else {
                                        echo '0';
                                    }; ?>%</td>
                                <?php } ?>
                            <?php } ?>
                        <? foreach($eff_ratings_list as $val) { ?>
                            <td class="overall_value overall_average"><font color="<?=$eff_rating[$name][$val.'_color'];?>"><?=($eff_rating[$name][$val]>0)?$eff_rating[$name][$val]:'0';?></font></td>
                            <? } ?>
                    </tr>
                    <?php } ?>
            </tbody>  
        </table>
    </div>
    <br>
    <center><?=$lang['value_ret_decr'],'<br>',$lang['overall_all_table'];?></center>
</div>