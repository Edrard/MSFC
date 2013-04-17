<?php
    error_reporting(E_ALL & ~E_STRICT);
    ini_set("display_errors", 1);
    if (file_exists(dirname(__FILE__).'/function/mysql.php')) {
        define('ROOT_DIR', dirname(__FILE__));
    }else{
        define('ROOT_DIR', '.');
    }
    //Starting script time execution timer
    $begin_time = microtime(true);

    //Checker
    include_once(ROOT_DIR.'/including/check.php');

    //MYSQL
    include_once(ROOT_DIR.'/function/mysql.php');

    $sql = "SHOW COLUMNS FROM `users` LIKE 'replays';";
    $q = $db->prepare($sql);
    if ($q->execute() == TRUE) {
        $up_rep = $q->fetchAll(PDO::FETCH_ASSOC);
    }else{ 
        die(show_message($q->errorInfo(),__line__,__file__,$sql));
    }    
    if(empty($up_rep)){
        $sql = "ALTER TABLE `users` ADD `replays` TINYINT(1) NOT NULL DEFAULT '0';";
        $q = $db->prepare($sql);
        if ($q->execute() != TRUE) {
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }
        echo "Обновление выполнено/Update complite";        
    }
?>
