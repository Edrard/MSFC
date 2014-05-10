<?php
/*
* Project:     Clan Stat
* License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
* Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
* -----------------------------------------------------------------------
* Began:       2011
* Date:        $Date: 2013-11-22 00:00:00 +0200 $
* -----------------------------------------------------------------------
* @author      $Author: Edd, Exinaus, SHW  $
* @copyright   2011-2013 Edd - Aleksandr Ustinov
* @link        http://wot-news.com
* @package     Clan Stat
* @version     $Rev: 3.1.0 $
*
*/


header("Content-type: text/html; charset=utf-8");
error_reporting(E_ALL & ~E_STRICT);
ini_set("display_errors", 1);
if (file_exists(dirname(__FILE__).'/function/mysql.php')) {
    define('ROOT_DIR', dirname(__FILE__));
}else{
    define('ROOT_DIR', '.');
}

//Checker
include(ROOT_DIR.'/including/check.php');

//MYSQL
include(ROOT_DIR.'/function/mysql.php');

//Multiget CURL
require(ROOT_DIR.'/function/curl.php');
require(ROOT_DIR.'/function/mcurl.php');

// Including main config files
include(ROOT_DIR.'/function/func.php');
include(ROOT_DIR.'/function/func_get.php');
include(ROOT_DIR.'/function/func_main.php');
include(ROOT_DIR.'/function/config.php');
include(ROOT_DIR.'/config/config_'.$config['server'].'.php');
require(ROOT_DIR.'/function/cache.php');

//Loading language pack
foreach(scandir(ROOT_DIR.'/translate/') as $files){
    if (preg_match ("/_".$config['lang'].".php/", $files)){
        require(ROOT_DIR.'/translate/'.$files);
    }
}
require(ROOT_DIR.'/admin/translate/login_'.$config['lang'].'.php');

$cache = new Cache(ROOT_DIR.'/cache/');

if( (304.0 - (float) $config['version']) > 0 ) {
    //Изменения вносимые в уникальные таблицы (без префикса для клана)

    /****************begin*****************/
    /*    Изменения в таблице `tanks`    */
    /*************************************/

    //Получаем структуру таблицы
    $sql = "SHOW COLUMNS FROM `tanks` ;";
    $q = $db->prepare($sql);
    if ($q->execute() != TRUE) {
        die(show_message($q->errorInfo(),__line__,__file__,$sql));
    }

    $ratings_structure = array_fill_keys($q->fetchAll(PDO::FETCH_COLUMN), 1);

    if(!isset($ratings_structure['title'])) {
      //это таблица старого формата, будем переделывать.

      //Изменяем таблицу
      $sql = "ALTER TABLE `tanks` ADD `title` VARCHAR( 40 ) NOT NULL AFTER `is_premium`;";
      $q = $db->prepare($sql);
      if ($q->execute() != TRUE) {
          die(show_message($q->errorInfo(),__line__,__file__,$sql));
      }

      //Очищаем таблицу
      $sql = "TRUNCATE TABLE `tanks`;";
      $q = $db->prepare($sql);
      if ($q->execute() != TRUE) {
          die(show_message($q->errorInfo(),__line__,__file__,$sql));
      }

      update_tanks_db();
      echo 'Table `tanks` - updated.<br>';
    }
    /*************************************/
    /*    Изменения в таблице `tanks`    */
    /****************end******************/

    //Получаем список префиксов из таблицы multiclan
    $sql = "SELECT prefix FROM multiclan;";
    $q = $db->prepare($sql);
    if ($q->execute() == TRUE) {
       $prefix = $q->fetchAll(PDO::FETCH_COLUMN);
    }   else {
       $prefix = array();
    }

    //Проверяем полученный массив префиксов. Если он не пустой устраиваем цикл, применяющий все префиксы
    //Для внесения изменений в БД всех мультикланов.
    if(empty($prefix)) {echo 'Error: Couldn\'t find info about any clan in db.<br>';}
    if(!empty($prefix)) {
      foreach($prefix as $t) {
        $db->change_prefix($t);
        $config = get_config();

        /****************begin*****************/
        /* Конфиги для рот (на всякий случай) */
        /*************************************/
        if(!isset($config['company'])) {
          $sql = "INSERT INTO `config` (`name`, `value`) VALUES ('company', '0'), ('company_count', '1');";
          $q = $db->prepare($sql);
          if ($q->execute() != TRUE) {
              die(show_message($q->errorInfo(),__line__,__file__,$sql));
          }
          $config['company'] = 0;
          $config['company_count'] = 1;

          echo 'Config table (`company` value) for prefix:',$t,' - updated.<br>';
        }

        /****************begin*****************/
        /*  Добавляем параметр DST в конфиг  */
        /*************************************/
        if(!isset($config['dst'])) {
          $sql = "INSERT INTO `config` (`name`,`value`) VALUES ('dst', '0');";
          $q = $db->prepare($sql);
          if ($q->execute() != TRUE) {
              die(show_message($q->errorInfo(),__line__,__file__,$sql));
          }
          $config['dst'] = 0;

          echo 'Config table (`dst` value) for prefix:',$t,' - updated.<br>';
        }

        /****************begin*****************/
        /*   Меняем версию модуля в конфиге   */
        /*************************************/
        if(!is_numeric($config['version']) or (304.0 - (float) $config['version']) > 0 ) {
          $sql = "UPDATE `config` SET `value` = '304.0' WHERE `name` = 'version' LIMIT 1 ;";
          $q = $db->prepare($sql);
          if ($q->execute() != TRUE) {
              die(show_message($q->errorInfo(),__line__,__file__,$sql));
          }
          echo 'Config table (`version` value) for prefix:',$t,' - updated.<br>';
        }

        /****************begin*****************/
        /* Изменения в таблице `col_ratings` */
        /*************************************/

        //Получаем структуру таблицы
        $sql = "SHOW COLUMNS FROM `col_ratings` ;";
        $q = $db->prepare($sql);
        if ($q->execute() != TRUE) {
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }

        $ratings_structure = array_fill_keys($q->fetchAll(PDO::FETCH_COLUMN), 1);

        if(isset($ratings_structure['integrated_rating_value']) or isset($ratings_structure['integrated_rating_place'])) {
          //это таблица старого формата, будем переделывать.

          //Удаляем таблицу
          $sql = "DROP TABLE `col_ratings`;";
          $q = $db->prepare($sql);
          if ($q->execute() != TRUE) {
              die(show_message($q->errorInfo(),__line__,__file__,$sql));
          }
          //Создаем новую
          $sql = "CREATE TABLE IF NOT EXISTS `col_ratings` (
                    `account_id` int(12) NOT NULL,
                    `updated_at` int(12) NOT NULL,
                    `battles_count_rank` int(12) NOT NULL,
                    `wins_ratio_rank` int(12) NOT NULL,
                    `frags_count_rank` int(12) NOT NULL,
                    `spotted_count_rank` int(12) NOT NULL,
                    `damage_dealt_rank` int(12) NOT NULL,
                    `survived_ratio_rank` int(12) NOT NULL,
                    `xp_avg_rank` int(12) NOT NULL,
                    `xp_max_rank` int(12) NOT NULL,
                    `hits_ratio_rank` int(12) NOT NULL,
                    `battles_count_value` int(12) NOT NULL,
                    `wins_ratio_value` int(12) NOT NULL,
                    `frags_count_value` int(12) NOT NULL,
                    `spotted_count_value` int(12) NOT NULL,
                    `damage_dealt_value` int(12) NOT NULL,
                    `survived_ratio_value` int(12) NOT NULL,
                    `xp_avg_value` int(12) NOT NULL,
                    `xp_max_value` int(12) NOT NULL,
                    `hits_ratio_value` int(12) NOT NULL
                  ) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
                  ";
          $q = $db->prepare($sql);
          if ($q->execute() != TRUE) {
              die(show_message($q->errorInfo(),__line__,__file__,$sql));
          }

          echo 'Table `col_ratings` for prefix:',$t,' - updated.<br>';
        }

        if(!isset($ratings_structure['global_rating_rank'])) {

          $sql = "ALTER TABLE `col_ratings`
                    ADD `global_rating_rank` int(12) NOT NULL,
                    ADD `global_rating_value` int(12) NOT NULL;";
          $q = $db->prepare($sql);
          if ($q->execute() != TRUE) {
              die(show_message($q->errorInfo(),__line__,__file__,$sql));
          }

          echo 'Table `col_ratings` for prefix:',$t,' - updated v2 (global_rating).<br>';
        }

        //Получаем структуру таблицы
        $sql = "SHOW COLUMNS FROM `col_ratings` ;";
        $q = $db->prepare($sql);
        if ($q->execute() != TRUE) {
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }

        $ratings_structure = array_fill_keys($q->fetchAll(PDO::FETCH_COLUMN), 1);

        if(isset($ratings_structure['spotted_count_rank'])) {

          $sql = "ALTER TABLE `col_ratings`
                      DROP `spotted_count_rank`,
                      DROP `spotted_count_value`;";
          $q = $db->prepare($sql);
          if ($q->execute() != TRUE) {
              die(show_message($q->errorInfo(),__line__,__file__,$sql));
          }

          echo 'Table `col_ratings` for prefix:',$t,' - updated v3 (remove spotted_count).<br>';
        }

        if(!isset($ratings_structure['survived_ratio_rank'])) {

          $sql = "ALTER TABLE `col_ratings`
                    ADD `survived_ratio_rank` int(12) NOT NULL,
                    ADD `survived_ratio_value` int(12) NOT NULL;";
          $q = $db->prepare($sql);
          if ($q->execute() != TRUE) {
              die(show_message($q->errorInfo(),__line__,__file__,$sql));
          }

          echo 'Table `col_ratings` for prefix:',$t,' - updated v4 (add survived_ratio).<br>';
        }

        /*************************************/
        /* Изменения в таблице `col_ratings` */
        /****************end******************/
      }
    }

        /****************begin*****************/
        /*     Удаляем старые lang файлы     */
        /*************************************/

    if(!is_writable(ROOT_DIR.'/translate/')) {
      if(!@chmod(ROOT_DIR.'/translate/', 0777)) {
        echo '<b>(Error) Directory <u>tranlate</u> are not writable, delete files translate/overall_*.php manually</b><br>';
      }
    }

    foreach(scandir(ROOT_DIR.'/translate/') as $files){
        if (preg_match ("/overall_[a-zA-Z]*.php/", $files)){
            if(@unlink(ROOT_DIR.'/translate/'.$files)) {
              echo 'File ',$files,' - removed.<br>';
            } else {
              echo '(Error) Failed to remove translate/',$files,' - delete it manually.<br>';
            }

        }
    }
} //if($config['version'] < 304.0)

if( (310.1 - (float) $config['version']) > 0 ) {
    //Получаем список префиксов из таблицы multiclan
    $sql = "SELECT prefix FROM multiclan;";
    $q = $db->prepare($sql);
    if ($q->execute() == TRUE) {
       $prefix = $q->fetchAll(PDO::FETCH_COLUMN);
    }   else {
       $prefix = array();
    }

    //Проверяем полученный массив префиксов. Если он не пустой устраиваем цикл, применяющий все префиксы
    //Для внесения изменений в БД всех мультикланов.
    if(empty($prefix)) {echo 'Error: Couldn\'t find info about any clan in db.<br>';}
    if(!empty($prefix)) {
      foreach($prefix as $t) {
        $db->change_prefix($t);
        $config = get_config();
        /****************begin*****************/
        /*Добавляем параметр api_lang в конфиг*/
        /*************************************/
        if(!isset($config['api_lang'])) {
          if(in_array($config['lang'],array('en','ru','pl','de','fr','es','zh-cn','tr','cs','th','vi','ko'))) {
            $lang = $config['lang'];
          } else {
            $lang = 'en';
          }
          $sql = "INSERT INTO `config` (`name`,`value`) VALUES ('api_lang', '".$lang."');";
          $q = $db->prepare($sql);
          if ($q->execute() != TRUE) {
              die(show_message($q->errorInfo(),__line__,__file__,$sql));
          }
          echo 'Config table (`api_lang` value) for prefix:',$t,' - updated.<br>';
        }
        /*****************begin******************/
        /*Добавляем параметр try_count в конфиг*/
        /***************************************/
        if(!isset($config['try_count'])) {
          $sql = "INSERT INTO `config` (`name`,`value`) VALUES ('try_count', '5');";
          $q = $db->prepare($sql);
          if ($q->execute() != TRUE) {
              die(show_message($q->errorInfo(),__line__,__file__,$sql));
          }
          echo 'Config table (`try_count` value) for prefix:',$t,' - updated.<br>';
        }
        /****************begin*****************/
        /*   Меняем версию модуля в конфиге   */
        /*************************************/
        if(!is_numeric($config['version']) or (310.1 - (float) $config['version']) > 0 ) {
          $sql = "UPDATE `config` SET `value` = '310.1' WHERE `name` = 'version' LIMIT 1 ;";
          $q = $db->prepare($sql);
          if ($q->execute() != TRUE) {
              die(show_message($q->errorInfo(),__line__,__file__,$sql));
          }
          echo 'Config table (`version` value) for prefix:',$t,' - updated.<br>';
        }
      }
    }
} //if( (310.1 - (float) $config['version']) > 0 )
if($config['lang'] == 'ru') { ?>
<br><br><br>
Внимательно прочтите отображаемый сверху текст, если он не содержит сообщений о ошибках - обновление завершено успешно, и вы можете продолжать использовать модуль статистики.<br>
В случае возникновения ошибок, постарайтесь внимательно прочитать сообщение о ошибке, и понять что от вас требуется, возможно вы сможете решить эту ошибку самостоятельно.
<? } else { ?>
<br><br><br>
Carefully read the text displayed above, if it contains no error messages - update completed successfully and you can continue to use "Module Statistics for Clans".<br>
If an error occurs, try to carefully read the error message and understand what is required from you, you may be able to solve this error yourself.
<? } ?>
<br><br><br>
<center><a href="./index.php" target="_self">index.php</a></center>