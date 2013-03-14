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
    $lang['admin_title'] = 'Module statistics Admin panel'; 
    $lang['admin_tab_opt'] = 'Options';
    $lang['admin_tab_tabs'] = 'Tabs';
    $lang['admin_tab_user'] = 'Users Control';
    $lang['admin_tab_tanks'] = 'Tanks list';
    $lang['admin_db'] = 'Database';
    $lang['admin_module'] = 'Back to module';
    $lang['admin_logout'] = 'Logout';
    $lang['admin_server'] = 'Server';
    $lang['admin_clan_id'] = 'Clan ID';
    $lang['admin_base_url'] = 'Base URL';
    $lang['admin_lang'] = 'Language';
    $lang['admin_cache'] = 'Cache time';
    $lang['admin_tabs_align'] = 'Tabs align';
    $lang['admin_curl_lib'] = 'Curl library';
    $lang['admin_offset'] = 'Time Offset';
    $lang['admin_cron'] = 'Collect statistics of players in time';
    $lang['admin_cron_time'] = 'The minimum period of data collection';
    $lang['admin_cron_time_warning'] = 'Warning!!! Do not change this this time';
    $lang['admin_multiget'] = 'Number of players simultaneously loaded';
    $lang['admin_news'] = 'News feed';
    $lang['admin_submit'] = 'Submit';
    $lang['admin_creat'] = 'Create';
    $lang['admin_file_upload'] = 'Choose a file to upload';
    $lang['admin_file_upload_butt'] = 'Upload File';
    $lang['admin_file_upload_new_tab'] = 'Upload new tab';
    $lang['admin_file_creat_new_tab'] = 'Create new Ajax tab';
    $lang['admin_file_edit_new_tab'] = 'Edit tabs';
    $lang['admin_msg_upl_1'] = 'The file';
    $lang['admin_msg_upl_2'] = 'has been uploaded';
    $lang['admin_msg_upl_3'] = 'There was an error uploading the file, please try again!';
    $lang['admin_tab_base'] = 'On/Off';
    $lang['admin_tab_file'] = 'File';
    $lang['admin_tab_id'] = 'Index number';
    $lang['admin_tab_name'] = 'Title';
    $lang['admin_tab_type'] = 'Type';
    $lang['admin_tab_auth'] = 'Access';
    $lang['admin_tab_del'] = 'Delete';
    $lang['admin_tab_delete_n'] = 'When you deleting tab, its delete it from Database and Storage';
    $lang['admin_ajax_new_file'] = 'Enter file name';
    $lang['admin_ajax_new_error'] = 'tab with such name allready exist';   
    $lang['admin_tabs_insert_error'] = 'Error. Looks like some forms not fieled';
    $lang['admin_tabs_insert_error2'] = 'Error. Can\'t be the same Index number';
    $lang['admin_confirm_delete'] = 'Are you shure, you want to delete';
    $lang['admin_user_name'] = 'Name';
    $lang['admin_user_group'] = 'Group';
    $lang['admin_user_edit'] = 'Edit';
    $lang['admin_user_del'] = 'Delete';
    $lang['admin_change_lang'] = 'After language chnging, pls, logout and login';

    $lang['admin_new_user_title'] = 'Create new user';
    $lang['admin_new_user_name'] = 'Name';
    $lang['admin_new_user_pass'] = 'Password';
    $lang['admin_new_user_group'] = 'Group';
    $lang['admin_new_user_error_1'] = 'Sorry, but you cant add user with name';
    $lang['admin_new_user_confirm_1'] = 'User with name -';
    $lang['admin_new_user_confirm_2'] = 'was added';
    $lang['admin_del_user_error'] = 'No user to delete';

    $lang['admin_new_user_edit'] = 'Edit user';

    $lang['admin_db_recreat'] = 'Recreate database';
    $lang['admin_db_warning'] = '<div class="ui-state-error ui-corner-all" align="center">Warning!!! All data will be deleted</div>';
    $lang['admin_db_but'] = 'Recreate';

    $lang['admin_db_up'] = 'Upgrade database';
    $lang['admin_db_up_warning'] = '<div class="ui-state-error ui-corner-all" align="center">Warning!!! Use it only for upgrading database</div>';
    $lang['admin_db_up_but'] = 'Upgrade';
    $lang['admin_clean_cache'] = 'Clean cache';

    $lang['admin_con_error'] = 'Sorry, but we cant get data from Wargaming site';

    $lang['admin_db_update_msg'] = 'Data was added to base';

    $lang['admin_db_creat'] = 'Install database';
    $lang['admin_db_cwarning'] = '<div class="ui-state-error ui-corner-all" align="center">Warning!!! All data will be deleted</div>';
    $lang['admin_db_cbut'] = 'Install';
    $lang['admin_dir_cache'] = 'Check cache folder, its must be writeble';
    $lang['admin_dir_sql'] = 'Check admin/sql folder, its must be writeble';

    $lang['admin_new_version_1'] = 'New version';
    $lang['admin_new_version_2'] = 'of module available now, pls visit';

    $lang['tank_list_title'] = 'Title';
    $lang['tank_list_nation'] = 'Nation';
    $lang['tank_list_lvl'] = 'Level';
    $lang['tank_list_type'] = 'Type';
    $lang['tank_list_link'] = 'Link';

    $lang['admin_clear_cache'] = 'Clear cached data';
    $lang['admin_clear_cache_but'] = 'Delete cache';

    $lang['admin_clear_a_cache'] = 'Clear cached activity data';
    $lang['admin_clear_a_cache_form'] = 'Delete activity cache, except last <input type="text" size="1" name="clear_a_cache_date" value="7" /> days.';
    $lang['admin_themes'] = 'Theme:';

    $lang['admin_cln_control'] = 'Clans Control';
    $lang['admin_current_calns'] = 'Current clans';

    $lang['admin_add_clan'] = 'Add new clan';

    $lang['admin_multi_id'] = 'Clan ID';
    $lang['admin_multi_teg'] = 'Abbreviation';
    $lang['admin_multi_mem_count'] = 'Number of players';
    $lang['admin_multi_prefix'] = 'Prefix in DB';
    $lang['admin_multi_index'] = 'Index number';
    $lang['admin_multi_main'] = 'Status';
    $lang['admin_multi_server'] = 'Server';
    $lang['admin_multi_main_msg'] = 'Main'; 
    $lang['admin_multi_add_new'] = 'Add';
    $lang['admin_multi_link'] = 'Link';
    $lang['admin_no_tanks'] = 'No data about tanks in DB';

    $lang['admin_cron_control'] = 'Data collection in time';
    $lang['admin_cron_auth'] = 'Authentication';
    $lang['admin_cron_multi'] = 'Multiclan';
    $lang['admin_cron_cache'] = 'Using the cache';
    $lang['admin_min'] = 'min';
    $lang['admin_hour'] = 'hour';
    $lang['admin_sec'] = 'sec';

    $lang['current_cron'] = 'Current cron log';
    $lang['recreat_cron'] = 'Recreat cron log file';

    $lang['yes'] = 'Yes';
    $lang['no'] = 'No';
    $lang['admin_allow_user_upload'] = 'Allow to the group "User" upload replays';

    $lang['admin_cron_we_loosed'] = 'Losed players';
    $lang['admin_cron_new_players'] = 'New players';
    $lang['admin_cron_main_progress'] = 'Main progress'; 
    $lang['admin_cron_medal_progress'] = 'Medal progress';
    $lang['admin_cron_new_tanks'] = 'New tanks';
    
    $lang['admin_cron_period'] = 'Periods used for data output';
    $lang['admin_user_access'] = 'Access';
    $lang['admin_cancel'] = 'Cancel';
?>