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
* @version     $Rev: 3.2.3 $
*
*/
error_reporting(E_ALL & ~E_STRICT);
ini_set("display_errors", 1);
if (file_exists(dirname(__FILE__).'/func_ajax.php')) {
    define('LOCAL_DIR', dirname(__FILE__));
    require(LOCAL_DIR.'/func_ajax.php');
    define('ROOT_DIR', base_dir('ajax'));

}else{
    define('LOCAL_DIR', '.');
    define('ROOT_DIR', '..');

}

include(ROOT_DIR.'/including/check.php');
require(ROOT_DIR.'/function/auth.php');
include(ROOT_DIR.'/function/mysql.php');
require(ROOT_DIR.'/function/func.php');
require(ROOT_DIR.'/function/func_main.php');
require(ROOT_DIR.'/function/func_time.php');
require(ROOT_DIR.'/function/rating.php');
$prefix = get_post('db_pref');
$db->change_prefix($prefix);
include(ROOT_DIR.'/function/config.php');
require(ROOT_DIR.'/config/config_'.$config['server'].'.php');

foreach(scandir(ROOT_DIR.'/translate/') as $files){
    if (preg_match ("/_".$config['lang'].".php/", $files)){
        require(ROOT_DIR.'/translate/'.$files);
    }
} 
require(ROOT_DIR.'/function/cache.php');

$btype = get_post('btype');
$key = get_post('key');

//cache
$cache = new Cache(ROOT_DIR.'/cache/');
// Checking for render

if($html = $cache->get('html_'.$btype.'_'.$key.'_'.$prefix, 60*60, ROOT_DIR.'/cache/other/')){
    echo $html; die;    
}
ob_start();
$new_roster = $cache->get('get_last_roster_'.$config['clan'],0);
$roster = roster_sort($new_roster['data'][$config['clan']]['members']);
$roster_id = roster_resort_id($roster);

if($config['company'] == 1 ) {
    $company = $cache->get('company_'.$config['clan'],0,ROOT_DIR.'/cache/other/');
    if(!isset($company['in_company'])) {
        $company['in_company'] = array();
    }
    if(!isset($company['tabs'])) {
        $company['tabs'] = array();
    }
}

//print_r($new_roster);
foreach($new_roster['data'][$config['clan']]['members'] as $val){
    $res[$val['account_name']] = $cache->get($val['account_id'],0,ROOT_DIR.'/cache/players/');
}
$col_check  = count($db->select('SELECT DISTINCT updated_at FROM `col_players`;',__line__,__file__));
if($config['cron'] == 1 && $col_check > 2){
    $main_progress = player_progress_main($roster_id,(now() - $config['main_progress']),now());
}                                                                                             
//print_r($main_progress); die;
if($config['cron'] == 1 && $col_check > 2 && count($main_progress['main']) > 0){ 
    $rand_main_progress = array_rand($main_progress['main'], 1);
    $stats2 = array('wins', 'losses', 'survived_battles', 'frags',
        'spotted', 'damage_dealt', 'damage_received',
        'capture_points', 'dropped_capture_points',  'xp'
        // 'shots',  'hits',  'draws', 'battle_avg_xp', 'hits_percents',
    );
    $stats7 = array('losses', 'wins', 'survived_battles');
    $ni = array ('battles');
    ?>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#tabs-sort-<?=$key;?>").tablesorter();
            $(".all_progress_hide").hide();
            $(".main_progress").show();
            $("#trigger_progress").buttonset();
            $("#show_main_progress").click(function() {
                $(".all_progress_hide").hide();
                $(".main_progress").show();
                return false;
            });
            $("#show_average_progress").click(function() {
                $(".all_progress_hide").hide();
                $(".average_progress").show();
                return false;
            });
        });
    </script>
    <div align="center">
        <form>
            <div id="trigger_progress" align="center">
                <input type="radio" id="show_main_progress" name="trigger_progress" checked="checked" /><label for="show_main_progress"><?=$lang['activity_main_progress'];?></label>
                <input type="radio" id="show_average_progress" name="trigger_progress" /><label for="show_average_progress"><?=$lang['activity_average_progress'];?></label>
            </div>
        </form>
        <table id="tabs-sort-<?=$key;?>" cellspacing="1" class="table-id-<?=$key;?> ui-widget-content">
            <thead>
                <tr>
                    <th><?=$lang['name'];?></th>
                    <?php if($config['company'] == 1 and in_array($key,$company['tabs'])) { ?>
                        <th><?=$lang['company']; ?></th>
                        <?php } ?>
                    <th><?=$lang['all_battles'];?></th>
                    <?
                    foreach ($stats2 as $val) {
                        if ($main_progress['totaldiff'][$btype][$val] <>0) {?>
                            <th class="{sorter: 'digit'} all_progress_hide main_progress"><?=$lang['all_'.$val];?></th>
                            <?php }
                        if (($main_progress['totalavr'][$btype][$val] <>0)&&(!in_array($key,$ni))) {?>
                            <th class="{sorter: 'digit'} all_progress_hide average_progress"><?=$lang['all_'.$val];?></th>
                            <?php }
                    } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($roster_id as $acc_id =>$val2) {
                    echo '<tr>';
                    echo '<td><a href="',$config['base'],$acc_id.'-'.$roster_id[$acc_id]['account_name'],'/','" target="_blank">',$roster_id[$acc_id]['account_name'],'</a></td>';
                    if($config['company'] == 1 and in_array($key,$company['tabs'])) {
                        echo '<td>';
                        echo in_array($acc_id,$company['in_company'])?$company['company_names'][$company['by_id'][$acc_id]]:'';
                        echo '</td>';
                    }
                    echo '<td>';
                    if (isset($main_progress['delta'][$acc_id][$btype]['battles'])) {
                        echo $main_progress['delta'][$acc_id][$btype]['battles'];
                    }
                    echo '</td>';
                    foreach ($stats2 as $val) {

                        if ($main_progress['totaldiff'][$btype][$val] <>0) {
                            echo '<td class="all_progress_hide main_progress">';
                            if (isset($main_progress['delta'][$acc_id][$btype][$val]) && ($main_progress['delta'][$acc_id][$btype][$val]<>0)) {
                                echo $main_progress['delta'][$acc_id][$btype][$val];
                            }
                            echo '</td>';
                        }

                        if (($main_progress['totalavr'][$btype][$val] <>0)&&(!in_array($val,$ni))) {
                            echo '<td class="all_progress_hide average_progress">';
                            if (isset($main_progress['average'][$acc_id][$btype][$val]) && ($main_progress['average'][$acc_id][$btype][$val]<>0)) {
                                if (in_array($val,$stats7)) {
                                    echo '<span class ="bb" title="';
                                    if ($main_progress['main'][$acc_id][$btype][$val]>0) echo '+';
                                    echo $main_progress['main'][$acc_id][$btype][$val],'">';
                                    echo $main_progress['average'][$acc_id][$btype][$val]*100,'%</span>';
                                }   else {
                                    echo round($main_progress['average'][$acc_id][$btype][$val],2);
                                }
                            }
                            echo '</td>';
                        }
                    }
                    echo '</tr>';
                } ?>
            </tbody>  
        </table>
    </div>
    <?php }else{ ?>
    <div class="num"><?=$lang['error_cron_off_or_none'];?></div>
    <?php } ?>
<?php    
$page = ob_get_contents();
$cache->clear('html_'.$btype.'_'.$key.'_'.$prefix,ROOT_DIR.'/cache/other/');
$cache->set('html_'.$btype.'_'.$key.'_'.$prefix, $page, ROOT_DIR.'/cache/other/');
ob_end_clean();
echo $page; 
?>