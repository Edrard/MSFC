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
<script>
$(document).ready(function() {
    $(".allmedalhide").hide();
    $(".heroshow").show();
    $("#show_hero").button().addClass("ui-state-focus");
    $("#show_major").button();
    $("#show_epic").button();
    $("#show_special").button();
    var medals_change = '#show_hero';
    $("#show_hero").click(function() {
      $(".allmedalhide").hide();
      $(".heroshow").show();
      $(medals_change).removeClass("ui-state-focus");
      $("#show_hero").addClass("ui-state-focus");
      medals_change = '#show_hero';
      return false;
    });
    $("#show_major").click(function() {
      $(".allmedalhide").hide();
      $(".majorshow").show();
      $(medals_change).removeClass("ui-state-focus");
      $("#show_major").addClass("ui-state-focus");
      medals_change = '#show_major';
      return false;
    });
    $("#show_epic").click(function() {
      $(".allmedalhide").hide();
      $(".epicshow").show();
      $(medals_change).removeClass("ui-state-focus");
      $("#show_epic").addClass("ui-state-focus");
      medals_change = '#show_epic';
      return false;
    });
    $("#show_special").click(function() {
      $(".allmedalhide").hide();
      $(".specialshow").show();
      $(medals_change).removeClass("ui-state-focus");
      $("#show_special").addClass("ui-state-focus");
      medals_change = '#show_special';
      return false;
    });
});
</script>
<div align="center">
<?=$lang['select_medals'];?><br /><br />
<a href="#" id="show_hero"><?=$lang['hero'];?></a>&nbsp;&nbsp;&nbsp;
<a href="#" id="show_major"><?=$lang['major'];?></a>&nbsp;&nbsp;&nbsp;
<a href="#" id="show_epic"><?=$lang['epic'];?></a>&nbsp;&nbsp;&nbsp;
<a href="#" id="show_special"><?=$lang['special'];?></a>
<br />
    <table id="all_medals_stat" class="tablesorter wid" cellspacing="1">
        <thead> 
            <tr>
                <th><?php echo $lang['name']; ?></th> 
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
                    ?>
                        <td align='center' class="allmedalhide <?=$tmed;?>show"><?php echo $result['value']; ?></td>
                    <?php
                            }
                          }
                    ?>
                </tr>
                <?php } ?>
        </tbody>  
    </table>
    <div class="allmedalhide specialshow" align="left">
    <span style="color:red;">*</span> <?php echo $lang['medal_max2']; ?><br />
    <span style="color:red;">**</span> <?php echo $lang['medal_max']; ?>
    </div>
</div>
