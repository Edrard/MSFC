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
    * @copyright   2011-2014 Edd - Aleksandr Ustinov
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 3.2.0 $
    *
    */


error_reporting(E_ALL & ~E_STRICT);
ini_set("display_errors", 1);
if (file_exists(dirname(__FILE__).'/func_ajax.php')) {
    define('LOCAL_DIR', dirname(__FILE__));
    require(LOCAL_DIR.'/func_ajax.php');
    define('ROOT_DIR', base_dir('ajax'));
}   else{
    define('LOCAL_DIR', '.');
    define('ROOT_DIR', '..');
}
include(ROOT_DIR.'/including/check.php');
require(ROOT_DIR.'/function/auth.php');
include(ROOT_DIR.'/function/mysql.php');
if (isset($_POST['db_pref'])) $db->change_prefix($_POST['db_pref']);
require(ROOT_DIR.'/function/func.php');
require(ROOT_DIR.'/function/func_main.php');
include(ROOT_DIR.'/function/config.php');
require(ROOT_DIR.'/config/config_'.$config['server'].'.php');

foreach(scandir(ROOT_DIR.'/translate/') as $files){
    if (preg_match ("/_".$config['lang'].".php/", $files)){
        require(ROOT_DIR.'/translate/'.$files);
    }
}
require(ROOT_DIR.'/function/cache.php');

//cache
$cache = new Cache(ROOT_DIR.'/cache/');
$new = $cache->get('get_last_roster_'.$config['clan'],0);

if($config['company'] == 1 ) {
  $company = $cache->get('company_'.$config['clan'],0,ROOT_DIR.'/cache/other/');
  if(!isset($company['in_company'])) {
    $company['in_company'] = array();
  }
  if(!isset($company['tabs'])) {
    $company['tabs'] = array();
  }
}

if (empty($new['data'][$config['clan']]['members'])){
    $res = array();
    $res_id = array();
}   else {
    foreach($new['data'][$config['clan']]['members'] as $val) {
        $res[] = $val['account_name'];
        $res_id[$val['account_name']] = $val['account_id'];
    }
}

if (isset($_POST['a_from']) and isset($_POST['a_to']) and preg_match('/[0-9]{2}.[0-9]{2}.[0-9]{4}/',$_POST['a_from']) and preg_match('/[0-9]{2}.[0-9]{2}.[0-9]{4}/',$_POST['a_to'])) {
    $t1 = explode('.',$_POST['a_from']);
    $t2 = explode('.',$_POST['a_to']);
    $time['from'] = mktime(0, 0, 0, $t1['1'], $t1['0'], $t1['2']);
    $time['to'] = mktime(23, 59, 59, $t2['1'], $t2['0'], $t2['2']);
    $time['to'] = $time['to'] + 86400;
}   else {
    $time['from'] = mktime(0, 0, 0, date('m'), date('d')-8, date('Y'));
    $time['to'] = time();
}

$activity = $tmp = $tmp2 = $count = array();
$count['all'] = $count['clan'] = $count['company'] = $sl_count = 0;
$showarr = array('all', 'clan', 'company');

$sl_count = $db->select('SELECT count(account_id) as count FROM `col_players` WHERE updated_at <= "'.$time['to'].'" AND updated_at >= "'.$time['from'].'" ;',__line__,__file__,'fetch');
if ($sl_count['count'] >0) {
    $tmp = $db->select('SELECT * FROM `col_players` WHERE updated_at <= "'.$time['to'].'" AND updated_at >= "'.$time['from'].'" ORDER BY updated_at ASC;',__line__,__file__);

    foreach ($tmp as $key => $val){
       $tmp2[date('d.m.Y',$val['updated_at'])][$val['account_id']] = $val;
    }

    foreach ($tmp2 as $keydata => $val){
      foreach ($val as $acc_id => $val2){
         $next = date('d.m.Y', strtotime($keydata .' +1 day'));
         if (isset ($tmp2[$next][$acc_id]['all_battles'])) {
             foreach ($showarr as $keyacc) {
                if ($keyacc == 'all') {
                    $activity[$keydata][$val2['nickname']]['all_battles'] = $tmp2[$next][$acc_id]['all_battles'] - $val2['all_battles'] +
                    $val2['clan_battles'] - $tmp2[$next][$acc_id]['clan_battles'] +
                    $val2['company_battles'] - $tmp2[$next][$acc_id]['company_battles'];
                }   else {
                    $activity[$keydata][$val2['nickname']][$keyacc.'_battles'] = $tmp2[$next][$acc_id][$keyacc.'_battles'] - $val2[$keyacc.'_battles'];
                }
                if (!isset ($activity[$keydata][$keyacc][$keyacc.'_battles'])) {
                     $activity[$keydata][$keyacc][$keyacc.'_battles'] = $activity[$keydata][$val2['nickname']][$keyacc.'_battles'];
                }    else {
                     $activity[$keydata][$keyacc][$keyacc.'_battles'] += $activity[$keydata][$val2['nickname']][$keyacc.'_battles'];
                }
             }
         }   else {
             $activity[$keydata][$val2['nickname']]['all_battles'] = $activity[$keydata][$val2['nickname']]['clan_battles'] = $activity[$keydata][$val2['nickname']]['company_battles'] = 0;
              if (!isset ($activity[$keydata]['all']['all_battles'])) {
                  $activity[$keydata]['all']['all_battles'] = $activity[$keydata]['clan']['clan_battles'] = $activity[$keydata]['company']['company_battles'] = 0;
              }
         }
      }
      foreach ($showarr as $keyacc) {
         $count[$keyacc] += $activity[$keydata][$keyacc][$keyacc.'_battles'];
      }
    }
    $time['to'] = $time['to'] - 86400;
    unset($tmp,$tmp2);
}

if (($sl_count['count'] == 0)||($count['all'] == 0 && $count['clan'] == 0 && $count['company'] == 0)) {
    echo '<div align="center" class="ui-state-highlight ui-widget-content">',$lang['activity_error_2'],'</div>';
}   else {
?>
<script type="text/javascript">
    $(document).ready(function()
    {
        $("#activity_table").tablesorter();
        $( "#triggeractivity" ).buttonset();
        $(".clan").hide();
        $(".company").hide();
        $("#change_all").click(function() {
          $(".clan").hide();
          $(".company").hide();
          $(".all").show();
          return false;
        });
        $("#change_clan").click(function() {
          $(".all").hide();
          $(".company").hide();
          $(".clan").show();
          return false;
        });
        $("#change_company").click(function() {
          $(".all").hide();
          $(".clan").hide();
          $(".company").show();
          return false;
        });
    });
</script>
<div align="center">
  <form>
    <div id="triggeractivity" align="center">
        <input type="radio" id="change_all" name="triggerrating" checked="checked" /><label for="change_all"><?=$lang['a_cat_1'];?></label>
        <input type="radio" id="change_clan" name="triggerrating" /><label for="change_clan"><?=$lang['a_cat_2'];?></label>
        <input type="radio" id="change_company" name="triggerrating" /><label for="change_company"><?=$lang['a_cat_3'];?></label>
    </div>
  </form>
</div>
<table id="activity_table" cellspacing="1" width="100%" class="table-id-<?=$_POST['key'];?>">
    <thead>
        <tr>
            <th><?=$lang['name']; ?></th>
            <? if($config['company'] == 1 and in_array($_POST['key'],$company['tabs'])) { ?>
                <th><?=$lang['company']; ?></th>
            <? } ?>
            <?php foreach ($showarr as $colname) {
                     for($i=$time['from'];$i<=$time['to'];$i+=86400) {
                        echo '<th class="'.$colname.'">',date('d.m.Y',$i),'</th>';
                     }
                     echo '<th class="'.$colname.'">',$lang['activity_4'],'</th>';
                  } ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach($res as $name){ ?>
            <tr>
                <td><a href="<?php echo $config['base'].$name.'/'; ?>" target="_blank"><?=$name; ?></a></td>
                <? if($config['company'] == 1 and in_array($_POST['key'],$company['tabs'])) { ?>
                    <td><?=in_array($res_id[$name],$company['in_company'])?$company['company_names'][$company['by_id'][$res_id[$name]]]:'';?></td>
                <? } ?>
                <?php foreach ($showarr as $colname) {
                         $count = 0;
                         for($i=$time['from'];$i<=$time['to'];$i+=86400) {
                             if (isset($activity[date('d.m.Y',$i)][$name][$colname.'_battles']) && $activity[date('d.m.Y',$i)][$name][$colname.'_battles'] <> 0) {
                                 echo '<td class="'.$colname.'">',$activity[date('d.m.Y',$i)][$name][$colname.'_battles'],'</td>';
                             $count += $activity[date('d.m.Y',$i)][$name][$colname.'_battles'];
                             }   else {
                                 echo '<td class="'.$colname.'"></td>';
                             }
                         }
                         echo '<td class="'.$colname.'">',$count,'</td>';
                      }?>
            </tr>
            <?php } ?>
    </tbody>
</table>
<?php }
unset($activity,$count);?>