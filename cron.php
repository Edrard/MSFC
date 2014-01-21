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
* @version     $Rev: 3.0.2 $
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
require(ROOT_DIR.'/function/func_gk.php');

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
    fwrite($fh, $date.": (Info) Loging Started\n");
    fwrite($fh, $date.": (Info) Authentication: ".$config['cron_auth']."\n");
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
//Authentication
if($config['cron_auth'] == 1){

    $auth = new Auth($db);

    if(($user) && ($pass)){
        $auth->login($user, $pass); // This order: User/Email Password True/False (if you want to use email as auth
    }
    $logged = 0;
    if($auth->isLoggedIn(1)){
        $logged = 1;
    }    
    if($auth->isLoggedInAdmin(1)){
        $logged = 2;
    }
    if($logged != 2){
        if($log == 1){ fwrite($fh, $date.": (Err) ".$lang['log_to_cron']."\n"); }
        die($lang['log_to_cron']);
    }    
}

if (($multi_prefix[$dbprefix]['cron'] + $config['cron_time']*3600) <= now() ){
    if ($config['cron'] == 1){

        //check table tanks
        cron_update_tanks_db();
        $nations = tanks_nations();
        $medals = medn($nations);
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
                    //Starting geting data
                    if (count($new2['data'][$config['clan']]['members']) > 0){
                        foreach ($new2['data'][$config['clan']]['members'] as $val){
                            $toload[$val['account_id']] = $val['account_id'];
                            //break; //leave for testing purpose
                        }           
                        if (!empty($toload)) {
                            $plc = count($toload);
                            if ($plc > 0){
                               if ($log == 1) fwrite($fh, $date.": (WG) Try to load info on ".$plc." players"."\n");
                            }
                            $toload = array_chunk($toload,$config['multiget']*5); 
                            $res1 = $res2 = $res3 = array();
                            foreach($toload as $links){
                                $res1 = array_special_merge($res1,multiget_v2($links, 'account/info', $config));
                                $res2 = array_special_merge($res2,multiget_v2($links, 'account/tanks', $config, array('mark_of_mastery', 'tank_id', 'statistics.battles', 'statistics.wins'))); //loading only approved fields
                                $res3 = array_special_merge($res3,multiget_v2($links, 'account/ratings', $config));  
                            }
                            foreach ($res1 as $key => $val) {
                                if (!isset($res2[$key]['status'])) {
                                     $res2[$key]['status'] = 'error';
                                }
                                if ($res2[$key]['status'] <> 'ok') {
                                    $res1[$key]['status'] = $res2[$key]['status'];
                                    if (isset($res2[$key]['error']['message'])) $res1[$key]['error']['message'] = $res2[$key]['error']['message'];
                                }  /* elseif ($res3[$key]['status'] <> 'ok' ) {
                                $res1[$key]['status'] = $res3[$key]['status'];
                                if (isset($res3[$key]['error']['message'])) $res1[$key]['error']['message'] = $res3[$key]['error']['message'];
                                } */
                            }
                            $plc = 1;
                            //print_r($res2);
                            foreach ($res1 as $key => $val) {
                                if (!isset($val['status'])) {
                                     $val['status'] = 'error';
                                }
                                if ($val['status'] == 'ok' ) {
                                    if (isset($res2[$key]['data'])) $val['data']['tanks'] = $res2[$key]['data'];
                                    if (isset($res3[$key]['data'])) $val['data']['ratings'] = $res3[$key]['data'];
                                    $val['data']['role'] = $new2['data'][$config['clan']]['members'][$val['data']['account_id']]['role'];
                                    $val['data']['created_at'] = $new2['data'][$config['clan']]['members'][$val['data']['account_id']]['created_at'];
                                    $cache->set($key, $val, ROOT_DIR.'/cache/players/');
                                    if($log == 1){ fwrite($fh, $date.": (Info) Writing player ".sprintf("%03d", $plc).": ".$val['data']['nickname']."\n"); }
                                    cron_insert_pars_data($val, $medals, $tanks, $nations, $time);
                                }   else {
                                    if($log == 1){
                                        if (isset($val['error']['message'])) {
                                            $message = ' ( '.$val['error']['message'].' )';
                                        }   else {
                                            $message = '';
                                        }
                                        fwrite($fh, $date.": (Err) Not correct data for player ".sprintf("%03d", $plc)." with ID : ".$val['data']['account_id'].$message."\n"); }
                                }
                                $plc++;
                            }
                            unset($links, $res1, $res2, $res3);
                            update_multi_cron($dbprefix);
                            if($log == 1) fwrite($fh, $date.": (Info) ".$lang['cron_done']."\n");
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
    if (isset($toload)) {
        fwrite($fh, $date.": (Info) ".count($toload)." players processed in ".(round($end_time - $begin_time,4).' '.$lang['sec'])."\n");
    }   else {
        fwrite($fh, $date.": (Info) Cron finished  in ".(round($end_time - $begin_time,4).' '.$lang['sec'])."\n");
    }
    if(is_numeric($db->count)) {
        fwrite($fh, $date.": (Info) Number of MySQL queries - ".($db->count)."\n");
    }
    fwrite($fh, $date.": (Info) End cron job\n");
}
?>


