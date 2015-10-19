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
* @version     $Rev: 3.2.2 $
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
<script type="text/javascript">
    $(document).ready(function() {
        $("#tabs-sort-<?=$key;?>").tablesorter();
    });
</script>
<div align="center">
    <table id="tabs-sort-<?=$key;?>" width="100%" cellspacing="1" cellpadding="2" class="ui-widget-content table-id-<?=$key;?>">
        <thead> 
            <tr>
                <?php echo '<th>'.$lang['name'].'</th>';
                if($config['company'] == 1 and in_array($key,$company['tabs'])) {
                    echo '<th>',$lang['company'],'</th>';
                }
                $exp = array ('xp', 'battle_avg_xp', 'max_xp');
                foreach($exp as $name){ ?>
                    <th class="{sorter: 'digit'}"><?php if ($name =='max_xp') {echo $lang[$name];} else {echo $lang['all_'.$name];} ?></th>
                    <?php } ?>
            </tr>  
        </thead>
        <tbody>
            <?php foreach($res as $name => $val){ ?>
                <tr> 
                    <td><a href="<?php echo $config['base'],$val['data']['account_id'].'-'.$name,'/'; ?>" target="_blank"><?=$name; ?></a></td>
                    <? if($config['company'] == 1 and in_array($key,$company['tabs'])) { ?>
                        <td><?=in_array($val['data']['account_id'],$company['in_company'])?$company['company_names'][$company['by_id'][$val['data']['account_id']]]:'';?></td>
                        <? } ?>
                    <?php foreach($exp as $column => $cat){
                        echo '<td>';
                        if ($cat=='max_xp') {
                            echo $val['data']['statistics'][$cat];
                        }   else {
                            echo $val['data']['statistics'][$btype][$cat];
                        }
                        echo '</td>';
                    } ?>
                </tr>
                <?php } ?>
        </tbody>  
    </table>
</div>
<?php    
$page = ob_get_contents();
$cache->clear('html_'.$btype.'_'.$key.'_'.$prefix,ROOT_DIR.'/cache/other/');
$cache->set('html_'.$btype.'_'.$key.'_'.$prefix, $page, ROOT_DIR.'/cache/other/');
ob_end_clean();
echo $page;
?>