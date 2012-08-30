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
    * @version     $Rev: 2.1.0 $
    *
    */
?>
<?php
    header("Content-type: text/html; charset=utf-8");
    error_reporting(E_ALL);
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

    //Cheker
    include_once(ROOT_DIR.'/including/check.php');

    //MYSQL
    include_once(ROOT_DIR.'/function/mysql.php');
    //Connecting to MySQL

    //HTML Dom
    include_once(ROOT_DIR.'/function/html_dom.php');
    //Multiget CURL
    include_once(ROOT_DIR.'/function/curl.php');
    include_once(ROOT_DIR.'/function/mcurl.php');

    // Include Module functions
    include_once(ROOT_DIR.'/function/auth.php');
    include_once(ROOT_DIR.'/function/rating.php');
    include_once(ROOT_DIR.'/function/func.php');
    include_once(ROOT_DIR.'/function/func_main.php');
    include_once(ROOT_DIR.'/function/func_cron.php');
    include_once(ROOT_DIR.'/function/oldfunc.php');
    include_once(ROOT_DIR.'/function/func_get.php');
    include_once(ROOT_DIR.'/function/func_gk.php');

    // Including main config files
    include_once(ROOT_DIR.'/function/config.php');
    include_once(ROOT_DIR.'/config/config_'.$config['server'].'.php');

    //Loding language pack
    foreach(scandir(ROOT_DIR.'/translate/') as $files){
        if (preg_match ("/_".$config['lang'].".php/", $files)){
            include_once(ROOT_DIR.'/translate/'.$files);
        }
    }
    include_once(ROOT_DIR.'/admin/translate/login_'.$config['lang'].'.php');
    //Authentication
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
    if($logged == 2){
        if($config['cron'] == 1){
            $new['data']['request_data']['items'] = array();
            //Geting clan roster fron wargaming or from local DB.
            if(is_valid_url($config['td']) == true){
                $new = get_player($config['clan'],$config);   //dg65tbhjkloinm 
                if($new['error'] != 0){
                    $new = get_last_roster();   
                }
            }
            // Creating empty array if needed.
            if(count($new['data']['request_data']['items']) == 0){
                $new['error'] = 1;  
            }

            //Sorting roster
            $roster = &roster_sort($new['data']['request_data']['items']);
            $now = now();
            //Starting geting data
            if(count($new['data']['request_data']['items']) > 0){

                $links = cron_time_checker($roster);
                if(count($links) > 0){
                    multiget($links, $result,$config['pars']);    
                }

                foreach($result as $name => $val){    
                    cron_insert_pars_data($val,$roster[$name],$config,$now);
                }
                echo $lang['cron_done'];
                // In $res array stored player statistic.  
            }
        }else{
            echo $lang['error_cron_off'];
        }   

    }else{
        echo $lang['log_to_cron'];

    }


    //print_r($lang);
    //Including Tabs array


?>
