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
?>
<?
$maps = get_api('wot/globalwar/maps');
$maps_id = array();
if(isset($maps['status']) and $maps['status'] == 'ok') {
  foreach($maps['data'] as $val) {
    if($val['type'] == 'active' and $val['state'] == 'event') { $maps_id[] = $val['map_id']; }
  }
}
if(empty($maps_id)){
  $maps_id = 'eventmap';
}
$fame = get_api('wot/globalwar/accountpoints',array('map_id' => $maps_id, 'account_id' => array_keys($roster_id)));
if(!isset($fame['status']) or $fame['status'] != 'ok') {
  $fame = array();
}
?>
<div align="center">
    <table id="tabs-sort-<?=$key;?>" width="100%" cellspacing="1" cellpadding="2" class="ui-widget-content table-id-<?=$key;?>">
        <thead>
          <tr>
            <th><?=$lang['name'];?></th>
            <th class="empty-bottom"><?=$lang['famepoints_points'];?></th>
            <th class="empty-bottom"><?=$lang['famepoints_position'];?></th>
          </tr>
        </thead>
        <tbody>
            <?php foreach($res as $name => $val){ ?>
                <tr>
                    <td><a href="<?php echo $config['base'],$name,'/'; ?>" target="_blank"><?=$name; ?></a></td>
                    <td><?=(isset($fame['data'][$val['data']['account_id']]['points']))?$fame['data'][$val['data']['account_id']]['points']:'';?></td>
                    <td><?=(isset($fame['data'][$val['data']['account_id']]['position']))?$fame['data'][$val['data']['account_id']]['position']:'';?></td>
                </tr>
                <?php } ?>
        </tbody>
    </table>
</div>