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
    * @version     $Rev: 2.0.0 $
    *
    */
?>
<?php
    if (preg_match ("/config.php/", $_SERVER['PHP_SELF']))
    {
        header ("Location: /index.php");
        exit;
    }

    if ( !extension_loaded('pdo') ) {
        if($language == 'en'){
            $message = '<i>ERROR:</i> <strong>php-pdo</strong> extention do not loaded, without it you cant use MySQL. Turn off Mysql in config.php or get extention load';
        }else{
            $message = '<i>ОШИБКА:</i> <strong>php-pdo</strong> расширение не загружено, без него MySQL не будет работать. Выключите MySQL в config.php или зпгрузите модуль';    
        }
    }elseif ( !extension_loaded('pdo_mysql') ) {
        if($language == 'en'){
            $message = '<i>ERROR:</i> <strong>php-pdo_mysql</strong> extention do not loaded, without it you cant use MySQL. Turn off Mysql in config.php or get extention load';
        }else{
            $message = '<i>ОШИБКА:</i> <strong>php-pdo_mysql</strong> расширение не загружено, без него MySQL не будет работать. Выключите MySQL в config.php или зпгрузите модуль';    
        } 
    }
?>
