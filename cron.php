<?php
    /*
    * Project:     Clan Stat
    * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
    * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
    * -----------------------------------------------------------------------
    * Began:       2011
    * Date:        $Date: 2011-10-24 11:54:02 +0200 $
    * -----------------------------------------------------------------------
    * @author      $Author: Edd, Exinaus, Shw  $
    * @copyright   2011-2012 Edd - Aleksandr Ustinov
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 2.2.0 $
    *
    */
?>
<?php
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
    // Check Admin(1) 

    //Cheker
    include_once(ROOT_DIR.'/including/check.php');

    //MYSQL
    include_once(ROOT_DIR.'/function/mysql.php');

    //Multiget CURL
    include_once(ROOT_DIR.'/function/curl.php');
    include_once(ROOT_DIR.'/function/mcurl.php');

    // Include Module functions
    include_once(ROOT_DIR.'/function/auth.php');
    include_once(ROOT_DIR.'/function/rating.php');
    include_once(ROOT_DIR.'/function/func.php');
    include_once(ROOT_DIR.'/function/func_main.php');
    include_once(ROOT_DIR.'/function/func_cron.php');
    include_once(ROOT_DIR.'/function/func_get.php');
    include_once(ROOT_DIR.'/function/func_gk.php');

    // Including main config files
    include(ROOT_DIR.'/function/config.php');
    include(ROOT_DIR.'/config/config_'.$config['server'].'.php');

    //Loding language pack
    foreach(scandir(ROOT_DIR.'/translate/') as $files){
        if (preg_match ("/_".$config['lang'].".php/", $files)){
            include_once(ROOT_DIR.'/translate/'.$files);
        }
    }
    include_once(ROOT_DIR.'/admin/translate/login_'.$config['lang'].'.php');
    include_once(ROOT_DIR.'/function/cache.php');

    $myFile = ROOT_DIR."/cron.log";
    $log = 0;
    $date = date('Y-m-d H:i');
    if($fh = fopen($myFile, 'a')){
        $log = 1;
        fwrite($fh, $date.": Loging Started\n");
        fwrite($fh, $date.": Authentication: ".$config['cron_auth']."\n");
        cron_current_run($fh,$date);
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
                    break;
                }
            }
        }
    }                 
    unset($multiclan);
    if(!$dbprefix){
        $dbprefix = 'msfc_';
    }
    if($log == 1){
        fwrite($fh, $date.": Current db prefix: ".$dbprefix."\n");
    }
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
            if($log == 1){
                fwrite($fh, $date.": ".$lang['log_to_cron']."\n");    
            }
            die($lang['log_to_cron']);
        }    
    }
    if(($multi_prefix[$dbprefix]['cron'] + $config['cron_time']*3600) <= now() ){
        if($config['cron'] == 1){
            //Geting clan roster fron wargaming or from local DB.
            $new = get_api_roster($config['clan'],$config);   //dg65tbhjkloinm 
            if(empty($new)){
                $new['status'] = 'error';
                $new['status_code'] = 'ERROR';
            }
            if($new['status'] == 'ok' &&  $new['status_code'] == 'NO_ERROR'){
                $cache->clear('get_last_roster_'.$config['clan']);
                $cache->set('get_last_roster_'.$config['clan'], $new);
                if($log == 1){
                    fwrite($fh, $date.": Used roster from WG\n");
                }
            }else{
                unset($new);
                $new = $cache->get('get_last_roster_'.$config['clan'],0);
                if($new === FALSE) {
                    if($log == 1){
                        fwrite($fh, $date.": No cahced data\n");
                    }
                    die('No cahced data');
                }
                if($log == 1){
                    fwrite($fh, $date.": Used cached roster ({$new['error']})\n");
                }
            }
            //Sorting roster
            $roster = roster_sort($new['data']['members']);
            $now = mktime(date("H"), 0, 0, date("m")  , date("d"), date("Y"));
            //Starting geting data
            if(count($new['data']['members']) > 0){
                
                $links = cron_links($roster,$config);
                $count = count($links); 
                if($count > 0){
                    if($log == 1){
                        fwrite($fh, $date.": Requested players num ".$count."\n");    
                    }
                    unset($count);
                    multiget($links, $res,$config,prepare_stat(),$roster,$lang,1);    
                }
                foreach($res as $name => $val){ 
                    cron_insert_pars_data($val,$roster[$name],$config,$now,$log,$fh,$date);
                }
                update_multi_cron($dbprefix);
                if($log == 1){
                    fwrite($fh, $date.": ".$lang['cron_done']."\n");    
                }
                echo $lang['cron_done'];
                // In $res array stored player statistic.  
            }
        }else{
            if($log == 1){
                fwrite($fh, $date.": ".$lang['error_cron_off']."\n");
            }
        }
    }else{
        if($log == 1){
            fwrite($fh, $date.": Time limit excided"."\n");
        }
    }   
    if($log == 1){
        if(is_numeric($db->count)) {
            fwrite($fh, $date.": Number of MySQL queries - ".($db->count)."\n");
        }
        fwrite($fh, $date.": End cron\n");
        fwrite($fh, $date."////////////////////////////////////////////--->\n");    
    }



    //print_r($lang);
    //Including Tabs array


?>