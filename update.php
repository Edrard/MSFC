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
* @version     $Rev: 3.2.3 $
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

if(!isset($config['version']) or !is_numeric($config['version'])) {
    $config['version'] = (float) 300.0;
}

$db->replacement2 = '$1$2$3';

if(!isset($config['api_lang'])) {

    //Получаем список префиксов из таблицы multiclan
    $sql = "SELECT prefix FROM `multiclan`;";
    $q = $db->prepare($sql);
    if ($q->execute() == TRUE) {
        $prefix = $q->fetchAll(PDO::FETCH_COLUMN);
    }   else {
        $prefix = array();
    }

    if(!empty($prefix)) {
        foreach($prefix as $t) {
            $db->change_prefix($t);
            $config = get_config();

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
                $config['api_lang'] = $lang;
            }
        }
    }
}

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
        include(ROOT_DIR.'/config/config_'.$config['server'].'.php');
        update_tanks_db();
        echo 'Table `tanks` - updated.<br>';
    }
    /*************************************/
    /*    Изменения в таблице `tanks`    */
    /****************end******************/

    //Получаем список префиксов из таблицы multiclan
    $sql = "SELECT prefix FROM `multiclan`;";
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
    $sql = "SELECT prefix FROM `multiclan`;";
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

$db->replacement2 = '$1msfcmt_$2$3';

if( (310.2 - (float) $config['version']) > 0 ) {
    /****************begin*********************/
    /*Меняем структуру таблиц до универсальной*/
    /******************************************/

    //Обнуляем подключение к БД
    $q = null;
    $db = null;

    //Создаем чистое подключение
    try {
        $db_2 = new PDO ( 'mysql:host=' . $dbhost . ';dbname=' . $dbname, $dbuser, $dbpass);
    } catch (PDOException $e) {
        die(show_message($e->getMessage()));
    }
    $sql = 'SHOW TABLES;';
    $q = $db_2->prepare($sql);
    if ($q->execute() == TRUE) {
        $tables = $q->fetchAll(PDO::FETCH_COLUMN);

        foreach($tables as $val) {
            if($val == 'tanks') {
                $sql = 'RENAME TABLE `tanks` TO `msfcmt_tanks`;';
                $q = $db_2->prepare($sql);
                if ($q->execute() != TRUE) {
                    die(show_message($q->errorInfo(),__line__,__file__,$sql));
                } else {
                    echo 'Table tanks - renamed.<br>';
                }
            }
            if($val == 'multiclan') {
                $sql = 'RENAME TABLE `multiclan` TO `msfcmt_multiclan`;';
                $q = $db_2->prepare($sql);
                if ($q->execute() != TRUE) {
                    die(show_message($q->errorInfo(),__line__,__file__,$sql));
                } else {
                    echo 'Table multiclan - renamed.<br>';
                }
            }
        }

    } else {
        die(show_message($q->errorInfo(),__line__,__file__,$sql));
    }

    //Обнуляем подключение к БД
    $q = null;
    $db_2 = null;
    //MYSQL заново
    include(ROOT_DIR.'/function/mysql.php');

    $sql = 'CREATE TABLE IF NOT EXISTS `achievements` (
    `name` varchar(40) NOT NULL,
    `section` varchar(20) NOT NULL,
    `section_i18n` varchar(20) NOT NULL,
    `options` text NOT NULL,
    `section_order` tinyint(2) NOT NULL,
    `image` varchar(150) NOT NULL,
    `name_i18n` varchar(20) NOT NULL,
    `type` varchar(20) NOT NULL,
    `order` smallint(10) NOT NULL,
    `description` varchar(250) NOT NULL,
    `condition` varchar(500) NOT NULL,
    `hero_info` varchar(250) NOT NULL,
    KEY `name` (`name`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;';
    $q = $db->prepare($sql);
    if ($q->execute() != TRUE) {
        die(show_message($q->errorInfo(),__line__,__file__,$sql));
    } else {
        echo 'Table achievements - created.<br>';
    }

    //Получаем список префиксов из таблицы multiclan
    $sql = "SELECT prefix FROM `multiclan`;";
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
            /*   Меняем версию модуля в конфиге   */
            /*************************************/
            if(!is_numeric($config['version']) or (310.2 - (float) $config['version']) > 0 ) {
                $sql = "UPDATE `config` SET `value` = '310.2' WHERE `name` = 'version' LIMIT 1 ;";
                $q = $db->prepare($sql);
                if ($q->execute() != TRUE) {
                    die(show_message($q->errorInfo(),__line__,__file__,$sql));
                }
                echo 'Config table (`version` value) for prefix:',$t,' - updated.<br>';
            }
        }
    }
} //if( (310.2 - (float) $config['version']) > 0 )

if( (310.3 - (float) $config['version']) > 0 ) {

    $sql = 'ALTER TABLE `achievements` CHANGE `name_i18n` `name_i18n` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;';
    $q = $db->prepare($sql);
    if ($q->execute() != TRUE) {
        die(show_message($q->errorInfo(),__line__,__file__,$sql));
    } else {
        echo 'Table achievements - updated (name_i18n).<br>';
    }

    //Получаем список префиксов из таблицы multiclan
    $sql = "SELECT prefix FROM `multiclan`;";
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
            //Удаляем параметр cron_auth
            if(isset($config['cron_auth'])) {
                $sql = "DELETE FROM `config` WHERE `name` = 'cron_auth' LIMIT 1;";
                $q = $db->prepare($sql);
                if ($q->execute() != TRUE) {
                    die(show_message($q->errorInfo(),__line__,__file__,$sql));
                }
                echo 'Config table (`cron_auth` value) for prefix:',$t,' - removed.<br>';
            }
            /****************begin*****************/
            /*   Меняем версию модуля в конфиге   */
            /*************************************/
            if(!is_numeric($config['version']) or (310.3 - (float) $config['version']) > 0 ) {
                $sql = "UPDATE `config` SET `value` = '310.3' WHERE `name` = 'version' LIMIT 1 ;";
                $q = $db->prepare($sql);
                if ($q->execute() != TRUE) {
                    die(show_message($q->errorInfo(),__line__,__file__,$sql));
                }
                echo 'Config table (`version` value) for prefix:',$t,' - updated.<br>';
            }
        }
    }
} //if( (310.3 - (float) $config['version']) > 0 )

if( (310.4 - (float) $config['version']) > 0 ) {
    require(ROOT_DIR.'/config/config_'.$config['server'].'.php');
    $achievements = achievements();
    // update list of all achievements in game from api if need
    if (empty($achievements)) {
        update_achievements_db($achievements);
        $achievements = achievements();
    }

    //Получаем список префиксов из таблицы multiclan
    $sql = "SELECT prefix FROM `multiclan`;";
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

            if(!empty($achievements)) {
                $sql = "show tables like 'col_medals';";
                $q = $db->prepare($sql);
                if ($q->execute() == TRUE) {
                    $tmp = $q->fetchAll();
                }   else {
                    die(show_message($q->errorInfo(),__line__,__file__,$sql));
                }

                if(count($tmp) > 0) {
                    $sql = "SHOW COLUMNS FROM `col_medals` ;";
                    $q = $db->prepare($sql);
                    if ($q->execute() != TRUE) {
                        die(show_message($q->errorInfo(),__line__,__file__,$sql));
                    }

                    $structure = array_fill_keys($q->fetchAll(PDO::FETCH_COLUMN), 1);

                    foreach($structure as $id => $val) {
                        if(!isset($achievements[$id]) && $id != 'account_id' && $id != 'updated_at') {
                            $sql = "ALTER TABLE `col_medals` DROP `{$id}`;";
                            $q = $db->prepare($sql);
                            if ($q->execute() != TRUE) {
                                die(show_message($q->errorInfo(),__line__,__file__,$sql));
                            }
                        }
                    }
                    echo 'col_medals table for prefix:',$t,' - updated.<br>';
                }
            }
            /****************begin*****************/
            /*   Меняем версию модуля в конфиге   */
            /*************************************/
            if(!is_numeric($config['version']) or (310.4 - (float) $config['version']) > 0 ) {
                $sql = "UPDATE `config` SET `value` = '310.4' WHERE `name` = 'version' LIMIT 1 ;";
                $q = $db->prepare($sql);
                if ($q->execute() != TRUE) {
                    die(show_message($q->errorInfo(),__line__,__file__,$sql));
                }
                echo 'Config table (`version` value) for prefix:',$t,' - updated.<br>';
            }
        }
    }
} //if( (310.3 - (float) $config['version']) > 0 )

if( (310.5 - (float) $config['version']) > 0 ) {

    //Получаем список префиксов из таблицы multiclan
    $sql = "SELECT prefix FROM `multiclan`;";
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

            //Получаем структуру таблицы
            $sql = "SHOW COLUMNS FROM `col_ratings` ;";
            $q = $db->prepare($sql);
            if ($q->execute() != TRUE) {
                die(show_message($q->errorInfo(),__line__,__file__,$sql));
            }

            $ratings_structure = array_fill_keys($q->fetchAll(PDO::FETCH_COLUMN), 1);

            foreach($ratings_structure as $id => $tmp) {
                if(preg_match('/(_rank|_value)$/',$id))  {
                    $sql = 'ALTER TABLE `col_ratings` MODIFY `'.$id.'` int(12) NOT NULL DEFAULT "0";';
                    $q = $db->prepare($sql);
                    if ($q->execute() != TRUE) {
                        die(show_message($q->errorInfo(),__line__,__file__,$sql));
                    }
                }
            }

            $sql = 'ALTER TABLE `col_ratings` ADD INDEX `index` ( `account_id` );';
            $q = $db->prepare($sql);
            if ($q->execute() != TRUE) {
                die(show_message($q->errorInfo(),__line__,__file__,$sql));
            }
            echo 'Table col_ratings for prefix:',$t,' - updated.<br>';
            /****************begin*****************/
            /*   Меняем версию модуля в конфиге   */
            /*************************************/
            if(!is_numeric($config['version']) or (310.5 - (float) $config['version']) > 0 ) {
                $sql = "UPDATE `config` SET `value` = '310.5' WHERE `name` = 'version' LIMIT 1 ;";
                $q = $db->prepare($sql);
                if ($q->execute() != TRUE) {
                    die(show_message($q->errorInfo(),__line__,__file__,$sql));
                }
                echo 'Config table (`version` value) for prefix:',$t,' - updated.<br>';
            }
        }
    }
} //if( (310.5 - (float) $config['version']) > 0 )

if( (310.6 - (float) $config['version']) > 0 ) {

    //Получаем список префиксов из таблицы multiclan
    $sql = "SELECT prefix FROM `multiclan`;";
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

            if(!isset($config['cron_autoclean'])) {
                $sql = "INSERT INTO `config` (`name`,`value`) VALUES ('cron_autoclean', '0');";
                $q = $db->prepare($sql);
                if ($q->execute() != TRUE) {
                    die(show_message($q->errorInfo(),__line__,__file__,$sql));
                }
                $config['cron_autoclean'] = 0;

                echo 'Config table (`cron_autoclean` value) for prefix:',$t,' - updated.<br>';
            }

            if(!isset($config['cron_cleanleft'])) {
                $sql = "INSERT INTO `config` (`name`,`value`) VALUES ('cron_cleanleft', '1');";
                $q = $db->prepare($sql);
                if ($q->execute() != TRUE) {
                    die(show_message($q->errorInfo(),__line__,__file__,$sql));
                }
                $config['cron_cleanleft'] = 1;

                echo 'Config table (`cron_cleanleft` value) for prefix:',$t,' - updated.<br>';
            }

            if(!isset($config['cron_cleanold'])) {
                $sql = "INSERT INTO `config` (`name`,`value`) VALUES ('cron_cleanold', '1');";
                $q = $db->prepare($sql);
                if ($q->execute() != TRUE) {
                    die(show_message($q->errorInfo(),__line__,__file__,$sql));
                }
                $config['cron_cleanold'] = 1;

                echo 'Config table (`cron_cleanold` value) for prefix:',$t,' - updated.<br>';
            }

            if(!isset($config['cron_cleanold_d'])) {
                $sql = "INSERT INTO `config` (`name`,`value`) VALUES ('cron_cleanold_d', '90');";
                $q = $db->prepare($sql);
                if ($q->execute() != TRUE) {
                    die(show_message($q->errorInfo(),__line__,__file__,$sql));
                }
                $config['cron_cleanold_d'] = 90;

                echo 'Config table (`cron_cleanold_d` value) for prefix:',$t,' - updated.<br>';
            }

            /****************begin*****************/
            /*   Меняем версию модуля в конфиге   */
            /*************************************/
            if(!is_numeric($config['version']) or (310.6 - (float) $config['version']) > 0 ) {
                $sql = "UPDATE `config` SET `value` = '310.6' WHERE `name` = 'version' LIMIT 1 ;";
                $q = $db->prepare($sql);
                if ($q->execute() != TRUE) {
                    die(show_message($q->errorInfo(),__line__,__file__,$sql));
                }
                echo 'Config table (`version` value) for prefix:',$t,' - updated.<br>';
            }
        }
    }
} //if( (310.6 - (float) $config['version']) > 0

if( (310.7 - (float) $config['version']) > 0 ) {

    //Получаем список префиксов из таблицы multiclan
    $sql = "SELECT prefix FROM `multiclan`;";
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

            if(!isset($config['cron_clean_log'])) {
                $sql = "INSERT INTO `config` (`name`,`value`) VALUES ('cron_clean_log', '1');";
                $q = $db->prepare($sql);
                if ($q->execute() != TRUE) {
                    die(show_message($q->errorInfo(),__line__,__file__,$sql));
                }
                $config['cron_clean_log'] = 1;

                echo 'Config table (`cron_clean_log` value) for prefix:',$t,' - updated.<br>';
            }

            /****************begin*****************/
            /*   Меняем версию модуля в конфиге   */
            /*************************************/
            if(!is_numeric($config['version']) or (310.7 - (float) $config['version']) > 0 ) {
                $sql = "UPDATE `config` SET `value` = '310.7' WHERE `name` = 'version' LIMIT 1 ;";
                $q = $db->prepare($sql);
                if ($q->execute() != TRUE) {
                    die(show_message($q->errorInfo(),__line__,__file__,$sql));
                }
                echo 'Config table (`version` value) for prefix:',$t,' - updated.<br>';
            }
        }
    }
} //if( (310.7 - (float) $config['version']) > 0

if( (311.0 - (float) $config['version']) > 0 ) {

    //Получаем список префиксов из таблицы multiclan
    $sql = "SELECT prefix FROM `multiclan`;";
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

        $sql = "SHOW INDEXES FROM `tanks`;";
        $q = $db->prepare($sql);
        if ($q->execute() != TRUE) {
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }

        $structure = $q->fetchAll(PDO::FETCH_ASSOC);

        if(count($structure) > 1) {
            $sql = "DROP INDEX `name_i18n` ON `tanks`;";
            $q = $db->prepare($sql);
            if ($q->execute() != TRUE) {
                die(show_message($q->errorInfo(),__line__,__file__,$sql));
            }

            echo 'Table `tanks` (`name_i18n` index) - updated.<br>';
        }
        foreach($prefix as $t) {
            $db->change_prefix($t);
            $config = get_config();

            /****************begin*****************/
            /*   Меняем версию модуля в конфиге   */
            /*************************************/
            if(!is_numeric($config['version']) or (311.0 - (float) $config['version']) > 0 ) {
                $sql = "UPDATE `config` SET `value` = '311.0' WHERE `name` = 'version' LIMIT 1 ;";
                $q = $db->prepare($sql);
                if ($q->execute() != TRUE) {
                    die(show_message($q->errorInfo(),__line__,__file__,$sql));
                }
                echo 'Config table (`version` value) for prefix:',$t,' - updated.<br>';
            }
        }
    }
} //if( (310.7 - (float) $config['version']) > 0

$upd_ver = 312.0;
if( ($upd_ver - (float) $config['version']) > 0 ) {

    echo '<br><br><br>Updating to version ',$upd_ver,'.<br>';

    //Получаем список префиксов из таблицы multiclan
    $sql = "SELECT prefix FROM `multiclan`;";
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
            /*   Меняем версию модуля в конфиге   */
            /*************************************/
            if(!is_numeric($config['version']) or ($upd_ver - (float) $config['version']) > 0 ) {

                $sql = "ALTER TABLE `tabs` ADD UNIQUE `file` ( `file` ) ;";
                $q = $db->prepare($sql);
                if ($q->execute() != TRUE) {
                    die(show_message($q->errorInfo(),__line__,__file__,$sql));
                }
                echo 'Table `tabs` (`file` index) for prefix:',$t,' - updated.<br>';

                $sql = "UPDATE `config` SET `value` = '".$upd_ver."' WHERE `name` = 'version' LIMIT 1 ;";
                $q = $db->prepare($sql);
                if ($q->execute() != TRUE) {
                    die(show_message($q->errorInfo(),__line__,__file__,$sql));
                }
                echo 'Config table (`version` value) for prefix:',$t,' - updated.<br>';
            }
        }
    }
} //$upd_ver = 312.0;

$upd_ver = 312.1;
if( ($upd_ver - (float) $config['version']) > 0 ) {

    echo '<br><br><br>Updating to version ',$upd_ver,'.<br>';

    //Получаем список префиксов из таблицы multiclan
    $sql = "SELECT prefix FROM `multiclan`;";
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
            /*   Меняем версию модуля в конфиге   */
            /*************************************/
            if(!is_numeric($config['version']) or ($upd_ver - (float) $config['version']) > 0 ) {

                $sql = "UPDATE `tabs` SET `auth`='2' WHERE `auth` = 'admin'; UPDATE `tabs` SET `auth`='1' WHERE `auth` = 'user'; UPDATE `tabs` SET `auth`='0' WHERE `auth` = 'all';";
                $q = $db->prepare($sql);
                if ($q->execute() == TRUE) {
                    echo 'Table `tabs` for prefix:',$t,' - updated.<br>';
                }

                $sql = "UPDATE `config` SET `value` = '".$upd_ver."' WHERE `name` = 'version' LIMIT 1 ;";
                $q = $db->prepare($sql);
                if ($q->execute() != TRUE) {
                    die(show_message($q->errorInfo(),__line__,__file__,$sql));
                }
                echo 'Config table (`version` value) for prefix:',$t,' - updated.<br>';
            }
        }
    }
} //$upd_ver = 312.1;
$upd_ver = 320.0;
if( ($upd_ver - (float) $config['version']) > 0 ) {

    echo '<br><br><br>Updating to version ',$upd_ver,'.<br>';

    //Получаем список префиксов из таблицы multiclan
    $sql = "SELECT prefix FROM `multiclan`;";
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
        $sql = "CREATE TABLE IF NOT EXISTS `stronghold` (
        `title` varchar(106) NOT NULL,
        `type` int(10) NOT NULL,
        `description` text NOT NULL,
        `image_url` varchar(130) NOT NULL,
        `short_description` varchar(162) NOT NULL,
        `reserve|image_url` varchar(143) NOT NULL,
        `reserve|description` text NOT NULL,
        `reserve|title` varchar(99) NOT NULL
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
        $q = $db->prepare($sql);
        if ($q->execute() != TRUE) {
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }
        echo 'New stronghod table added.<br>';
        foreach($prefix as $t) {
            $db->change_prefix($t);
            $config = get_config();
            $sql = "UPDATE `config` SET `value` = '".$upd_ver."' WHERE `name` = 'version' LIMIT 1 ;";
            $q = $db->prepare($sql);
            if ($q->execute() != TRUE) {
                die(show_message($q->errorInfo(),__line__,__file__,$sql));
            }
            echo 'Config table (`version` value) for prefix:',$t,' - updated.<br>';
            $sql = "INSERT INTO `tabs` (`id`, `name`, `file`, `type`, `status`, `auth`) VALUES
            (160, 'Укрепрайон', './stronghold.php', 1, 1, '0')";
            $q = $db->prepare($sql);
            if ($q->execute() != TRUE) {
                die(show_message($q->errorInfo(),__line__,__file__,$sql));
            }
            echo 'Added Stronghold tab for prefix:',$t,' - updated.<br>';
        }
    }
} //$upd_ver = 320.0;
$upd_ver = 321.0;
if( ($upd_ver - (float) $config['version']) > 0 ) {

    echo '<br><br><br>Updating to version ',$upd_ver,'.<br>';

    //Получаем список префиксов из таблицы multiclan
    $sql = "SELECT prefix FROM `multiclan`;";
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
            $sql = "UPDATE `config` SET `value` = '".$upd_ver."' WHERE `name` = 'version' LIMIT 1 ;";
            $q = $db->prepare($sql);
            if ($q->execute() != TRUE) {
                die(show_message($q->errorInfo(),__line__,__file__,$sql));
            }
            echo 'Config table (`version` value) for prefix:',$t,' - updated.<br>';
        }
    }
} //$upd_ver = 320.0;

$mline[] = 'globalmap_absolute_spotted'; 
$mline[] = 'globalmap_absolute_hits';
$mline[] = 'globalmap_absolute_battle_avg_xp'; 
$mline[] = 'globalmap_absolute_draws';
$mline[] = 'globalmap_absolute_wins'; 
$mline[] = 'globalmap_absolute_losses'; 
$mline[] = 'globalmap_absolute_capture_points'; 
$mline[] = 'globalmap_absolute_battles';
$mline[] = 'globalmap_absolute_damage_dealt'; 
$mline[] = 'globalmap_absolute_hits_percents'; 
$mline[] = 'globalmap_absolute_damage_received'; 
$mline[] = 'globalmap_absolute_shots'; 
$mline[] = 'globalmap_absolute_xp'; 
$mline[] = 'globalmap_absolute_frags'; 
$mline[] = 'globalmap_absolute_survived_battles';
$mline[] = 'globalmap_absolute_dropped_capture_points';

$mline[] = 'globalmap_champion_spotted'; 
$mline[] = 'globalmap_champion_hits';
$mline[] = 'globalmap_champion_battle_avg_xp'; 
$mline[] = 'globalmap_champion_draws';
$mline[] = 'globalmap_champion_wins'; 
$mline[] = 'globalmap_champion_losses'; 
$mline[] = 'globalmap_champion_capture_points'; 
$mline[] = 'globalmap_champion_battles';
$mline[] = 'globalmap_champion_damage_dealt'; 
$mline[] = 'globalmap_champion_hits_percents'; 
$mline[] = 'globalmap_champion_damage_received'; 
$mline[] = 'globalmap_champion_shots'; 
$mline[] = 'globalmap_champion_xp'; 
$mline[] = 'globalmap_champion_frags'; 
$mline[] = 'globalmap_champion_survived_battles';
$mline[] = 'globalmap_champion_dropped_capture_points';

$mline[] = 'globalmap_middle_spotted'; 
$mline[] = 'globalmap_middle_hits';
$mline[] = 'globalmap_middle_battle_avg_xp'; 
$mline[] = 'globalmap_middle_draws';
$mline[] = 'globalmap_middle_wins'; 
$mline[] = 'globalmap_middle_losses'; 
$mline[] = 'globalmap_middle_capture_points'; 
$mline[] = 'globalmap_middle_battles';
$mline[] = 'globalmap_middle_damage_dealt'; 
$mline[] = 'globalmap_middle_hits_percents'; 
$mline[] = 'globalmap_middle_damage_received'; 
$mline[] = 'globalmap_middle_shots'; 
$mline[] = 'globalmap_middle_xp'; 
$mline[] = 'globalmap_middle_frags'; 
$mline[] = 'globalmap_middle_survived_battles';
$mline[] = 'globalmap_middle_dropped_capture_points';

$upd_ver = 322.0;
if( ($upd_ver - (float) $config['version']) > 0 ) {

    echo '<br><br><br>Updating to version ',$upd_ver,'.<br>';

    //Получаем список префиксов из таблицы multiclan
    $sql = "SELECT prefix FROM `multiclan`;";
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
            $sql = "UPDATE `config` SET `value` = '".$upd_ver."' WHERE `name` = 'version' LIMIT 1 ;";
            $q = $db->prepare($sql);
            if ($q->execute() != TRUE) {
                die(show_message($q->errorInfo(),__line__,__file__,$sql));
            }
            foreach($mline as $line){
                $sql = "ALTER TABLE `col_players` ADD `".$line."` INT( 8 ) NOT NULL;";
                $q = $db->prepare($sql);
                if ($q->execute() != TRUE) {
                    die(show_message($q->errorInfo(),__line__,__file__,$sql));
                }
            }
            echo 'Table col_players for prefix:',$t,' - updated.<br>';


            echo 'Config table (`version` value) for prefix:',$t,' - updated.<br>';
        }
    }
} //$upd_ver = 322.0;
unset($mline);

$mline[] = 'clan_spotted'; 
$mline[] = 'clan_hits';
$mline[] = 'clan_battle_avg_xp'; 
$mline[] = 'clan_draws';
$mline[] = 'clan_wins'; 
$mline[] = 'clan_losses'; 
$mline[] = 'clan_capture_points'; 
$mline[] = 'clan_battles';
$mline[] = 'clan_damage_dealt'; 
$mline[] = 'clan_hits_percents'; 
$mline[] = 'clan_damage_received'; 
$mline[] = 'clan_shots'; 
$mline[] = 'clan_xp'; 
$mline[] = 'clan_frags'; 
$mline[] = 'clan_survived_battles';
$mline[] = 'clan_dropped_capture_points';

$upd_ver = 323.0;
if( ($upd_ver - (float) $config['version']) > 0 ) {

    echo '<br><br><br>Updating to version ',$upd_ver,'.<br>';

    //Получаем список префиксов из таблицы multiclan
    $sql = "SELECT prefix FROM `multiclan`;";
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
            $sql = "UPDATE `config` SET `value` = '".$upd_ver."' WHERE `name` = 'version' LIMIT 1 ;";
            $q = $db->prepare($sql);
            if ($q->execute() != TRUE) {
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
            }    
            $sql = "DESCRIBE {$t}col_players";
            $sel = $db->select($sql);
            $mark = 0;
            foreach($sel as $vv){
                if (strpos($vv['Field'],'clan_') !== false) {
                    $mark++;
                }
            }
            if($mark = 0){
                foreach($mline as $line){
                    //$sql = "ALTER TABLE `col_players` ADD `".$line."` INT( 8 ) NOT NULL;";

                    $sql = "ALTER TABLE `col_players` ADD `".$line."` INT( 8 ) NOT NULL;";
                    $q = $db->prepare($sql);
                    if ($q->execute() != TRUE) {
                        die(show_message($q->errorInfo(),__line__,__file__,$sql));
                    }
                }
            }
            echo 'Table col_players for prefix:',$t,' - updated.<br>';


            echo 'Config table (`version` value) for prefix:',$t,' - updated.<br>';
        }
    }
} //$upd_ver = 322.0;
unset($mline);

/*
$upd_ver = 312.1;
if( ($upd_ver - (float) $config['version']) > 0 ) {

echo '<br><br><br>Updating to version ',$upd_ver,'.<br>';

//Получаем список префиксов из таблицы multiclan
$sql = "SELECT prefix FROM `multiclan`;";
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

if(!is_numeric($config['version']) or ($upd_ver - (float) $config['version']) > 0 ) {

$sql = "UPDATE `config` SET `value` = '".$upd_ver."' WHERE `name` = 'version' LIMIT 1 ;";
$q = $db->prepare($sql);
if ($q->execute() != TRUE) {
die(show_message($q->errorInfo(),__line__,__file__,$sql));
}
echo 'Config table (`version` value) for prefix:',$t,' - updated.<br>';
}
}
}

$db->insert('ALTER TABLE `achievements` CHANGE `name_i18n` `name_i18n` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;',__line__,__file__);
echo 'Achievements table (`name_i18n` value) - updated.<br>';
} //$upd_ver = 312.1;
*/
//Clear cache
$cache->clear_all(array(), ROOT_DIR.'/cache/');
$cache->clear_all(array(), ROOT_DIR.'/cache/players/');

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