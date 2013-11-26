<?php
        if (preg_match ('/mysql.config.php/', $_SERVER['PHP_SELF']))
        {
        exit;
        }

        $dbhost  ='';
        $dbuser  ='';
        $dbpass  ='';
        $dbname  ='';
        ?>