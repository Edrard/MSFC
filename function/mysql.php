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
    * @version     $Rev: 2.1.6 $
    *
    */
?>
<?php
    if (preg_match ("/mysql.php/", $_SERVER['PHP_SELF']))
    {
        header ("Location: /index.php");
        exit;
    }

    $dbhost ='localhost';
    // username and password to log onto db SERVER
    $dbuser ='';
    $dbpass  ='';
    // name of database
    $dbname='';
    $sqlchar = 'utf8';

    //$db = new PDO ( 'mysql:host=' . $dbhost . ';dbname=' . $dbname, $dbuser, $dbpass);

    try {
        $db = new PDO ( 'mysql:host=' . $dbhost . ';dbname=' . $dbname, $dbuser, $dbpass);
    } catch (PDOException $e) {
        //echo $e->getMessage();
        die(show_message($e->getMessage()));
    }
    
    
    $db->query ( 'SET character_set_connection = '.$sqlchar );
    $db->query ( 'SET character_set_client = '.$sqlchar );
    $db->query ( 'SET character_set_results = '.$sqlchar );
?>