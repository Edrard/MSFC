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
    * @version     $Rev: 2.1.6 $
    *
    */
?>
<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    if (file_exists(dirname(__FILE__).'/func_ajax.php')) {
        define('LOCAL_DIR', dirname(__FILE__));
        include_once (LOCAL_DIR.'/func_ajax.php');

        define('ROOT_DIR', base_dir('ajax'));

    }else{
        define('LOCAL_DIR', '.');
        include_once (LOCAL_DIR.'/func_ajax.php');

        define('ROOT_DIR', '..');

    }
    include_once(ROOT_DIR.'/including/check.php');
    include_once(ROOT_DIR.'/function/auth.php');
    include_once(ROOT_DIR.'/function/mysql.php');
    include_once(ROOT_DIR.'/function/func.php');
    include_once(ROOT_DIR.'/function/func_main.php');
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
    $new = $cache->get('get_last_roster_'.$config['clan'],0);

    if(empty($new['data']['members'])){
        $res = array();
    } else {
      foreach($new['data']['members'] as $val) {
        $res[] = $val['account_name'];
      }
    }

    $time = array();
    $activity = array();

    $cache_activity = new Cache(ROOT_DIR.'/cache_activity/');

    if(isset($_POST['a_from']) and isset($_POST['a_to']) and preg_match('/[0-9]{2}.[0-9]{2}.[0-9]{4}/',$_POST['a_from']) and preg_match('/[0-9]{2}.[0-9]{2}.[0-9]{4}/',$_POST['a_to'])) {
      $t1 = explode('.',$_POST['a_from']);
      $t2 = explode('.',$_POST['a_to']);

      $time['from'] = mktime(0, 0, 0, $t1['1'], $t1['0'], $t1['2']);
      $time['to'] = mktime(23, 59, 59, $t2['1'], $t2['0'], $t2['2']);
    } else {
      $time['from'] = mktime(0, 0, 0, date('m'), date('d')-7, date('Y'));
      $time['to'] = time();
    }
    
    if(isset($_POST['a_all']) and $_POST['a_all'] ==1) {
      $a_all = 1;
    } else {
      $a_all = 0;
    }

    for($i=$time['from'];$i<=$time['to'];$i+=86400) {
      $t[date('d.m.Y',$i)] = $cache_activity->get(date('d.m.Y',$i),0);
    }

    $empty = 0;
    foreach($t as $date => $val) {
      if(isset($val)) {
        $activity[$date] = $val['players'];
        if(!empty($val['players'])) { ++$empty; }
      }
    }
    unset($t,$cache_activity);
?>
<?php if($empty != 0 or ($a_all == 1)) { ?>
<script type="text/javascript" id="js">
    $(document).ready(function()
        {   $("#activity_table").tablesorter({sortList:[[0,0]], widthFixed: false, headerTemplate : '{content} {icon}',  widgets: ['uitheme', 'zebra'],
                                              widgetOptions: {uitheme : 'jui'}
            });
        });
</script>
<table id="activity_table" cellspacing="1" width="100%">
    <thead>
        <tr>
            <th><?=$lang['name']; ?></th>
            <?php for($i=$time['from'];$i<=$time['to'];$i+=86400) { ?>
                  <?php if(isset($activity[date('d.m.Y',$i)]) or ($a_all == 1)) { ?>
                        <th align="center"><?php echo date('d.m.Y',$i); ?></th>
                  <?php } ?>
            <?php } ?>
            <th align='center' style="min-width: 30px;"><?=$lang['activity_4'];?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($res as $val => $name){ $x = 0; ?>
            <tr>
                <td><a href="<?php echo $config['base'].$name.'/'; ?>"
                        target="_blank"><?=$name; ?></a></td>
                <?php for($i=$time['from'];$i<=$time['to'];$i+=86400) {
                  if(isset($activity[date('d.m.Y',$i)]) or ($a_all == 1)) {
                  if(isset($activity[date('d.m.Y',$i)][$name])) { ?>
                    <td align="center"><?=$activity[date('d.m.Y',$i)][$name];?></td> <? $x += $activity[date('d.m.Y',$i)][$name];
                  } else { ?>
                    <td></td>
               <? }
                  }
                } ?>
                <td align='center'><?=$x; ?></td>
            </tr>
            <?php } ?>
    </tbody>
</table>
<?php } else {
 echo $lang['activity_error_2'];
 } 
unset($activity);?>