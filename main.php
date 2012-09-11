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
    * @version     $Rev: 2.1.3 $
    *
    */
?>
<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    if (file_exists(dirname(__FILE__).'/function/mysql.php')) {
        define('ROOT_DIR', dirname(__FILE__));
    }else{
        define('ROOT_DIR', '.');    
    }
    //Starting script time execution timer
    $begin_time = microtime(true);

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
    include_once(ROOT_DIR.'/function/oldfunc.php');
    include_once(ROOT_DIR.'/function/func_get.php');
    include_once(ROOT_DIR.'/function/func_gk.php');
    include_once(ROOT_DIR.'/function/func_time.php');
    include_once(ROOT_DIR.'/function/cache.php');
    
    // Including main config files
    include_once(ROOT_DIR.'/function/config.php');
    include_once(ROOT_DIR.'/config/config_'.$config['server'].'.php');
    if(!isset($config['clan'])){
        header ( 'Location: admin/index.php' );
    }
    //Loding language pack
    foreach(scandir(ROOT_DIR.'/translate/') as $files){
        if (preg_match ("/_".$config['lang'].".php/", $files)){
            include_once(ROOT_DIR.'/translate/'.$files);
        }
    }
    include_once(ROOT_DIR.'/admin/translate/login_'.$config['lang'].'.php');
    //cache
    $cache = new Cache(ROOT_DIR.'/cache/');
    //if($config['cache']){
    //    $cache->setExpiryInterval($config['cache']*3600);
    //}
    //Authentication
    $auth = new Auth($db);

    if ( isset($_GET['logout']) ) {
        $auth->logout();
    }

    if ( isset($_POST['login']) ) {
        //echo $_POST['login'];
        $auth->login($_POST['user'], $_POST['pass']); // This order: User/Email Password True/False (if you want to use email as auth
    }
    if (isset($_GET['error'])){
        $data['msg'][] = 'You need to login';    
    }

    $logged = 0;
    if($auth->isLoggedIn(1)){
        $logged = 1;
    }    
    if($auth->isLoggedInAdmin(1)){
        $logged = 2;
    }    
    //print_r($lang);
    //Including Tabs array   
    include_once(ROOT_DIR.'/function/tabs.php');


    if(!isset($_GET['ver'])){
        $_GET['ver'] = '0';
    }

    // Including Forming players statistic manipulations.
    include_once(ROOT_DIR.'/including/show.php');                 

    // Including Global map manipulation
    include_once(ROOT_DIR.'/including/gk.php');

    //print_r($roster_id);
    // View part.
    if(count($res) > 0 ){   
        $tanks_group = tanks_group($res);
        $rand_keys = array_rand($res, 1);
        $eff_rating = eff_rating($res,$lang);
        if($config['cron'] == 1 && $col_check > 2){
            $we_loosed = went_players($roster,(now() - 172800),now());
            $new_players = new_players($roster,(now() - 172800),now());
            $main_progress = player_progress_main((now() - 259200),now());
            $best_main_progress = best_player_progress_main($main_progress);
            $medal_progress = medal_progress((now() - 259200),now());
            $best_medal_progress = best_medal_progress($medal_progress['unsort']);
            $new_tanks = new_tanks($roster,$col_tables,(now() - 259200),now());
            //$palyer = player_progress('92327',$col_tables);
            //print_r($roster_id);
            //print_r($main_progress);
        }                                                             

        include_once(ROOT_DIR.'/views/header.php');    
        include_once(ROOT_DIR.'/views/body.php');
        include_once(ROOT_DIR.'/views/footer.php');

    }else{
        include_once(ROOT_DIR.'/views/header.php');
        include_once(ROOT_DIR.'/views/error.php');
        include_once(ROOT_DIR.'/views/footer.php');    
    }

?>
