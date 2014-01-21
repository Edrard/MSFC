<?php
    if( !isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest' ) )
    {
        die('No direct access allowed');
    }
    if(!is_writable(ROOT_DIR) and !file_exists(ROOT_DIR.'/mysql.config.php')) {
      die('No permission to create files in root directory, pls change permission to 777 to setup mysql connection. <br> Нет доступа на создание файлов в корневой директории модуля, пожалуйста измените права доступа на 777 для настройки MySQL соединения');
    }
    try {
        $db = new PDO ( 'mysql:host=' . $_POST['host'] . ';dbname=' . $_POST['dbname'], $_POST['user'], $_POST['pass']);
        $db = null;
        echo 'done';
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
?>
