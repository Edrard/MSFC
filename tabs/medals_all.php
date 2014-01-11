<?php
    /*
    * Project:     Clan Stat
    * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
    * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
    * -----------------------------------------------------------------------
    * Began:       2011
    * Date:        $Date: 2013-11-20 00:00:00 +0200 $
    * -----------------------------------------------------------------------
    * @author      $Author: Edd, Exinaus, SHW  $
    * @copyright   2011-2013 Edd - Aleksandr Ustinov
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 3.0.2 $
    *
    */
?>
<script>
    $(document).ready(function() {
        $(".amh").hide();
        $(".heros").show();
        $("#triggermedals").buttonset();
        $("#show_hero").click(function() {
            $(".amh").hide();
            $(".heros").show();
            return false;
        });
        $("#show_major").click(function() {
            $(".amh").hide();
            $(".majors").show();
            return false;
        });
        $("#show_epic").click(function() {
            $(".amh").hide();
            $(".epics").show();
            return false;
        });
        $("#show_epic2").click(function() {
            $(".amh").hide();
            $(".epic2s").show();
            return false;
        });
        $("#show_special").click(function() {
            $(".amh").hide();
            $(".specials").show();
            return false;
        });
        $("#show_special2").click(function() {
            $(".amh").hide();
            $(".special2s").show();
            return false;
        });
        $("#show_expert").click(function() {
            $(".amh").hide();
            $(".experts").show();
            return false;
        });
        $("#show_mechanic").click(function() {
            $(".amh").hide();
            $(".mechanics").show();
            return false;
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
            <br />
            <input type="radio" id="show_special" name="triggermedals" /><label for="show_special"><?=$lang['special'];?> - 1</label>
            <input type="radio" id="show_special2" name="triggermedals" /><label for="show_special2"><?=$lang['special'];?> - 2</label>
            <input type="radio" id="show_expert" name="triggermedals" /><label for="show_expert"><?=$lang['expert'];?></label>
            <input type="radio" id="show_mechanic" name="triggermedals" /><label for="show_mechanic"><?=$lang['mechanic'];?></label>
        </div>
    </form>

    <br />
    <table id="all_medals_stat" width="100%" cellspacing="1" cellpadding="2"  class="table-id-<?=$key;?>">
        <thead>
            <tr>
                <th><?=$lang['name']; ?></th>
                <? if($config['company'] == 1 and in_array($key,$company['tabs'])) { ?>
                    <th><?=$lang['company']; ?></th>
                <? } ?>
               <? foreach ($medn as $medname => $val) { ?>
                  <th valign='top' class="{sorter: 'digit'} bb amh <?=$val['type'];?>s"
                  <?php
                     echo 'title="<table class=\'ui-widget-content\' cellspacing=\'0\' cellpadding=\'0\' style=\'border: 0px !important;\'><tr><td>';
                     echo '<img src=\'./',$val['img'],'\' /></td><td><span class=\'spcl\'>',$lang['medal_'.$medname],'.</span><br> ',$lang['title_'.$medname],'</td></tr></table>">';
                     echo '<img src="',$val['img'],'" style="width:60px;" /><br>',$lang['medal_'.$medname];
                     echo '</th>';
               } ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach($res as $name => $val){ ?>
                <tr>
                  <td><a href="<?php echo $config['base'],$name,'/'; ?>" target="_blank"><?=$name; ?></a></td>
                  <? if($config['company'] == 1 and in_array($key,$company['tabs'])) { ?>
                      <td><?=in_array($val['data']['account_id'],$company['in_company'])?$company['company_names'][$company['by_id'][$val['data']['account_id']]]:'';?></td>
                  <? } ?>
                  <? foreach ($medn as $mdn => $val) {
                      if ($mdn <> 'lumberjack') {
                          if ($mdn == 'diehard') {
                              if ($res[$name]['data']['achievements'][$mdn] == 0) {
                                  @$result['value'] = '0 ('.$res[$name]['data']['achievements']['max_diehard_series'].' <span style="color:red;">**</span>)';
                              }   else {
                                  @$result['value'] = $res[$name]['data']['achievements'][$mdn];
                              }
                          }   elseif ($mdn == 'handOfDeath'){
                              @$result['value'] = $res[$name]['data']['achievements']['max_killing_series'];
                          }   elseif ($mdn == 'invincible'){
                              if ($res[$name]['data']['achievements'][$mdn] == 0) {
                                  @$result['value'] = '0 ('.$res[$name]['data']['achievements']['max_invincible_series'].' <span style="color:red;">*</span>)';
                              }   else {
                                  @$result['value'] = $res[$name]['data']['achievements'][$mdn];
                              }
                          }   elseif ($mdn == 'armor_piercer'){
                              @$result['value'] = $res[$name]['data']['achievements']['max_piercing_series'];
                          }   elseif ($mdn == 'title_sniper'){
                              @$result['value'] = $res[$name]['data']['achievements']['max_sniper_series'];
                          }   elseif (!isset($val['type'])){
                              //print_R($val);
                          }   elseif (($val['type'] =='expert') || ($val['type'] =='mechanic')){
                              $num_n = 0;
                              if ($res[$name]['data']['achievements'][$mdn] == 1) {
                                  $num_n = 1;
                                  @$result['value'] = '<img src="./images/cgreen.png" />';
                              }   else {
                                  @$result['value'] = '';
                              }

                          }   else {
                              @$result['value'] = $res[$name]['data']['achievements'][$mdn];
                          }

                          if (isset($num_n)) {
                              echo "<td class='amh ".$val['type']."s'><span style='display: none;'>",$num_n,"</span>",$result['value'],"</td>";
                              unset($num_n);
                          }   else {
                              echo "<td class='amh ".$val['type']."s'>".$result['value']."</td>";
                          }
                      }
                   }?>
                </tr>
            <?php } ?>
        </tbody>  
    </table>
    <div class="amh specials" align="left">
        <span style="color:red;">*</span> <?=$lang['medal_max2']; ?><br />
        <span style="color:red;">**</span> <?=$lang['medal_max']; ?>
    </div>
</div>