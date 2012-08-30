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
        print_r($q->errorInfo());
        die();
    }
    //Geting clan roster fron wargaming or from local DB.

    $new = get_player($config['clan'],$config);   //dg65tbhjkloinm 
    if($new['error'] != 0){
        unset($new);
        $new = $cache->get('get_last_roster',0);
        if($new === FALSE) { die('No cahced data'); }  
    }else{
        $cache->clear('get_last_roster', $new);
        $cache->set('get_last_roster', $new);
    }

    // Creating empty array if needed.
    if(count($new['data']['request_data']['items']) == 0){
        $new['error'] = 1;
        $new['data']['request_data']['items'] = array();
    }

    //Sorting roster

    $roster = &roster_sort($new['data']['request_data']['items']);
    $roster_id = &roster_resort_id($roster);  


    //Starting geting data
    if(count($new['data']['request_data']['items']) > 0){
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
        }

    }
    // In $res array stored player statistic.  
    $col_tables = reform($db->query("SHOW TABLES FROM `".$dbname."` LIKE 'col\_tank\_%';")->fetchAll());
    $col_check = count($db->query("SELECT DISTINCT up FROM col_players ;")->fetchAll());
?>
