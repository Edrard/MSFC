<?php
    function updater(){
        
        global $db;
        
        $sql = "SELECT * FROM multiclan ORDER BY sort ASC;";

        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            $updater_clan = $q->fetchAll(PDO::FETCH_ASSOC);
        }else{ 
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        } 
        $current_prefix = $db->current_prefix();
        foreach($updater_clan as $upp){
            $db->change_prefix($upp['prefix']);
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
            }
        }
        $db->change_prefix($current_prefix);
    }
    updater();
?>
