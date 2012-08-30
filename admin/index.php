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
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    if (file_exists(dirname(__FILE__).'/func_admin.php')) {
        define('LOCAL_DIR', dirname(__FILE__));
        include_once (LOCAL_DIR.'/func_admin.php');
        include_once (LOCAL_DIR.'/translate/tabs.php');

        define('ROOT_DIR', base_dir('admin'));

        include_once (ROOT_DIR.'/function/auth.php');
        include_once(ROOT_DIR.'/function/mysql.php');
        include_once(ROOT_DIR.'/function/func.php');  
        include_once(ROOT_DIR.'/function/func_get.php');
        include_once(ROOT_DIR.'/function/func_main.php');
        include_once (ROOT_DIR.'/function/config.php');   
        include_once(ROOT_DIR.'/config/config_'.$config['server'].'.php');                      

        foreach(scandir(LOCAL_DIR.'/translate/') as $files){
            if (preg_match ("/_".$config['lang'].".php/", $files)){
                include_once(LOCAL_DIR.'/translate/'.$files);
            }
        }
    }else{
        define('LOCAL_DIR', '.');
        include_once (LOCAL_DIR.'/func_admin.php');
        include_once (LOCAL_DIR.'/translate/tabs.php');

        define('ROOT_DIR', '..');

        include_once (ROOT_DIR.'/function/auth.php');
        include_once(ROOT_DIR.'/function/mysql.php');
        include_once(ROOT_DIR.'/function/func.php');
        include_once(ROOT_DIR.'/function/func_get.php');
        include_once(ROOT_DIR.'/function/func_main.php');
        include_once (ROOT_DIR.'/function/config.php');
        include_once(ROOT_DIR.'/config/config_'.$config['server'].'.php');                      

        foreach(scandir(LOCAL_DIR.'/translate/') as $files){
            if (preg_match ("/_".$config['lang'].".php/", $files)){
                include_once(LOCAL_DIR.'/translate/'.$files);
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
        $data['msg'][] = 'You need to login';    
    }
    if (isset($config['error'])){
        if($config['error'] = '2'){
            $page = 'install';  
        }
    }

    switch ($page) {


        case 'login':
            if ( $auth->isLoggedInAdmin(1) ) { 
                header ( 'Location: index.php?page=main' );
            }

            include_once(LOCAL_DIR.'/views/ad_header.php');
            include_once(LOCAL_DIR.'/views/ad_login.php');
            include_once(LOCAL_DIR.'/views/ad_footer.php');
            break;

        case 'main':

            if ( !$auth->isLoggedInAdmin(1) ) {
                header ( 'Location: index.php?error=1' );

            }
            //Controller
            include_once(LOCAL_DIR.'/including/ad_main.php');
            //Viewing
            include_once(LOCAL_DIR.'/views/ad_header.php');
            include_once(LOCAL_DIR.'/views/ad_main.php');
            include_once(LOCAL_DIR.'/views/ad_footer.php');
            break;

        case 'install':
            if ( $config['error'] != 2) {
                header ( 'Location: index.php' );
            }
            //Controller
            include_once(LOCAL_DIR.'/including/ad_install.php');
            //Viewing
            include_once(LOCAL_DIR.'/views/ad_install.php');
            include_once(LOCAL_DIR.'/views/ad_footer.php');
            break;

    }

?>
    
    
