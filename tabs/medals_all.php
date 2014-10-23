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
    * @version     $Rev: 3.1.2 $
    *
    */

    //get sections for achievements
    //filter medals, which no on in clave have
    $achievements_split = achievements_split($res,$achievements);
?>
<script>
    $(document).ready(function() {
      $("#triggermedals").buttonset();
      $(".ach_am").hide();
      $(".ach_special0").show();
      $("#triggermedals").click(function() {
          $(".ach_am").hide();
          $(".ach_" + $("#triggermedals :radio:checked").attr('id') ).show();
          return true;
      });
    });
</script>
<div align="center">
    <? if (isset($achievements_split['sections']) ) { ?>    
       <?=$lang['select_medals'];?><br /><br />
       <form>
           <div id="triggermedals" align="center">
               <? $split = 1; ?>
               <? foreach($achievements_split['sections'] as $id => $val) { ?>
                  <input type="radio" id="<?=$id;?>0" name="triggermedals" <?=($id=='battle0')?'checked="checked"':'';?> /><label for="<?=$id;?>0" value="<?=$val;?>"><?=$val;?></label>
                  <? $split++; ?>
                  <? if(count($achievements_split['split'][$id]) > 1) {
                       for($i=1;$i<=(count($achievements_split['split'][$id])-1);$i++) { ?>
                        <input type="radio" id="<?=$id,$i;?>" name="triggermedals" /><label for="<?=$id,$i;?>" value="<?=$val,' - ',$i;?>"><?=$val,' - ',$i;?></label>
                      <? $split++; }
                     }
                  if($split > 5) { echo '<br />'; $split = 1; }
                  } ?>
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
                   <?
                   foreach($achievements_split['split'] as $id => $tmp) {
                     foreach($tmp as $n => $tmp2){
                       foreach($tmp2 as $ach_id){
                         $ach = $achievements[$ach_id];
                   ?>
                         <th valign='top' class="{sorter: 'digit'} bb ach_am ach_<?=$ach['section'],$n;?>" title="<div style='min-width:400px;'><?=str_replace('"',"'",$ach['description']),(!empty($ach['condition'])?'<div style=\'padding:0px;margin:10px 0 0 15px\'>'.nl2br($ach['condition']).'</div>':'');?></div>"><img src="<?=$ach['image'];?>" style="width:60px;" /><br><?=(!empty($ach['name_i18n'])?$ach['name_i18n']:'Unknown'),(($ach['type']=='series')?'&nbsp;<span style="color:red;">*</span>':'');?></th>
                   <?
                       }
                     }
                   }
                   ?>
               </tr>
           </thead>
           <tbody>
               <?php foreach($res as $name => $val){ ?>
                   <tr>
                     <td><a href="<?php echo $config['base'],$name,'/'; ?>" target="_blank"><?=$name; ?></a></td>
                     <? if($config['company'] == 1 and in_array($key,$company['tabs'])) { ?>
                         <td><?=in_array($val['data']['account_id'],$company['in_company'])?$company['company_names'][$company['by_id'][$val['data']['account_id']]]:'';?></td>
                     <? } ?>
                     <?
                     foreach($achievements_split['split'] as $id => $tmp) {
                       foreach($tmp as $n => $tmp2){
                         foreach($tmp2 as $ach_id){
                           $ach = $achievements[$ach_id];
                     ?>
                           <td align="center" class="ach_am ach_<?=$ach['section'],$n;?>"><?=isset($val['data']['achievements'][$ach_id])?$val['data']['achievements'][$ach_id]:'';?></td>
                     <?
                         }
                       }
                     }
                     ?>
                   </tr>
               <?php } ?>
           </tbody>
       </table>
    <? } else {
          show_message($lang['error_no_medals']);
       } ?>
</div>