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
    * @version     $Rev: 3.0.0 $
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
        }  else {
           $config['lang'] = 'ru';
        }
        if (isset($_POST['server'])){
           $config['server'] = $_POST['server'];
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
           $sql = "SELECT file, name FROM `tabs`;";
           $q = $db->prepare($sql);
           if ($q->execute() == TRUE) {
               $tabsindb = $q->fetchAll();
           }else{
               die(show_message($q->errorInfo(),__line__,__file__,$sql));
           };
           foreach($tabs_lang['ru'] as $key => $val2){
              foreach ($tabsindb as $val) {
                  if (($val['name']) == $val2) {
                     $sql = "UPDATE `tabs` SET name = '".$tabs_lang[$config['lang']][$key]."' WHERE file = '".$val['file']."'; ";
                     $q = $db->prepare($sql);
                     if ($q->execute() != TRUE) {
                        die(show_message($q->errorInfo(),__line__,__file__,$sql));
                     };
                  }
              }
           }
         }
         header ( 'Location: index.php' );
         exit;
    }
?>