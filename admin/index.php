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
    * @copyright   2011-2013 Edd - Aleksandr Ustinov
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 3.2.3 $
    *
    */

    error_reporting(E_ALL & ~E_STRICT);
    ini_set("display_errors", 1);
    if (file_exists(dirname(__FILE__).'/func_admin.php')) {
        define('LOCAL_DIR', dirname(__FILE__));

        require(LOCAL_DIR.'/func_admin.php');
        require(LOCAL_DIR.'/translate/tabs.php');

        define('ROOT_DIR', base_dir('admin'));

        //Cheker
        include(ROOT_DIR.'/including/check.php');

        //Multiget CURL
        require(ROOT_DIR.'/function/curl.php');
        require(ROOT_DIR.'/function/mcurl.php');

        require(ROOT_DIR.'/function/auth.php');
        require(ROOT_DIR.'/function/cache.php');
        include(ROOT_DIR.'/function/mysql.php');

        require(ROOT_DIR.'/function/func.php');
        require(ROOT_DIR.'/function/func_get.php');
        require(ROOT_DIR.'/function/func_main.php');
        include(ROOT_DIR.'/function/config.php');
        require(ROOT_DIR.'/function/atm.php');
        require(ROOT_DIR.'/config/config_'.$config['server'].'.php');

        foreach(scandir(LOCAL_DIR.'/translate/') as $files){
            if (preg_match ("/_".$config['lang'].".php/", $files)){
                require(LOCAL_DIR.'/translate/'.$files);
            }
        }
    }else{
        define('LOCAL_DIR', '.');
        define('ROOT_DIR', '..');

        //Cheker
        include(ROOT_DIR.'/including/check.php');

        //Multiget CURL
        require(ROOT_DIR.'/function/curl.php');
        require(ROOT_DIR.'/function/mcurl.php');

        require(LOCAL_DIR.'/func_admin.php');
        require(LOCAL_DIR.'/translate/tabs.php');

        require(ROOT_DIR.'/function/auth.php');
        require(ROOT_DIR.'/function/cache.php');
        include(ROOT_DIR.'/function/mysql.php');
        require(ROOT_DIR.'/function/func.php');
        require(ROOT_DIR.'/function/func_get.php');
        require(ROOT_DIR.'/function/func_main.php');
        include(ROOT_DIR.'/function/config.php');
        require(ROOT_DIR.'/function/atm.php');
        require(ROOT_DIR.'/config/config_'.$config['server'].'.php');

        foreach(scandir(LOCAL_DIR.'/translate/') as $files){
            if (preg_match ("/_".$config['lang'].".php/", $files)){
                require(LOCAL_DIR.'/translate/'.$files);
            }
        } 
    }

    if (!isset($config['error'])){
        $auth = new Auth($db); // This order: Database User Password Host
    }
    if ( isset($_GET['page']) ) {
        $page = $_GET['page'];
    }else{
        $page = 'login';
    }

    if ( isset($_GET['logout']) ) {
        $auth->logout();
    }

    if ( isset($_POST['login']) ) {
        //echo $_POST['login'];
        $auth->login($_POST['user'], $_POST['pass']); // This order: User/Email Password True/False (if you want to use email as auth
    }
    if (isset($_GET['error'])){
        $data['msg'][] = $lang['login_err_ynlog'];
    }
    if (isset($config['error'])){
        if($config['error'] == '2'){
            $page = 'install';
        }
        if (isset($_POST['multiadd'])){
            add_multiclan($_POST,$lang);     
        }
    }
    $multi_get = '';
    if(isset($_GET['multi'])){
        $multi_get = '&multi='.$_GET['multi'];
    }
    if(empty($dbhost) and empty($dbuser) and empty($dbpass) and empty($dbname) and $config['error'] == '2') {
      $page = 'mysql';
    }
    if(!file_exists(ROOT_DIR.'/mysql.config.php')) {
      $page = 'mysql';
    }

    switch ($page) {

        case 'login':
            if ( $auth->isLoggedInAdmin(1) ) {
                header ( 'Location: index.php?page=main'.$multi_get );
                exit;
            }

            include(LOCAL_DIR.'/views/ad_header.php');
            include(LOCAL_DIR.'/views/ad_login.php');
            include(LOCAL_DIR.'/views/ad_footer.php');
            break;

        case 'main':

            if ( !$auth->isLoggedInAdmin(1) ) {
                header ( 'Location: index.php?error=1'.$multi_get );
                exit;
            }
            //cache
            $cache = new Cache(ROOT_DIR.'/cache/');
            //Controller
            include(LOCAL_DIR.'/including/ad_main.php');
            //Viewing
            include(LOCAL_DIR.'/views/ad_header.php');
            include(LOCAL_DIR.'/views/ad_main.php');
            include(LOCAL_DIR.'/views/ad_footer.php');
            break;

        case 'install':
            if ( $config['error'] != 2) {
                header ( 'Location: index.php' );
                exit;
            }
            //cache
            $cache = new Cache(ROOT_DIR.'/cache/');
            //Controller
            include(LOCAL_DIR.'/including/ad_install.php');
            //Viewing
            include(LOCAL_DIR.'/views/ad_header.php');
            include(LOCAL_DIR.'/views/ad_install.php');
            include(LOCAL_DIR.'/views/ad_footer.php');
            break;

        case 'mysql':
            if ( $config['error'] != 2) {
                header ( 'Location: index.php' );
                exit;
            }
            //Controller
            include(ROOT_DIR.'/admin/including/ad_mysql.php');
            //Viewing
            include(ROOT_DIR.'/admin/views/ad_mysql.php');
            break;
    }

?>