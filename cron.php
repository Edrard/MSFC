<?php
/*
* Project:     Clan Stat
* License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
* Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
* -----------------------------------------------------------------------
* Began:       2011
* Date:        $Date: 2013-11-22 00:00:00 +0200 $
* -----------------------------------------------------------------------
* @author      $Author: Edd, Exinaus, SHW  $
* @copyright   2011-2013 Edd - Aleksandr Ustinov
* @link        http://wot-news.com
* @package     Clan Stat
* @version     $Rev: 3.1.1 $
*
*/


header("Content-type: text/html; charset=utf-8");
error_reporting(E_ALL & ~E_STRICT);
ini_set("display_errors", 1);
if (file_exists(dirname(__FILE__).'/function/mysql.php')) {
    define('ROOT_DIR', dirname(__FILE__));
}else{
    define('ROOT_DIR', '.');    
}
// IS_CRON - this parametr show us thats we running cron.php, to write errors to cron.log. Code for writing errors in including/check.php
define('IS_CRON', '1');
//Starting script time execution timer
$begin_time = microtime(true);

if(!isset($_GET['user']) && !isset($_GET['pass'])){
    $user = '';
    $pass = '';
}else{
    $user = $_GET['user'];
    $pass = $_GET['pass'];
}                                          

//Checker
include(ROOT_DIR.'/including/check.php');

//MYSQL
include(ROOT_DIR.'/function/mysql.php');

//Multiget CURL
require(ROOT_DIR.'/function/curl.php');
require(ROOT_DIR.'/function/mcurl.php');

// Include Module functions
require(ROOT_DIR.'/function/auth.php');
require(ROOT_DIR.'/function/func.php');
require(ROOT_DIR.'/function/func_main.php');
require(ROOT_DIR.'/function/func_cron.php');
require(ROOT_DIR.'/function/func_get.php');

// Including main config files
include(ROOT_DIR.'/function/config.php');
include(ROOT_DIR.'/config/config_'.$config['server'].'.php');

//Loading language pack
foreach(scandir(ROOT_DIR.'/translate/') as $files){
    if (preg_match ("/_".$config['lang'].".php/", $files)){
        require(ROOT_DIR.'/translate/'.$files);
    }
}
require(ROOT_DIR.'/admin/translate/login_'.$config['lang'].'.php');
require(ROOT_DIR.'/function/cache.php');

$myFile = ROOT_DIR."/cron.log";
$log = 0;
$links = array();
$date = date('Y-m-d H:i');
$time = time();
if($fh = fopen($myFile, 'a')){
    $log = 1;
    fwrite($fh, $date."////////////////////////////////////////////--->\n");
    fwrite($fh, $date.": (Info) Loging Started (v. ".$config['version'].")\n");
    cron_current_run($fh, $date);
}

//cache
$cache = new Cache(ROOT_DIR.'/cache/');
//Multiclan
$multiclan = read_multiclan();
$multi_prefix = array_resort($multiclan,'prefix');

if($config['cron_multi'] == 1){
    // Multiget.
    foreach($multiclan as $val){
        $cron_time = get_config_cron_time($val['prefix']);
        if(($val['cron'] + $cron_time[0]['value']*3600) <= now() ){
            if($db->change_prefix($val['prefix']) == TRUE){ 
                $dbprefix = $val['prefix'];
                unset($config);
                include(ROOT_DIR.'/function/config.php');
                include(ROOT_DIR.'/config/config_'.$config['server'].'.php');
                $config['clan'] = $val['id'];
                break;
            }
        }
    }
}
unset($multiclan);
if(!$dbprefix){
    $dbprefix = 'msfc_';
}
if($log == 1)  fwrite($fh, $date.": (Info) Current db prefix: ".$dbprefix.", clain ID: ".$config['clan']."\n");

if (($multi_prefix[$dbprefix]['cron'] + $config['cron_time']*3600) <= now() ){
    if ($config['cron'] == 1){

        //check table tanks
        cron_update_tanks_db();
        $nations = tanks_nations();
        $medals = achievements();
        $tanks = tanks();
        //check other tables
        check_tables($medals, $nations, $tanks);

        //Geting clan roster from wargaming.
        $new = $cache->get('get_last_roster_'.$config['clan'],0);
        $new2 = get_clan_v2($config['clan'], 'info', $config); //dg65tbhjkloinm
        //print_r($new2); die;
        if ($new2 === FALSE) {
            if($log == 1)  fwrite($fh, $date.": (Err) No roster from WG!"."\n");
            die('Some problem with downloading data from WG');
        }   else { //new2 ok
            if ($new === FALSE) {
                if($log == 1)  fwrite($fh, $date.": (WG) First load of roster from WG."."\n");
            }   else {
                if($log == 1)  fwrite($fh, $date.": (WG) Successful get some data from WG."."\n");
            }
            if (empty($new2) || (!isset($new2['status']))) {
                $new2['status'] = 'error';
            }
            if (!isset($new['data'][$config['clan']]['updated_at'])){
                $new['data'][$config['clan']]['updated_at'] = 0;
            }
            if($new2['status'] == 'error'){
                $new2 = $new;
            }    
            //$new2 = $new; //leave for testing purpose
            if ((isset($new2['status'])) && ($new2['status'] == 'ok')) {
                if ($new2['data'][$config['clan']]['updated_at'] >= $new['data'][$config['clan']]['updated_at']) {
                    //write to cache
                    $cache->clear('get_last_roster_'.$config['clan']);
                    $cache->set('get_last_roster_'.$config['clan'], $new2);
                    //Sorting roster
                    $roster = roster_sort($new2['data'][$config['clan']]['members']);
                    //prepare some data
                    $counter = $error_messages = array();
                    $counter['old'] = array();
                    //get list of members in DB, if some of theme already there from previous unsucessfull runs
                    $sql = 'SELECT account_id FROM `col_players` WHERE updated_at >= "'.(now()-$config['cron_time']*60*60).'" ORDER BY updated_at DESC;';
                    $q = $db->prepare($sql);
                    if ($q->execute() == TRUE) {
                       $tmp = $q->fetchAll(PDO::FETCH_COLUMN);
                       if(!empty($tmp)) {
                         $counter['old'] = array_flip($tmp);
                       }
                    }
                    //Starting geting data
                    if (count($new2['data'][$config['clan']]['members']) > 0){
                        foreach ($new2['data'][$config['clan']]['members'] as $val){
                          if(!isset($counter['old'][$val['account_id']])) {
                            $toload[] = $val['account_id'];
                            //break; //leave for testing purpose
                          }
                        }
                        if (!empty($toload)) {
                            //prepare some data
                            $counter['total'] = count($toload);
                            $counter['total_members'] = count($new2['data'][$config['clan']]['members']);
                            $counter['old_count'] = count($counter['old']);
                            $counter['get'] = $try = 0;

                            if ($counter['old_count'] > 0 and $log == 1){
                               fwrite($fh, $date.": (Info) Found info about ".$counter['old_count']." players in database"."\n");
                            }
                            if ($counter['total'] > 0 and $log == 1){
                               fwrite($fh, $date.": (WG) Try to load info on ".$counter['total']." players"."\n");
                            }
                            do {
                              $res1 = $res2 = $res3 = $res4 = array();
                              $res1 = multiget_v2('account_id', $toload, 'account/info');
                              $res2 = multiget_v2('account_id', $toload, 'account/tanks', array('mark_of_mastery', 'tank_id', 'statistics.battles', 'statistics.wins')); //loading only approved fields
                              $res3 = multiget_v2('account_id', $toload, 'ratings/accounts', array(), array('type'=>'all'));
                              $res4 = multiget_v2('account_id', $toload, 'account/achievements');

                              foreach($toload as $link_id => $p_id) {
                                //info
                                if( !isset($res1[$p_id]['status']) or $res1[$p_id]['status'] != 'ok' or empty($res1[$p_id]['data']) ) {
                                  if(isset($res1[$p_id]['error']['message'])) {$error_messages[$p_id] = ' ( '.$res1[$p_id]['error']['message'].' )';}
                                  continue;
                                }
                                //tanks
                                if( !isset($res2[$p_id]['status']) or $res2[$p_id]['status'] != 'ok' ) {
                                  if(isset($res2[$p_id]['error']['message'])) {$error_messages[$p_id] = ' ( '.$res2[$p_id]['error']['message'].' )';}
                                  continue;
                                }
                                //ratings
                                if( !isset($res3[$p_id]['status']) or $res3[$p_id]['status'] != 'ok') {
                                  if(isset($res3[$p_id]['error']['message'])) {$error_messages[$p_id] = ' ( '.$res3[$p_id]['error']['message'].' )';}
                                  continue;
                                }
                                //achievements
                                if( !isset($res4[$p_id]['status']) or $res4[$p_id]['status'] != 'ok') {
                                  if(isset($res4[$p_id]['error']['message'])) {$error_messages[$p_id] = ' ( '.$res4[$p_id]['error']['message'].' )';}
                                  continue;
                                }

                                $counter['get']++;
                                $to_cache = array();
                                $to_cache = $res1[$p_id];
                                if(isset($res2[$p_id]['data'])) { $to_cache['data']['tanks'] = array_resort($res2[$p_id]['data'],'tank_id'); }
                                if(isset($res3[$p_id]['data'])) { $to_cache['data']['ratings'] = $res3[$p_id]['data']; }
                                if(isset($to_cache['data']['achievements'])) {
                                  unset($to_cache['data']['achievements']);
                                }
                                $to_cache['data']['achievements'] = $res4[$p_id]['data']['achievements'];
                                $to_cache['data']['role'] = $new2['data'][$config['clan']]['members'][$p_id]['role'];
                                $to_cache['data']['created_at'] = $new2['data'][$config['clan']]['members'][$p_id]['created_at'];

                                $cache->set($p_id, $to_cache, ROOT_DIR.'/cache/players/');
                                cron_insert_pars_data($to_cache, $medals, $tanks, $nations, $time);

                                unset($toload[$link_id]);
                              }
                              $try++;
                            }  while ( !empty($toload) and $try < $config['try_count'] );
                            //log how many players are added to db
                            if($log == 1) {
                              fwrite($fh, $date.": (Info) Successfully added information about ".$counter['get']." players out of ".$counter['total']."\n");
                            }
                            //if some players are still not loaded
                            if (!empty($toload) and $log == 1) {
                              foreach($toload as $p_id) {
                                if(isset($error_messages[$p_id])) {$message = $error_messages[$p_id];} else {$message = '';}
                                fwrite($fh, $date.": (Err) No correct data for player ".$new2['data'][$config['clan']]['members'][$p_id]['account_name']." with ID : ".$p_id.$message."\n");
                              }
                            }
                            unset($toload, $res1, $res2, $res3,$to_cache);
                            if( ($counter['old_count']+$counter['get']) == $counter['total_members'] ) {
                              update_multi_cron($dbprefix);
                              if($log == 1) fwrite($fh, $date.": (Info) ".$lang['cron_done']."\n");
                            } else {
                              if($log == 1) fwrite($fh, $date.": (Err) ".$lang['cron_done']."\n");
                            }
                            echo $lang['cron_done'];
                        }
                    }   else {//count($new2['data']['members'] <=0
                        if($log == 1)  fwrite($fh, $date.": (Err) Members count is zero."."\n");
                    }
                }   else {// data from WG is older than in cache
                    if($log == 1)  fwrite($fh, $date.": (Err) Get old data from WG."."\n");
                }
            }   else { //new2['status'] <> 'ok'
                if (isset($new2['error']['message'])) {
                    $message = '( '.$new2['error']['message'].' )';
                }   else {
                    $message = '';
                }
                if($log == 1) fwrite($fh, $date.": (Err) Unusual roster from WG. ".$message."\n");
            }
        } //new2ok
    }   else { // ($config['cron'] <> 1
        if($log == 1) fwrite($fh, $date.": (Err) ".$lang['error_cron_off']."\n");
        echo $lang['error_cron_off'];
    }
}   else { // $config['cron_time']*3600) > now
    if($log == 1) fwrite($fh, $date.": (Err) Cron started too early. Time limit excided"."\n");
    echo 'Cron started too early!';
}
//write some data for debug
if ($log == 1){
    $end_time = microtime(true);
    fwrite($fh, $date.": (Info) Cron finished  in ".(round($end_time - $begin_time,4).' '.$lang['sec'])."\n");
    if(is_numeric($db->count)) {
        fwrite($fh, $date.": (Info) Number of MySQL queries - ".($db->count)."\n");
    }
    fwrite($fh, $date.": (Info) End cron job\n");
}
?>