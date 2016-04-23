<?php

function get_update_prefix(){
    global $db;

    $prefix = array();
    $sql = "SELECT prefix FROM `multiclan`;";
    $q = $db->prepare($sql);
    if ($q->execute() == TRUE) {
        $prefix = $q->fetchAll(PDO::FETCH_COLUMN);
    }   else {
        $prefix = array();
    }  
    return $prefix;
}  
function min_version(&$prefix,&$config){
    global $db;
    $current = $db->prefix;
    $min = $config['version'];
    foreach($prefix as $t){
        $db->change_prefix($t);
        $conf = get_config();
        if($min > $conf['version']){
            $new = array();
            $new[] = $t;
            $config['version'] = $min = $conf['version'];
        }elseif($min == $conf['version']){
            $new[] = $t;
        }
    }
    if(isset($new)){
        $prefix = $new;
    }
    $db->change_prefix($current);
}