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
        $new['status'] = 'ERROR';
    }
    
    //print_r($new);
    if($new['status'] == 'ok' &&  $new['status_code'] == 'NO_ERROR'){
        $tmp_roster = &$cache->get('get_last_roster',0);
        $cache->clear('get_last_roster');
        $cache->set('get_last_roster', $new);
    }else{
        unset($new);
        $new = $cache->get('get_last_roster',0);
        if($new === FALSE) { die('No cahced data'); }  
    }

    //Starting geting data
    if($new['status'] == 'ok' &&  $new['status_code'] == 'NO_ERROR'){
        //Sorting roster

        $roster = &roster_sort($new['data']['members']);
        $roster_id = &roster_resort_id($roster);  
        if(!empty($tmp_roster)){
            $cached_roster =  &roster_sort($tmp_roster['data']['members']);
            $diff_roster =  key_compare_func($cached_roster, $roster);
            unset($cached_roster,$tmp_roster);
        }
        //Check if DB updating now
        while(lock_check() !== TRUE){
            sleep('10');
        } 
        $res = $cache->get('res',$config['cache']*3600);
        if ($res === FALSE)  
        {  
            //Checking if there any link, then starting multiget from wargaming api.

            //Trying to lock DB
            while(lockin_mysql() !== TRUE){
                sleep('10');
            }
            $links = link_creater($new['data']['members'],$config);
            //print_r($links);
            multiget($links, $result,$config['pars'],$config['multiget']);
            //print_r($result);
            $transit = prepare_stat();
            foreach($result as $name => $val){ 
                $json = json_decode($val,TRUE);
                if($json['status'] == 'ok' && $json['status_code'] == 'NO_ERROR'){
                    $transit = insert_stat($json,$roster[$name],$config,$transit);
                    $res[$name] = pars_data2($json,$name,$config,$lang,$roster[$name]);
                    unset($result[$name]);   
                }
            }
            unset($result,$json,$links,$transit);
            // Unlocking DB now
            lockout_mysql();
            $cache->set('res', $res);
        }elseif(!empty($diff_roster)){
            $cache->clear('res');
            while(lockin_mysql() !== TRUE){
                sleep('10');
            }
            if(!empty($diff_roster['new'])){
                $links = link_creater($diff_roster['new'],$config);
                multiget($links, $result,$config['pars'],$config['multiget']);
                //print_r($result);
                $transit = prepare_stat();
                foreach($result as $name => $val){
                    $json = json_decode($val,TRUE);
                    if($json['status'] == 'ok' && $json['status_code'] == 'NO_ERROR'){
                        $transit = insert_stat($json,$roster[$name],$config,$transit);
                        $res_new[$name] = pars_data2($json,$name,$config,$lang,$roster[$name]);
                        unset($result[$name]);   
                    }
                }
                unset($result,$json,$links,$transit);
                //print_R($res_new);
            }
            if(!empty($diff_roster['unset'])){
                unset_diff($res,$diff_roster['unset']);    
            }
            if(isset($res_new)) {
                array_special_merge_res($res,$res_new);
            }
            lockout_mysql();
            $cache->set('res', $res);
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
?>
