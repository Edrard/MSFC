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
    $sql = "SHOW TABLES FROM `".$dbname."` LIKE 'tank\_%';";
    $q = $db->prepare($sql);
    if ($q->execute() == TRUE) {
        $tables = reform($q->fetchAll());
    } else {
        die(show_message($q->errorInfo(),__line__,__file__,$sql));
    }
    //Geting clan roster fron wargaming or from local DB.

    $new = get_player($config['clan'],$config);   //dg65tbhjkloinm 
    $tmp_roster = &$cache->get('get_last_roster',0);
    if($new['error'] != 0){
        unset($new);
        $new = $cache->get('get_last_roster',0);
        if($new === FALSE) { die('No cahced data'); }  
    }else{
        $cache->clear('get_last_roster');
        $cache->set('get_last_roster', $new);
    }

    // Creating empty array if needed.
    if(empty($new['data']['request_data']['items'])){
        $new['error'] = 1;
        $new['data']['request_data']['items'] = array();
    }

    //Starting geting data
    if(!empty($new['data']['request_data']['items'])){
        //Sorting roster

        $roster = &roster_sort($new['data']['request_data']['items']);
        $roster_id = &roster_resort_id($roster);  
        if(!empty($tmp_roster)){
            $cached_roster =  &roster_sort($tmp_roster['data']['request_data']['items']);
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
            //Checking for cached player statistic data in DB
            /*foreach($new['data']['request_data']['items'] as $val){
            $tmp = checker($val,$lang,$config,$tables,$new['error']);

            if(isset($tmp['link'])){
            $links[$val['name']] = $tmp['link'];
            $last_played[$val['name']] = &$tmp['last_played'];
            }
            if(isset($tmp['data'])){
            $data[$val['name']] = $tmp['data'];
            }    
            }
            unset($tmp,$tables);    */
            //Checking if there any link, then starting multiget from wargaming api.

            //Trying to lock DB
            while(lockin_mysql() !== TRUE){
                sleep('10');
            }
            $links = link_creater($new['data']['request_data']['items'],$config);
            multiget($links, $result,$config['pars']);
            //print_r($result);
            foreach($result as $name => $val){ 
                $json = json_decode($val,TRUE);
                if($json['status'] == 'ok' && $json['status_code'] == 'NO_ERROR'){
                    insert_stat($json,$roster[$name],$config);
                    $res[$name] = pars_data2($json,$name,$config,$lang,$roster[$name]);
                    unset($result[$name]);   
                }
            }
            unset($result,$json,$links);
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
                multiget($links, $result,$config['pars']);
                //print_r($result);
                foreach($result as $name => $val){ 
                    $json = json_decode($val,TRUE);
                    if($json['status'] == 'ok' && $json['status_code'] == 'NO_ERROR'){
                        insert_stat($json,$roster[$name],$config);
                        $res_new[$name] = pars_data2($json,$name,$config,$lang,$roster[$name]);
                        unset($result[$name]);   
                    }
                }
                unset($result,$json,$links);
                //print_R($res_new);
            }
            if(!empty($diff_roster['unset'])){
                unset_diff($res,$diff_roster['unset']);    
            }
            array_special_merge_res($res,$res_new);
            lockout_mysql();
            $cache->set('res', $res);
        }

    }
    // In $res array stored player statistic.  
    $col_tables = reform($db->query("SHOW TABLES FROM `".$dbname."` LIKE 'col\_tank\_%';")->fetchAll());
    $col_check = count($db->query("SELECT DISTINCT up FROM col_players ;")->fetchAll());
?>
