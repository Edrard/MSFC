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
if($btype == 'all'){
    /* code for eff. ratings */
    $eff_rating = $cache->get('eff_ratings_'.$config['clan'], 0, ROOT_DIR.'/cache/other/');
    build_ratings_tables();

    if(is_array($eff_rating)) {
        $tmp = array_diff(array_keys($roster),array_keys($eff_rating));
        if(!empty($tmp)) { $update_eff = 1; }
        unset($tmp);
    }

    if(isset($update_eff) or $eff_rating == false) {
        $eff_rating = eff_rating($res,$wn8);
        $cache->clear('eff_ratings_'.$config['clan'],ROOT_DIR.'/cache/other/');
        $cache->set('eff_ratings_'.$config['clan'], $eff_rating, ROOT_DIR.'/cache/other/');
    }
}
?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#tabs-sort-<?=$key;?>").tablesorter();
        $( "#triggeroverall" ).buttonset();
        $(".overall_average").hide();
        $(".overall_value").show();
        $("#change_overall_value").click(function() {
            $(".overall_average").hide();
            $(".overall_value").show();
            return false;
        });
        $("#change_overall_average").click(function() {
            $(".overall_value").hide();
            $(".overall_average").show();
            return false;
        });
    });
</script>
<? $eff_ratings_list = array('eff','wn7','wn8','brone'); ?>
<form>
    <div id="triggeroverall" align="center">
        <input type="radio" id="change_overall_value" name="triggeroverall" checked="checked" /><label for="change_overall_value"><?=$lang['overall_value'];?></label>
        <input type="radio" id="change_overall_average" name="triggeroverall" /><label for="change_overall_average"><?=$lang['overall_average'];?></label>
    </div>
</form>
<div align="center">
    <table id="tabs-sort-<?=$key;?>" width="100%" cellspacing="1" class="table-id-<?=$key;?>">
        <thead>
            <tr>
                <th><?=$lang['name']; ?></th>
                <? if($config['company'] == 1 and in_array($key,$company['tabs'])) { ?>
                    <th><?=$lang['company']; ?></th>
                    <? } ?>
                <?php
                $overall = array ('battles', 'wins', 'losses', 'survived_battles');
                foreach($overall as $name){ ?>
                    <th class="{sorter: 'digit'} <? if ($name != 'battles') {echo 'overall_value';} ?>"><?=$lang['all_'.$name];?></th>
                    <?php }
                foreach($overall as $column => $name){
                    if($name != 'battles') { ?>
                        <th class="{sorter: 'digit'} overall_average sorter-percent"><?=$lang['all_'.$name];?></th>
                        <?php }
                } ?>
                <?php if($btype == 'all'){ ?>
                    <? foreach($eff_ratings_list as $val) { ?>
                        <th class="overall_value overall_average"><?=$lang[$val.'_ret'];?></th>
                        <? } ?>
                    <? } ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach($res as $name => $val){ ?>
                <tr>
                    <td><a href="<?php echo $config['base'],$val['data']['account_id'].'-'.$name,'/'; ?>" target="_blank"><?=$name; ?></a></td>
                    <? if($config['company'] == 1 and in_array($key,$company['tabs'])) { ?>
                        <td><?=in_array($val['data']['account_id'],$company['in_company'])?$company['company_names'][$company['by_id'][$val['data']['account_id']]]:'';?></td>
                        <? } ?>
                    <?php
                    foreach($overall as $column => $namec){?>
                        <td class="<? if($namec != 'battles') {echo 'overall_value';} ?>"><?= $val['data']['statistics'][$btype][$namec];?></td>
                        <?php }
                    foreach($overall as $column => $namec){?>
                        <?php if($namec != 'battles') { ?>
                            <td class="overall_average"><?php
                                if ($val['data']['statistics'][$btype]['battles']<> 0) {
                                    echo (number_format($val['data']['statistics'][$btype][$namec]/$val['data']['statistics'][$btype]['battles']*100,2));
                                }   else {
                                    echo '0';
                                }; ?>%</td>
                            <?php } ?>
                        <?php } ?>
                    <?php if($btype == 'all'){ ?>
                        <? foreach($eff_ratings_list as $val) { ?>
                            <td class="overall_value overall_average"><font color="<?=$eff_rating[$name][$val.'_color'];?>"><?=($eff_rating[$name][$val]>0)?$eff_rating[$name][$val]:'0';?></font></td>
                            <? } ?>
                        <?php } ?>
                </tr>
                <?php } ?>
        </tbody>  
    </table>
</div>
<br>
<?php if($btype == 'all'){ ?>
    <center><?=$lang['value_ret_decr'],'<br>',$lang['overall_all_table'];?></center>
    <?php } ?>
<?php    
$page = ob_get_contents();
$cache->clear('html_'.$btype.'_'.$key.'_'.$prefix,ROOT_DIR.'/cache/other/');
$cache->set('html_'.$btype.'_'.$key.'_'.$prefix, $page, ROOT_DIR.'/cache/other/');
ob_end_clean();
echo $page;
?>