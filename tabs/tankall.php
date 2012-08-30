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
    $("#change_button_tanks").button();
    $(".2hide").hide();
    $(".heavyTank10t").show();
    $("#change_button_tanks").click(function() {
      // get type and lvl
      var selectedType = $("#selectTypes").val();
      var selectedLvls = $("#selectLvls").val();
      var myshow;
      // form string what to show
      if((selectedType == 'all') && (selectedLvls == 'all')) {
        alert('<?=$lang['select_restriciton'];?>');
        myshow = ".heavyTank10t";
      } else if(selectedType == 'all' && selectedLvls != 'all') {
        myshow = "."+selectedLvls+"lvl";
      } else if(selectedLvls == 'all' && selectedType != 'all') {
        myshow = "."+selectedType+"class";
      } else {
          myshow = '.'+selectedType+selectedLvls+'t';
      }
      //hide all
      $(".2hide").hide();
      //show selected
      $(myshow).show();
      return false;
    });
});
</script>
<div align="center">
<?=$lang['select_tanks'];?>&nbsp;&nbsp;&nbsp;
<select name="selecttype" id="selectTypes">
	<option value="heavyTank"><?=$lang['heavyTank'];?></option>
	<option value="mediumTank"><?=$lang['mediumTank'];?></option>
	<option value="lightTank"><?=$lang['lightTank'];?></option>
	<option value="AT-SPG"><?=$lang['AT-SPG'];?></option>
	<option value="SPG"><?=$lang['SPG'];?></option>
	<option value="all"><?=$lang['all_types'];?></option>
</select>
<select name="selectlvl" id="selectLvls">
    <? for($i=10;$i>=1;$i--) { ?>
	<option value="<?=$i;?>"><?=$i;?></option>
    <? } ?>
    <option value="all"><?=$lang['all_lvls'];?></option>
</select>
<a href="#" id="change_button_tanks"><?=$lang['select_show'];?></a>
    <table id="all_tanks_stat" class="tablesorter wid" cellspacing="1">
        <thead>
            <tr>
                <th><?php echo $lang['name']; ?></th>
                <?php foreach($tanks_group as $type => $types){
                         foreach($types as $lvl => $tank) {
                            foreach($tank as $column => $tmp){ ?>
                            <th class="<?=$type,$lvl,'t ',$lvl,'lvl ',$type,'class';?> 2hide"><?php echo $column; ?></th>
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
                            foreach($types as $lvl => $tmp){
                                foreach($tmp as $column => $one){ ?>
                                <td class="<?=$type,$lvl,'t ',$lvl,'lvl ',$type,'class';?> 2hide">
                                <?php
                                        if(isset($val['tank'][$lvl][$type][$column])){
                                            if($val['tank'][$lvl][$type][$column]['total'] == 0){
                                                $percent = 0;
                                            }else{
                                                $percent = round($val['tank'][$lvl][$type][$column]['win']*100/$val['tank'][$lvl][$type][$column]['total'],2);
                                            }

                                            echo $percent.'% ('.$val['tank'][$lvl][$type][$column]['total'].'/'.$val['tank'][$lvl][$type][$column]['win'].')';
                                        }else{
                                            echo '0% (0/0)';
                                        }

                                ?>
                                </td>
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