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
* @version     $Rev: 3.2.1 $
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
?>
<div align="center">
    <script>
        $(document).ready(function() {
            $("#tabs-sort-<?=$key;?>").tablesorter();
            $( "#triggerperform" ).buttonset();
            $(".fs").hide();
            $(".as").show();
            $("#change_button_averageshow").click(function() {
                $(".fs").hide();
                $(".as").show();
                return false;
            });
            $("#change_button_fullshow").click(function() {
                $(".as").hide();
                $(".fs").show();
                return false;
            });
        });
    </script>
    <form>
        <div id="triggerperform" align="center" class="table-id-<?=$key;?>">
            <input type="radio" id="change_button_fullshow" name="triggerperform" /><label for="change_button_fullshow"><?=$lang['show_full_perform'];?></label>
            <input type="radio" id="change_button_averageshow" name="triggerperform" checked="checked" /><label for="change_button_averageshow"><?=$lang['show_average_perform'];?></label>
        </div>
    </form>
    <table id="tabs-sort-<?=$key;?>" width="100%" cellspacing="1" class="table-id-<?=$key;?>">
        <thead>
            <tr>
                <th><?=$lang['name']; ?></th>
                <? if($config['company'] == 1 and in_array($key,$company['tabs'])) { ?>
                    <th><?=$lang['company']; ?></th>
                    <? } ?>
                <? $perform = array ('hits_percents', 'frags',  'damage_dealt', 'damage_received', 'spotted', 'capture_points', 'dropped_capture_points');
                foreach($perform as $cat){ ?>
                    <?php if($cat == 'hits_percents') { ?>
                        <th class='fs as'><?=$lang['all_'.$cat];?></th>
                        <? } else { ?>
                        <th class='as'><?=$lang['all_'.$cat];?></th>
                        <th class='fs'><?=$lang['all_'.$cat];?></th>
                        <?php } } ?>
            </tr>  
        </thead>
        <tbody>
            <?php foreach($res as $name => $val){  ?>
                <tr> 
                    <td><a href="<?php echo $config['base'],$val['data']['account_id'].'-'.$name,'/'; ?>" target="_blank"><?=$name; ?></a></td>
                    <? if($config['company'] == 1 and in_array($key,$company['tabs'])) { ?>
                        <td><?=in_array($val['data']['account_id'],$company['in_company'])?$company['company_names'][$company['by_id'][$val['data']['account_id']]]:'';?></td>
                        <? } ?>
                    <?php foreach($perform as $cat){ ?>
                        <?php if($cat == 'hits_percents') { ?>
                            <td class='fs as'>
                                <?php echo $val['data']['statistics'][$btype][$cat]; ?>
                            </td>
                            <?php } else { ?>
                            <td class='as'>
                                <? if($val['data']['statistics'][$btype]['battles'] > 0) { echo round($val['data']['statistics'][$btype][$cat]/$val['data']['statistics'][$btype]['battles'],2); } else { echo '0'; } ?>
                            </td>
                            <td class='fs'>
                                <?php echo $val['data']['statistics'][$btype][$cat]; ?>
                            </td>
                            <?php }
                    } ?>
                </tr>
                <?php } ?>
        </tbody>  
    </table>
</div>
<? unset($column); unset($cat); unset($name); unset($val); ?>
<?php    
$page = ob_get_contents();
$cache->clear('html_'.$btype.'_'.$key.'_'.$prefix,ROOT_DIR.'/cache/other/');
$cache->set('html_'.$btype.'_'.$key.'_'.$prefix, $page, ROOT_DIR.'/cache/other/');
ob_end_clean();
echo $page;
?>