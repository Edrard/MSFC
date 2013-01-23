<?php
    /*
    * Project:     Clan Stat
    * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
    * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
    * -----------------------------------------------------------------------
    * Began:       2011
    * Date:        $Date: 2011-10-24 11:54:02 +0200 $
    * -----------------------------------------------------------------------
    * @author      $Author: Edd $
    * @copyright   2011-2012 Edd - Aleksandr Ustinov
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 2.0.3 $
    *
    */
?>
<?php
    //Geting clan roster fron wargaming or from local DB.

    //$new2 = get_player($config['clan'],$config);   //dg65tbhjkloinm 
    $new = get_api_roster($config['clan'],$config);
    if(empty($new)){
        $new['status'] = 'error';
        $new['status_code'] = 'ERROR';
    }

    //print_r($new);
    if($new['status'] == 'ok' &&  $new['status_code'] == 'NO_ERROR'){
        $cache->clear('get_last_roster_'.$config['clan']);
        $cache->set('get_last_roster_'.$config['clan'], $new);
    }else{
        unset($new);
        $new = $cache->get('get_last_roster_'.$config['clan'],0);
        if($new === FALSE) { die('No cahced data'); }  
    }
    //Starting geting data
    if($new['status'] == 'ok' &&  $new['status_code'] == 'NO_ERROR'){
        //Sorting roster

        $roster = roster_sort($new['data']['members']);
        $roster_id = roster_resort_id($roster);
        //Check if DB updating now
        while(lock_check() !== TRUE){
            sleep('10');
        } 
        $get = array();
        foreach($roster as $name => $pldata){
            $tmp = $cache->get($name,$config['cache']*3600,ROOT_DIR.'/cache/players/');
            if($tmp === FALSE){
                $get[$name] = $pldata;      
            }else{
                $res[$name] = $tmp;
            }
        }
        if (!empty($get))  
        {  
            //Trying to lock DB
            while(lockin_mysql() !== TRUE){
                sleep('10');
            }
            foreach($get as $val){
                $cache->clear($val['account_name'],ROOT_DIR.'/cache/players/');
                $links[$val['account_name']] = $config['td'].'/uc/accounts/'.$val['account_id'].'/api/1.8/?source_token=Intellect_Soft-WoT_Mobile-unofficial_stats';
            }   
            multiget($links, $res,$config,prepare_stat(),$roster,$lang);
            //print_r($result);
            unset($links);           
            // Unlocking DB now
            lockout_mysql();
            //$cache->set('res', $res,ROOT_DIR.'/cache/players');
        }
    }
    // In $res array stored player statistic.
    $sql = "SHOW TABLES FROM `".$dbname."` LIKE 'col_tank_%';";
    $q = $db->prepare($sql);
    if ($q->execute() == TRUE) {
        $col_tables = reform($q->fetchAll());
    } else {
        die(show_message($q->errorInfo(),__line__,__file__,$sql));
    }

    $sql = "SELECT DISTINCT up FROM `col_players` ;";
    $q = $db->prepare($sql);
    if ($q->execute() == TRUE) {
        $col_check = count($q->fetchAll());
    } else {
        die(show_message($q->errorInfo(),__line__,__file__,$sql));
    }
    $multiclan = read_multiclan();

    foreach($multiclan as $clan){
        if($clan['id'] != $config['clan']){
            $multiclan_info[$clan['id']] = $cache->get('get_last_roster_'.$clan['id'],0);
            if($multiclan_info[$clan['id']] === FALSE) {
                $multiclan_info[$clan['id']] = get_api_roster($clan['id'],$config);    
            }
            if(empty($multiclan_info)){
                $multiclan_info[$clan['id']]['status'] = 'error';
                $multiclan_info[$clan['id']]['status'] = 'ERROR';
            }
            if($multiclan_info[$clan['id']]['status'] == 'ok' &&  $multiclan_info[$clan['id']]['status_code'] == 'NO_ERROR'){
                $cache->clear('get_last_roster_'.$clan['id']);
                $cache->set('get_last_roster_'.$clan['id'], $multiclan_info[$clan['id']]);
            }else{
                die('No cahced data');
            }
        }else{
            $multiclan_info[$clan['id']] = &$new;    
        }
    }
    //Autoclener
   autoclean((86400*7),$multiclan,$config,ROOT_DIR.'/cache/players/'); 
   
?>