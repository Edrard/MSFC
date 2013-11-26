<?php
    if( !isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest' ) )
    {
        die('No direct access allowed');
    }   
    try {
        $db = new PDO ( 'mysql:host=' . $_POST['host'] . ';dbname=' . $_POST['dbname'], $_POST['user'], $_POST['pass']);
        $db = null;
        echo 'done';
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
?>
