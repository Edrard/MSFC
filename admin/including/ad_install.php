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
* @version     $Rev: 3.2.2 $
*
*/


if (isset($_POST['recdb'])){

    recreat_db();
    insert_file(LOCAL_DIR.'/sql/clan.sql');

    if (isset($_POST['clan'])){
        $config['clan'] = $_POST['clan'];
    }  else {
        $config['clan'] = '37';
    }
    if (isset($_POST['lang'])){
        $config['lang'] = $_POST['lang'];
        $lang_api = array_keys($api_langs);
        if(in_array($config['lang'],$lang_api)) {
            $config['api_lang'] = $config['lang'];
        } else {
            $config['api_lang'] = 'en';
        }
    }  else {
        $config['lang'] = 'ru';
        $config['api_lang'] = 'ru';
    }
    if (isset($_POST['server'])){
        $config['server'] = $_POST['server'];
        $config['application_id'] = app_id_region($config['server'], 'demo');
    }  else {
        $config['server'] = 'ru';
    }
    if (isset($_POST['pars'])){
        $config['pars'] = $_POST['pars'];
    }  else {
        $config['pars'] = 'curl';
    }
    insert_multicaln($config['clan'],$config['server'],$dbprefix);
    insert_config($config);

    if ($config['lang'] <> 'ru') {
        $tabsindb = $db->select('SELECT file, name FROM `tabs`;',__line__,__file__);
        foreach($tabs_lang['ru'] as $key => $val2){
            foreach ($tabsindb as $val) {
                if (($val['name']) == $val2) {
                    $db->insert('UPDATE `tabs` SET name = "'.$tabs_lang[$config['lang']][$key].'" WHERE file = "'.$val['file'].'";',__line__,__file__);
                }
            }
        }
    }
    header ( 'Location: index.php' );
    exit;
}
?>