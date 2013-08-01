<?php
    /*
    * Project:     Clan Stat
    * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
    * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
    * -----------------------------------------------------------------------
    * Began:       2012
    * Date:        $Date: 2012-11-27
    * -----------------------------------------------------------------------
    * @author      $Author: SHW $
    * @copyright   2012-2012 SHW
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 2.1.5 $
    *
    */
?>
<?php
    $lang['tank_choise2'] = '<div class="ui-state-highlight ui-widget-content">Не применен фильтр</div>';
    $lang['tank_filt'] = 'Применен фильтр: ';
    error_reporting(E_ALL & ~E_STRICT);
    ini_set("display_errors", 1);
    if (file_exists(dirname(__FILE__).'/func_ajax.php')) {
        define('LOCAL_DIR', dirname(__FILE__));
        include_once (LOCAL_DIR.'/func_ajax.php');
        define('ROOT_DIR', base_dir('ajax'));
    }else{
        define('LOCAL_DIR', '.');
        include_once (LOCAL_DIR.'/func_ajax.php');
        define('ROOT_DIR', '..');
    };
    include_once(ROOT_DIR.'/function/mysql.php');
    $db->change_prefix($_POST['db_pref']);
    include_once(ROOT_DIR.'/function/func.php');
    include_once(ROOT_DIR.'/function/config.php');
    include_once(ROOT_DIR.'/config/config_'.$config['server'].'.php');

    foreach(scandir(ROOT_DIR.'/translate/') as $files){
        if (preg_match ("/_".$config['lang'].".php/", $files)){
            include_once(ROOT_DIR.'/translate/'.$files);
        }
    } 
    include_once(ROOT_DIR.'/function/cache.php');

    //cache
    $cache = new Cache(ROOT_DIR.'/cache/');
?>

<script type="text/javascript" id="js">     
    $(document).ready(function() 
    {   $("#tankonlist").tablesorter({
          headerTemplate : '<div style="padding-right: 12px;">{content}</div>{icon}',
          widgets : ['uitheme', 'zebra'],
          widthFixed : false,
          sortList:[[0,0]],
          theme : 'jui'
        });
    });
    $('.bb[title]').tooltip({
        track: false,
        delay: 0,
        fade: 250,
        items: "[title]",
        content: function() {
            var element = $( this );
            if ( element.is( "[title]" ) ) {
                 return element.attr( "title" );
            }
        }
    });
</script>

<? if (isset($_POST['nation']) ) {
        $tank_nation = $_POST['nation'];
    }else{
        $tank_nation = '';
    };
    if (isset($_POST['type']) ) {
        $tank_type = $_POST['type'];
    }else{
        $tank_type = '';
    };
    if ((isset($_POST['lvl'])) && ($_POST['lvl'] <>'-1')) {
        $tank_lvl = $_POST['lvl'];
    }else{
        $tank_lvl = '';
    };
    if (isset($_POST['tank']) ) {
        $tank_name = $_POST['tank'];
    }else{
        $tank_name = '';
    };
if (($tank_nation<>'')&&($tank_type<>'')&&($tank_lvl<>'')&&($tank_name<>'')){
     $new_roster = $cache->get('get_last_roster_'.$config['clan'],0);
     foreach($new_roster['data']['members'] as $val)
        $res[$val['account_name']] = $cache->get($val['account_name'],0,ROOT_DIR.'/cache/players/');
     $display = array ();
     foreach($res as $keyname => $name){
        if (!isset($name['tank'])) $name['tank'] =array();
        foreach($name['tank'] as $keylvl => $lvl) {
           if (($tank_lvl == $keylvl)||($tank_lvl == 'all')){
              foreach($lvl as $keytype => $type) {
                 if (($tank_type == $keytype)||($tank_type == 'all')){
                    foreach($type as $tankname => $val) {
                       if (($tank_nation == $val['nation'])||($tank_nation == 'all')) {
                           if (($tank_name == $tankname)||($tank_name == 'all')) {
                                $display[$keyname][$tankname][] = $val['total'];
                                $display[$keyname][$tankname][] = round(($val['win']/$val['total'])*100,2);
                                $display[$keyname][$tankname][] = round($val['survivedBattles']/$val['total']*100,2);
                                $display[$keyname][$tankname][] = round($val['damageDealt']/$val['total'],2);
                                $display[$keyname][$tankname][] = round($val['frags']/$val['total'],2);
                                $display[$keyname][$tankname][] = round($val['spotted']/$val['total'],2);

                                $display[$keyname][$tankname][] = $val['win'];
                                $display[$keyname][$tankname][] = $val['survivedBattles'];
                                $display[$keyname][$tankname][] = $val['damageDealt'];
                                $display[$keyname][$tankname][] = $val['frags'];
                                $display[$keyname][$tankname][] = $val['spotted'];
                                $display[$keyname][$tankname][] = $keylvl;
                           }
                       }
                    }
                 }
              }
           }
        }
     };
};
      if (!empty($display)) {
         if ($tank_name <> 'all') { ?>
           <center><?=$lang['tank_filt'].$tank_name;?></center>
            <table id="tankonlist" width="100%" cellspacing="1" class="table-id-<?=$_POST['key'];?>">
                 <thead>
                    <tr>
                        <th><?=$lang['name']; ?></th>
                        <th class="{sorter: 'digit'}"><?=$lang['total']; ?></th>
                        <th class="{sorter: 'digit'}"><?=$lang['winp']; ?></th>
                        <th class="{sorter: 'digit'}"><?=$lang['alivep']; ?></th>
                        <th class="{sorter: 'digit'}"><?=$lang['dmg']; ?></th>
                        <th class="{sorter: 'digit'}"><?=$lang['frg']; ?></th>
                        <th class="{sorter: 'digit'}"><?=$lang['spot']; ?></th>
                    </tr>
                 </thead>
                 <tbody>
                      <?php foreach($display as $keyname => $val){?>
                            <tr>
                               <td><a href="<?=$config['base'].$keyname.'/'; ?>" target="_blank"><?=$keyname; ?></a></td>
                               <?php for ($i = 0; $i <= 5; $i++) {?>
                               <td><?=$val[$tank_name][$i];?></td>
                               <?php } ?>
                            </tr>
                      <?php } ?>
                </tbody>
            </table>
<?php    } else { ?>
<center><?php echo $lang['tank_filt'];
        if (($tank_nation=='all')&&($tank_type=='all')&&($tank_lvl=='all')) echo 'Все';
        if ($tank_nation <> 'all') echo $lang[$tank_nation];
        if ($tank_type <> 'all') echo " ".$lang[$tank_type];
        if ($tank_lvl <> 'all') echo " ".$tank_lvl." лвл";?>
<br></center>
<?php       foreach ($display as $keyname => $val) {
               foreach ($val as $keytank => $val2){
                  $tanksbest[$keytank]['lvl'] = $val2[11];
                  $tanksbest[$keytank]['maxg']  = 0; $tanksbest[$keytank]['maxgpl']  = ''; $tanksbest[$keytank]['maxgstr'] = '';
                  $tanksbest[$keytank]['maxw']  = 0; $tanksbest[$keytank]['maxwpl']  = ''; $tanksbest[$keytank]['maxwstr'] = '';
                  $tanksbest[$keytank]['maxsp'] = 0; $tanksbest[$keytank]['maxsppl'] = ''; $tanksbest[$keytank]['maxspstr'] = '';
                  $tanksbest[$keytank]['maxd']  = 0; $tanksbest[$keytank]['maxdpl']  = ''; $tanksbest[$keytank]['maxdstr'] = '';
                  $tanksbest[$keytank]['maxsu'] = 0; $tanksbest[$keytank]['maxsupl'] = ''; $tanksbest[$keytank]['maxsustr'] = '';
                  $tanksbest[$keytank]['maxf']  = 0; $tanksbest[$keytank]['maxfpl']  = ''; $tanksbest[$keytank]['maxfstr'] = '';
               }
            };

            foreach ($display as $keyname => $val) {
               foreach ($val as $keytank => $val2){
                  if ($val2[0] >20) {
                     if ($tanksbest[$keytank]['maxg'] <= $val2[0]) {
                         $tanksbest[$keytank]['maxg'] = $val2[0];
                         $tanksbest[$keytank]['maxgpl'] = $keyname;
                         $tanksbest[$keytank]['maxgstr'] = $val2[0];
                     };
                     if ($tanksbest[$keytank]['maxw'] <= $val2[1]) {
                         $tanksbest[$keytank]['maxw'] = $val2[1];
                         $tanksbest[$keytank]['maxwpl'] = $keyname;
                         $tanksbest[$keytank]['maxwstr'] = $val2[6].'/'.$val2[0];
                     };
                     if ($tanksbest[$keytank]['maxsu'] <= $val2[2]) {
                         $tanksbest[$keytank]['maxsu'] = $val2[2];
                         $tanksbest[$keytank]['maxsupl'] = $keyname;
                         $tanksbest[$keytank]['maxsustr'] = $val2[7].'/'.$val2[0];
                     };
                     if ($tanksbest[$keytank]['maxd'] <= $val2[3]) {
                         $tanksbest[$keytank]['maxd'] = $val2[3];
                         $tanksbest[$keytank]['maxdpl'] = $keyname;
                         $tanksbest[$keytank]['maxdstr'] = $val2[8].'/'.$val2[0];
                     };
                     if ($tanksbest[$keytank]['maxf'] <= $val2[4]) {
                         $tanksbest[$keytank]['maxf'] = $val2[4];
                         $tanksbest[$keytank]['maxfpl'] = $keyname;
                         $tanksbest[$keytank]['maxfstr'] = $val2[9].'/'.$val2[0];
                     };
                     if ($tanksbest[$keytank]['maxsp'] <= $val2[5]) {
                         $tanksbest[$keytank]['maxsp'] = $val2[5];
                         $tanksbest[$keytank]['maxsppl'] = $keyname;
                         $tanksbest[$keytank]['maxspstr'] = $val2[10].'/'.$val2[0];
                     };
                  }
               }
            };

echo '<div class="ui-state-highlight ui-widget-content">В таблице указаны игроки с максимальными показателями. Танкист, совершивший менее 20 боев на данной технике, не учитывается.</div>';?>
<table id="tankonlist" width="100%" cellspacing="1" class="table-id1-<?=$_POST['key'];?>">
  <thead>
    <tr>
      <th>лвл</th>
      <th>Танк</th>
      <th class="{sorter: 'digit'}"><img class="bb" src="<?=$config['rating_link']; ?>gpl.png" title="<?=$lang['gpl']; ?>"></th>
      <th class="bb" title="Игрок с максимальным количеством боев на данной технике"><?=$lang['name']; ?></th>
      <th class="{sorter: 'digit'}"><img class="bb" src="<?=$config['rating_link']; ?>win.png" title="<?=$lang['winp']; ?>"></th>
      <th class="bb" title="Игрок с максимальным % побед"><?=$lang['name']; ?></th>
      <th class="{sorter: 'digit'}"><?=$lang['alivep']; ?></th>
      <th class="bb" title="Игрок с максимальным % выживаемости"><?=$lang['name']; ?></th>
      <th class="{sorter: 'digit'}"><img class="bb" src="<?=$config['rating_link']; ?>dmg.png" title="<?=$lang['dmg']; ?>"></th>
      <th class="bb" title="Игрок с максимальным средним уроном за бой"><?=$lang['name']; ?></th>
      <th class="{sorter: 'digit'}"><img class="bb" src="<?=$config['rating_link']; ?>frg.png" title="<?=$lang['frg']; ?>" ></th>
      <th class="bb" title="Игрок с максимальным средним количеством уничтоженой техники за бой"><?=$lang['name']; ?></th>
      <th class="{sorter: 'digit'}"><img class="bb" src="<?=$config['rating_link']; ?>spt.png" title="<?=$lang['spot']; ?>"></th>
      <th class="bb" title="Игрок с максимальным количеством обнаруженной техники за бой"><?=$lang['name']; ?></th>
    </tr>
  </thead>
  <tbody>
<?php
  foreach ($tanksbest as $key => $temp ){
    if (isset($temp['maxw']) && ($temp['maxw'] <> 0 )) {?>
    <tr>
      <td><?=$temp['lvl'];?></td>
      <td><b><?=$key;?></b></td>
                <td class="bb" align="right" title=<?=$temp['maxgstr'];?>><?=$temp['maxg'];?></td>
                <td><?=$temp['maxgpl'];?></td>
        <td class="bb" align="right" title=<?=$temp['maxwstr'];?>><?=$temp['maxw'];?></td>
        <td><?=$temp['maxwpl'];?></td>
                <td class="bb" align="right" title=<?=$temp['maxsustr'];?>><?=$temp['maxsu'];?></td>
                <td><?=$temp['maxsupl'];?></td>
        <td class="bb" align="right" title=<?=$temp['maxdstr'];?>><?=$temp['maxd'];?></td>
        <td><?=$temp['maxdpl'];?></td>
                <td class="bb" align="right" title=<?=$temp['maxfstr'];?>><?=$temp['maxf'];?></td>
                <td><?=$temp['maxfpl'];?></td>
        <td class="bb" align="right" title=<?=$temp['maxspstr'];?>><?=$temp['maxsp'];?></td>
        <td><?=$temp['maxsppl'];?></td>
    </tr>
<?php
    }
  }; ?>
  </tbody>
</table>
<?php }; //else tank_name<>all
    } else {?>
      <center><?=$lang['tank_choise2'];?></center>
<?php };
unset($tanksbest, $display); ?>