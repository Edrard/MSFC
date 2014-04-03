<pre>
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
* @version     $Rev: 3.0.2 $
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
    /*   Меняем версию модуля в конфиге   */
    /*************************************/
    if($config['version'] == '3.0.3') {
      $sql = "UPDATE `config` SET `value` = '3.0.4' WHERE `name` = 'version' LIMIT 1 ;";
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

    /*************************************/
    /* Изменения в таблице `col_ratings` */
    /****************end******************/
  }
}

?>