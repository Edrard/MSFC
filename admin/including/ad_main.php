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
    if (isset($_POST['cron_recreat'])){
        cron_file_recreat();
    }
    if (isset($_POST['ajaxcre'])){
        if(creat_ajax_tab($_POST) == TRUE){
            $message['text'] = $_POST['file'].' '.$lang['admin_ajax_new_error'];
            $message['color'] = 'red';
        }
    }
    if (isset($_GET['del'])){
        delete_tab($_GET);
        if (!headers_sent()) {
            header ( 'Location: index.php?page=main'.$multi_get.'#tabs-2' );
            exit;
        } else { ?>
        <script type="text/javascript">
            location.replace("index.php?page=main<?=$multi_get;?>#tabs-2");
        </script>
        <?      }
    }
    if (isset($_GET['removeclan'])){
        delete_multi($_GET);
        if (!headers_sent()) {
            header ( 'Location: index.php?page=main'.$multi_get.'#tabs-8' );
            exit;
        } else { ?>
        <script type="text/javascript">
            location.replace("index.php?page=main<?=$multi_get;?>#tabs-8");
        </script>
        <?      }
    }
    if (isset($_POST['consub']) || isset($_POST['consub_2'])){
        insert_config($_POST);
        if(isset($_POST['consub'])) {
          if (!headers_sent()) {
              header ( 'Location: index.php?page=main'.$multi_get.'#tabs-1' );
              exit;
          } else { ?>
          <script type="text/javascript">
              location.replace("index.php?page=main<?=$multi_get;?>#tabs-1");
          </script>
          <?      }
        }
    } 
    if (isset($_POST['mcsort'])){
        edit_multi_clan($_POST);
    }
    if (isset($_POST['tabsub'])){
        $error = tabs_info_db($_POST);
        if($error == 1){
            $message['text'] = $lang['admin_tabs_insert_error'];
            $message['color'] = 'red';    
        }elseif($error == 2){
            $message['text'] = $lang['admin_tabs_insert_error2'];
            $message['color'] = 'red';
        }
    }
    if (isset($_GET['userdel'])){
        if(delete_user($_GET) == TRUE){
            $message['text'] = $lang['admin_del_user_error'];
            $message['color'] = 'red';    
        }else{
            if (!headers_sent()) {
                header ( 'Location: index.php?page=main'.$multi_get.'#tabs-3' );
                exit;
            } else { ?>
            <script type="text/javascript">
                location.replace("index.php?page=main<?=$multi_get;?>#tabs-3");
            </script>
            <?      }
        }
    }
    if (isset($_POST['edituser'])){
        edit_user($_POST);
    } 
    if (isset($_POST['newuser'])){ 
        if(new_user($_POST) == TRUE){
            $message['text'] = $lang['admin_new_user_error_1'].' '.$_POST['user'];
            $message['color'] = 'red';    
        }else{
            $message['text'] = $lang['admin_new_user_confirm_1'].' '.$_POST['user'].' '.$lang['admin_new_user_confirm_2'];
            $message['color'] = 'green';
        }
    } 
    if (isset($_POST['fileup'])){
        $target_path = ROOT_DIR.'/tabs/';
        $target_path = $target_path . basename( $_FILES['uploadedfile']['name']); 

        if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
            chmod($target_path, 777);
            $message['text'] = $lang['admin_msg_upl_1'].' '. basename( $_FILES['uploadedfile']['name']).' '.$lang['admin_msg_upl_2'];
            $message['color'] = 'green';
        } else{
            $message['text'] = $lang['admin_msg_upl_3'];
            $message['color'] = 'red';
        }
    } 



    //DB
    if (isset($_POST['recdb'])){
        recreat_db();
        //Чистим кэш
        $cache->clear_all(array(), ROOT_DIR.'/cache/');
        $cache->clear_all(array(), ROOT_DIR.'/cache/players/');
        insert_file(LOCAL_DIR.'/sql/clan.sql');
        insert_multicaln($config['clan'],$config['server'],$dbprefix);
        insert_config($config);
    }
    /**if (isset($_POST['syncdb'])){
    if(is_valid_url($config['td']) == true){
    $new = get_player($config['clan'],$config);   //dg65tbhjkloinm 
    if($new['error'] != 0){
    $message['text'] = $lang['admin_con_error'];
    $message['color'] = 'red';  
    }else{
    sync_roster($new['data']['request_data']['items']);
    }
    }else{
    $message['text'] = $lang['admin_con_error'];
    $message['color'] = 'red';
    }

    }  **/
    if (isset($_POST['newup'])){
        $target_path = LOCAL_DIR.'/sql/';
        $target_path = $target_path . basename( $_FILES['uploadedfile']['name']); 
        if (file_exists($target_path)) {
            $target_path = $target_path.'_'.now() ;
        }
        if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
            chmod($target_path, 777);
            insert_file($target_path);
            $message['text'] = $lang['admin_db_update_msg'];
            $message['color'] = 'green';
        } else{
            $message['text'] = $lang['admin_msg_upl_3'];
            $message['color'] = 'red';
        }
    } 
    $dir_val = scandir(ROOT_DIR.'./theme/');
    array_shift($dir_val);array_shift($dir_val);

    $ver = json_decode(get_url('http://wot-news.com/ajax/clanstat',$config),TRUE);
    // Config
    $config = update_array($config,get_config());
    // Scaning /tabs/ directory
    $tabs_dir = read_tabs_dir();

    // Checking if all files in db
    $tabs_check = check_tabs_db($tabs_dir);
    // Reading current db
    $current_tab = read_tabs();
    $current_user = read_users();

    /**Мультиклан считываем**/ 

    $multiclan = read_multiclan();
    $multiclan_main = multi_main($multiclan);

    foreach($multiclan as $clan){
        $multiclan_info[$clan['id']] = $cache->get('get_last_roster_'.$clan['id'],0);
        if($multiclan_info[$clan['id']] === FALSE) {
            $multiclan_info[$clan['id']] = get_api_roster($clan['id'],$config);    
        }
        if(empty($multiclan_info)){
            $multiclan_info[$clan['id']]['status'] = 'error';
            $multiclan_info[$clan['id']]['status'] = 'ERROR';
        }
        if($multiclan_info[$clan['id']]['status'] == 'ok' &&  $multiclan_info[$clan['id']]['status_code'] == 'NO_ERROR'){
            $cache->clear('get_last_roster_'.$clan['id']);
            $cache->set('get_last_roster_'.$clan['id'], $multiclan_info[$clan['id']]);
        }else{
            die('No cahced data');
        }
    }
    //print_r($multiclan_info);

    /*-----------------------------------*/
    //Update top tanks tab info
    if(isset($_POST['toptanksupd'])) {
        update_top_tanks($_POST['Array']);
    }
    if(isset($_POST['tanklist']) && isset($_POST['Array'])){ 
        update_tanks_list($_POST['Array']);
    }
    if(isset($_POST['toptanksadd'])) {

        if($_POST['adm_top_tanks_action'] == 'add'){
            add_top_tanks($_POST['adm_top_tanks_lvl'],$_POST['adm_top_tanks_type']);
        }

        if($_POST['adm_top_tanks_action'] == 'delete'){
            delete_top_tanks($_POST['adm_top_tanks_lvl'],$_POST['adm_top_tanks_type']);
        }

    }
    if (isset($_GET['removetoptank'])){
        delete_top_tank($_GET['tank']);
        if (!headers_sent()) {
            header ( 'Location: index.php?page=main'.$multi_get.'#tabs-6' );
            exit;
        } else { ?>
        <script type="text/javascript">
            location.replace("index.php?page=main<?=$multi_get;?>#tabs-6");
        </script>
        <?      }
    }

    //Clear cache
    if (isset($_POST['admclearcache'])){
        $cache->clear_all(array(), ROOT_DIR.'/cache/');
        $cache->clear_all(array(), ROOT_DIR.'/cache/players/');
    }

    //Clear activity cache
    if (isset($_POST['admclearacache'])){
        if(isset($_POST['clear_a_cache_date']) and is_numeric($_POST['clear_a_cache_date'])) {
            $left_days = $_POST['clear_a_cache_date'];
        } else {
            $left_days = 7;
        }
        $exclude_list = array();
        for($i=$left_days;$i>=0;$i--) {
            $exclude_list[] = date('d.m.Y',mktime(0, 0, 0, date('m'), date('d')-$i, date('Y')));
        }
        $cache_activity = new Cache(ROOT_DIR.'/cache/activity/');
        $cache_activity->clear_all($exclude_list);
        unset($exclude_list,$cache_activity,$left_days);
    }
    //Save available tanks index's
    if (isset($_POST['available_tanks_add_index'])){
        $index_list = array();
        foreach($_POST['Array']['title'] as $index => $value) {
          if(isset($value)) {
            $index_list[$index] = $value;
          } else {
            $index_list[$index] = $index;
          }
        }
        $cache->clear('available_tanks_'.$config['clan'], ROOT_DIR.'/cache/other/');
        $cache->set('available_tanks_'.$config['clan'],$index_list, ROOT_DIR.'/cache/other/');
        unset($index_list);
    }
    //Get top tanks for Tab
    $adm_top_tanks = get_top_tanks_list();
    $adm_avalTanks = get_available_tanks_index();
    if($adm_avalTanks['count'] > 1) {
      $adm_avalTanks['names'] = $cache->get('available_tanks_'.$config['clan'],0, ROOT_DIR.'/cache/other/');
    }
    $tanks_list = get_tanks_list();
?>