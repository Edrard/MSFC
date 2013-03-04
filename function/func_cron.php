<?php
    /*
    * Project:     Clan Stat
    * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
    * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
    * -----------------------------------------------------------------------
    * Began:       2011
    * Date:        $Date: 2011-10-24 11:54:02 +0200 $
    * -----------------------------------------------------------------------
    * @author      $Author: Edd, Exinaus, Shw  $
    * @copyright   2011-2012 Edd - Aleksandr Ustinov
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 2.2.0 $
    *
    */
?>
<?php
    function cron_current_run($fh,$date)
    {
        global $db, $config;

        $sql = "SELECT account_id FROM `col_players` LIMIT 1;";
        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            $id = $q->fetchColumn();
        } else {
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }

        $sql = "SELECT COUNT(account_id) FROM `col_players` WHERE account_id = '".$id."';";
        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            $player_stat = $q->fetchColumn();
        } else {
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }
        fwrite($fh, $date.": Current run number ".($player_stat + 1)."\n"); 

    } 
    function cron_links($players,$config)
    {

        $links = array();
        foreach($players as $val){
            $links[$val['account_name']] = $config['td'].'/uc/accounts/'.$val['account_id'].'/api/1.9/?source_token=Intellect_Soft-WoT_Mobile-unofficial_stats';
        }

        return($links);
    }
    function cron_insert_pars_data($data,$roster,$config,$now,$log,$fh,$date){   

        global $db;
        //print_r($data);
        if(!is_array($data)){ 
            $data = json_decode($data,TRUE);
        }
        //print_r($data['data']['achievements']);die;
        if($data['status'] == 'ok' && $data['status_code'] == 'NO_ERROR'){
            if(count($data['data']) > 0){
                if($log == 1){
                    fwrite($fh, $date.": Writing player ".$data['data']['name']."\n");    
                }
                $mod = 1;
                $dbb['account_id'] = $roster['account_id'];
                $dbb['name'] = $data['data']['name'];
                $dbb['role'] = $roster['role'];
                $dbb['server'] = $config['server'];
                $dbb['reg'] = $data['data']['created_at'];
                $dbb['local'] = $data['data']['updated_at'];
                $dbb['member_since'] = $roster['created_at'];
                $dbb['up'] = $now;

                $dbb['total'] = $data['data']['summary']['battles_count'];
                $dbb['win'] = $data['data']['summary']['wins'];
                $dbb['lose'] = $data['data']['summary']['losses'];
                $dbb['alive'] = $data['data']['summary']['survived_battles'];

                $dbb['des'] = $data['data']['battles']['frags'];
                $dbb['spot'] = $data['data']['battles']['spotted'];
                $dbb['accure'] = (int) $data['data']['battles']['hits_percents'];
                $dbb['dmg'] = $data['data']['battles']['damage_dealt'];
                $dbb['cap'] = $data['data']['battles']['capture_points'];
                $dbb['def'] = $data['data']['battles']['dropped_capture_points'];
                $dbb['exp'] = $data['data']['experience']['xp'];
                $dbb['averag_exp'] = $data['data']['experience']['battle_avg_xp'];
                $dbb['max_exp'] = $data['data']['experience']['max_xp'];
                $dbb['gr_v'] = $data['data']['ratings']['integrated_rating']['value'];
                $dbb['gr_p'] = $data['data']['ratings']['integrated_rating']['place'];
                $dbb['wb_v'] = $data['data']['ratings']['battle_avg_performance']['value'];
                $dbb['wb_p'] = $data['data']['ratings']['battle_avg_performance']['place'];
                $dbb['eb_v'] = $data['data']['ratings']['battle_avg_xp']['value'];
                $dbb['eb_p'] = $data['data']['ratings']['battle_avg_xp']['place'];
                $dbb['win_v'] = $data['data']['ratings']['battle_wins']['value'];
                $dbb['win_p'] = $data['data']['ratings']['battle_wins']['place'];
                $dbb['gpl_v'] = $data['data']['ratings']['battles']['value'];
                $dbb['gpl_p'] = $data['data']['ratings']['battles']['place'];
                $dbb['cpt_p'] = $data['data']['ratings']['ctf_points']['place'];
                $dbb['cpt_v'] = $data['data']['ratings']['ctf_points']['value'];
                $dbb['dmg_p'] = $data['data']['ratings']['damage_dealt']['place'];
                $dbb['dmg_v'] = $data['data']['ratings']['damage_dealt']['value'];
                $dbb['dpt_p'] = $data['data']['ratings']['dropped_ctf_points']['place'];
                $dbb['dpt_v'] = $data['data']['ratings']['dropped_ctf_points']['value'];
                $dbb['frg_p'] = $data['data']['ratings']['frags']['place'];
                $dbb['frg_v'] = $data['data']['ratings']['frags']['value'];
                $dbb['spt_p'] = $data['data']['ratings']['spotted']['place'];
                $dbb['spt_v'] = $data['data']['ratings']['spotted']['value'];
                $dbb['exp_p'] = $data['data']['ratings']['xp']['place'];
                $dbb['exp_v'] = $data['data']['ratings']['xp']['value'];
                //print_r($status);
                if($data['data']['name']){

                    $sql = "INSERT INTO `col_players` (".(implode(",",array_keys($dbb))).") VALUES ('".(implode("','",$dbb))."');";

                    $q = $db->prepare($sql);
                    if ($q->execute() != TRUE) {
                        die(show_message($q->errorInfo(),__line__,__file__,$sql));
                    }


                    //$current_tmp = $db->query("SELECT id,tank,nation,title FROM tanks;")->fetchAll();

                    $sql = "SELECT id,tank,nation,title FROM `tanks`;";
                    $q = $db->prepare($sql);
                    if ($q->execute() == TRUE) {
                        $current_tmp = $q->fetchAll();
                    } else {
                        die(show_message($q->errorInfo(),__line__,__file__,$sql));
                    }

                    foreach($current_tmp as $val){
                        $current[$val['id']] = $val['title'].'_'.$val['nation'];    
                    }


                    if(!isset($current)){
                        $current = array();
                    }

                    $tmp = array();
                    $tmp_second = array();
                    $current_flip = array_flip($current);
                    if(isset($data['data']['vehicles'])){
                        foreach($data['data']['vehicles'] as $val){
                            if(in_array($val['name'].'_'.$val['nation'],$current)){
                                $tmp[$val['nation']][$current_flip[trim($val['name']).'_'.$val['nation']].'_t'] = str_replace(' ','',$val['battle_count']);
                                $tmp[$val['nation']][$current_flip[trim($val['name']).'_'.$val['nation']].'_w'] = str_replace(' ','',$val['win_count']);        
                                $tmp_second[$val['nation']][$current_flip[trim($val['name']).'_'.$val['nation']].'_sp'] = (int) str_replace(' ','',$val['spotted']);
                                $tmp_second[$val['nation']][$current_flip[trim($val['name']).'_'.$val['nation']].'_dd'] = (int) str_replace(' ','',$val['damageDealt']);
                                $tmp_second[$val['nation']][$current_flip[trim($val['name']).'_'.$val['nation']].'_sb'] = (int) str_replace(' ','',$val['survivedBattles']);
                                $tmp_second[$val['nation']][$current_flip[trim($val['name']).'_'.$val['nation']].'_fr'] = (int) str_replace(' ','',$val['frags']);
                            }else{
                                $tank = array(
                                    'tank' => trim($val['localized_name']),
                                    'nation' => $val['nation'],
                                    'type' => $val['class'],
                                    'lvl' => $val['level'],
                                    'link' => $val['image_url'],
                                    'title' => $val['name'],
                                );
                                $tsql = "INSERT INTO `tanks` (".(implode(",",array_keys($tank))).") VALUES ('".(implode("','",$tank))."');";
                                $q = $db->prepare($tsql);
                                if ($q->execute() !== TRUE) {
                                    die(show_message($q->errorInfo(),__line__,__file__,$tsql));
                                }
                                $id = $db->lastInsertId(); //шикарная функция, возвращает значение автоинкремент поля из последнего запроса

                                $sql = "show tables like 'col_tank_".$val['nation']."';";
                                $q = $db->prepare($sql);
                                if ($q->execute() == TRUE) {
                                    $nation_db = $q->fetchAll();
                                } else {
                                    die(show_message($q->errorInfo(),__line__,__file__,$sql));
                                }
                                $sql = "show tables like 'col_rating_tank_".$val['nation']."';";
                                $q = $db->prepare($sql);
                                if ($q->execute() == TRUE) {
                                    $col_nation_db = $q->fetchAll();
                                } else {
                                    die(show_message($q->errorInfo(),__line__,__file__,$sql));
                                }
                                if(count($nation_db) < 1){
                                    $sql = "CREATE TABLE IF NOT EXISTS `col_tank_".$val['nation']."` (
                                    `account_id` INT(12),
                                    `up` INT( 12 ) NOT NULL,
                                    KEY `up` (`up`) ) ENGINE=MYISAM;";
                                    $db->prepare($sql)->execute();
                                }
                                if(count($col_nation_db) < 1){
                                    $sql = "CREATE TABLE IF NOT EXISTS `col_rating_tank_".$val['nation']."` (
                                    `account_id` INT(12),
                                    `up` INT( 12 ) NOT NULL,
                                    KEY `up` (`up`) ) ENGINE=MYISAM;";
                                    $db->prepare($sql)->execute();

                                }
                                $ask = "ALTER TABLE `col_tank_".$val['nation']."`
                                ADD `".$id."_w` INT( 12 ) NOT NULL,
                                ADD `".$id."_t` INT( 12 ) NOT NULL;";
                                $db->prepare($ask)->execute();
                                $ask = "ALTER TABLE `col_rating_tank_".$val['nation']."`
                                ADD `".$id."_sp` INT( 12 ) NOT NULL,
                                ADD `".$id."_dd` INT( 12 ) NOT NULL,
                                ADD `".$id."_sb` INT( 12 ) NOT NULL,
                                ADD `".$id."_fr` INT( 12 ) NOT NULL;";
                                $db->prepare($ask)->execute();

                                $tmp[$val['nation']][$id.'_t'] = str_replace(' ','',$val['battle_count']);
                                $tmp[$val['nation']][$id.'_w'] = str_replace(' ','',$val['win_count']); 
                                $tmp_second[$val['nation']][$id.'_sp'] = (int) str_replace(' ','',$val['spotted']);
                                $tmp_second[$val['nation']][$id.'_dd'] = (int) str_replace(' ','',$val['damageDealt']);
                                $tmp_second[$val['nation']][$id.'_sb'] = (int) str_replace(' ','',$val['survivedBattles']);
                                $tmp_second[$val['nation']][$id.'_fr'] = (int) str_replace(' ','',$val['frags']); 

                            }
                        }
                    }
                    //print_r($tmp_second);


                    //Inserting tanks
                    $sql = "show tables like 'col_tank_%';";
                    $q = $db->prepare($sql);
                    if ($q->execute() == TRUE) {
                        $nation_db_now = $q->fetchAll();
                    } else {
                        die(show_message($q->errorInfo(),__line__,__file__,$sql));
                    }

                    foreach($tmp as $key => $t){
                        $t['account_id'] = $roster['account_id'];
                        $t['up'] = $now;
                        $q = $db->prepare("INSERT INTO `col_tank_".$key."` (".(implode(",",array_keys($t))).") VALUES ('".(implode("','",$t))."');")->execute();
                    }
                    //Inserting second part of tanks

                    $sql = "show tables like 'col_rating_tank_%';";
                    $q = $db->prepare($sql);
                    if ($q->execute() == TRUE) {
                        $nation_db_now = $q->fetchAll();
                    } else {
                        die(show_message($q->errorInfo(),__line__,__file__,$sql));
                    }

                    foreach($tmp_second as $key => $t){
                        $t['account_id'] = $roster['account_id'];
                        $t['up'] = $now;
                        $q = $db->prepare("INSERT INTO `col_rating_tank_".$key."` (".(implode(",",array_keys($t))).") VALUES ('".(implode("','",$t))."');")->execute();

                    }

                    foreach($data['data']['achievements']  as $key => $val){
                        if(is_array($val)){
                            foreach($val as $yek => $v){
                                $data['data']['achievements'][$key.'_'.$yek] = (int)$v;
                                unset($val[$yek]);    
                            }
                            unset($data['data']['achievements'][$key]);
                        }
                    }

                    //print_r($new_med);
                    $data['data']['achievements']['account_id'] = $roster['account_id'];
                    $data['data']['achievements']['up'] = $now;
                    $sql = "INSERT INTO `col_medals` (".(implode(",",array_keys($data['data']['achievements']))).") VALUES ('".(implode("','",$data['data']['achievements']))."');";
                    //echo $sql;
                    $q = $db->prepare($sql)->execute();

                }
            } 
        }else{
            if($log == 1){
                fwrite($fh, $date.": No data for player with ID ".$roster['account_id']." ({$data['error']})\n");    
            }
        }
    }
    function update_multi_cron($dbprefix)
    {
        global $db;
        $sql = "UPDATE multiclan SET cron = '".now()."' WHERE prefix = '".$dbprefix."';";
        $q = $db->prepare($sql);
        if ($q->execute() != TRUE) {
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }    
    }
    function get_config_cron_time($prefix)
    {
        global $db;
        $sql = "SELECT * FROM ".$prefix."config WHERE name = 'cron_time';";    

        $q = $db->prepare($sql);
        if ($q->execute() == TRUE) {
            return $q->fetchAll(PDO::FETCH_ASSOC);
        }else{ 
            die(show_message($q->errorInfo(),__line__,__file__,$sql));
        }  
    }

?>