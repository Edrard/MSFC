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
    * @version     $Rev: 3.0.4 $
    *
    */


    if (isset($_POST['mysql'])){

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
        $text = "<?php
        if (preg_match ('/mysql.config.php/', \$_SERVER['PHP_SELF']))
        {
        exit;
        }

        \$dbhost  ='".$_POST['host']."';
        \$dbuser  ='".$_POST['user']."';
        \$dbpass  ='".$_POST['pass']."';
        \$dbname  ='".$_POST['dbname']."';
        ?>";
        $fh = fopen(ROOT_DIR.'/mysql.config.php', 'w+') or die("can't open file");
        fwrite($fh, $text);
        fclose($fh);
        header ( 'Location: index.php' );
        exit;
    }
?>