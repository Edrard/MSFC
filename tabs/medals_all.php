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
<script>
    $(document).ready(function() {
        $(".allmedalhide").hide();
        $(".heroshow").show();
        $("#triggermedals").buttonset();

        $("#show_hero").click(function() {
            $(".allmedalhide").hide();
            $(".heroshow").show();
        });
        $("#show_major").click(function() {
            $(".allmedalhide").hide();
            $(".majorshow").show();
        });
        $("#show_epic").click(function() {
            $(".allmedalhide").hide();
            $(".epicshow").show();
        });
        $("#show_epic2").click(function() {
            $(".allmedalhide").hide();
            $(".epic2show").show();
        });
        $("#show_special").click(function() {
            $(".allmedalhide").hide();
            $(".specialshow").show();
        });
        $("#show_special2").click(function() {
            $(".allmedalhide").hide();
            $(".special2show").show();
        });
        $("#show_expert").click(function() {
            $(".allmedalhide").hide();
            $(".expertshow").show();
        });
    });
</script>
<div align="center">
    <?=$lang['select_medals'];?><br /><br />
    <form>
        <div id="triggermedals" align="center">
            <input type="radio" id="show_hero" name="triggermedals" checked="checked" /><label for="show_hero"><?=$lang['hero'];?></label>
            <input type="radio" id="show_major" name="triggermedals" /><label for="show_major"><?=$lang['major'];?></label>
            <input type="radio" id="show_epic" name="triggermedals"  /><label for="show_epic"><?=$lang['epic'];?> - 1</label>
            <input type="radio" id="show_epic2" name="triggermedals" /><label for="show_epic2"><?=$lang['epic'];?> - 2</label>
            <input type="radio" id="show_special" name="triggermedals" /><label for="show_special"><?=$lang['special'];?> - 1</label>
            <input type="radio" id="show_special2" name="triggermedals" /><label for="show_special2"><?=$lang['special'];?> - 2</label>
            <input type="radio" id="show_expert" name="triggermedals" /><label for="show_expert"><?=$lang['expert'];?></label>
        </div>
    </form>

    <br />
    <table id="all_medals_stat" width="100%" cellspacing="1" cellpadding="2">
        <thead> 
            <tr>
                <th><?=$lang['name']; ?></th>
                <?php foreach($res[$rand_keys]['medals'] as $tmed => $hkey){
                        foreach($hkey as $tm => $column) { ?>
                        <th align='center' class="{sorter: 'digit'} bb allmedalhide <?=$tmed;?>show" <?php echo 'title="<table width=\'100%\' border=\'0\' cellspacing=\'0\' cellpadding=\'0\'><tr><td><img src=\'./'.$column['img'].'\' /></td><td><span align=\'center\' style=\'font-weight: bold;\'>'.$column['title'].'.</span><br> '.$lang['title_'.$tm].'</td></tr></table>"';?>><?php echo '<img src=\'./'.$column['img'].'\' /><br>'.$column['title']; ?></th>
                        <?php   }
                    }
                ?>
            </tr>  
        </thead>
        <tbody>
            <?php foreach($res as $name => $val){ ?>
                <tr> 
                    <td><a href="<?php echo $config['base'].$name.'/'; ?>" 
                        target="_blank"><?php echo $name; ?></a></td>
                    <?php foreach($val['medals'] as $tmed => $tm) {
                            foreach($tm as $hkey => $result) {
                                if($hkey == 'titleSniper' || $hkey == 'armorPiercer' || $hkey == 'handOfDeath'){
                                    @$result['value'] = $result['max'];
                                }
                                if($hkey == 'invincible'){
                                    if($result['value'] > 0 ){
                                        @$result['value'] = $result['max'];
                                    }else{
                                        @$result['value'] = '0 ('.$result['max'].' <span style="color:red;">*</span>)';
                                    }
                                }
                                if($hkey == 'diehard'){
                                    if($result['value'] == 0 ){
                                        @$result['value'] = '0 ('.$result['max'].' <span style="color:red;">**</span>)';
                                    }
                                }
                                if($tmed == 'expert'){
                                    $num_n = 0;
                                    if($result['value'] == 1){
                                        $num_n = 1;
                                        $result['value'] = '<img src="./images/cgreen.png" />';
                                    }else{
                                        $result['value'] = '';
                                    } 
                                ?>
                                <td align='center' class="allmedalhide <?=$tmed;?>show"><span style="display: none;"><?php echo $num_n; ?></span><?php echo $result['value']; ?></td>
                                <?php    
                                }else{
                                ?>
                                <td align='center' class="allmedalhide <?=$tmed;?>show"><?php echo $result['value']; ?></td>
                                <?php
                                }
                            }
                        }
                    ?>
                </tr>
                <?php } ?>
        </tbody>  
    </table>
    <div class="allmedalhide specialshow" align="left">
        <span style="color:red;">*</span> <?=$lang['medal_max2']; ?><br />
        <span style="color:red;">**</span> <?=$lang['medal_max']; ?>
    </div>
</div>