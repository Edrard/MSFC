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
    * @version     $Rev: 2.0.3 $
    *
    */
?>
<?php
    if (isset($_POST['ajaxcre'])){
        if(creat_ajax_tab($_POST) == TRUE){
            $message['text'] = $_POST['file'].' '.$lang['admin_ajax_new_error'];
            $message['color'] = 'red';    
        }
    }
    if (isset($_GET['del'])){
        delete_tab($_GET);
        header ( 'Location: index.php?page=main#tabs-2' );
    }
    if (isset($_POST['consub'])){
        insert_config($_POST);
    } 
    if (isset($_POST['tabsub'])){
        if(tabs_info_db($_POST) == TRUE){
            $message['text'] = $lang['admin_tabs_insert_error'];
            $message['color'] = 'red';    
        }
    }
    if (isset($_GET['userdel'])){
        if(delete_user($_GET) == TRUE){
            $message['text'] = $lang['admin_del_user_error'];
            $message['color'] = 'red';    
        }else{
            header ( 'Location: index.php?page=main#tabs-2' );
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
        insert_file(LOCAL_DIR.'/sql/clan.sql');
    } 
    if (isset($_POST['syncdb'])){
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

    }
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

    $ver = json_decode(get_url('http://wot-news.com/ajax/clanstat'),TRUE);
    // Config
    $config = update_array($config,get_config());
    // Scaning /tabs/ directory
    $tabs_dir = read_tabs_dir();

    // Checking if all files in db
    $tabs_check = check_tabs_db($tabs_dir);
    // Reading current db
    $current_tab = read_tabs();
    $current_user = read_users();

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
       // header ( 'Location: index.php?page=main#tabs-10' );
    }


    //Get top tanks for Tab
    $adm_top_tanks = get_top_tanks_list();
    $tanks_list = get_tanks_list();

?>
