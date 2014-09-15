<?php
/*
* Project:     Clan Stat
* License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
* Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
* -----------------------------------------------------------------------
* Began:       2011
* Date:        $Date: 2013-11-20 00:00:00 +0200 $
* -----------------------------------------------------------------------
* @author      $Author: Edd, Exinaus, SHW  $
* @copyright   2011-2013 Edd - Aleksandr Ustinov
* @link        http://wot-news.com
* @package     Clan Stat
* @version     $Rev: 3.1.1 $
*
*/


if (preg_match ("/config.php/", $_SERVER['PHP_SELF']))
{
    header ("Location: /index.php");
    exit;
}
/* Подключаем информацию о языках доступных через апи */
require(ROOT_DIR.'/admin/translate/api_lang.php');

/* Обьявляем функцию вывода сообщений о ошибках */
function show_message($message = NULL,$line = NULL,$file = NULL,$footer = NULL) {
    if (!headers_sent()) {
        header('Content-type: text/html; charset=utf-8');
    }
    echo '<center><div align="center" class="ui-state-error ui-corner-all">';
    if(isset($message)) {
        if(is_array($message)) {
            echo'<div align="left"><pre>';
            print_r($message);
            echo'</pre></div>';
        } else {
            echo $message.'<br />';
        }
    }
    if(isset($line) and isset($file)) { echo "Error on file <b>$file</b>, on line: <b>$line</b>"; }
    if(isset($footer)) {
        if(is_array($footer)) {
            echo'<div align="left"><pre style="font-size: 12px;">';
            foreach ($footer as $line_num => $line) {
                echo "Line #<b>{$line_num}</b> : " . htmlspecialchars(trim($line)) . "<br />";
            }
            echo'</pre></div>';
        } else {
            echo '<br /><br />Code: <b>'.htmlspecialchars(trim($footer)).'</b>';
        }
    }
    echo '</div></center>';

    //add errors to cron.log when running cron.php
    if(defined('IS_CRON')){
      global $log;
      if($log == 1) {
        global $fh,$date;
        fwrite($fh, $date.": (Err) Error while running cron task, in file: ".$file." on line: ".$line."\n");
        if(isset($message) and !empty($message) and is_array($message)) {
          fwrite($fh, $date.": (Err) Error message: ".trim($message['2'])."\n");
        } elseif (isset($message) and !empty($message)) {
          fwrite($fh, $date.": (Err) Error message: ".trim($message)."\n");
        }
        if(isset($footer) and !empty($footer) and !is_array($footer))
        fwrite($fh, $date.": (Err) Error code: ".trim($footer)."\n");
      }
    }
}

/* Создаем функцию подменяющую стандартное отображение ошибок, в ней мы добавим вывод ошибок через функцию show_message,
и будем выводить строку, или несколько, исходного кода, где произошла ошибка. */
function myErrorHandler($errno, $errstr, $errfile, $errline)
{
    global $config;

    if (!(error_reporting() & $errno)) {
        // Этот код ошибки не включен в error_reporting
        return;
    }
    
    $code = NULL;
    if(file_exists($errfile) and is_readable($errfile)) {
        $f = file($errfile);
        if(isset($_GET['e_show']) and is_numeric($_GET['e_show']))
        {
            $up = $errline-$_GET['e_show']; $down = $errline+$_GET['e_show'];
            if($up < 1) { $up = 1; }

            for ($x=$up; $x<=$down; $x++) { $code[$x] = $f[$x-1]; }

        } else {
            $code = $f[$errline-1];
        }
        unset($f);
    }

    if (!isset($config['theme'])) { $config['theme'] = 'ui-lightness'; }
    echo '<link rel="stylesheet" href="./theme/'.$config['theme'].'/jquery-ui.css" type="text/css" media="print, projection, screen" />';
    show_message($errstr,$errline,$errfile,$code);

    /* Не запускаем внутренний обработчик ошибок PHP */
    return true;
}


/* Применяем созданную функцию */
set_error_handler("myErrorHandler");

/* Создаем функцию закрывающей конект к БД по завершению работы скрипта */
/* Функция сработает и при успешном завершении скрипта, и при вызове die*/

function msfc_end_script() {
  global $db,$q;

  $q = null;
  $db = null;
}

/* Применяем функцию */
register_shutdown_function('msfc_end_script');

/* Создаем папки для кэша */

if(is_writable(ROOT_DIR)) {
    if(!is_dir(ROOT_DIR.'/cache/')){
        mkdir(ROOT_DIR.'/cache/',0777);
        chmod(ROOT_DIR.'/cache/', 0777);
    }
    if(!is_dir(ROOT_DIR.'/cache/players/')){
        mkdir(ROOT_DIR.'/cache/players/',0777);
        chmod(ROOT_DIR.'/cache/players/', 0777);
    }
    if(!is_dir(ROOT_DIR.'/cache/activity/')){
        mkdir(ROOT_DIR.'/cache/activity/',0777);
        chmod(ROOT_DIR.'/cache/activity/', 0777);
    }
    if(!is_dir(ROOT_DIR.'/cache/tanks/')){
        mkdir(ROOT_DIR.'/cache/tanks/',0777);
        chmod(ROOT_DIR.'/cache/tanks/', 0777);
    }
    if(!is_dir(ROOT_DIR.'/cache/other/')){
        mkdir(ROOT_DIR.'/cache/other/',0777);
        chmod(ROOT_DIR.'/cache/other/', 0777);
    }
}

/* Попытка создать cron.log */
if(!file_exists(ROOT_DIR.'/cron.log')) {
    if($fh = fopen(ROOT_DIR.'/cron.log', 'a')){
        fclose($fh);
    }
    chmod(ROOT_DIR.'/cron.log', 0777);
}
if(!is_writable(ROOT_DIR.'/cron.log')){
    chmod(ROOT_DIR.'/cron.log', 0777); 
}
/* Попытка сделать SQL читаймой */
if(!is_writable(ROOT_DIR.'/admin/sql')) {
    chmod(ROOT_DIR.'/admin/sql', 0777);
}
/* Попытка сделать корневую директорию доступной для записи файла настроек mysql */
if(!is_writable(ROOT_DIR) and !file_exists(ROOT_DIR.'/mysql.config.php')) {
  chmod(ROOT_DIR, 0777);
}

/* Выводим сообщения о ошибках */

$lang['pdo_off'] = '<i>ERROR:</i> <strong>php-pdo</strong> extention do not loaded, without it this site can\'t function.<br /> В настройках PHP вашего хостинга отключено использование расширения <strong>php-pdo</strong>, без него MySQL не будет работать.';
$lang['pdo_mysql_off'] = '<i>ERROR:</i> <strong>php-pdo_mysql</strong> extention do not loaded, without it this site can\'t function.<br />В настройках PHP вашего хостинга отключено использование расширения <strong>php-pdo_mysql</strong>, без него MySQL не будет работать.';
$lang['js_off'] = 'Your browser does not support JavaScript. Please enable JavaScript in your browser settings to use full version of this site.<br />Для работы с сайтом необходима поддержка Javascript. Включите его поддержку для полной функциональности сайта.';
$lang['short_tag_off'] = 'Using of <b>short_open_tag</b> are disabled in PHP settings. Please enable it for correct work of this site.<br /> В настройках PHP вашего хостинга отключено использование <b>short_open_tag</b>, без него корректная работа модуля статистики невозможна.';
$lang['chmod_off'] = 'Directory <b>cache</b> doesn\'t exist, or no permission to write. <br /> Директория <b>cache</b> не существует, или невозможна запись.';
$lang['cronlog_off'] = 'File <b>cron.log</b> doesn\'t exist, or no permission to write. <br /> Файл <b>cron.log</b> не существует, или невозможна запись.';
$lang['a_chmod_off'] = 'Directory <b>cache/activity</b> doesn\'t exist, or no permission to write. <br /> Директория <b>cache_activity</b> не существует, или невозможна запись.';
$lang['b_chmod_off'] = 'Directory <b>/cache/players</b> doesn\'t exist, or no permission to write. <br /> Директория <b>/cache/players</b> не существует, или невозможна запись.';
$lang['c_chmod_off'] = 'Directory <b>/admin/sql</b> doesn\'t exist, or no permission to write. <br /> Директория <b>/admin/sql</b> не существует, или невозможна запись.';
$lang['d_chmod_off'] = 'Directory <b>/cache/other/</b> doesn\'t exist, or no permission to write. <br /> Директория <b>/cache/other/</b> не существует, или невозможна запись.';
$lang['e_chmod_off'] = 'No permission to create files in root directory, pls change permission to 777 to setup mysql connection. <br> Нет доступа на создание файлов в корневой директории модуля, пожалуйста измените права доступа на 777 для настройки MySQL соединения';
$lang['curl_off'] = '<i>ERROR:</i> <strong>cURL</strong> extention do not loaded, without it this site can\'t function.<br /> В настройках PHP вашего хостинга отключено использование расширения <strong>cURL</strong>, без него Модуль Статистики не будет работать.';

if ( !extension_loaded('pdo') ) {
    show_message($lang['pdo_off']);
}
if ( !extension_loaded('pdo_mysql') ) {
    show_message($lang['pdo_mysql_off']);
}
if (!extension_loaded("curl")) {
   show_message($lang['curl_off']);
}
if(ini_get('short_open_tag') != 1) {
    show_message($lang['short_tag_off']);
}
if(!file_exists(ROOT_DIR.'/cache/') || !is_writable(ROOT_DIR.'/cache/')) {
    show_message($lang['chmod_off']);
}
if(!file_exists(ROOT_DIR.'/admin/sql/') || !is_writable(ROOT_DIR.'/admin/sql')) {
    show_message($lang['c_chmod_off']);
}
if(!file_exists(ROOT_DIR.'/cache/activity/') || !is_writable(ROOT_DIR.'/cache/activity/')) {
    show_message($lang['a_chmod_off']);
}
if(!file_exists(ROOT_DIR.'/cron.log') || !is_writable(ROOT_DIR.'/cron.log')) {
    show_message($lang['cronlog_off']);
}
if(!file_exists(ROOT_DIR.'/cache/players/') || !is_writable(ROOT_DIR.'/cache/players/')){
    show_message($lang['b_chmod_off']);
}
if(!file_exists(ROOT_DIR.'/cache/other/') || !is_writable(ROOT_DIR.'/cache/other/')){
    show_message($lang['d_chmod_off']);
}
if(!is_writable(ROOT_DIR) and !file_exists(ROOT_DIR.'/mysql.config.php')) {
  show_message($lang['e_chmod_off']);
}
define("VER",'3.1.1');
?>